<?php

namespace App\Models;

use App\Common\CommonUtils as CU;
use App\Common\Image;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use App\Models\UserAttr;
class User extends Model implements AuthenticatableContract,
AuthorizableContract,
CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 't_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['display_name', 'email','mobile','wechat_token', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $guarded = ['id'];

    public function getBackgroundAttribute($value) {
        return Image::makeImage($value);
    }

    public function getBackgroundUrlAttribute(){
        return $this->background;
    }

    public function getAvatarAttribute($value)
    {
        return Image::makeImage($value);
    }
    public function getAgeAttribute($value){
        $birthDayAttr = $this->getAttrAge;
        if(!is_null($birthDayAttr)){
          $birthDay = $birthDayAttr->attr_value;
          $seconds  = strtotime(date('Y-m-d')) - strtotime($birthDay);
          $year = $seconds/(60*60*24*365.25);
          return (int)$year;
        }
        else{
          return 0;
        }
    }
    public function getSignatureAttribute(){
        return $this->userInfo->sign;
    }
    public function userInfo(){
        return $this->hasOne('App\Models\UserInfo', 'user_id','id');
    }
    public function attrs(){
        return $this->hasMany('App\Models\UserAttr', 'user_id','id');
    }
    public function signatureAttr()
    {
        return $this->hasOne('App\Models\UserAttr', 'user_id','id')->where('attr_code', '=', '20006')->orderBy('id', 'desc');
    }
    public function getDetailStatus()
    {
        return $this->hasOne('App\Models\UserDetailStatus', 'user_id','id');
    }
    //接收金币数
    public function getReceiveGoldAttribute(){
        if(!is_null($this->getDetailStatus)){
            return $this->getDetailStatus->receive_gold;
        }else{
            return 0;
        }
    }
    //可用的金币
    public function getGoldAttribute(){
        if(!is_null($this->getDetailStatus)){
            return $this->getDetailStatus->gold;
        }else{
            return 0;
        }
    }

    //关联
    public function getSumFollowNum(){
        return $this->hasOne('App\Models\UserSum', 'user_id','id')
            ->where('sum_code', '=', '21001');
    }
    public function getFollowNumAttribute()
    {
        if (is_null($this->getSumFollowNum)) {
            return 0;
        }
        return $this->getSumFollowNum->value;
    }
    public function getSumFansNum()
    {
        return $this->hasOne('App\Models\UserSum', 'user_id')
            ->where('sum_code', '=', '21002');
    }
    public function getFansNumAttribute()
    {
        if (is_null($this->getSumFansNum)) {
            return 0;
        }
        return $this->getSumfansNum->value;
    }
    public function likeIps()
    {
        return $this->hasMany('App\models\LikeModel', 'user_id');
    }

    public function getlikeIpsAttribute($value)
    {
        return $value->where('resource', 'ip');
    }

    public function recommendSolution()
    {
        return $this->hasOne('App\Models\UserOrder', 'user_id');
    }

    public function getRecommendSollutionAttribute($value)
    {
        return $value->code;
    }

    public function getHomeUrlAttribute()
    {
        if($this->id == 0) return '#';
        return '/home/list/default/0/' . $this->id;
    }
    public function getDetailUrlAttibute(){
        return $this->HomeUrl;
    }
    public function getLabelAttribute($value)
    {
        if (!is_null($this->getDetailStatus)) {
            $label = '';
            if (!is_null($this->getDetailStatus->attrSetBehavior)) {
                $label .= $this->getDetailStatus->attrSetBehavior->name;
            }
            if (!is_null($this->getDetailStatus->attrSetCharacter)) {
                $label .= $this->getDetailStatus->attrSetCharacter->name;
            }
            if (!is_null($this->getDetailStatus->attrSetPosition)) {
                $label .= $this->getDetailStatus->attrSetPosition->name;
            }
            return $label;
        }
        return '';

    }
    public function getAttrSkill()
    {
    	return $this->hasMany('App\Models\UserSkill', 'user_id');
    }
    public function getSkillInfo($skillCode)
    {
        return $this->getAttrSkill->where('code', $skillCode)->first();
    }
    public function getShotName($length){
       $str_cut = $this->display_name;
       if (strlen($str_cut) > $length)
       {
           for($i=0; $i < $length; $i++)
               if (ord($str_cut[$i]) > 128)  $i++;
           $str_cut = substr($str_cut,0,$i)."..";
       }
       return $str_cut;
    }
    public function getUserCredit(){
        $userCredit = UserCredit::where('user_id', $this->id)->first();
        $level = 3;
        if(!is_null($userCredit)){
            $level = $userCredit->level;
        }
        if($level > 5) $level = 5;
        if($level < 1) $level = 1;
        $creditMap = [ 1=>'一般', 2=>'合格', 3=>'良好', 4=>'优秀', 5=>'SSS' ];
        return [ 'id' => $level, 'label' => $creditMap[$level]];
    }
    public function getNewLetterNumAttribute(){
    	return $this->hasMany('App\Models\UserPrivateLetter', 'user_id','id')->where('status','=','N');
    }
}
