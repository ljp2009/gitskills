<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Ip;
use App\Models\IpRole;
use App\Models\IpAttr;
use App\Models\IpIntro;
use App\Models\IpScene;
use App\Models\IpDialogue;
use App\Models\IpColleague;
use App\Models\IpPeripheral;
use App\Models\UserProduction;
use App\Models\SysAttr;
use App\Models\SysTag;
use App\Common\CommonUtils as CU;
use App\Common\Image;
use Input, Request, File;

class IpController extends Controller
{
    //IP 操作
    public function showIpList(){
        $allIp = Ip::paginate(15);
        return view('admins.pages.iplist',['ips'=>$allIp,'num'=>'15','uploadParams'=>$this->getUploadParams()]);
    }
    public function getList(){
        $allIp = Ip::paginate(15);
        return view('admins.pages.iplist',['ips'=>$allIp, 'num'=>'15', 'uploadParams'=>$this->getUploadParams()]);
    }
    public function postList(){
        $searchStr = Input::get('search');
        $allIp = Ip::where('name','like','%'.$searchStr.'%')->paginate(30);

        return view('admins.pages.iplist',['ips'=>$allIp,'num'=>'30', 'search'=>$searchStr,'uploadParams'=>$this->getUploadParams()]);
    }
    private function getUploadParams(){
        $uploadParams = Image::getUploadAliImageParams();
        return $uploadParams;
    }
    public function postDelete()
    {
        $id = Input::get('id');
        $ip = Ip::findOrFail($id);
        $ip->delete();
        return $ip->id;
    }
    //编辑封面
    public function postEditCover()
    {
        $id = Input::get('id');
        $imgName = Input::get('cover');
        $ip = Ip::findOrFail($id);
        $ip->cover=$imgName;
        $ip->save();
        $arr = ['id'=>$id, 'cover'=>$ip->cover];
        return response()->json($arr);
    }

    //Ip简介编辑
    public function getEditIntro($ipid)
    {
        $intro = IpIntro::where('ip_id',$ipid)->take(1)->get();
        $arr = array(
            'id' => $ipid,
            'intro' => ($intro->count()==0)?'':$intro[0]->intro);
        return response()->json($arr);
    }
    public function postEditIntro()
    {
        $id = Input::get('id');
        $introstr = Input::get('intro');
        $intro = IpIntro::where('ip_id',$id)->take(1)->get();
        if($intro->count()==0){
            IpIntro::create(['ip_id'=>$id, 'intro'=>$intro]);
        }else{
            $intro[0]->intro = $introstr;
            $intro[0]->save();
        }
        return 'true';
    }
    //Ip标题编辑
    public function getEditTitle($ipid)
    {
        $ip = Ip::find($ipid);
        $arr = array(
            'id' => $ipid,
            'title' => $ip->name
        );
        return response()->json($arr);
    }
    public function postEditTitle()
    {
        $id = Input::get('id');
        $ip = Ip::find($id);
        $ip->name = Input::get('title');
        $ip->save();
        return 'true';
    }

