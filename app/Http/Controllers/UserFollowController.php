<?php
namespace App\Http\Controllers;

use Auth;
use App\Models\Dimension;
use App\Models\DimensionEnter;
use App\Common\CommonUtils;
use DB;
use App\Models\ListItem;
use App\Models\UserProduction;
/**
 * 我的关注
 * @author admin
 *
 */
class UserFollowController extends Controller
{
    public function showUserFollowList($pageType, $page){
        return view('userfollowlist',['type'=>$pageType, 'page'=>$page]);
    }

    //获取用户关注人的作品
    public function getUserProductionFollowData($from, $to){
        if(!Auth::check()) return '';
        $id = Auth::user()->id;

        $result = DB::table('t_user_production')->select('t_user_production.id')
            ->join('t_user_relation',function($join){
                $join->on('t_user_production.user_id','=','t_user_relation.follow_id');
            })->where('t_user_relation.user_id', $id)
            ->orderBy('t_user_production.like_sum', 'desc')
            ->skip($from)->take($to-$from+1)->get();
        $proIds = [];
        foreach ($result as $key => $value) {
            array_push($proIds, $value->id);
        }
        $productions = UserProduction::whereIn('id', $proIds)->get();
        $models = ListItem::makeUserProductionListItems($productions);
        return view('partview.detaillistitem', array('models'=>$models));

    }
    //获取入驻次元
    public function getUserDimensionFollowData($from, $to){

        if(!Auth::check()) return '';

        $id = Auth::user()->id;
        $model = DimensionEnter::where('user_id',$id);
        $results = array();
        $dimensions = CommonUtils::handleListDetails($model, $from, $to , true,'id');
        foreach ($dimensions as $key => $value) {
            array_push($results, $value->dimension);
        }
        return view('partview.dimension.dimensionitem', array('models'=>$results));
    }
}
