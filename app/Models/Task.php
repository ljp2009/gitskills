<?php

namespace App\Models;

use App\Common\CommonUtils as CU;
use Illuminate\Database\Eloquent\Model;
use App\Common\Image;
use App\Common\TaskStep;
use App\Common\TaskModel;
use App\Common\TaskPartnerStatus;
class Task extends Model
{
    const TASKSTEP_WAIT_CHECK    = 0; //待审核
    const TASKSTEP_WAIT_PUBLISH  = 1; //待发布
    const TASKSTEP_WAIT_JOIN     = 2; //待参与
    const TASKSTEP_WAIT_COMFIRM  = 3; //待定标
    const TASKSTEP_WAIT_DELIVERY = 4; //待交付
    const TASKSTEP_WAIT_PAY      = 5; //待支付
    const TASKSTEP_FINISH        = 6; //完成
    const TASKSTEP_CANCEL        = -1; //取消

    const TASKTYPE_SIMPLE  = 'simple'; //简单任务
    const TASKTYPE_TENDERS = 'tenders'; //招标任务
    const TASKTYPE_APPOINT = 'simple'; //约定任务
    const TASKTYPE_PK      = 'tenders'; //pk任务

    const DELIVERY_ONLINE  = 1;
    const DELIVERY_OFFLINE = 2;

    const MIN_PK_SERVICE_GOLD = 2000;
    const MIN_APPOINT_SERVICE_GOLD = 2000;
    const APPOINT_SERVICE_PERCENT = 5;
    const PK_SERVICE_PERCENT = 5;
    protected $table       = 't_task';
    protected $guarded     = ['id'];
    /*****基础属性*****/
    //图片对象
    public function getImageAttribute($value){
        return Image::makeImages($value);
    }
    //封面图像
    public function getCoverAttribute(){
        if(count($this->image)>0){
            return $this->image[0];
        }else{
            return Image::makeImage('');
        }
    }
    //格式化的介绍
    public function getFormatIntroAttribute(){
        return '<p>'.str_replace("\n", "</p><p>", $this->intro).'</p>';
    }
    //格式化的创建时间
    public function getCreatedDateAttribute()
    {
        return date('Y-m-d', strtotime($this->created_at));
    }
    //奖金
    public function getAmountAttribute($value){
        return intval($value);
    }
    
    //加入币种的奖金
    public function getAmountValueAttribute()
    {
        $txt = $this->pay_type == 'coin' ? '金币：' : 'RMB：';
        return $txt . $this->amount;
    }
    //任务链接
    public function getDetailUrlAttribute()
    {
        if($this->step<2){
            return '/pubtask/manage-main/'.$this->id;
        }else{
            return '/task/'.$this->id;
        }
    }
    //任务技能名称
    public function getSkillName(){
        if(is_null($this->skill)){
            return '(未设置)';
        }
        return $this->skill;
    }
    //发布时间
    public function getPublishDateAttribute($value){
        if($this->step<2){//尚未发布的任务
            return '(未发布)';
        }
        return $value;
    }
    //担保方式
    public function getGuaranteeNameAttribute(){
        if(is_null($this->guarantee) || $this->guarantee == 0){
            return '(未设置)';
        }else{
            if($this->guarantee == 1){
                return '有妹评估';
            }else{
                return '其他评估';
            }
        }
    }
    //参与条件
    public function getJoinConditionAttribute(){
        $resArr = [
            'skill'=>['key'=>0, 'value'=>'不限制技能等级'],
            'credit'=>['key'=>0, 'value'=>'不限制信誉等级']
        ];
        $codeArr = [];
        if($this->skill_level > 0){
            array_push($codeArr, $this->skill_level);
        }
        if($this->credit_level > 0){
            array_push($codeArr, $this->credit_level);
        }
        if(count($codeArr)>0){
            $enums = SysAttrEnum::whereIn('code', $codeArr)->get();
            foreach($enums as $enum){
                $valueArr = ['key'=>$enum->code, 'value'=>$enum->name];
                if($enum->column == '20013'){
                    $resArr['skill'] = $valueArr;
                }
                if($enum->column == '20014'){
                    $resArr['credit'] = $valueArr;
                }
            }
        }
        return $resArr;
    }
    //已经设置了详细详细信息
    public function getIsSetDetailAttribute(){
        return !empty($this->intro);
    }
    //设置了交付条件
    public function getIsSetConditionAttribute(){
        return $this->conditions->count() > 0;
    }
    //设置了里程碑
    public function getIsSetMilestoneAttribute(){
        return $this->milestones->count() > 0;
    }
    //评审图片(展示用）
    public function getReviewShowImgAttribute() {
        $img = $this->newReviewImg;
        //当未设置评审图片时候，使用图片中第一张作为评审图片
        if(!$img->checkSet() && count($this->image) > 0){
            return $this->image[0];
        }else{
            return $img;
        }
    }
    //评审图片(原始数据)
    public function getOriginReviewImgAttribute($value) {
         $img = Image::makeImage($this->review_img);
         return $img;
    }

