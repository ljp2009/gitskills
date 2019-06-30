<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\Image;
use App\Models\User;
use App\Models\Ip;
use App\Models\IpRole;
use App\Models\Dimension;
use App\Models\IpScene;
use App\Models\IpDialogue;
use App\Models\IpColleague;
use App\Models\IpPeripheral;
use App\Models\UserProduction;
use App\Models\ProductionContent;
use Input;
class UserController extends Controller
{
    public static function getPrefix(){
        $urlPrefix = 'http://www.umeiii.com/';
        return $urlPrefix;
    }
    public function getList(){
        $allUser = User::paginate(15);
        return view('admins.pages.userlist',['items'=>$allUser]);
    }
    public function getIpList($uid){
        $items = Ip::where('creator', $uid)->paginate(15);
        $arr = [];
        foreach($items as $ip){
            $ipArr = [];
            $ipArr['id'] = $ip->id;
            $ipArr['name'] = $ip->name;
            $ipArr['img'] = Image::makeImage($ip->cover)->getPath(1,'64h_64w_1e|64x64-2rc');
            $arr[$ip->id] = $ipArr;
        }
        return view('admins.pages.uliplist',['items'=>$items,'itemShow'=>$arr, 'prefix'=>self::getPrefix().'ip/']);
    }
    public function getRoleList($uid){
        $items = IpRole::where('creator', $uid)->paginate(15);
        $arr = [];
        foreach($items as $item){
            $itemArr = [];
            $itemArr['id'] = $item->id;
            $itemArr['name'] = $item->name;
            $itemArr['img'] = Image::makeImage($item->header)->getPath(1,'64h_64w_1e|64x64-2rc');
            $arr[$item->id] = $itemArr;
        }
        return view('admins.pages.uliplist',['type'=>'角色','items'=>$items,'itemShow'=>$arr, 'prefix'=>self::getPrefix().'roles/']);
    }
    public function getDimList($uid){
        $items = Dimension::where('user_id', $uid)->paginate(15);
        $arr = [];
        foreach($items as $item){
            $itemArr = [];
            $itemArr['id'] = $item->id;
            $itemArr['name'] = $item->name;
            $itemArr['img'] = Image::makeImage($item->header)->getPath(1,'64h_64w_1e|64x64-2rc');
            $arr[$item->id] = $itemArr;
        }
        return view('admins.pages.uliplist',['type'=>'次元','items'=>$items,'itemShow'=>$arr, 'prefix'=>self::getPrefix().'dimension/list/user/0/']);
    }
    public function getSceneList($uid){
        $items = IpScene::where('user_id', $uid)->paginate(15);
        $arr = [];
        foreach($items as $item){
            $itemArr = [];
            $itemArr['id'] = $item->id;
            $itemArr['name'] = $item->text;
            $itemArr['img'] = Image::makeImage($item->image)->getPath(1,'64h_64w_1e|64x64-2rc');
            $arr[$item->id] = $itemArr;
        }
        return view('admins.pages.uliplist',['type'=>'场景','items'=>$items,'itemShow'=>$arr, 'prefix'=>self::getPrefix().'ipscene/']);
    }
    public function getDialList($uid){

        $items = IpDialogue::where('user_id', $uid)->paginate(15);
        $arr = [];
        foreach($items as $item){
            $itemArr = [];
            $itemArr['id'] = $item->id;
            $itemArr['name'] = $item->text[0];
            $itemArr['author'] = $item->text[1];
            $itemArr['img'] = 'http://img.umeiii.com/default.jpg@64h_64w_1e|64x64-2rc';
            $arr[$item->id] = $itemArr;
        }
        return view('admins.pages.uliplist',['type'=>'台词', 'items'=>$items,'itemShow'=>$arr, 'prefix'=>self::getPrefix().'ipdialogue/']);
    }
    public function getProdList($uid, $type='none'){
        $query = UserProduction::where('user_id', $uid);
        if($type != 'none'){
            $query = $query->where('relate_type', $type);
        }
        $items = $query->paginate(15);
        $arr = [];
        $nArr = ['coll'=>'同人作品', 'peri'=>'周边产品', 'disc'=>'长评论', 'none'=>'用户作品'];
        return view('admins.pages.uprodlist',['type'=>$nArr[$type], 'items'=>$items, 'prefix'=>self::getPrefix().'/user/product/']);
    }
    public function getCollList($uid){
        return $this->getProdList($uid, 'coll');
    }
    public function getPeriList($uid){
        return $this->getProdList($uid, 'peri');
    }
    public function getDiscList($uid){
        return $this->getProdList($uid, 'disc');
    }
    public function postDeleteProd(){
        $id = Input::get('id');
        UserProduction::where('id', $id)->delete();
        ProductionContent::where('production_id', $id)->delete();
        return response()->json(['res'=>true, 'info'=>$id]);
    }
}
