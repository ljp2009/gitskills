<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils as CU;
class Vote extends Model
{
    protected $table = 't_vote';
    protected $guarded = ['id'];
    
    private $_voteToken = '';
    const MULTIPLE = 9;
    const SINGLE = 1;

    public function voteResource(){
        switch($this->resource){
        case "task":
            return $this->hasOne('App\Models\Task', 'id', 'resource_id');
            break;
        default:
            return null;
        }
    }
    public function getAlternativeObjectsAttribute(){
        $ids = explode(';',$this->alternatives);
        $objClass = $this->getVoteObject();
        $objs = $objClass::whereIn('id', $ids)->get();
        $idsFlip = array_flip($ids);
        $res = [];
        foreach($objs as $obj){
           $index = $idsFlip[$obj->id];
           $res[$index] = $obj;
        }
        ksort($res);
        return $res;
    }
    public function getSingleAlternativeAttribute(){
        $objId = '0';
        if(count($this->alternatives > 0)){
          $objId  = $this->alternatives[0];
        }
        $obj = $this->getVoteObject();
        return $obj::findOrFail($objId);
    }
    public function getMultipuleAlternativesAttribute(){
        $obj = $this->getVoteObject();
        return $obj::whereIn('id', $this->alternatives)->get();
    }
    public function getReviewImg(){
        $voteRes = $this->voteResource;
        return $voteRes->reviewShowImg;
    }
    public function getReviewIntro(){
        $voteRes = $this->voteResource;
        return $voteRes->review_intro;
    }
    //选票的token，为了避免刷票，用一个临时的token代替vote的id
    public function getVoteTokenAttribute(){
        return $this->_voteToken;
    }
    public function setVoteTokenAttribute($value){
        $this->_voteToken = $value;
    }
    private function getVoteObject(){
        switch($this->resource){
        case "task":
            return 'App\Models\TaskDelivery';//目前投票系统仅对任务系统
        default:
            return "";
        }
    }
    public function getVoteType(){
        $typeStr = sprintf('%02d', $this->type);
        $voteCode = substr($typeStr, 0, 1);
        switch($voteCode) {
        case '0':
            return 'image';
        case '1':
            return 'text';
        }    
        return '';
    }
    public function getVoteStep(){
        $typeStr = sprintf('%02d', $this->type);
        $voteCode = substr($typeStr, 1, 1);
        return $voteCode;
    }

}
