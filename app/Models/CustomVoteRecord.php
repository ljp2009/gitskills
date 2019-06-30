<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomVoteRecord extends Model
{
    //
    protected $table     = 't_custom_vote_record';
    protected $guarded   = ['id'];
    public function getVotesArr(){
        $voteStr = $this->voted;
        $votedArr = explode(';', $voteStr);
        $res = [];
        foreach($votedArr as $voted){
            $tmp = explode(':', $voted);
            if(count($tmp) == 2) {
                $res[$tmp[0]] = $tmp[1];
            } 
        }
        return $res;
    }
}