    //Ip场景
    public function getSceneList($id)
    {
        $scenes = IpScene::where('ip_id', $id)->paginate(15);
        return view('admins.pages.ipscenelist', ['ip_id'=>$id, 'items'=>$scenes]);
    }
    public function postDeleteScene()
    {
        $id = Input::get('id');
        $sc = IpScene::find($id);
        $sc->delete();
        return $id;
    }
    public function getEditScene($id)
    {
        $sc = IpScene::find($id);
        $arr = array(
            'id' => $sc->id,
            'text' =>$sc->text);
        return response()->json($arr);
    }
    public function postEditScene()
    {
        $sc = IpScene::find(Input::get('id'));
        $sc->text = Input::get('text');
        $sc->save();
        return 'true';
    }
    //Ip对话
    public function getDialList($id)
    {
        $items = IpDialogue::where('ip_id', $id)->paginate(15);
        return view('admins.pages.ipdiallist', ['ip_id'=>$id,'num'=>'15', 'items'=>$items]);
    }
    public function postDeleteDial()
    {
        $id = Input::get('id');
        $sc = IpDialogue::find($id);
        if(!is_null($sc)){
            $sc->delete();
        }
        return $id;
    }
    public function getEditDial($id)
    {
        $sc = IpDialogue::find($id);
        $arr = array(
            'id' => $sc->id,
            'text' =>$sc->textPart,
            'text2' =>$sc->rolePart);
        return response()->json($arr);
    }
    public function postEditDial()
    {
        $sc = IpDialogue::find(Input::get('id'));
        $sc->text = [Input::get('text'), Input::get('text2')];
        $sc->save();
        return 'true';
    }
    //Ip同人
    public function getCollList($id)
    {
        $items = UserProduction::where('ip_id', $id)
            ->where('relate_type', 'coll')->paginate(15);
        return view('admins.pages.uprodlist',['type'=>'同人作品', 'items'=>$items, 'prefix'=>'/user/product/']);
    }
    ////Ip周边
    public function getPeriList($id)
    {
        $items = UserProduction::where('ip_id', $id)
            ->where('relate_type', 'peri')->paginate(15);
        return view('admins.pages.uprodlist',['type'=>'周边产品', 'items'=>$items, 'prefix'=>'/user/product/']);
    }
    ////Ip长评论
    public function getDiscList($id)
    {
        $items = UserProduction::where('ip_id', $id)
            ->where('relate_type', 'disc')->paginate(15);
        return view('admins.pages.uprodlist',['type'=>'长评论', 'items'=>$items, 'prefix'=>'/user/product/']);
    }
    //Ip属性
    public function getAttrList($id)
    {
        $items = IpAttr::where('ip_id', $id)->paginate(15);
        return view('admins.pages.ipattrlist', ['ip_id'=>$id, 'items'=>$items]);
    }
    public function postDeleteAttr()
    {
        $id = Input::get('id');
        $sc = IpAttr::find($id);
        $sc->delete();
        return $id;
    }
    public function getEditAttr($id)
    {
        $sc = IpAttr::find($id);
        $arr = array(
            'id' => $sc->id,
            'code'=>$sc->code,
            'value' =>$sc->value);
        return response()->json($arr);
    }
    public function postEditAttr()
    {
        $sc = IpAttr::find(Input::get('id'));
        $sc->value = Input::get('value');
        $sc->save();
        return 'true';
    }
    public function getAddAttr($pid)
    {
        $ip = Ip::find($pid);
        $items = IpAttr::where('ip_id', $pid)->get();
        $existArr = array();
        foreach($items as $item){
            array_push($existArr, $item->code);
        }
        $type = $ip->type;
        if($type == 'light') $type = 'story';
        $attrs = SysAttr::whereIn('depend',['ip',$type])
            ->whereNotIn('code',$existArr)->get();
        $attrArr = array();
        foreach($attrs as $attr){
            array_push($attrArr,['name'=>$attr->name,'value'=>$attr->code]);
        }
        return response()->json($attrArr);
    }
    public function postAddAttr()
    {
        $sc = new IpAttr;
        $sc->ip_id = Input::get('pid');
        $sc->code = Input::get('code');
        $sc->value = Input::get('value');
        $sc->save();
        return 'true';
    }
    //Ip标签
    public function getTagList($id)
    {
        $ip = Ip::find($id);
        $tags = SysTag::where('depend', $ip->type)->get();
        return view('admins.pages.iptags', [
            'ip_id'=>$id,
            'ip'=>$ip,
            'tags'=>$tags ]);
    }
    public function postTagUpdate()
    {
        $id = Input::get('id');
        $tags = Input::get('tags');
        $tagsArr = explode(';', $tags);
        $id = Input::get('id');
        $ip = Ip::find($id);
        $ip->tags = $tagsArr;
        $ip->save();
        return response()->json(['res'=>true,
            'tagStr' =>implode('&nbsp;&nbsp;&nbsp;&nbsp;',$ip->tags)]);
    }
    //IpRole修改
    public function getRoleList($id)
    {
        $items = IpRole::where('ip_id', $id)->paginate(15);
        return view('admins.pages.iprolelist', ['ip_id'=>$id, 'items'=>$items]);
    }
    public function postDeleteRole()
    {
        $id = Input::get('id');
        $sc = IpRole::find($id);
        $sc->delete();
        return $id;
    }
    public function getEditRole($id)
    {
        $sc = IpRole::find($id);
        $arr = array(
            'id' => $sc->id,
            'name'=>$sc->name,
            'intro' =>$sc->intro);
        return response()->json($arr);
    }
    public function postEditRole()
    {
        $sc = IpRole::find(Input::get('id'));
        $sc->name = Input::get('name');
        $sc->intro = Input::get('intro');
        $sc->save();
        return 'true';
    }


