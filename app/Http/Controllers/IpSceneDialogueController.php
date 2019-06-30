<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\CommonLikeController;

use App\Models\IpScene;
use App\Models\IpDialogue;
use App\Models\ListItem;

use App\Common\CommonUtils;
use App\Common\IpContributorHandler;
use App\Common\GoldManager;
use App\Common\OwnerHandler;

use Auth, Redirect, Input, Publish;

class IpSceneDialogueController extends Controller
{
	protected $PAGNATE = 1;//Display only one dialogue and scene in IPDetail page
	protected $RESOURCE_NAME_SCENE = 'ip_scene';
	protected $RESOURCE_NAME_DIALOGUE = 'ip_dialogue';

	//$verfied = 0 All, 1 verfied, 2 not verified
	public function getVerifiedSceneList($page, $ipid){
        return view('detaillist', ['title'=>'更多场景', 'resource'=>'ip_scene', 'type'=>'ipscene',
            'listName'=>'verified', 'id'=>$ipid,'page'=>$page]);
	}
	public function getVerifiedSceneContent($from, $to, $ipid){
		$verified = 1;
		$scenes = $this->getScenes($ipid, $verified, $from, $to);
        $models = ListItem::makeSceneListItems($scenes);
		return view('partview.detaillistitem', array('models'=>$models));
	}
	public function getSceneDetail($id) {
		$item = IpScene::findOrFail($id);
        $models = ListItem::makeSceneListItems([$item]);
		return view('detailinfo',['title'=>'场景', 'value'=>$models, 'resource'=>'ip_scene','type'=>'ipscene', 'id'=>$id]);
	}
	private function getScenes($ipid, $verified=1, $from=0, $to=0){
		$likeController = new CommonLikeController;
		$model = IpScene::where('ip_id', $ipid);
		if($verified>0){
			$v = ($verified==1?1:0);
			$model=$model->where('verified', $v);
		}
		$results = CommonUtils::handleListDetails($model, $from, $to,true);
		$results = $likeController->attachLikes($this->RESOURCE_NAME_SCENE, $results);
		foreach($results as $res){
			$res->pics = CommonUtils::getPics($res->images);
		}
		return $results;
	}

	public function displayCreateScene($ipid){
        $params = [
            'title' =>'创建经典场景',
            'description'=> '',
            'id'=>0,
            'fields' =>[
                'pid'=>['value'=>$ipid],
                'content' => ['value'=>'', 'placeholder'=>'场景描述', 'max'=>'500'],
                'image' => ['value'=>[], 'max'=>'2', 'require'=>'require'],
            ],
            'postUrl' => '/ipscene/create',
            'fieldName' => 'scene',
        ];
		return view("detailpubpage", $params);
	}

	public function displayEditScene($id){
        $scene =IpScene::findOrFail($id);
        if(Auth::user()->id != $scene->user_id) return back();

        $content = $scene->text;
        $imgArr = [];
        foreach($scene->image as $img){
            $imgArr[$img->originName] = $img->getPath(1);
        }
        $params = [
            'title' =>'修改经典场景',
            'description'=> '',
            'id'=>$id,
            'fields' =>[
                'content' => ['value'=>$scene->text, 'placeholder'=>'场景描述', 'max'=>'500'],
                'image' => ['value'=>$imgArr, 'max'=>'2', 'require'=>'require'],
            ],
            'postUrl' => '/ipscene/edit',
            'fieldName' => 'scene',
        ];
		return view("detailpubpage", $params);
	}

	public function addScene(){

        $userId = Auth::id();
        $payRes = GoldManager::publishPayGold('ip_scene', 0, $userId);
        if(!$payRes){
            return response()->json(['res'=>false, 'info'=>'金币不足，无法创建', 'url'=>'' ]);
        }
		$model = new IpScene;
		$model->ip_id= intval(Input::get('pid'));
		$model->user_id= $userId;
		$model->text = CommonUtils::escapeSpecialChars(Input::get('content'));
		$model->image = CommonUtils::evalPics(Input::get('image'));
		$model->verified = 1;
		$model->save();
		IpContributorHandler::SaveIpContributor(intval(Input::get('pid')), $userId, $model->id, 'ip_scene');
		//return redirect(noBackUrl('/ipscene/'.$model->id));
        return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/ipscene_'.$model->id]);
	}
	
	public function editScene(){
		$id = intval(Input::get('id'));
		$model = IpScene::findOrFail($id);
        if(!OwnerHandler::checkByObj('ip_scene',$model)){
            return response()->json(['res'=>false, 'info'=>'']);
        }
		$model->text = CommonUtils::escapeSpecialChars(Input::get('content'));
		$model->image = CommonUtils::evalPics(Input::get('image'));
		$model->verified = 1;
		$model->save();
		//return redirect(noBackUrl('/ipscene/'.$model->id));
        return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/ipscene_'.$model->id]);
	}
	public function deleteScene(){
		$id = intval(Input::get('id'));
		$model = IpScene::find($id);
        if(!OwnerHandler::checkByObj('ip_scene',$model)){
            return response()->json(['res'=>false, 'info'=>'']);
        }
		$model->delete();
        return response()->json(['res'=>true]);
	}

