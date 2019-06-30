<?php

namespace App\Http\Controllers\Admin;

use App\Common\RedirectCode;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SysTag;
use App\Models\Ip;
use App\Models\UserProduction;
use App\Models\Dimension;
use App\Models\Activity;
use App\Models\DimensionPublish;
use App\Models\Special;
use Input, DB;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return view('admins.pages.systemctrl');
    }
    /*---------二维码以及链接管理--------*/
    public function getQrCode(){
        return view('admins.pages.qrcode');
    }
    public function postMakeValue(){
        $type     = Input::get('type');
        $resource = Input::get('resource');
        $id       = Input::get('id');
        $result = true;
        $imgUrl = '';
        switch($resource){
            case '0000':
                $imgUrl =  'http://img.umeiii.com/avatar/def-1497073963147-.jpg';
                break;
            case '9000':
                $imgUrl = '';
                break;
            default:
                $res = $this->getResource($resource,$id);
                if(is_null($res)){
                  $result = false;
                }else{
                    $imgUrl = $res->cover->getPath(1,'100w_100h_1e_1c');
                }
        }
        $urlSet = $this->getQrUrl($type, $resource, $id);
        return response()->json(['res'=>$result, 'url'=>$urlSet['url'], 'imgPath'=>$imgUrl,'jumpPath'=>$urlSet['jumpPath']]);
    }
    private function getQrUrl($type, $resource, $id){
        $redirectCode = new RedirectCode();
        $redirectCode->mode         = RedirectCode::MODE_LOGIN;
        $redirectCode->type         = $type;
        $redirectCode->resource    = $resource;
        $redirectCode->resource_id = $id;

        $code = $redirectCode->getCode();
        $url  = $this->getTypePath($type);
        $url .= $code;

        $jump = $redirectCode->getUrl();

        $urlArr=array(
            "url"     => 'http://www.umeiii.com'. $url,
            "jumpPath" => $jump
        );
        return $urlArr;
    }
    private function getTypePath($type){
        $url = '';
        switch($type){
            case RedirectCode::TYPE_WECHAT:
                $url .= "/wechat";
                break;
            case RedirectCode::TYPE_QQ:
                $url .= "/qq";
                break;
            case RedirectCode::TYPE_WEB:
                $url .= "/auth";
                break;
        }
        return $url.'/login/';
    }

    private function getResource($resourceCode, $id){
        switch($resourceCode){
            case '1000':
                return Ip::find($id);
            case '2000':
                return UserProduction::find($id);
            case '3000':
                return Dimension::find($id);
            case '3100':
                return DimensionPublish::find($id);
            case '5000':
                return Activity::find($id);
            case '6000':
                return Special::find($id);
        }
        return null;
    }
    /*---------标签管理--------*/
    public function getTags()
    {
        $tags = SysTag::All(); 
        $tagsArr = [];
        foreach($tags as $tag){
            if(!array_key_exists($tag->depend,$tagsArr)){
                $tagsArr[$tag->depend] = [];
            }
            array_push($tagsArr[$tag->depend], $tag);
        }
        return view('admins.pages.tagsCtrl',['tags'=>$tagsArr]);
    }
    public function postAddTag(){
        $type = Input::get('type');
        $tagName = Input::get('tagName');
        //检查是否存在
        if($this->checkTagExist($tagName, $type)){
            return response()->json(['res'=>false, 'info'=>'标签已经存在了。']);
        }
        //获取最大标签号
        $code = $this->getNewTagNumber($type);
        //创建标签
        $tag = new SysTag;
        $tag->name = Input::get('tagName');
        $tag->depend = $type;
        $tag->hot = 1;
        $tag->code = $code;
        $tag->save();
        return response()->json(['res'=>true, 'name'=>$tag->name, 'code'=>$tag->code]);
    }
    public function postDeleteTag(){
        $code = Input::get('code');
        $tag = SysTag::where('code', $code)->first();
        if(is_null($tag)){
            return response()->json(['res'=>false, 'info'=>'标签不存在。']);
        }
        $oldName = $tag->name;
        $oldDepend = $tag->depend;
        $tag->delete();
        //移除IP的标签
        Ip::where('tags', 'like', '%'.$oldName.';%')->where('type',$oldDepend)->update([
            'tags'=>DB::raw("replace(tags, '$oldName;', '')")
        ]);
        return response()->json(['res'=>true ]);
    }
    public function postEditTag(){
        $code = Input::get('code');
        $tagName = Input::get('tagName');
        $tag = SysTag::where('code', $code)->first();
        if(is_null($tag)){
            return response()->json(['res'=>false, 'info'=>'标签不存在。']);
        }
        $oldName = $tag->name;
        $tag->name = $tagName;
        $tag->save();
        //更新IP的标签
        Ip::where('tags', 'like', '%'.$oldName.';%')->where('type',$tag->depend)->update([
            'tags'=>DB::raw("replace(tags, '$oldName;', '$tagName;')")
        ]);
        return response()->json(['res'=>true, 'name'=>$tag->name, 'code'=>$tag->code]);
    }
    private function getNewTagNumber($type){
        $tag = SysTag::where('depend', $type)->orderBy('code', 'desc')->first();
        $maxCode = $tag->code;
        return $maxCode+1;
    }
    private function checkTagExist($name, $type, $isName = true){
        $ct = SysTag::where($isName?'name':'code', $name)->where('depend', $type)->count();
        return $ct > 0;
    }
}
