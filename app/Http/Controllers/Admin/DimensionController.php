<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Dimension;
use App\Models\DimensionEnter;
use App\Models\DimensionSum;
use App\Models\DimensionPublish;
use App\Models\DimensionLatelyUser;

use App\Models\SysAttr;
use App\Common\CommonUtils as CU;
use App\Common\Image;
use Input, Request, File;

class DimensionController extends Controller
{
    //IP 操作
    public function showIpList(){
        $allDim = Dimension::paginate(15);
        return view('admins.pages.dimensionlist',['dims'=>$allDim,'num'=>count($allDim),'uploadParams'=>$this->getUploadParams()]);
    }
    public function getList(){
        $allDim = Dimension::paginate(15);
        return view('admins.pages.dimensionlist',['dims'=>$allDim, 'num'=>count($allDim), 'uploadParams'=>$this->getUploadParams()]);
    }
    public function postList(){
        $searchStr = Input::get('search');
        $allDim = Dimension::where('name','like','%'.$searchStr.'%')->paginate(30);

        return view('admins.pages.dimensionlist',['dims'=>$allDim,'num'=>count($allDim), 'search'=>$searchStr,'uploadParams'=>$this->getUploadParams()]);
    }
    private function getUploadParams(){
        $uploadParams = Image::getUploadAliImageParams();
        return $uploadParams;
    }
    public function postDelete()
    {
        $id = Input::get('id');
        $dim = Dimension::findOrFail($id);
        $dim->delete();

        DimensionSum::where('dimension_id', $id)->delete();
        DimensionEnter::where('dimension_id', $id)->delete();

        return $dim->id;
    }

    //帖子
    public function getPublishList($id)
    {
        $items = DimensionPublish::where('dimension_id', $id)->paginate(15);
        return view('admins.pages.dimensionpublist', ['dimension_id'=>$id, 'items'=>$items]);
    }

    public function postDeletePublish()
    {
        $id = Input::get('id');
        $sc = DimensionPublish::find($id);
        $sc->delete();
        return $id;
    }

    //入住用户列表
    public function getLatelyUserList($id)
    {
        $items = DimensionLatelyUser::where('dimension_id', $id)->paginate(15);
        return view('admins.pages.dimensionlatuserlist', ['dimension_id'=>$id, 'items'=>$items]);
    }

    //编辑封面
    public function postEditCover()
    {
        $id = Input::get('id');
        $imgName = Input::get('cover');
        $dim = Dimension::findOrFail($id);
        $dim->header=$imgName;
        $dim->save();
        $arr = ['id'=>$id, 'cover'=>$dim->header];
        return response()->json($arr);
    }
}