	public function getVerifiedDialogueList($page, $ipid){
        return view('detaillist', ['title'=>'更多台词', 'resource'=>'ip_dialogue', 'type'=>'ipdialogue',
            'listName'=>'verified', 'id'=>$ipid,'page'=>$page]);
	}
	public function getVerifiedDialogueContent($from, $to, $ipid){
		$dialogues = $this->getDialogues($ipid, 1, $from, $to);
        $models = ListItem::makeDialogueListItems($dialogues);
		return view('partview.detaillistitem', array('models'=>$models));
	}
	public function getDialogueDetail($id) {
		$item = IpDialogue::findOrFail($id);
        $models = ListItem::makeDialogueListItems([$item]);
		return view('detailinfo',['title'=>'台词', 'value'=>$models, 'resource'=>'ip_dialogue','type'=>'ipdialogue', 'id'=>$id]);
	}
	private function getDialogues($ipid, $verified=1, $from=0, $to=0){
		$likeController = new CommonLikeController;
		$model = IpDialogue::where('ip_id', $ipid);
		if($verified>0){
			$v = ($verified==1?1:0);
			$model=$model->where('verified', $v);
		}
		$results = CommonUtils::handleListDetails($model, $from, $to,true);
		$results = $likeController->attachLikes($this->RESOURCE_NAME_DIALOGUE, $results);
		return $results;
	}

	public function displayCreateDialogue($ipid){
        $params = [
            'title' =>'创建经典台词',
            'description'=> '',
            'id'=>0,
            'fields' =>[
                'pid'=>['value'=>$ipid],
                'title' => ['value'=>'', 'placeholder'=>'台词的原作者', 'max'=>'50'],
                'content' => ['value'=>'', 'placeholder'=>'台词内容', 'max'=>'500'],
            ],
            'postUrl' => '/ipdialogue/create',
            'fieldName' => 'dialogue',
        ];
		return view("detailpubpage", $params);
	}
	public function displayEditDialogue($id){
        $dial =IpDialogue::findOrFail($id);
        if(Auth::user()->id != $dial->user_id) return back();

        $text = $dial->textPart;
        $role = $dial->rolePart;
        $params = [
            'title' =>'修改经典台词',
            'description'=> '',
            'id'=>$id,
            'fields' =>[
                'title' => ['value'=>$role, 'placeholder'=>'台词的原作者', 'max'=>'50'],
                'content' => ['value'=>$text, 'placeholder'=>'台词内容', 'max'=>'500'],
            ],
            'postUrl' => '/ipdialogue/edit',
            'fieldName' => 'dialogue',
        ];
		return view("detailpubpage", $params);
	}
	public function addDialogue(){
        $userId = Auth::id();
        $payRes = GoldManager::publishPayGold('ip_dialogue', 0, $userId);
        if(!$payRes){
            return response()->json(['res'=>false, 'info'=>'金币不足，无法创建', 'url'=>'' ]);
        }
		$model = new IpDialogue;
		$model->ip_id= intval(Input::get('pid'));
		$model->user_id= $userId;
        $text =  CommonUtils::escapeSpecialChars(Input::get('content'));
        $role =  CommonUtils::escapeSpecialChars(Input::get('title'));
        if(is_null($role)) $role = '';
        $model->text =[$text, $role];
        $model->verified = 1;
        $model->save();
        IpContributorHandler::SaveIpContributor(intval(Input::get('pid')), $userId, $model->id, 'ip_dialogue');
        return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/ipdialogue_'.$model->id]);
	}

	public function updateDialogue(){
		$id = intval(Input::get('id'));
		$model = IpDialogue::findOrFail($id);
        $text =  CommonUtils::escapeSpecialChars(Input::get('content'));
        $role =  CommonUtils::escapeSpecialChars(Input::get('title'));
        if(is_null($role)) $role = '';
        $model->text =[$text, $role];
		$model->verified = 1;
		$model->save();
		//return redirect(noBackUrl('/ipdialogue/'.$model->id));
        return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/ipdialogue_'.$model->id]);
	}
	public function deleteDialogue(){
		$id = intval(Input::get('id'));
		$model = IpDialogue::find($id);
        if(!OwnerHandler::checkByObj('ip_dialogue',$model)){
            return response()->json(['res'=>false, 'info'=>'']);
        }
		$model->delete();
        return response()->json(['res'=>true]);
	}

	public function showDialogueEdit($id){
		$model = IpDialogue::find($id);
		return view('partview.ip.dialogueedit',array('model'=>$model));
	}
	public function editDialogue(){
		$id = Input::get('id');
		$model = IpDialogue::find($id);
        if(!OwnerHandler::checkByObj('ip_dialogue',$model)){
            return response()->json(['res'=>false, 'info'=>'']);
        }
        $text =  CommonUtils::escapeSpecialChars(Input::get('text'));
        $role =  CommonUtils::escapeSpecialChars(Input::get('role'));
        if(is_null($role)) $role = '';
        $model->text =[$text, $role];
		$model->save();
		return redirect('/ipdialogue/list/verified/0/'.$model->ip_id);
	}


	
}
?>
