<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models as MD;
use Input;
use Auth, DB;
class CheckController extends Controller
{
    public function getProd(){
        $query = MD\UserProduction::orderBy(DB::raw('ifnull(`verified_at`,1)'))
            ->orderBy('id');
        $items = $query->paginate(15);
        $arr = [];
        $nArr = ['coll'=>'同人作品', 'peri'=>'周边产品', 'disc'=>'长评论', 'none'=>'用户作品'];
        return view('admins.pages.uprodlist',['type'=>'用户作品', 'items'=>$items, 'prefix'=>'/user/product/']);
    }

    public function postDelete($entityName)
    {
        $ids = Input::get('ids');
        $idArr = explode(',',$ids);
        foreach($idArr as $id){
            if(!is_numeric($id)) continue;
            $entity = self::getEntity($entityName, $id);
            if(!is_null($entity)) {
                $entity->delete();
            }
        }
        return response()->json(['res'=>true, 'info'=>$ids]);
    }
    public function postApprove($entityName)
    {
        $ids = Input::get('ids');
        $idArr = explode(',',$ids);
        foreach($idArr as $id){
            if(!is_numeric($id)) continue;
            $entity = self::getEntity($entityName, $id);
            if(!is_null($entity)) {
                $entity->approve();
            }
        }
        return response()->json(['res'=>true, 'info'=>$ids]);
    }
    public function postReject($entityName)
    {
        $ids = Input::get('ids');
        $idArr = explode(',',$ids);
        foreach($idArr as $id){
            if(!is_numeric($id)) continue;
            $entity = self::getEntity($entityName, $id);
            if(!is_null($entity)) {
                $entity->reject();
            }
        }
        return response()->json(['res'=>true, 'info'=>$ids]);
    }
    //private static functions
    private static function getEntity($entityName, $id)
    {
        switch($entityName){
        case 'ip':
            return MD\Ip::findOrFail($id);
        case 'ip_scene':
            return MD\IpScene::findOrFail($id);
        case 'ip_dial':
            return MD\IpDialogue::findOrFail($id);
        case 'role':
            return MD\IpRole::findOrFail($id);
        case 'coll':
            return MD\UserProduction::findOrFail($id);
        case 'peri':
            return MD\UserProduction::findOrFail($id);
        case 'long_discussion':
            return MD\UserProduction::findOrFail($id);
        case 'user_production':
            return MD\UserProduction::findOrFail($id);
        }
        return null;
    }
}
