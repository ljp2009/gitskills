<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Schedules\PkTaskSchedule as PTS;
use Input, Request, File;

class ScheduleController extends Controller
{
    public function getIndex(){
        return view('admins.pages.systemctrl');
    }
    
    public function postPkSchedule(){
        $date = Input::get('date');
        try{
            $pts = new PTS;
            $pts->setDate($date);
            $pts->run();
            return response()->json(['res'=>true, 'info'=>$pts->debug]);
        }
        catch(Exception $e){
            return response()->json(['res'=>false, 'info'=>$e]);
        }
    }
}
