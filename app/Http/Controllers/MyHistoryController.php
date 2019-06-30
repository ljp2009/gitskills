<?php
namespace App\Http\Controllers;

use Auth;
use DB;
use App\Models\VisitLog;
use App\Models\Dimension;
use App\Models\Ip;
use App\Models\UserProduction;
/**
 * 浏览历史记录
 * @author admin
 *
 */
class MyHistoryController extends Controller
{
    public function showMyHistoryList($page){

        return view('myhistorylist',['page'=>$page]);
    }

    //获取浏览历史记录
    public function loadMyHistoryListData($from, $to){

        if(!Auth::check()) return '';
        $user_id = Auth::user()->id;
        $visitLogList = DB::select('select * from (select * from t_visit_log where user_id=? and resource in (?,?,?) order By created_at desc) vl 
            group By resource, resource_id order By created_at desc limit ?,?', [$user_id,'ip', 'dimension', 'product', $from, $to-$from+1]);

        $result = [];
        foreach ($visitLogList as $value) {
            # code...
            array_push($result, [
                'name'        => $this->getTypeModel($value->resource, $value->resource_id),
                'url'         => $this->getTypeUrl($value->resource, $value->resource_id), 
                'created_at'  => $value->created_at]);
        }

        return view('partview.myhistoryitem', array('models'=>$result));
    }

    //获取浏览类型
    public function getTypeModel($resource, $id){

        switch ($resource) {
            //二次元
            case 'dimension':
                # code...
                $dim = Dimension::find($id);
                return $dim->name;
            //IP
            case 'ip':
                # code...
                $ip = Ip::find($id);
                return $ip->name;
                break;
            //作品
            case 'product':
                # code...
                $userPro = UserProduction::find($id);
                return $userPro->name;
                break;
            //默认
            default:
                return '未知';
                break;
        }
    }

    public function getTypeUrl($resource, $id){
        switch ($resource) {
            //二次元
            case 'dimension':
                return '/dimpub/list/diminfo/0/'.$id;
                break;
            //ip
            case 'ip':
                return '/ip/'.$id;
                break;
            //作品
            case 'product':
                return '/user/product/'.$id;
                break;
            default:
                break;
        }
    }

    // public function loadMyHistoryList()
    
}
