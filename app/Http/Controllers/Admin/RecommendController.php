<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Common\CommonUtils as CU;
use App\Common\Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\HallBanner;
use App\Models\UmeiiiRecommend;
use App\Models\UmeiiiRecommendBatch as Batch;
use App\Models\UmeiiiMaster;
use App\Models\UmeiiiDimension;
use App\Models\UserProduction;
use App\Models\Ip;
use App\Models\User;
use App\Models\Dimension;
use Input, Auth;
class RecommendController extends Controller
{
    /*
     * 首页头部大图
     * */
    public function getBannerList() {
        $bs = HallBanner::orderBy('created_at','desc')->paginate(15);
        $uploadParams = Image::getUploadAliImageParams();
        return view('admins.pages.resbannerlist',['items'=>$bs, 'uploadParams'=>$uploadParams]);
    }
    public function getBanner($id){
        $banner = HallBanner::findOrFail($id);
        $arr = ['id'=>$id, 'url'=>$banner->url,'description'=>$banner->description];
        return response()->json($arr);
    }
    public function postAddBanner() {
        $banner = new HallBanner();
        $banner->url = Input::get('url');
        $banner->description = Input::get('description');
        $banner->image = 'default.jpg';//创建时候不设置图片
        $banner->save();
        return response()->json(['id'=>$banner->id, 'value'=>'']);
    }
    public function postDeleteBanner() {
        $id = Input::get('id');
        $banner = HallBanner::find($id);
        if(!is_null($banner)){
            $banner->delete();
        }
        return $id;
    }
    public function postEditBanner() {
        $id = Input::get('id');
        $banner = HallBanner::findOrFail($id);
        $attrs =explode(',', Input::get('attr'));
        $arr = array('id'=>$id);
        foreach($attrs as $attr){
            if($attr == '') continue;
            if(in_array($attr,['url','description','image'])){
                $banner->$attr = Input::get($attr);
                $arr[$attr] = $banner->$attr;
                if($attr == 'image'){
                    $arr[$attr] = $banner->imagePath;
                }
            }
        }
        $banner->save();
        return response()->json($arr);
    }
    /*
     * 首页有妹推荐
     * */
    public function getBatchList(){
        $list = Batch::orderBy('publish_date','desc')->paginate(7);
        $uploadParams = Image::getUploadAliImageParams();
        return view('admins.pages.recommendlist',['items'=>$list, 'uploadParams'=>$uploadParams]);
    }
    public function postSaveBatch(){
        $cc = Input::get('cc');
        $batchId = Input::get('batchId');
        $userId = Auth::user()->id;
        if($batchId == 0){
            $batch = new Batch;
            $batch->batch_no = date('Ymd');
            $batch->user_id = $userId;
        }else{
            $batch = Batch::findOrFail($batchId);
            if($batch->user_id != $userId){
                return response()->json(['res'=>false,'无编辑权限。']);
            }
        }
        $batch->publish_date = Input::get('publish_date');
        $batch->save();
        if($batchId != 0){
            UmeiiiRecommend::where('batch_id', $batchId)->delete();
        }
        for($i=0; $i<intval($cc); $i++){
            $recId = Input::get('id_'.$i);
            if($recId == '' || $recId == '0') continue;
            $rec = new UmeiiiRecommend;
            $rec->name = Input::get('title_'.$i);
            $rec->image = Input::get('img_'.$i);
            $rec->intro = Input::get('intro_'.$i);
            $rec->batch_id = $batch->id;
            $rec->type = Input::get('type_'.$i);
            $rec->resource_id = Input::get('id_'.$i);
            $rec->creator = Auth::user()->id;
            $rec->updator = Auth::user()->id;
            $rec->save();
        }
        return response()->json(['res'=>true]);
    }
    public function getBatch($id){
        $params = [];
        $params['uploadParams'] = Image::getUploadAliImageParams();
        $params['id'] = $id;
        if($id > 0){
            $params['batch'] = Batch::findOrFail($id);
        }

        return view('admins.pages.recommendform',$params);
    }
    public function postDeleteBatch() {
        $id = Input::get('id');
        $item = Batch::find($id);
        if($item->user_id != Auth::user()->id){
            return response()->json(['res'=>false, 'id'=>$id, 'info'=>'权限不足。']);
        }
        if(!is_null($item)){
            $item->delete();
        }
        return response()->json(['res'=>true, 'id'=>$id]);
    }
    /*
     * 有妹达人推荐
     * */
    public function getMaster(){
        $masters = UmeiiiMaster::orderBy('order')->get();
        return view('admins.pages.recommendmaster', ['masters'=>$masters]);
    }
    public function postMaster(){
        $cc = Input::get('cc');
        $userId = Auth::user()->id;
        $masters = UmeiiiMaster::orderBy('order')->get();
        $masterArr = [];
        foreach($masters as $master){
            $masterArr[$master->order] = $master;
        }
        if($cc == 0){
            $i = 0;
            while(array_key_exists($i, $masterArr)){
                $i += 1;
            }
            $masterArr[$i] = new UmeiiiMaster;
            $masterArr[$i]->creator = Auth::user()->id;
            $masterArr[$i]->name = Input::get('title');
            $masterArr[$i]->img = Input::get('img');
            $masterArr[$i]->user_id = Input::get('id');
            $masterArr[$i]->order = $i;
            $masterArr[$i]->save();
        }else{
            for($i=0; $i<intval($cc); $i++){
                if(Input::get('id_'.$i)>0){//保存有效推荐
                    if(!array_key_exists($i, $masterArr)){
                        $masterArr[$i] = new UmeiiiMaster;
                        $masterArr[$i]->creator = Auth::user()->id;
                    }
                    $masterArr[$i]->name = Input::get('title_'.$i);
                    $masterArr[$i]->img = Input::get('img_'.$i);
                    $masterArr[$i]->user_id = Input::get('id_'.$i);
                    $masterArr[$i]->order = $i;
                    $masterArr[$i]->save();
                }
                else{
                    if(array_key_exists($i, $masterArr)){//移除无效推荐
                        $masterArr[$i]->delete();
                    }
                }
            }
        }
        return response()->json(['res'=>true]);
    }
    /*
     * 有妹次元推荐
     * */
    public function getDimension(){
        $dims = UmeiiiDimension::orderBy('order')->get();
        return view('admins.pages.recommenddimension', ['dims'=>$dims]);
    }
    public function postDimension(){
        $cc = Input::get('cc');
        $userId = Auth::user()->id;
        $dims = UmeiiiDimension::orderBy('order')->get();
        $dimArr = [];
        foreach($dims as $dim){
            $dimArr[$dim->order] = $dim;
        }
        if($cc == 0){
            $i = 0;
            while(array_key_exists($i, $dimArr)){
                $i += 1;
            }
            $dimArr[$i] = new UmeiiiDimension;
            $dimArr[$i]->creator = Auth::user()->id;
            $dimArr[$i]->name = Input::get('title');
            $dimArr[$i]->img = Input::get('img');
            $dimArr[$i]->dimension_id = Input::get('id');
            $dimArr[$i]->order = $i;
            $dimArr[$i]->save();
        }else{
            for($i=0; $i<intval($cc); $i++){
                if(Input::get('id_'.$i)>0){//保存有效推荐
                    if(!array_key_exists($i, $dimArr)){
                        $dimArr[$i] = new UmeiiiDimension;
                        $dimArr[$i]->creator = Auth::user()->id;
                    }
                    $dimArr[$i]->name = Input::get('title_'.$i);
                    $dimArr[$i]->img = Input::get('img_'.$i);
                    $dimArr[$i]->dimension_id = Input::get('id_'.$i);
                    $dimArr[$i]->order = $i;
                    $dimArr[$i]->save();
                }
                else{
                    if(array_key_exists($i, $dimArr)){//移除无效推荐
                        $dimArr[$i]->delete();
                    }
                }
            }
        }
        return response()->json(['res'=>true]);
    }
    /*
     * 公用
     * */
    public function postSearchItem(){
        $type = Input::get('type');
        $keyword = Input::get('keyword');
        if(in_array($type, ['cartoon','game', 'story', 'light'])){
            return $this->searchIp($type, $keyword);
        }
        elseif(in_array($type, ['coll', 'peri', 'disc','original'])){
            return $this->searchProd($type, $keyword);
        }
        elseif($type=='user'){
            return $this->searchUser($keyword);
        }
        elseif($type=='dimension'){
            return $this->searchDimension($keyword);
        }
    }
    private function searchIp($type, $keyword){
        $items = Ip::where('type',$type)->where('name', 'like', '%'.$keyword.'%')->take(11)->get();
        $arr = [];
        foreach($items as $item){
            array_push($arr, ['title'=>$item->name, 'id'=>$item->id, 'imgPerview'=>$item->cover->getPath(1,'64w_64h_1e_1c'), 'img'=>$item->cover->getOriginName(), 'type'=>$type]);
        }
        return response()->json($arr);
    }
    private function searchProd($type, $keyword){
        if($type == 'original'){
            $query = UserProduction::whereNull('relate_type')->where('name', 'like', '%'.$keyword.'%');
        }else{
            $query = UserProduction::where('relate_type',$type)->where('name', 'like', '%'.$keyword.'%');
        }
        $items = $query->take(11)->get();
        $arr = [];
        foreach($items as $item){
            array_push($arr, [
                'title'=>$item->name,
                'id'=>$item->id,
                'imgPerview'=>$item->cover->getPath(1,'64w_64h_1e_1c'),
                'img'=>$item->cover->getOriginName(),
                'type'=>$type]);
        }
        return response()->json($arr);
    }
    private function searchUser($keyword){
        $items = User::where('display_name', 'like', '%'.$keyword.'%')->take(11)->get();
        $arr = [];
        foreach($items as $item){
            array_push($arr, ['title'=>$item->display_name, 'id'=>$item->id, 'imgPerview'=>$item->avatar->getPath(1,'64w_64h_1e_1c'), 'img'=>$item->avatar->getOriginName(), 'type'=>'user']);
        }
        return response()->json($arr);
    }
    private function searchDimension($keyword){
        $items = Dimension::where('name', 'like', '%'.$keyword.'%')->take(11)->get();
        $arr = [];
        foreach($items as $item){
            array_push($arr, ['title'=>$item->name, 'id'=>$item->id, 'imgPerview'=>$item->header->getPath(1,'64w_64h_1e_1c'), 'img'=>$item->header->getOriginName(), 'type'=>'dimension']);
        }
        return response()->json($arr);
    }
}
