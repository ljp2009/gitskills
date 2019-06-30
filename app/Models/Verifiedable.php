<?php
namespace App\Models;
use Auth;
trait Verifiedable
{
    public function getVerifiedStatus(){
        if(is_null($this->verified_at)){
            return null;
        }else{
            return $this->verified == 1;
        }
    }
    public function getVerifiedDisplay(){
        if(is_null($this->verified_at)){
            return '未审核';
        }else{
            if($this->verified == 1){
                return '已通过';
            }else{
                return '已拒绝';
            }
        }
    }
    public function approve(){
        if(Auth::check()){
            $this->verified = 1;
            $this->verified_at = date('Y-m-d h:i:s');
            $this->verified_by = Auth::id();
            $this->save();
        }
    }
    public function reject(){
        if(Auth::check()){
            $this->verified = 0;
            $this->verified_at = date('Y-m-d h:i:s');
            $this->verified_by = Auth::id();
            $this->save();
        }
    }
}
?>
