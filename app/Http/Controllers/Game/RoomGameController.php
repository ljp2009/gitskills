<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RoomGameController extends Controller
{
    public function setReady($roomId, $userId){
    
    }

    public function joinGame($roomId, $userId){

    }

    public function leaveGame($roomId, $userId){

    }

    public function isAllReady($roomId){

    }

    public function canStart($roomId){

    }

    public function newTurn($roomId){

    }

    public function getRoomSize(){
        if(isset($ROOM_SIZE))
            return $ROOM_SIZE;
        else
            return 4;
    }
}
