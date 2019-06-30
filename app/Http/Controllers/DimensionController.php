<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\CommonLikeController;

use App\Common\CommonUtils;
use App\Common\GoldManager;
use App\Common\OwnerHandler;

use App\Models\Dimension;
use App\Models\DimensionAttr;
use App\Models\DimensionEnter;
use App\Models\DimensionSum;
use App\Models\DimensionPublish;
use App\Models\DimensionLatelyUser;
use App\Models\ListItem;
use App\Models\LikeModel;
use App\Models\SysAttrEnum;

use Auth, Redirect, Input, DB;
use App\Models\Activity;
class DimensionController extends Controller
{
	protected $RESOURCE_NAME_DIMENSION = 'dimension';
	protected $RESOURCE_NAME_DIMENSIONPUBLISH = 'dimension_publish';
	public function showDimensionList($listName, $page, $id='0') {
        $name = '';
        switch($listName){
            case 'default':
                $name = "更多次元";
                break;
            case 'user':
                $name = "入驻次元";
                break;
            case 'ip':
                $name = "相关次元";
                break;
        }
        return view('dimensionlist', ['page'=>$page, 'name'=>$name,
            'listName'=>$listName, 'id'=>$id, 'deleteRoute'=>'/dimension/delete' ]);
	}

	public function loadListData($listName, $from, $to, $id='0') {
        switch($listName){
            case 'default':
                $model = Dimension::where('user_id','>',0)->where('authority', 0);
                break;
            case 'user':
                $model = Dimension::where('user_id',$id);
                break;
            case 'ip':
                $model = Dimension::where('ip_id',$id);
                break;
        }
		$dimension = CommonUtils::handleListDetails($model, $from, $to , true,'id');
		return view('partview.dimension.dimensionitem', array('models'=>$dimension));
	}

	public function showDimensionInfo($page,$id){
		$dimension = Dimension::findOrFail($id);
		return view('dimensioninfo', ['model'=>$dimension, 'page'=>$page, 'id'=>$id]);
	}

	public function loadListDimensionData($from, $to, $id){
		$result = $this->getPublishList($id, $from, $to);
		$models = ListItem::makeDimensionPublishListItems($result);
		return view('partview.detaillistitem', array('models'=>$models));
	}

	private function getPublishList($id, $from, $to){
		$likeController = new CommonLikeController;

		$model = DimensionPublish::where('dimension_id', $id);
		$results = CommonUtils::handleListDetails($model, $from, $to,true);
		$results = $likeController->attachLikes($this->RESOURCE_NAME_DIMENSION, $results);
		// 		var_dump($results);die;
		return $results;
	}

	public function dimensionInfo($id)
	{
        $result = DimensionPublish::findOrFail($id);
		$models = ListItem::makeDimensionPublishListItems([$result]);
		return view('detailinfo',['title'=>'帖子', 'value'=>$models, 'resource'=>'dimension_publish', 'type'=>'dimpub', 'id'=>$id]);
	}

	private function convertDimensionsPublishToListItem($role)
	{
		if(!empty($role)){
			$item = new ListItem();
			$item->user = $role->user;
			// 		$item->title = $role->name;
			$item->text = $role->text;
			$item->createAt = $role->created_at;
			$item->objectType = $this->RESOURCE_NAME_DIMENSIONPUBLISH;
			$item->objectId = $role->id;
			$item->linkUrl = $role->detailUrl;
			$query = LikeModel::with('user')
			->where('resource_id',$role->id)
			->where('resource',$this->RESOURCE_NAME_DIMENSIONPUBLISH);
			if(Auth::check())
			{
				$item->likeStatus = $query
				->where('user_id',Auth::user()->id)->count() >0?"2":1;
			}
			$item->likeCount = $query->count();

			$likes = $query->orderBy('created_at','desc')
			->take(7)->get();

			foreach ($likes as $key => $like) {
				array_push($item->likeUsers, $like->user);
			}
			$item->imageList = array_filter($role->imagePaths);
			$item->editUrl = "/dimension/publishedit/";
			return $item;
		}
		return array();
	}

