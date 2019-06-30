<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserSignIn;
use App\Models\UserDetailStatus;
use App\Common\GoldManager;
use Auth;
class SignInController extends Controller
{
    const COINS_MAP = ['1'=>10, '3'=>15, '5'=>20, '7'=>30];
    public function getCheck(){
        if(!Auth::check()){
            return response()->json([ 'res'=>false, 'info'=>false ]);
        }
        $userId = Auth::id();
        $sign = UserSignIn::where('user_id', $userId)
            ->where('date', date('Y-m-d'))
            ->first();
        $signStatus = !is_null($sign);
        return response()->json(['res'=>true, 'info'=>$signStatus]);
    }
    
    public function postSign(){
        if(!Auth::check()){
            return response()->json([ 'res'=>false, 'info'=>'' ]);
        }
        $userId = Auth::id();
        $newestSign = UserSignIn::where('user_id', $userId)
            ->orderBy('id', 'desc')->first();
        if(!is_null($newestSign) && $newestSign->date == date('Y-m-d')){
            //已经签到过了
            return response()->json(['res'=>false, 'info'=>'exist']);
        } 
        $detailStatus = UserDetailStatus::where('user_id', $userId)
            ->first();
        $signCt = $detailStatus->sign_count;
        if(is_null($newestSign)){
            $signCt = 1;
            $dayCount = 0; 
        }else{
            $yestaday = date('Y-m-d', strtotime('-1 days'));
            $dayCount = ((strtotime($yestaday) - strtotime($newestSign->date))/60/60/24);
            if($dayCount == 0){
                $signCt += 1;
            }else{
                $signCt = 1;
            }
        }
        $coins = 0;
        foreach(self::COINS_MAP as $days=>$value){
            if($signCt >= $days){
                $coins = $value;
            }else{
                break;
            }
        }
        $intro =  '签到'.$signCt.'天';
        $userSign = new UserSignIn;
        $userSign->user_id = $userId; 
        $userSign->coins = $coins;
        $userSign->date = date('Y-m-d');
        $userSign->intro = $intro;
        $userSign->save();
        $res = GoldManager::incomeGold($coins, '5000103', $userSign->id, $userId, $intro, false);
        $detailStatus->sign_count = $signCt;
        $detailStatus->save();
        $res = [
            'days'=>$signCt,
            'coins'=>$coins,
            'daysCount' =>$dayCount
        ];
        return response()->json(['res'=>true, 'info'=>$res]);
    }
}
