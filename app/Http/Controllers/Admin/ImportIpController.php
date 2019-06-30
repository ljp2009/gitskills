<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Ip;
use App\Models\IpIntro;
use App\Models\IpAttr;
use App\Models\IpRole;
use App\Models\IpRoleSkill;
use App\Models\SysTag;
use Input, Auth, File, DB;

class ImportIpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('importdata');
    }
    public function postImportTags(){
        $fileInfo = Input::file('dataFile');
        $file = fopen($fileInfo->getPathname(),'r');
        $rowHeader = fgetcsv($file);// 预读第一行，去除标题
        DB::delete('delete from sys_tag');
        while(!feof($file)) {
            $row = fgetcsv($file);
            $name = $row[0];
            $type = $row[1];
            $hot = $row[2];
            $code = $row[3];
            echo "<pre>";
            var_dump($row);
            echo "</pre>";
            $tag = new SysTag;
            $tag->name = $name;
            $tag->hot = $hot;
            $tag->code = $code;
            $tag->depend = $type;
            $tag->save();
        }
    }
    public function postImportIpTag(){
        $fileInfo = Input::file('dataFile');
        $file = fopen($fileInfo->getPathname(),'r');
        $rowHeader = fgetcsv($file);// 预读第一行，去除标题
        while(!feof($file)) {
            $row = fgetcsv($file);
            $this->saveTag($row);
        }
    }
    private function saveTag($rowData){
        $type = $rowData[0];
        $ipName = $rowData[1];
        if(empty($ipName)) return;
        $ips = Ip::where('name', $ipName)->where('type', $type)->get();
        $tags = '';
        for($i=2;$i<6;$i++){
            if(isset($rowData[$i]) && !empty($rowData[$i])){
                $tags .= ($rowData[$i].';');
            }
        }
        foreach($ips as $ip){
            $ip->tags = $tags;
            $ip->save();
        }
        if(count($ips) != 1){
            echo $type.' '.$ipName.' '.count($ips).' '.$tags.'<br />';
        }
    }
    public function postImportIp(Request $request){
        $type = Input::get('type');
        $fileInfo = Input::file('dataFile');
        $file = fopen($fileInfo->getPathname(),'r');
        $rowHeader = fgetcsv($file);// 预读第一行，去除标题
        $set = $this->getExcelMap($type, $rowHeader);
        while(!feof($file)) {
            $row = fgetcsv($file);
            $this->saveData($type, $set, $row);
        }
    }
    private function saveData($type, $set, $dataRow){
        $ip = [];
        $ip['type'] = $type;
        $ip['creator'] = 2;
        $ip['validated'] = 1;
        $ip['validator'] = 2;
        $ip['like_sum'] = 0;

        $ipIntro = '';
        $ipAttrs = [];
        $ipRoles = [];
        for($i=0;$i<count($set);$i++){
            if($set[$i]=='index'){
                if(empty($dataRow[$i])){
                    return;
                }else{
                    continue;
                }
            }
            if(empty($dataRow[$i])) continue; 
            $sp = explode('_', $set[$i]);
            switch($sp[0]){
            case 'ip':
                $ip[$sp[1]] = ($sp[1] == 'cover'?($type.'/'.$dataRow[$i]):$dataRow[$i]);
                break;
            case 'intro':
                $ipIntro = $dataRow[$i];
                break;
            case 'attr':
                $ipAttrs[$sp[1]] = $dataRow[$i];
                break;
            case 'role':
                if(!array_key_exists($sp[1], $ipRoles)){
                    $ipRoles[$sp[1]] = [];
                }
                $ipRoles[$sp[1]][$sp[2]] = ($sp[2] == 'image'?($type.'/'.$dataRow[$i]):$dataRow[$i]);
                break;
            }
        }
        /*
        echo '<pre>';
        $ipinfo = [];
        $ipinfo['ip'] = $ip;
        $ipinfo['intro'] = ['intro'=>$ipIntro];
        $ipinfo['attrs'] = $ipAttrs;
        $ipinfo['roles'] = $ipRoles;
        var_dump($ipinfo);
        echo '</pre>';
         */
        //return;
        $ipId = $this->saveIp($ip);
        if(!empty($ipIntro)){
            $this->saveIntro($ipId, $ipIntro);
        }

        foreach($ipAttrs as $key=>$attr){
            if($attr == '') continue;
            $this->saveIpAttr($ipId, $key, $attr);
        }
        
        foreach($ipRoles as $role){
            if(!array_key_exists('name', $role)) continue;
            if(!array_key_exists('skill', $role)) $role['skill'] = '';
            if(!array_key_exists('image', $role)) $role['image'] = '';

            $this->saveRole($ipId, $role['name'],$role['skill'], $role['image']);
        }

    }
    private function saveIp($ipArr){
        $tmpIp = Ip::where('name', $ipArr['name'])->where('type', $ipArr['type'])->first();
        if(is_null($tmpIp)){
            $tmpIp = new Ip;
            foreach($ipArr as $key=>$value){
                $tmpIp->$key = $ipArr[$key];
            }
            $tmpIp->save();
            return $tmpIp->id*-1;
        }
        else{
            if(!$tmpIp->cover->checkSet() && !empty($ipArr['cover'])){
                $tmpIp->cover = $ipArr['cover'];
                $tmpIp->save();
            }
            return $tmpIp->id;
        }
    }
    private function saveIntro($ipId, $ipIntro){
        if($ipId < 0){
            $tmpIpIntro = new IpIntro;
            $tmpIpIntro->ip_id = $ipId * -1;
        }else{
            $tmpIpIntro = IpIntro::where('ip_id', $ipId)->first();
            if(is_null($tmpIpIntro)){
            $tmpIpIntro = new IpIntro;
            $tmpIpIntro->ip_id = $ipId;
            }
        }
        $tmpIpIntro->intro = $ipIntro;
        $tmpIpIntro->save();
    }
    private function saveIpAttr($ipId, $code, $value){
        if($ipId < 0){
            $attr = new IpAttr;
            $attr->ip_id = $ipId * -1;
            $attr->code = $code;
        }else{
            $attr = IpAttr::where('ip_id', $ipId)->where('code', $code)->first();
            if(is_null($attr)){
                $attr = new IpAttr;
                $attr->ip_id = $ipId;
                $attr->code = $code;
            }
        }
        $attr->value = $value;
        $attr->save();
    }
    private function saveRole($ipId, $name, $skill, $image){
        $isNew = false;
        if($ipId < 0){
            $role = new IpRole;
            $isNew = true;
            $role->ip_id = $ipId * -1;
        }else{
            $role = IpRole::where('ip_id', $ipId)->where('name', $name)->first();
            if(is_null($role)){
                $isNew = true;
                $role = new IpRole;
                $role->ip_id = $ipId;
            }else{
                if(!$role->image->checkSet() && !empty($image)){
                    $role->image = $image;
                    $role->header = $image;
                }
            }
        }
        if($isNew){
            $role->name = $name;
            $role->intro = $name.(empty($skill)?'':(' 技能：'.$skill));
            if(!empty($image)){
                $role->header = $image;
                $role->image = $image;
            }
            $role->creator = 2;
            $role->user_id = 2;
            $role->is_lock = 0;
            $role->like_sum = 0;
        }
        $role->save();
    }

    public function getExcelMap($type, $rowHeader){
        $mp = [
            'cartoon'=>[
                '序号'=> 'index',
                '名称'=> 'ip_name',
                '封面'=> 'ip_cover',
                '简介'=> 'intro_intro',
                '作品状态'=> 'attr_10002',
                '作画监督'=> 'attr_10001',
                '首播时间'=> 'attr_10003',
                '集数'=> 'attr_10004',
                '主角1姓名' => 'role_a_name',
                '主角2姓名' => 'role_b_name',
                '主角3姓名' => 'role_c_name',
                '主角4姓名' => 'role_d_name',
                '主角1技能' => 'role_a_skill',
                '主角2技能' => 'role_b_skill',
                '主角3技能' => 'role_c_skill',
                '主角4技能' => 'role_d_skill',
                '主角1形象' => 'role_a_image',
                '主角2形象' => 'role_b_image',
                '主角3形象' => 'role_c_image',
                '主角4形象' => 'role_d_image',
            ],
            'story'=>[
                '序号'=> 'index',
                '名称'=> 'ip_name',
                '作者'=> 'attr_10008',
                '作品状态'=> 'attr_10009',
                '首发时间'=> 'attr_10010',
                '字数'=> 'attr_10011',
                '封面'=> 'ip_cover',
                '故事简介'=> 'intro_intro',
                '主角1姓名' => 'role_a_name',
                '主角1技能' => 'role_a_skill',
                '主角1形象' => 'role_a_image',
                '主角2姓名' => 'role_b_name',
                '主角2技能' => 'role_b_skill',
                '主角2形象' => 'role_b_image',
                '主角3姓名' => 'role_c_name',
                '主角3技能' => 'role_c_skill',
                '主角3形象' => 'role_c_image',
                '主角4姓名' => 'role_d_name',
                '主角4技能' => 'role_d_skill',
                '主角4形象' => 'role_d_image',
            ],
            'light'=>[
                '序号'=> 'index',
                '名称'=> 'index',
                '作者'=> 'index',
                '作品状态'=> 'index',
                '首发时间'=> 'index',
                '字数'=> 'index',
                '封面'=> 'index',
                '故事简介'=> 'index',
            ],
            'game'=>[
                '序号'=> 'index',
                '中文名'=> 'ip_name',
                '简介'=> 'intro_intro',
                '开发商'=> 'attr_10005',
                '发行日期'=> 'attr_10006',
                '封面'=> 'ip_cover',
                '角色1姓名' => 'role_a_name',
                '角色1形象' => 'role_a_image',
                '角色1技能' => 'role_a_skill',
                '角色2姓名' => 'role_b_name',
                '角色2形象' => 'role_b_image',
                '角色2技能' => 'role_b_skill',
                '主角3姓名' => 'role_c_name',
                '主角3形象' => 'role_c_image',
                '主角3技能' => 'role_c_skill',
                '主角4姓名' => 'role_d_name',
                '主角4形象' => 'role_d_image',
                '主角4技能' => 'role_d_skill',
            ],
        ];
        $tmp = $mp[$type];
        $res = [];
        for($i=0;$i<count($rowHeader);$i++){
            $res[$i] = $tmp[$rowHeader[$i]];
        }
        return $res;
    }
}