	private function convertDimensionsToListItem($role)
	{
		$item = new ListItem();
		$item->user = $role->user;
// 		$item->title = $role->name;
		$item->text = $role->text;
		$item->createAt = $role->created_at;
		$item->objectType = $this->RESOURCE_NAME_DIMENSION;
		$item->objectId = $role->id;
		$item->linkUrl = $role->detailUrl;
		$query = LikeModel::with('user')
		->where('resource_id',$role->id)
		->where('resource',$this->RESOURCE_NAME_DIMENSION);
		if(Auth::check())
		{
			$item->likeStatus = $query
			->where('user_id',Auth::user()->id)->count() >0?"2":1;
		}
		$item->likeCount = $query->count();

		$likes = $query->orderBy('created_at','desc')
		->take(7)->get();

		foreach ($likes as $key => $like) {
			array_push($item->likeUsers, $like->user);
		}
		$item->imageList = array_filter($role->imagePaths);

		return $item;
	}

	public function dimensionDetail($id)
	{
		$item = $this->convertDimensionsToListItem(DimensionPublish::find($id));
		return view('detailinfo',array('value'=>$item));
	}

	//创建二次元画面
	public function displayCreateDimension($id=''){
		$params = [
            'title' =>'发布次元',
            'description'=> '',
            'id'=>0,
            'fields' =>[
                'act_id' => ['value'=>Input::get('act_id')?Input::get('act_id'):-1],
                'pid'=>['value'=>$id],
                'content' => ['value'=>'', 'placeholder'=>'请填写您的次元介绍，不能超过100个字...', 'max'=>'100', 'rows'=>'6'],
                'image' => ['value'=>[], 'max'=>'1', ],
                'title'=>['value'=>'', 'placeholder'=>'请填写您要发布的次元名称，不超过20个字...', 'max'=>'20'],
                'imageedit' => '1',
            ],
            'postUrl' => '/dimension/create',
            'fieldName' => 'header',

        ];
		return view("detailpubpage", $params);

	}
	//创建次元
	public function addDimension(){
        $userId = Auth::id();
        $payRes = GoldManager::publishPayGold('dimension', 0, $userId);
        if(!$payRes){
            return response()->json(['res'=>false, 'info'=>'金币不足，无法创建', 'url'=>'' ]);
        }
		$dimension = new Dimension;
		$dimension['name'] = CommonUtils::escapeSpecialChars(Input::get('title'));
		$dimension['text'] = CommonUtils::escapeSpecialChars(Input::get('content'));
		$dimension['user_id'] = $userId;
		$dimension['ip_id'] = Input::get('id','');
		$dimensionNew = Dimension::create([
			'name' => $dimension['name'],
			'ip_id'=> $dimension['ip_id'],
			'header' => Input::get('image'),
			'text' => $dimension['text'],
			'user_id' => $dimension['user_id'],
		]);
		$this->initializePublishNum($dimensionNew->id);
		$this->initializeEnter($dimensionNew->id);
		//return Redirect('/dimpub/list/diminfo/0/'. $dimensionNew->id);
        return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/dimension_'.$dimensionNew->id]);
	}

	public function initializePublishNum($id,$value = 0){
		$dimensionSum = new DimensionSum;
		$dimensionSum->dimension_id = $id;
		$dimensionSum->code = '31002';
		$dimensionSum->value = $value;
		$dimensionSum->save();
	}

	public function initializeEnter($id){
		$dimensionSum = new DimensionSum;
		$dimensionSum->dimension_id = $id;
		$dimensionSum->code = '31001';
		$dimensionSum->value = 1;
		$dimensionSum->save();
		$dimensionEnter = new DimensionEnter;
		$dimensionEnter->dimension_id = $id;
		$dimensionEnter->user_id = Auth::user()->id;
		$dimensionEnter->is_enter = 'Y';
		$dimensionEnter->save();
	}