    public function getReviewIntroAttribute($value) {
        if(empty($value)){
            return mb_substr($this->intro, 0, 100, 'utf-8');
        }else{
            return $value;
        }
    }
    /*****关联对象*****/
    //创建用户
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    //任务邀请
    public function taskInvite()
    {
        return $this->hasMany('App\Models\TaskInvite', 'task_id', 'id');
    }
    //任务技能类型
    public function skill()
    {
        return $this->hasOne('App\Models\SysUserSkill','code','skill_type');
    }
    //里程碑
    public function milestones()
    {
        return $this->hasMany('App\Models\TaskMilestone', 'task_id','id');
    }
    //交付条件
    public function conditions()
    {
        return $this->hasMany('App\Models\TaskDeliverCondition', 'task_id','id');
    }
    //任务进展
    public function timelines(){
        return $this->hasMany('App\Models\TaskTimeline', 'task_id','id');
    }
    //pk任务参与者
    public function pkPartners(){
        return $this->hasMany('App\Models\TaskPartner', 'task_id','id')->where('status',TaskPartnerStatus::PARTNER);
    }
    //约定认任务合作者
    public function appointPartner(){
        return $this->hasOne('App\Models\TaskPartner', 'task_id','id')->where('status', TaskPartnerStatus::PARTNER);
    }
    //等待确认的合作者
    public function waitChoices(){
        return $this->hasMany('App\Models\TaskPartner', 'task_id','id')->where('status', TaskPartnerStatus::JOININ);
    }
    //请求参与的用户
    public function requestUsers(){
        return $this->hasMany('App\Models\TaskPartner', 'task_id','id');
    }
    //取消申请(已经确认的或者等待确认的)
    public function cancelRequest(){
        return $this->hasOne('App\Models\TaskCancelRequest', 'task_id','id')->where('status', TaskCancelRequest::STATUS_REQUEST);
    }
    //全部取消申请(包含已经撤销)
    public function allCancelRequest(){
        return $this->hasMany('App\Models\TaskCancelRequest', 'task_id','id');
    }
    /*****控制属性*****/
    //任务模式
    public function getModelAttribute($value){
        return $this->task_type;
    }
    public function setModelAttribute($value){
        $this->attributes['task_type'] = $value;
    }
    public function getModelName(){
        $model = TaskModel::make($this);
        return $model->getName();
    }
    //任务步骤
    public function getStepAttribute($value){
        if($value == TaskStep::PUBLISHED && $this->waitchoices->count()>0){
            return TaskStep::CHOICING;
        }
        return $value;
    }
    public function getStepName()
    {
        $step = TaskStep::make($this);
        return $step->getName();
    }
    public function getVoteType(){
        if($this->model != TaskModel::PK){
            return '';
        }
        if(in_array($this->skill_type, [ '2001005', '2001006', '2001009', '2001010', '2001008'])){
            return 'image';
        }
        else if(in_array($this->skill_type, ['2001001'])){
            return 'text';
        }else{
            return '';
        }
    }
    public function getVoteCode(){
        $voteType = $this->getVoteType();
        $typeCode = 0;
        if($voteType == 'image'){
            $typeCode = 0;
        }else if($voteType == 'text'){
            $typeCode = 10;
        }else{
            return 0;
        }
        if($this->step == TaskStep::REVIEW_1) {
            $stepCode = 1;
        }
        else if($this->step == TaskStep::REVIEW_2) {
            $stepCode = 2;
        }else{
            return 0;
        }
        return ($stepCode+$typeCode);
    }
}
