<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Special;
use App\Models\SpecialItem;
use App\Models\Ip;
use App\Models\UserProduction;
use App\Common\Image;
use Input, Auth;
class SpecialController extends Controller
{
    public function getList(){
        $items = Special::orderBy('created_at','desc')->paginate(15);
        $uploadParams = Image::getUploadAliImageParams();
        return view('admins.pages.speciallist',['items'=>$items]);
    }
    public function getSpecial($id){
        $special = Special::find($id);
        $params = array();
        if(!is_null($special)) $params['model'] = $special;
        $uploadParams = Image::getUploadAliImageParams();
        $params['uploadParams'] = $uploadParams;
        return view('admins.pages.specialform', $params);
    }
    public function postSpecial(){
        $id = Input::get('id');
        $name = Input::get('name');
        $intro = Input::get('intro');
        $publishDate = Input::get('publish_date');
        $image = Input::get('image');
        $userId = Auth::user()->id;
        if($id == 0){
            $special = new Special;
            $special->creator = $userId;
        }else{
            $special = Special::findOrFail($id);
        }
        $special->updater = $userId;
        $special->name = $name;
        $special->intro = $intro;
        $special->img = $image;
        $special->publish_date = $publishDate;
        $special->status = 1;
        $special->save();
        return redirect('/admin/sp/list');
    }
    public function postChangeStatus(){
        $id = Input::get('id');
        $status = input::get('status');
        $special = Special::findOrFail($id);
        $special->status = $status;
        $special->updater = Auth::user()->id;
        $special->save();
        return response()->json(['res'=>true]);
    }
    public function postDeleteSpecial(){
        $id = Input::get('id');
        Special::where('id', $id)->delete();
        SpecialItem::where('special_id', $id)->delete();
        return response()->json(['res'=>true]);
    }
    public function getItemList($id){
       $items = SpecialItem::where('special_id', $id)->get();
       $uploadParams = Image::getUploadAliImageParams();
       return view('admins.pages.specialitemlist',['items'=>$items, 'id'=>$id, 'uploadParams'=>$uploadParams]);
    }
    public function postAddItem(){
        $specialId = Input::get('special_id');
        $type = Input::get('type');
        $resourceId = Input::get('id');
        $specialItem = new SpecialItem;
        if(in_array($type, ['game','story','cartoon','light'])){
            $ip = Ip::findOrFail($resourceId);
            $specialItem->special_id = $specialId;
            $specialItem->url= $ip->ipPath;
            $specialItem->intro = '无推荐说明';
            $specialItem->name = $ip->name;
            $specialItem->img = $ip->cover->getOriginName();
            $specialItem->creator = Auth::user()->id;
            $specialItem->type = $type;
            $specialItem->resource_id = $resourceId;
            $specialItem->save();
        }
        elseif(in_array($type, ['coll','peri','disc', 'original'])){
            $userProd = UserProduction::findOrFail($resourceId);
            $specialItem->special_id = $specialId;
            $specialItem->url= $userProd->detailUrl;
            $specialItem->intro = '无推荐说明';
            $specialItem->name = $userProd->name;
            $specialItem->img = $userProd->cover->getOriginName();
            $specialItem->creator = Auth::user()->id;
            $specialItem->type = $type;
            $specialItem->resource_id = $resourceId;
            $specialItem->save();
        }
        return response()->json(['res'=>true]);
    }
    public function getSpecialItem($id){
        $item = SpecialItem::findOrFail($id);
        $arr = [];
        $arr['name'] = $item->name;
        $arr['intro'] = $item->intro;
        $arr['img'] = $item->img->getOriginName();
        $arr['imgPerview'] = $item->img->getPath(1,'93w_110h_1e_1c');
        $arr['id'] = $id;
        return response()->json($arr);
    }
    public function postSpecialItem(){
        $id = Input::get('id');
        $name = Input::get('name');
        $intro = Input::get('intro');
        $img = Input::get('img');

        $item = SpecialItem::findOrFail($id);
        $item->name = $name;
        $item->intro = $intro;
        $item->img = $img;
        $item->save();
        return response()->json(['res'=>true]);
    }
    //删除专辑内容
    public function postSpecialItemDel(){
        $id = Input::get('id');
        $item = SpecialItem::findOrFail($id);
        $item->delete();
        return response()->json(['res'=>true]);
    }
}
