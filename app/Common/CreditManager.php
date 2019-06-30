<?php
namespace App\Common;

use App\Models\UserCredit;
use App\Models\UserCreditRecord;
use Config;
class CreditManager{
    /* 设置初始信誉等级
     * */
    public static function initCredit($userId){
        $initLevel = '3';
        $initScore = 1000;
        //创建信誉记录
        $credit = new UserCredit;
        $credit->level =$initLevel;
        $credit->score = 0;
        $credit->user_id = $userId;
        $credit->save();
        $category = '5000201';//记录分类, 初始化信誉
        self::updateCredit($userId, $initScore, $category);
    }
    /* 设置更新信誉值
     * 默认值为完成任务
     * 类别5000202标识任务完成
     * */
    public static function UpdateCredit($userId, $score, $category='5000202', $resource_id=0){
        //更新信誉分数
        $userCredit = UserCredit::where('user_id', $userId)->first();
        $userCredit->score += $score;
        //创建分数更新记录
        $recordId = self::recordCreditChange($userId, $score, 'score', $category, $resource_id);
        //更新等级
        self::updateCreditLevel($userCredit, $recordId);
        //保存对信誉的更新
        $userCredit->save();
    }
    public static function checkLevel($userId, $level){
        $userCredit = UserCredit::where('user_id', $userId)->first();
        if(is_null($userCredit)){
            self::initCredit($userId);
            $userCredit = UserCredit::where('user_id', $userId)->first();
        }
        return $userCredit->level >= $level;
    }
    private static function updateCreditLevel($userCredit, $recordId){
        $level = self::checkCreditLevel($userCredit->score);
        if($level != $userCredit->level){
            $category = ($level > $userCredit->level ? '5000208':'5000209');//'5000208':升级, '5000209':降级;
            $userCredit->level = $level;
            self::recordCreditChange($userId, $level, 'level', $category, $recordId);
        }
    }
    private static function checkCreditLevel($score){
        $levelMap = self::getLevelMap();
        for($i=0; $i<count($levelMap); $i++){
            if($score < $levelMap[$i]){
                return $i;
            }
        }
        return $i;//大于所有等级，返回最大信誉等级
    }
    private static function recordCreditChange($userId, $value, $type, $category, $remark=''){
            $record = new UserCreditRecord;
            $record->user_id = $userId;
            $record->type = $type;
            $record->value = $value;
            $record->category = $category;
            if(!empty($remark)){
                $record->remark = $remark;
            }
            $record->save();
            //发送变更通知
    }
    private static function noticeCreditLevelChange(){}
    private function getCategoryCodeMap(){
        $categoryArr = [];
        $categoryArr['5000201'] = 'init credit';
        $categoryArr['5000202'] = 'Finish Task';
        $categoryArr['5000203'] = 'Cancel Task';
        $categoryArr['5000204'] = 'Give up Task';
        $categoryArr['5000208'] = 'level Up';
        $categoryArr['5000209'] = 'level down';
        return $categoryArr;
    }
    private static function getLevelMap(){
        $map = [];
        $map[0] = 850;
        $map[1] = 950;
        $map[2] = 990;
        $map[3] = 1030;
        $map[5] = 1130;
        return $map;
    }
}
