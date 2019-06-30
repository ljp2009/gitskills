<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use App\Models\User;
use App\Models\Ip;
use Input, DB;

class StatisticalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMain()
    {
        return redirect('/admin/ip/list');
        return view('admins.pages.main');
    }
    public function getIndex()
    {
        return view('admins.pages.statistical');
    }
    public function getDetailSession($type, $date){
        $gf = $this->getGroupField($type, $date);
        $groupField = $gf['groupField'];
        $groupValue = $gf['groupValue'];
        $query = DB::table('sys_user_session')
            ->select(DB::raw('user_token, count(distinct session_no) as session_ct, sum(session_second) as session_long'))
            ->where($groupField, $groupValue)
            ->groupBy(db::raw('user_token,'.$groupField));
        $res = $query->paginate(15);
        $models = [];
        foreach($res as $r){
            array_push($models, [
                'userName'          => $r->user_token,
                'sessionCt'         => $r->session_ct,
                'sessionTotalTime'  => $r->session_long,
                'sessionAvgTime'    => sprintf('%.3f', $r->session_long/$r->session_ct)
            ]);
        }
        return view('admins.pages.statisticaldetail', ['models'=>$models, 'res'=>$res]);
    }
    public function postVisitSession(){
        $type = Input::get('type');
        $date = Input::get('date');
        $gf = $this->getGroupField($type, $date);
        $groupField = $gf['groupField'];
        $groupValue = $gf['groupValue'];
        $res = DB::table('sys_user_session')
            ->select(DB::raw('count(distinct user_token) as user_ct, count(distinct session_no) as session_ct, sum(session_second) as session_long'))
            ->where($groupField, $groupValue)
            ->groupBy($groupField)
            ->first();
        if(is_null($res)){
            return response()->json([
                'res' => true,
                'totalSession' => 0,
                'totalSessionLong' => 0,
                'avgSession' => 0,
                'totalUser' => 0
            ]);
        }else{
            return response()->json([
                'res' => true,
                'totalSession' => $res->session_ct,
                'totalSessionLong' => $res->session_long,
                'avgSession' => $res->session_long/$res->session_ct,
                'totalUser' => $res->user_ct
            ]);
        } 
        
    }
    private function getGroupField($type, $date) {
        $groupField = '';
        $groupValue = '';
        if($type == '1'){
            $groupField = 'session_day';
            $groupValue = date('Y-m-d', strtotime($date));
        }
        else if($type == '2'){
            $groupField = 'session_week';
            $groupValue = date('Y~W', strtotime($date));
        }
        else if($type == '3'){
            $groupField = 'session_month';
            $groupValue = date('Y F', strtotime($date));
        }
        return [
            'groupField' => $groupField,
            'groupValue' => $groupValue
        ];
    }
    /*
     * 计算注册用户数与留存率
     * @date 计算日期
    * */
    public function postUserCount(){
        $date = Input::get('date');
        $dateFormat = date('Y-m-d', strtotime($date)); 
        $dateFrom   = $dateFormat.' 00:00:00';
        $dateTo     = $dateFormat.' 23:59:59';
        $userCt     = User::where('created_at','>=',$dateFrom)->where('created_at', '<', $dateTo)->count();    
        $userCtR1   = 0;    
        $userCtR3   = 0;    
        $userCtR7   = 0;    
        if($userCt > 0) {
            $userCtR1 = sprintf('%.2f', $this->getUserRetention($date, 1)/$userCt*100);    
            $userCtR3 = sprintf('%.2f', $this->getUserRetention($date, 3)/$userCt*100);    
            $userCtR7 = sprintf('%.2f', $this->getUserRetention($date, 7)/$userCt*100);    
        }
        return response()->json([
            'res' => true,
            'dataFrom' =>$dateFrom,
            'dataTo' =>$dateTo,
            'ct'  => $userCt,
            'r1'  => $userCtR1,
            'r3'  => $userCtR3,
            'r7'  => $userCtR7
        ]);
    }
    /*
     * 留存率计算
     * @date 用户注册日期
     * @days 留存率的天数
    * */
    private function getUserRetention($date, $days){
        $dateFormat = date('Y-m-d', strtotime($date)); 
        $dateFrom   = $dateFormat.' 00:00:00';
        $dateTo     = $dateFormat.' 23:59:59';

        $dateNew = date('Y-m-d', strtotime('+'.$days.' days', strtotime($dateFormat)));
        $dateNewFrom   = $dateNew.' 00:00:00';
        $dateNewTo     = $dateNew.' 23:59:59';
        
        $userCt     = SystemLog::join('t_user','t_user.id', '=', 'sys_log.user_id')
            ->where('t_user.created_at','>=',$dateFrom)->where('t_user.created_at', '<', $dateTo)
            ->where('sys_log.created_at','>=',$dateNewFrom)->where('sys_log.created_at', '<', $dateNewTo)
            ->count(db::raw('distinct sys_log.user_id'));    
        return $userCt;
    }
}
