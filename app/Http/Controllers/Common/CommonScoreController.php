<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\ScoreModel;
use App\Models\ScoreSumModel;
use App\Models\Ip;
use App\Common\CommonUtils as cu;
use Auth, Input;
class CommonScoreController extends Controller
{
    public static $UNDEFINED = 0;

    private function composeResult($isScore, $result = null){
        if($isScore){
            if($result == null){
                return array("id"=>0, "score"=>self::$UNDEFINED);
            }else{
                return array("id"=>$result->id, "score"=>$result->score);
            }          
        }else{
            if($result == null){
                return array("id"=>0, "score"=>self::$UNDEFINED, "count"=>self::$UNDEFINED);
            }else{
                return array("id"=>$result->id, "score"=>$result->score_sum, "count"=>$result->score_count);
            }  
        }

    }

    public function getSysScoreContent($resource, $resourceId){
        $scoreObj = $this->getSysScore($resource, $resourceId);
        $score = $scoreObj['score'];
        $scoreId=  $scoreObj['id'];   
        $arr = array('isSys'=>true, 'score'=>$score);
        return $arr;    
    }

    public function getUserScoreContent($resource, $resourceId){
        $scoreObj = $this->getUserScore($resource, $resourceId);
        $score = $scoreObj['score'];
        $scoreId=  $scoreObj['id'];
        $name = $resource.'_'.$resourceId;
        if($scoreId!=null&&$scoreId>0){
            $name.='_'.$scoreId;
        }
        $arr = array('isSys'=>true, 'isLogin'=>Auth::check(), 
             'score'=>$score, 'resourceName'=>$resource, 
             'resourceId'=>$resourceId, 'scoreId'=>$scoreId,
             'objName'=>$name);
        return $arr;
    }

    public function getSysScore($resource, $resourceId) {
         $result = ScoreModel::where('resource', $resource)->where('resource_id', $resourceId)->where('is_sys', 1)->first();
        if($result == null){
            return $this->composeResult(true);
        }else{
            return $this->composeResult(true, $result);
        }
    }

    public function getUserScore($resource, $resourceId, $userId=0){
        if($userId==0){
            if(Auth::check())
                $userId = Auth::user()->id;
            else
                return $this->composeResult(true);
        }
        $result = ScoreModel::where('resource', $resource)->where('resource_id', $resourceId)->where('user_id', $userId)->first();
        if($result == null){
            return $this->composeResult(true);
        }else{
            return $this->composeResult(true, $result);
        }
    }

    public function getUserScoreSum($resource, $resourceid){
        $result = ScoreSumModel::where('resource', $resource)->where('resource_id', $resourceId)->first();
        if($result == null){
            return $this->composeResult(false);
        }else{
            return $this->composeResult(false, $result);
        }
    }

    public function getScoreSumById($sumid){
        $result = ScoreSumModel::find($sumid);
        return $result->score_sum;
    }

    //umeiii default score
    public function postSysScore(){
        $resource = Input::get('resource');
        $resourceId = intval(Input::get('resourceId'));

        $score = intval(Input::get('score'));
        $scoreId = Input::get('scoreId');
        $model = null;
        if($scoreId==null||strlen($scoreId)==0){
            $model = ScoreModel::where('resource', $resource)->where('resource_id', $resourceId)->where('is_sys', 1)->first();               
        }else{
            $model = ScoreModel::find(intval($scoreId));
        }
        if($model == null){
            $model = new ScoreModel;
            $model->resource = $resource;
            $model->resource_id = $resourceId;
            $model->score = $score;
            $model->is_sys = 1;
            $model->save();
        }else{
            $model->score = $score;
            $model->save();
        }
        return 'true';
    }
    public function setUserScore(){
        if(!Auth::check()){
            return response()->json(['res'=>false,'info'=>'notlogin']);
        }
        $userId = Auth::user()->id;
        $resource = Input::get('resource');
        $resourceId = intval(Input::get('resourceId'));
        $score = intval(Input::get('score'));
        $model = ScoreModel::where('resource', $resource)
            ->where('resource_id', $resourceId)
            ->where('user_id', $userId)->first();
        $value = 0;
        $isNewUser= is_null($model);
        if($isNewUser){
            $model = new ScoreModel;
            $model->resource = $resource;
            $model->resource_id = $resourceId;
            $model->user_id = $userId;
            $model->is_sys = 0;
            $model->score = 0;
        }
        $value = $score - $model->score;
        $model->score = $score;
        $model->save();
        $this->updateResourceSumScore($resource, $resourceId, $value, $isNewUser);
        return response()->json(['res'=>true, 'score'=>$score]);
    }
    private function updateResourceSumScore($resource, $resourceId, $value, $isNewUser = false){
        $model = ScoreSumModel::where('resource', $resource)
            ->where('resource_id', $resourceId)->first();
        if(is_null($model)){
            $model = new ScoreSumModel;
            $model->resource = $resource;
            $model->resource_id = $resourceId;
            $model->score_count = 0;
            $model->score_sum = 0;
        }
        $model->score_sum += $value;
        $model->score_count += ($isNewUser?1:0);
        $model->save();
    }
    public function postUserScore(){
        if(Auth::check()){
            $resource = Input::get('resource');
            $resourceId = intval(Input::get('resourceId'));

            $score = intval(Input::get('score'));
            $scoreId = Input::get('scoreId');
            $userId =  Auth::user()->id;
            $sumid = 0;
            $model = null;
            if($scoreId==null||strlen($scoreId)==0){
                $model = ScoreModel::where('resource', $resource)->where('resource_id', $resourceId)->where('user_id', $userId)->first();               
            }else{
                $model = ScoreModel::find(intval($scoreId));
            }
            if($model == null){
                $sumid = $this->updateScoreSum($resource, $resourceId, $score, $sumid, true);
                $model = new ScoreModel;
                $model->resource = $resource;
                $model->resource_id = $resourceId;
                $model->score_sum_id = $sumid;
                $model->score = $score;
                $model->user_id = $userId;
                $model->is_sys = 0;
                $model->save();
            }else{
                $sumid = $model->score_sum_id;
                $diff = $score - $model->score;
                if($diff!=0){
                    $this->updateScoreSum($resource, $resourceId, $diff, $sumid);
                    $model->score = $score;
                    $model->save();
                }
            }
            if($resource == 'ip'){
            	$grade = Ip::find($resourceId)->averageGrade();
            }
			return array('code'=>1,'parm'=>array('grade'=>$grade));
        }
        return 'false';

    }

    private function updateScoreSum($resource, $resourceId, $diff, $sumid, $doAdd=false){
        $model = null;
        if($sumid > 0){
            $model = ScoreSumModel::find($sumid);
        }else{
            $model = ScoreSumModel::firstOrNew(array("resource"=>$resource, "resource_id"=>intval($resourceId)));
        }
        if($model == null){
            $model = new ScoreSumModel;
            $model->resource = $resource;
            $model->resource_id = $resourceId;
        }
        $model->score_sum = $model->score_sum + $diff;
        if($doAdd){
            $model->score_count = $model->score_count + 1;
        }
        $model->save();

        return $model->id;
    }
}
?>