    //批量更新IP的属性
    public function getBatchUpdate()
    {
        return view('admins.pages.batchupdate');
    }
    public function postBatchUpdate()
    {
        $attrInfo =explode('_', Input::get('attrName'));
        $arr['ipType'] = $attrInfo[0];
        $arr['attrName'] = $attrInfo[1];
        $arr['keyName'] = Input::get('keyName');
        $arr['emptyValue'] = Input::get('emptyValue');
        $fileInfo = Input::file('dataFile');
        $file = fopen($fileInfo->getPathname(),'r');
        $arr['fileName'] = $fileInfo->getFileName();
        $arr['findIpCount'] = 0;
        $arr['notFindIpCount'] = 0;
        $arr['items'] = array();
        $arr['errItems'] = array();
        $arr['findItems'] = array();
        fgetcsv($file);// 预读第一行，去除标题
        while(!feof($file)) {
            $row = fgetcsv($file);
            if(is_null($row[0])|| $row[0] == '') continue;
            array_push($arr['items'], ['number'=>$row[0], 'name'=>$row[1], 'value'=>$row[2]]);
        }
        foreach($arr['items'] as $key=>$value)
        {
            if($arr['keyName']  == 'name'){
                $ips = Ip::where('name','=', trim($value['name']))
                    ->where('type',$arr['ipType'])->get();
            }elseif($arr['keyName']  == 'id'){
                $ips = Ip::where('id','=', trim($value['number']))->get();
            }
            if($ips->count()==1){
                $arr['findItems'][$ips[0]->id] = $value;
                $arr['findIpCount']++;
            }else{
                array_push($arr['errItems'], $value);
                $arr['notFindIpCount']++;
            }
        }
        unset($arr['items']);
        fclose($file);
        foreach($arr['findItems'] as $key=>$value){
            if($arr['attrName'] == 'type'){
                //更新ＩＰ分类
                $ip = Ip::find($key);
                $ip->type = $value['value'];
                $ip->save();
            }else{
                //更新属性
                $attrs = IpAttr::where('code',$arr['attrName'])->where('ip_id',$key)->get();
                if($value['value'] == ''){
                    if($arr['emptyValue']=='default'){
                        foreach($attrs as $attr){
                            $attr->delete();
                        }
                    }
                }else{
                    if($attrs->count()>0){
                        foreach($attrs as $attr){
                            $attr['value'] = $this->getEnumKey($value['value']);
                            $attr->save();
                        }
                    }else{
                        $attr = new IpAttr();
                        $attr->code = $arr['attrName'];
                        $attr->value = $this->getEnumKey($value['value']);
                        $attr->ip_id = $key;
                        $attr->save();
                    }
                }
            }
        }
        return view('admins.pages.batchupdate', ['attrName'=>$arr['attrName'],'items'=>$arr['errItems']]);
    }

    private function getEnumKey($name)
    {
        return $name;
    }
}
