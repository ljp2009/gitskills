<?php
/**
 * Pk任务结束任务
 * @author ViewsTap xiaocui Programmer
 * @date 20160217
 */
namespace App\Schedules;

use DB, Log;

class UserLogSchedule
{
    private $nowDate = null;
    private $timePoint = ' 03:00:00';
    private $runTime = null;
    public function run($step=[]){
        $dateTimePoint = $this->getDateTimePoint();
        $fromTime = $dateTimePoint . $this->timePoint;
        $toTime = date('Y-m-d', strtotime('+1 day', strtotime($dateTimePoint))) . $this->timePoint;
        $batch = $dateTimePoint.'_';
        $this->genrateUserLogSession($fromTime, $toTime, $batch);
    }
    public function setTimePoint($date){
        $this->nowDate = $date;
    }
    public function test(){
        Log::info('Test Schedule');
    }
    public function setNowDate($nowDate){
            $this->nowDate = $nowDate;
    }
    private function getDatetimePoint(){
        if(is_null($this->nowDate)){
            $this->nowDate = date('Y-m-d');
        }
        return $this->nowDate;
    }
    private function genrateUserLogSession($fromTime, $toTime, $batch){
        DB::select('call calculateUserLog(?,?,?)',[$fromTime, $toTime, $batch]);
    }
}
