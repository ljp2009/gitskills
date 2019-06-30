<?php

namespace App\Models;
use App\Models\Dimension;
use App\Models\DimensionPublish;
use App\Models\LikeModel;
use App\Models\LikeSumModel;
use App\Models\Discussion;
use App\Models\DiscussionSum;
use App\Common\OwnerHandler;
use Auth;

class ListItem
{
	private $user = null;
    private $user_id = 0;
	private $imageList = array();
	private $title = null;
	private $text = '';
	private $label = '';
	private $createdAt = null;
	private $resource = '';
	private $id = 0;
	private $url ='';
    private $link = null;
    private $object = null;
	private $likeStatus = false;//0,unknow; 1,unlike; 2,like
	private $isDelete = true;
    private $imgFormat = '128w_128h_1e_1c';
    public function __construct($resource, $id){
        $this->resource = $resource;
        $this->id = $id;
    }
    public function __get($name){
        return isset($this->$name)?$this->$name:null;
    }
    public function __set($name, $value){
        $this->$name = $value;
        if($name == 'user'){
            $this->user_id = $user->id;
        }
    }
    public function setDefImgFormat($value){
        $this->imgFormat = $value;
    }
    public function getDefImgFormat(){
        return $this->imgFormat;
    }
    public function attachImg($img){
        array_push($this->imageList, $img);
    }
    public function getEditUrl(){
        switch($this->resource){
            case "dimension":
                return '/dimension/edit/'.$this->id;
            case "scene":
                return ''.$this->id;
            case "dialouge":
                return ''.$this->id;
            case "role":
                return ''.$this->id;
            case "ip_role":
                return ''.$this->id;
            case "user_production":
                return ''.$this->id;
            default:
                return '/'.$this->resource.'/'.$this->id;
        }
    }
    public function getDeleteUrl(){
        switch($this->resource){
            case "dimension":
                return '/dimension/delete/'.$this->id;
            case "scene":
                return ''.$this->id;
            case "dialouge":
                return ''.$this->id;
            case "role":
                return ''.$this->id;
            case "ip_role":
                return ''.$this->id;
            case "user_production":
                return ''.$this->id;
            default:
                return '/'.$this->resource.'/'.$this->id;
        }
    }
    public function getLikeCount(){
        return LikeSumModel::countLike($this->resource, $this->id);
    }
    public function getDiscCount(){
        return Discussion::countDiscuss($this->resource, $this->id);
    }
    public function checkUserLike(){
        return LikeModel::checkLike($this->resource, $this->id);
    }
    public function checkOwner(){
        return OwnerHandler::checkByObj($this->resource, $this);
    }
    public function getShotText(){
        $st =  mb_substr($this->text, 0,70,'utf-8');
        if(strlen($this->text) > 70){
            $st .= '...';
        }
        return $st;
    }
    public function getFormatText(){
        return '<p>'.str_replace("\n", "</p><p>", $this->text).'</p>';
    }
    /*
     * 静态方法
    * */
    public static function makeDimensionPublishListItems($dimPublishs){
        $listArr = [];
        foreach($dimPublishs as $dimPublish){
            $item = new ListItem('dimension_publish', $dimPublish->id);
            $item->object = $dimPublish;
            $item->user = $dimPublish->user;
            $item->createdAt = $dimPublish->created_at;
            $item->imageList = $dimPublish->image;
            $item->url = $dimPublish->detailUrl;
            $item->text = $dimPublish->text;
            array_push($listArr, $item);
        }
        return $listArr;
    }
    public static function makeSceneListItems($scenes){
        $listArr = [];
        foreach($scenes as $scene){
            $item = new ListItem('ip_scene', $scene->id);
            $item->object = $scene;
            $item->user = $scene->user;
            $item->createdAt = $scene->created_at;
            $item->imageList = $scene->image;
            $item->url = $scene->detailUrl;
            $item->text = $scene->text;
            array_push($listArr, $item);
        }
        return $listArr;
    }
    public static function makeDialogueListItems($dialogues){
        $listArr = [];
        foreach($dialogues as $dialogue){
            $item = new ListItem('ip_dialogue', $dialogue->id);
            $item->object = $dialogue;
            $item->user = $dialogue->user;
            $item->createdAt = $dialogue->created_at;
            $item->url = $dialogue->detailUrl;
            $item->text = $dialogue->textPart;
            $item->label ='-----'.$dialogue->rolePart;
            array_push($listArr, $item);
        }
        return $listArr;
    }
    public static function makeUserProductionListItems($productions){
        $listArr = [];
        foreach($productions as $production){
            $item = new ListItem('user_production', $production->id);
            $item->object = $production;
            $item->user = $production->user;
            $item->createdAt = $production->created_at;
            if($production->relate_type == 'disc'){
                $item->imageList = [];
            }else{
                $item->imageList = $production->images;
            }
            $item->url = $production->detailUrl;
            $item->text = $production->intro;
            $item->title = $production->title;
            $item->link = $production->link;
            array_push($listArr, $item);
        }
        return $listArr;
    }
    public static function makeRoleListItem($roles){
        $listArr = [];
        foreach($roles as $role){
            $item = new ListItem('ip_role', $role->id);
            $item->object = $role;
            $item->user = $role->user;
            $item->createdAt = $role->created_at;
            $item->imageList = [$role->header];
            $item->url = $role->detailUrl;
            $item->title = $role->name;
            $item->text = $role->intro;
            $item->setDefImgFormat('100h_100w_1e|100x100-2rc');
            array_push($listArr, $item);
        }
        return $listArr;
    }
}
