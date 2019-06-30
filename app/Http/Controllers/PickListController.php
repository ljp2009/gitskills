<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
/*
 * 管理全部的选择列表
 * 返回值结构为[
 *    0=>['key'=>$itemKey, 'text'=>$itemText, 'img'=>$itemImage, 'parent'=>$itemParentKey],
 *    1=>['key'=>$itemKey, 'text'=>$itemText, 'img'=>$itemImage, 'parent'=>$itemParentKey],
 *    2=>['key'=>$itemKey, 'text'=>$itemText, 'img'=>$itemImage, 'parent'=>$itemParentKey],
 * ];
    * */
class PickListController extends Controller
{
    //反复partview形式的picklist数据
    public function getPartview($listName, $fieldName){
        $data = $this->getListData($listName);
        return view('task.partview.edptselector', ['fieldName'=>$fieldName, 'data'=>$data]);
    }
   // 返回json格式的picklist数据
    public function getJson($listName, $fieldName){
        $data = $this->getListData($listName, $partName);
        return response()->json($data);
    }

    // 页面的partview
    private function getListData($listName){
        switch($listName){
            case 'user_skill':
                return self::getUserSkillList();
            case 'task_model':
                return self::getUserSkillList();
        }
    }
    private static function getUserSkillList(){
        $skills   = SysUserSkill::orderBy('hot', 'desc')->get();
        $skillArr = array();
        foreach ($skills as $skill) {
            array_push($skillArr, ['key'=>$skill->code, 'text'=>$skill->name]);
        }
        return $skillArr;
    }
    private static function getTaskModelList(){
        $arr = [
            ['key'=>'simple', 'text'=>'约定模式'],
            ['key'=>'tenders', 'text'=>'PK模式']
        ];
        return $arr;
    }
}
