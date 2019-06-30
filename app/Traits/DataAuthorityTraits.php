<?php

namespace App\Traits;;

trait DataAuthorityTraits
{
    const AUTH_NORMAL = 0;
    const AUTH_MANAGER = 0;
    const AUTH_OWNER = 0;
    const AUTH_LOCK = 0;
    public $isAdminUse = false;
    public function getAuthorityAttribute($value){
        
    }
}