	//编辑二次元
	public function showDimensionEdit($id)
	{

		$model = Dimension::find($id);
		$img = 
		$params = [
            'title' =>'编辑二次元',
            'description'=> '',
            'id'=>$id,
            'fields' =>[
                'act_id' => ['value'=>Input::get('act_id')?Input::get('act_id'):-1],
                'pid'=>['value'=>$id],
                'content' => ['value'=>$model->text, 'placeholder'=>'请填写您的次元介绍，不能超过100个字...', 'max'=>'100', 'rows'=>'6'],
                'image' => ['value'=>[$model->header->getOriginName()=>$model->header->getPath(1,'150w_160h_1e_1c')], 'max'=>'1'],
                'title'=>['value'=>$model->name, 'placeholder'=>'请填写您要发布的次元名称，不超过20个字...', 'max'=>'20']
            ],
            'postUrl' => '/dimension/edit',
            'fieldName' => 'header',
        ];
		return view("detailpubpage", $params);

	}

	public function editDimension(){
		$id = Input::get('id');
		$model = Dimension::find($id);
		$model->name = CommonUtils::escapeSpecialChars(Input::get('title'));
		$model->text = CommonUtils::escapeSpecialChars(Input::get('content'));
		$model->header = CommonUtils::evalPics(Input::get('image'));
        $model->save();
		//return Redirect('/dimpub/list/diminfo/0/'. $id);
        return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/dimension_'.$model->id]);
	}

    public function switchEnter(){
        $id = Input::get('id');
        $userId = Auth::user()->id;
        $dimensionEnter = DimensionEnter::where('user_id', $userId)
                ->where('dimension_id', $id)->first();
        $status = '';
        if($dimensionEnter == null){
            $dimensionEnter = new DimensionEnter;
            $dimensionEnter->user_id = $userId;
            $dimensionEnter->dimension_id = $id;
            $dimensionEnter->is_enter = 'Y';
            $status = 'Y';
        }else{
            $status = $dimensionEnter->is_enter=='Y'?'N':'Y';
            $dimensionEnter->is_enter = $status;
        }
        $dimensionEnter->save();
        return response()->json(['res'=>true, 'info'=>$status]);
    }

	public function postEnter($pid){
		if(Auth::check()){
			$id = $pid;
			$dimensionEnter = DimensionEnter::where('dimension_id',$id)->where('user_id',Auth::user()->id)->first();
			if(!empty($dimensionEnter)){
				if($dimensionEnter->is_enter == 'Y'){
					$enter = 'N';
				}else{
					$enter = 'Y';
				}
				$dimensionEnter->is_enter = $enter;
				$dimensionEnter->save();
			}else{
				$enter = 'Y';
				$dimensionEnterObj = new DimensionEnter;
				$dimensionEnterObj->dimension_id = $id;
				$dimensionEnterObj->user_id = Auth::user()->id;
				$dimensionEnterObj->is_enter = 'Y';
				$dimensionEnterObj->save();
			}
			$dimensionSum = DimensionSum::where('dimension_id',$id)->where('code','31001')->first();
			if($enter == 'Y'){
				$dimensionSum->value = $dimensionSum->value + 1;
			}else{
				$dimensionSum->value = $dimensionSum->value - 1;
			}
			$dimensionSum->save();
			return Redirect::back();
		}
	}


