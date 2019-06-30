<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Ip;
use App\Models\SysUserSkill;
use App\Models\UserProduction;
use App\Models\ProductionContent;
use App\Models\UserProdSellInfo;
use Input, Auth, Redirect;
use App\Models\ActivityPartner;
use App\Models\Activity;
use App\Common\IpContributorHandler;
use App\Common\GoldManager;
use App\Common\OwnerHandler;
class ProductionPublishController extends Controller
{
    //创建作品
    public function getCreate(){
        return $this->getCreatePage('发布作品','',0);
    }
    //创建同人
    public function getCreateColl($ipId=0){
        return $this->getCreatePage('发布同人','coll',$ipId);
    }
    //创建周边
    public function getCreatePeri($ipId=0){
        return $this->getCreatePage('发布周边','peri',$ipId);
    }
    //创建长评论
    public function getCreateDiscussion($ipId=0){
        return $this->getCreatePage('发布长评','disc', $ipId);
    }

    private function getCreatePage($title, $relateType, $ipId){
        $arr['act_id'] = Input::get('act_id')?Input::get('act_id'):-1;
        $arr['title'] = $title;
        $arr['post'] = '/pub/create-work';
        if($ipId > 0 && in_array($relateType, ['coll', 'peri', 'disc'])){
            $ip = Ip::findOrFail($ipId);
            $arr['ip'] = [
                'id'=>$ip->id,
                'cover'=>$ip->cover->getPath(1,'64w_64h_1e_1c'),
                'name'=>$ip->name,
                'cardInfo'=>$ip->cardInfo
            ];
            $arr['relateType'] = $relateType;
        }
        return view('publish.setproduction', $arr);
    }
    //编辑作品
    public function getModify($id){
        return $this->getModifyPage($id,'编辑作品', null);
    }
    //创建同人
    public function getModifyColl($id=0){
        return $this->getModifyPage($id,'编辑同人', 'coll');
    }
    //创建周边
    public function getModifyPeri($id=0){
        return $this->getModifyPage($id,'编辑周边', 'peri');
    }
    //创建长评论
    public function getModifyDiscussion($id=0){
        return $this->getModifyPage($id,'编辑长评', 'disc');
    }
    private function getModifyPage($id, $title, $relateType){
        $arr['act_id'] = Input::get('act_id')?Input::get('act_id'):-1;
        $arr['title'] = $title;
        $arr['post'] = '/pub/modify-work';

        $prod = UserProduction::findOrFail($id);
        $res = $prod->convertContent();
        if($res > 0){
            $prod = UserProduction::findOrFail($id);
        }
        $arr['originData'] = $prod;
        if(!is_null($prod->ip)){
            $ip = $prod->ip;
            $arr['ip'] = [
                'id'=>$ip->id,
                'cover'=>$ip->cover->getPath(1,'64w_64h_1e_1c'),
                'name'=>$ip->name,
                'cardInfo'=>$ip->cardInfo
            ];
            $arr['relateType'] = $prod->relate_type;
        }
        return view('publish.setproduction', $arr);
    }
    //保存创建作品
    public function postCreateWork(Request $request){
        return $this->postWork($request);
    }
    //保存更新作品
    public function postModifyWork(Request $request){
        return $this->postWork($request);
    }
    private function postWork(Request $request){
        //检查输入有效性
        if(!$this->checkPostData($request)){
            return response()->json(['res'=>false, 'info'=>'']);
        }
        //检查Prod有效性（编辑）
        $id = $request['id'];
        $prod = null;
        if($id > 0){
            $prod = UserProduction::find($id);
            if(is_null($prod)){ //作品不存在
                return response()->json(['res'=>false, 'info'=>'']);
            }
            if(!OwnerHandler::checkByObj('user_production', $prod)){
                return response()->json(['res'=>false, 'info'=>'']);
            }
        }else{
            $payRes = GoldManager::publishPayGold('user_production','0', Auth::id(), $request['name']);
            if(!$payRes){
                return response()->json(['res'=>false, 'info'=>'金币不足']);
            }
            $prod = new UserProduction;
            $prod->user_id = Auth::id();
            $prod->is_deleted = 0;
        }
        //设置预览和摘要
        $name = $request['name'];
        $intro = null;
        $img = null;
        $contents = $request['contents'];
        foreach($contents as $content){
            if($content['type'] == 'text' && is_null($intro)){
                $intro = $content['text'];
            }
            if($content['type'] == 'image' && is_null($img)){
                $img = str_replace('http://img.umeiii.com', '', $content['url']);
            }
            if(!is_null($img) && !is_null($img)){
                break;
            }
        }
        $prod->name  = $name;
        $prod->is_original = $request['is_origin'] == '1';
        $prod->intro = (is_null($intro) ? '' : $intro);
        $prod->image = (is_null($img)   ? '' : $img);
        //设置IP关联
        $ip = $request['ip']['id'];
        $relateType = $request['related_type'];
        if($ip > 0){
            $prod->ip_id = $ip;
            $prod->relate_type = $relateType;
        }
        //保存作品
        $prod->save();
        //更新内容部分
        $conIds = [];
        foreach($contents as $content){
            $cid = $content['id'];
            if($cid==0){
                $pct = new ProductionContent();
            }else{
                $pct = ProductionContent::find($cid);
                if(is_null($pct)){
                    $pct = new ProductionContent();
                }
            }
            $pct->production_id = $prod->id; 
            $pct->type  =  $content['type'];
            if(array_key_exists('text', $content)){
                $pct->text  =  $content['text'];
            }
            if(array_key_exists('url', $content)){
                $pct->url  =  $content['url'];
            }
            $pct->order =  $content['order'];
            $pct->status =  $content['status'];
            $pct->save();
            array_push($conIds, $pct->id);
        }
        ProductionContent::where('production_id', $prod->id)->whereNotIn('id', $conIds)->delete();
        //生成IP贡献记录
        if($ip > 0){
            IpContributorHandler::SaveIpContributor($ip, Auth::id(), $prod->id, $relateType);
        }
        //设置活动关联
        $res = Activity::is_act($prod->id,'user_production');
        if($res){
            //return response()->json(['res'=>true, 'info'=>noBackUrl('/act/getshowjoin/'.$res)]);
            return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/act_'.$$res]);
        }else{
            //return response()->json(['res'=>true, 'info'=>noBackUrl($prod->detailUrl)]);
            return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/userproduction_'.$prod->id]);
        }
    }
    private function checkPostData(Request $request){
        $name = $request['name'];
        $relateType = $request['related_type'];
        $contents = $request['contents'];
        $ip = $request['ip']['id'];
        $id = $request['id'];
        if(empty($name)){
            return false;
        }
        if(!in_array($relateType, ['coll', 'peri', 'disc'])){
            return false;
        }
        if(count($contents) == 0){
            return false;
        }
        if($ip != 0 && Ip::find($ip) == null) {
            return false;
        }
        return true;
    }

