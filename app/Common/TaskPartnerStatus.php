<?php
namespace App\Common;
class TaskPartnerStatus
{
    const REJECT = 0;
    const REQUEST = 1;
    const JOININ = 2;
    const PARTNER = 3;
    private $status =self::REQUEST;
    public function __get($value){
        if($value == 'status'){
            return $this->status;
        }
    }
}