    public function getDimTags(){

		$attr = SysAttrEnum::where('column','40001')->get()->toArray();
        $arr = array();
        foreach($attr as $item){
            array_push($arr, ['key'=>$item["code"], 'name'=>$item["name"]]);
        }
        return response()->json($arr);
    }
    /*
     * 创建和修改,删除次元帖子
        * */ 
	public function displayCreateDimensionPublish($id){
        $params = [
            'title' =>'发帖子',
            'description'=> '',
            'id'=>0,
            'fields' =>[
                'act_id' => ['value'=>Input::get('act_id')?Input::get('act_id'):-1],
                'pid'=>['value'=>$id],
                'content' => ['value'=>'', 'placeholder'=>'帖子内容', 'max'=>'2500'],
                'image' => ['value'=>[], 'max'=>'9', ],
            ],
            'postUrl' => '/dimpub/publishcreate',
            'fieldName' => 'dimpub',
        ];
		return view("detailpubpage", $params);
	}

	public function addDimensionPublish(){
        $userId = Auth::id();
        $payRes = GoldManager::publishPayGold('dimension_publish', 0, $userId);
        if(!$payRes){
            return response()->json(['res'=>false, 'info'=>'金币不足，无法创建', 'url'=>'' ]);
        }
        $dimensionId = Input::get('pid');
		$dimensionPublish = new DimensionPublish;
		$dimensionPublish->dimension_id = $dimensionId;
		$dimensionPublish->text = Input::get('content');
		$dimensionPublish->image = CommonUtils::evalPics(Input::get('image'));
		$dimensionPublish->user_id = $userId;
		$dimensionPublish->save();
        $dimension = Dimension::find($dimensionId);
        $pubSum = $dimension->publishSum;
        if(!is_null($pubSum)){
            $pubSum->value += 1;
        }else{
            $pubSum = new DimensionSum;
            $pubSum->dimension_id = $dimensionPublish->dimension_id;
            $pubSum->code = '31002';//帖子数编号未31002
            $pubSum->value = 1;
        }
        $pubSum->save();

        $res = Activity::is_act($dimensionPublish->id,'dimension_publish');
        
        if($res){
            //return redirect(noBackUrl('/act/getshowjoin/'.$res));
            return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/act_'.$res]);
        }
        //return redirect(noBackUrl('/dimpub/'.$dimensionPublish->id));
        return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/dimensionpublish_'.$dimensionPublish->id]);
	}

	public function showDimensionPublishEdit($id)
	{
		$model = DimensionPublish::findOrFail($id);
        $imgArr = [];
        foreach($model->image as $img){
            $imgArr[$img->originName] = $img->getPath();
        }
        $params = [
            'title' =>'编辑帖子',
            'description'=> '',
            'id'=>$id,
            'fields' =>[
                'content' => ['value'=>$model->text, 'placeholder'=>'帖子内容', 'max'=>'2500'],
                'image' => ['value'=>$imgArr, 'max'=>'9'],
            ],
            'postUrl' => '/dimpub/publishedit',
            'fieldName' => 'dimpub',
        ];
		return view("detailpubpage", $params);
		return view('partview.dimension.dimensionpublishedit',array('model'=>$model));
	}

	public function editDimensionPublish()
	{
		$id = Input::get('id');
		$model = DimensionPublish::find($id);
        if(!OwnerHandler::checkByObj('dimension_publish',$model)){
            return response()->json(['res'=>false, 'info'=>'']);
        }
		$model->text = CommonUtils::escapeSpecialChars(Input::get('content'));
		$model->image = CommonUtils::evalPics(Input::get('image'));
		$model->save();
        //return redirect(noBackUrl('/dimpub/'.$model->id));
        return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/dimensionpublish_'.$model->id]);
	}
	public function deleteDimensionPublish(){
		$id = Input::get('id');
		$model = DimensionPublish::find($id);
        if(!OwnerHandler::checkByObj('dimension_publish',$model)){
            return response()->json(['res'=>false]);
        }
		$model->delete();
		$pubSum = DimensionSum::where('dimension_id',$model->dimension_id)->where('code','31002')->first();
		$pubSum->value =$pubSum->value - 1;
		$pubSum->save();
        return response()->json(['res'=>true]);
	}

}
?>