    public function postDelete(){
        $this->deleteProduction(Input::get('id'));
        return redirect('/home/list/works/0/'.Auth::user()->id);
    }
    public function postDeleteAjax(){
        $this->deleteProduction(Input::get('id'));
        return response()->json(['res'=>true]);
    }
    private function deleteProduction($id){
        $prod = UserProduction::findOrFail($id);
        $prod->is_deleted = true;
        $prod->save();
        if(!is_null($prod->sellInfo)){
            $prod->delete();
        }
        ProductionContent::where('production_id', $prod->id)->delete();
        $prod->delete();
    }
    //快速查询作品
    public function postQuickSearch(){
        $keywd = Input::get('keywd');
        $arr = [];
        if(trim($keywd) != ''){
            //仅显示最先被查询出来的10个IP
            $ips = Ip::where('name', 'like', '%' . $keywd . '%')
                ->take(10)->get();
            foreach($ips as $ip){
                array_push($arr, [
                    'id'=>$ip->id,
                    'cover'=>$ip->cover->getPath(1,'64w_64h_1e_1c'),
                    'name'=>$ip->name,
                    'cardInfo'=>$ip->cardInfo
                ]);
            }
        }
        return response()->json($arr);
    }
    private function getProdTypes(){
        $userSkills = SysUserSkill::all();
        $prodTypes = [];
        $prodTypes['0'] = '选择作品属性';
        foreach ($userSkills as $skill) {
            $prodTypes[$skill->code] = $skill->name;
        }
        return $prodTypes;
    }
}
