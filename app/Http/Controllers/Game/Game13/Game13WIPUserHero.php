<?php

namespace App\Http\Controllers\Game\Game13;

use App\Models as MD;
use App\Http\Controllers\Controller;

class Game13WIPUserHero extends Controller{
    private static $FUWEN_EVAL = [3, 6, 7, 8, 999];

    public static $BISHA_TYPE = ['attack'=>'attack', 
            'control'=>'control'];

    private static function randomHit($rv, $le=1){
        $rate = 1;
        for($i=0; $i<$le; $i++){
            $rate = $rate * 10;
        }
        $ran = mt_rand(0, $rate);
        $rvr = intval($rv * $rate);
        return $ran <= $rvr;
    }

        //range = [0.1, 0.1, 0.3, 0.1, 0.2, 0.2]
    private static function randomHitRange($range, $le=1){
    	if(is_array($range)){
    		$re = 0;
    		$rate = 1;
    		for($i=0; $i<$le; $i++){
    			$rate = $rate * 10;
    		}
    		$ran = mt_rand(0, $rate);
    		for($i=0; $i<sizeof($range); $i++){
    			$re += $range[$i];
    			$curv = intval($re * $rate);
    			if($ran <= $curv){
    				return $i;
    			}
    		}
    		return sizeof($range);
    	}else{
    		return self::randomHit($range, $le);
    	}
    }

    public static function checkWin($game) {
        $user1 = $game->user1; $user2 = $game->user2;

        $ctrl1 = new Game13WIPUserHero($user1);
        $ctrl2 = new Game13WIPUserHero($user2);

        $whowin = 0;

        $alives1 = $ctrl1->getNumberOfAliveHeros();
        $alives2 = $ctrl2->getNumberOfAliveHeros();

        $terminate = ($alives1==0||$alives2==0);
        if($alives1 == $alives2){
            if($alives1 == 0){
                $whowin = 0;
            }else{
                $blood1 = $ctrl1->getTotalBloods();
                $blood2 = $ctrl2->getTotalBloods();
                if($blood1 == $blood2){
                    $whowin = 0;
                }else if($blood1 > $blood2){
                    $whowin = $user1;
                }else{
                    $whowin = $user2;
                }
            }

        }else if($alives1 > $alives2){
            $whowin = $user1;
        }else{
            $whowin = $user2;
        }

        return ['win'=>$whowin, 'terminate'=>$terminate];
    }

    public static function getAttackBaseValue($gameHero) {
    	return $gameHero->gong * 5;
    }

    public static function getFangTogetherRate($gameUserFrom, $gameUserTo){
        $fangTogetherRateMap = ['1'=>0.1, '2'=>0.2, '3'=>0.3, '4'=>0.4, '5'=>0.5, '6'=>0.6, '7'=>0.7, '8'=>0.8, '9'=>0.9, '10'=>0.10, '11'=>0.11, '12'=>0.12, '13'=>0.13, '14'=>0.14, '15'=>0.15, '16'=>0.16, '17'=>0.17, '18'=>0.18, '19'=>0.19, '20'=>0.20, '21'=>0.21, '22'=>0.22, '23'=>0.23, '24'=>0.24, '25'=>0.25, '26'=>0.26, '27'=>0.27, '28'=>0.28, '29'=>0.29, '30'=>0.30, '31'=>0.31, '32'=>0.32, '33'=>0.33, '34'=>0.34, '35'=>0.35, '36'=>0.36, '37'=>0.37, '38'=>0.38, '39'=>0.39, '40'=>0.40, '41'=>0.41, '42'=>0.42, '43'=>0.43, '44'=>0.44, '45'=>0.45, '46'=>0.46, '47'=>0.47, '48'=>0.48, '49'=>0.49, '50'=>0.50, '51'=>0.51, '52'=>0.52, '53'=>0.53, '54'=>0.54, '55'=>0.55, '56'=>0.56, '57'=>0.57, '58'=>0.58, '59'=>0.59, '60'=>0.60, '61'=>0.61, '62'=>0.62, '63'=>0.63, '64'=>0.64, '65'=>0.65, '66'=>0.66, '67'=>0.67, '68'=>0.68, '69'=>0.69, '70'=>0.70, '71'=>0.71, '72'=>0.72, '73'=>0.73, '74'=>0.74, '75'=>0.75, '76'=>0.76, '77'=>0.77, '78'=>0.78, '79'=>0.79, '80'=>0.80, '81'=>0.81, '82'=>0.82, '83'=>0.83, '84'=>0.84, '85'=>0.85, '86'=>0.86, '87'=>0.87, '88'=>0.88, '89'=>0.89, '90'=>0.90];
        $fangTogetherAttackMap = ['1'=>0.5, '2'=>0.5, '3'=>0.5, '4'=>0.5, '5'=>0.5, '6'=>0.5, '7'=>0.5, '8'=>0.5, '9'=>0.5, '10'=>0.5, '11'=>0.5, '12'=>0.5, '13'=>0.5, '14'=>0.5, '15'=>0.5, '16'=>0.5, '17'=>0.5, '18'=>0.5, '19'=>0.5, '20'=>0.5, '21'=>0.5, '22'=>0.5, '23'=>0.5, '24'=>0.5, '25'=>0.5, '26'=>0.5, '27'=>0.5, '28'=>0.5, '29'=>0.5, '30'=>0.5, '31'=>0.6, '32'=>0.7, '33'=>0.8, '34'=>0.9, '35'=>0.10, '36'=>0.11, '37'=>0.12, '38'=>0.13, '39'=>0.14, '40'=>0.15, '41'=>0.16, '42'=>0.17, '43'=>0.18, '44'=>0.19, '45'=>0.20, '46'=>0.21, '47'=>0.22, '48'=>0.23, '49'=>0.24, '50'=>0.25, '51'=>0.26, '52'=>0.27, '53'=>0.28, '54'=>0.29, '55'=>0.30, '56'=>0.31, '57'=>0.32, '58'=>0.33, '59'=>0.34, '60'=>0.35, '61'=>0.36, '62'=>0.37, '63'=>0.38, '64'=>0.39, '65'=>0.40, '66'=>0.41, '67'=>0.42, '68'=>0.43, '69'=>0.44, '70'=>0.45, '71'=>0.46, '72'=>0.47, '73'=>0.48, '74'=>0.49, '75'=>0.50, '76'=>0.51, '77'=>0.52, '78'=>0.53, '79'=>0.54, '80'=>0.55, '81'=>0.56, '82'=>0.57, '83'=>0.58, '84'=>0.59, '85'=>0.60, '86'=>0.61, '87'=>0.62, '88'=>0.63, '89'=>0.64, '90'=>0.65
        ];

        $ctrl1 = new Game13WIPUserHero($gameUserFrom->id, $gameUserFrom);
        $ctrl2 = new Game13WIPUserHero($gameUserTo->id, $gameUserTo);

        if($ctrl1->isAllAlive()){
            $fangpoints1 = $ctrl1->getTotalFangPoints();
            $fangpoints2 = $ctrl2->getTotalFangPoints();
            $diff = $fangpoints1 - $fangpoints2;
            if($diff > 0){
                if($diff > 90){
                    $diff = 90;
                }
                $rate = $fangTogetherAttackMap[strval($diff)];
                return [$fangTogetherRateMap[strval($diff)], 
                        self::randomHit($rate, 2)];
            }else{
                return [0, false];
            }
        }else{
            return [0, false];
        }
    }

    public static function getFangAttackRate($gameHero){
    	$fangPointRateMap = [0.10, 0.15, 0.20, 0.22, 0.25, 0.27, 0.29, 0.31, 0.34, 0.36, 0.38, 0.40, 0.42, 0.44, 0.46, 0.48, 0.50, 0.52, 0.54, 0.56, 0.58, 0.60, 0.62, 0.64, 0.66, 0.68, 0.70, 0.72, 0.74, 0.75, 0.76, 0.77, 0.78, 0.79, 0.80];

    	$fang = self::evalPointsValue($gameHero->fang);
    	if($fang == 0){
    		return 0;
    	}else{
    		if($fang > sizeof($fangPointRateMap)){
    			$fang = sizeof($fangPointRateMap);
    		}

    		return $fangPointRateMap[$fang - 1];
    	}
    }

    public static function evalHeroBloodValue($ti){
    	return 300 + 30 * $ti;
    }

    public static function evalFinalHurtWithProtect($hero, $hurt, $togetherFang=0){
        $fangrate = self::getFangAttackRate($hero);
        $hurt = intval($hurt * (1 - $fangrate) * (1 - $togetherFang));
        return $hurt;
    }

    public static function evalGongHurt($hero, $baoji=false) {
        $hurt = $hero->gong * 5;
        if($baoji){
            $hurt = intval($hurt * 1.5);
        }
        return $hurt;
    }

    public static function evalFanjiHurt($hero){
        return intval(self::evalGongHurt($hero) / 3);
    }

    public static function evalJiHurt($heroFrom, $heroTo, $aoe, $jitype){
        $jidiff = $heroFrom->ji - $heroTo->ji;
        if($jidiff < 3){
            $jidiff = 3;
        }
        $hurt = 0;
        if($jitype == 'attack'){
            if($aoe){
                $hurt = $jidiff * 3;
            }else{
                $hurt = $jidiff * 6;
            }
        }else{
            if($aoe){
                $hurt = $jidiff * 1.5;
            }else{
                $hurt = $jidiff * 3;
            }            
        }
        return $hurt;
    }

    public static function evalBishaHurt($hero, $gun, $aoe, $jitype){
        $gj = $hero->gong + $hero->ji;
        $hurt = 0;
        if($aoe){
            if($jitype == self::$BISHA_TYPE['control']){
                $hurt = intval($gj * 1.2);
            }else{
                $hurt = intval($gj * 1.5);
            }
        }else{
            if($jitype == self::$BISHA_TYPE['control']){
                $hurt = intval($gj * 1.5);
            }else{
                $hurt = intval($gj * 3);
            }            
        }
        if($gun){
            $hurt = intval($hurt * 1.25);
        }
        return $hurt;
    }

    public static function evalJiType(){
        $range = [0.25, 0.25, 0.25, 0.25];
        $type = ['attack', 'weak', 'enhance', 'blood'];
        $idx = self::randomHitRange($range, 2);
        if($idx == 4){
            $idx = 3;
        }
        return $type[$idx];
    }

    public static function willManaAOE($gameHero1, $gameHero2){
        $jiDiffMap = ["0"=>0.10, "1"=>0.10, "2"=>0.20, "3"=>0.30, "4"=>0.40, "5"=>0.50, "6"=>0.60, "7"=>0.70, "8"=>0.80, "9"=>0.90, "10"=>1];
        $jiDiff = $gameHero1->ji - $gameHero2->ji;
        if($jiDiff >= 0){
            if($jiDiff >= 10){
                return true;
            }else{
                $rate = $jiDiffMap[strval($jiDiff)];
                return self::randomHit($rate, 2);
            }
        }else{
            return false;
        }
    }
    public static function willSuperJiAOEHit($gameHero1, $gameHero2){
        $speedJiDiffMap = ["-18"=>0.5, "-17"=>0.5, "-16"=>0.5, "-15"=>0.5, "-14"=>0.5, "-13"=>0.5, "-12"=>0.5, "-11"=>0.5, "-10"=>0.5, "-9"=>0.5, "-8"=>0.5, "-7"=>0.5, "-6"=>0.5, "-5"=>0.5, "-4"=>0.5, "-3"=>0.7, "-2"=>0.9, "-1"=>0.11, "0"=>0.13, "1"=>0.15, "2"=>0.17, "3"=>0.19, "4"=>0.21, "5"=>0.24, "6"=>0.27, "7"=>0.30, "8"=>0.33, "9"=>0.37, "10"=>0.44, "11"=>0.60, "12"=>0.76, "13"=>0.92, "14"=>0.92, "15"=>0.92, "16"=>0.92, "17"=>0.92, "18"=>0.92];
        $speedJ11 = $gameHero1->ji + $gameHero1->su;
        $speedJ12 = $gameHero2->ji + $gameHero2->su;

        $diff = ($speedJ11 - $speedJ12);
        if(array_key_exists(strval($diff), $speedJiDiffMap)){
            $rate = $speedJiDiffMap[strval($diff)];
        }else{
            if($diff > 18){
                $rate =$speedJiDiffMap['18'];
            }else{
                $rate = $speedJiDiffMap['-18'];
            }
        }

        return self::randomHit($rate, 2);
    }

    public static function willJiMiss($gameHero1, $gameHero2){
    	$speedMissMap = [0.10, 0.15, 0.20, 0.25, 0.30, 0.35, 0.40, 0.45, 0.50];
    	$diff = $gameHero1->su - $gameHero2->su;
    	if($diff > -1){
    		return false;
    	}else{
    		if($diff < -9)
    			$diff = -9;
    		$rate = $speedMissMap[-1 - $diff];
    		return self::randomHit($rate, 2);
    	}
    }

    public static function willAttackBao($gameHero1, $gameHero2, $gun){
    	$attackBaoMap =  [0.10, 0.15, 0.20, 0.28, 0.36, 0.44, 0.52, 0.62, 0.75];
    	$diff = $gameHero1->gong - $gameHero2->gong;
    	if($diff < 1){
    		return false;
    	}else{
    		if($diff > 9)
    			$diff = 9;
    		$rate = $attackBaoMap[$diff - 1];
            if($gun){
                $rate = $rate * 1.5;
            }
    		return self::randomHit($rate, 2);
    	} 
    }

    public static function willAttackLian($gameHero1, $gameHero2, $gun){
    	$speedDoubleMap = [0.10, 0.15, 0.20, 0.28, 0.36, 0.44, 0.52, 0.62, 0.75];

    	$diff = $gameHero1->su - $gameHero2->su;
    	if($diff < 1){
    		return false;
    	}else{
    		if($diff > 9)
    			$diff = 9;
    		$rate = $speedDoubleMap[$diff - 1];
            if($gun) {
                $rate = $rate * 1.5;
            }
    		return self::randomHit($rate, 2);
    	}    	
    }

    public static function willAttackMiss($gameHero1, $gameHero2){
    	$speedMissMap = [0.10, 0.15, 0.20, 0.25, 0.30, 0.35, 0.40, 0.45, 0.50];
    	$diff = $gameHero1->su - $gameHero2->su;
    	if($diff > -1){
    		return false;
    	}else{
    		if($diff < -9)
    			$diff = -9;
    		$rate = $speedMissMap[-1 - $diff];
    		return self::randomHit($rate, 2);
    	}
    }

    public static function hitSpecialCard ($userheroCtrl1, $userheroCtrl2){
    	$diff = $userheroCtrl1->getCurrentUserLuck() - $userheroCtrl2->getCurrentUserLuck();
    	if($diff < 0){
    		return false;
    	}

    	$cardLevel = [12, 11, 10, 9, 8];
    	$luckRateCardLevelMap = ['4'=>[0.01, 0.01, 0.06, 0.32, 0.6], '13'=>[0.01, 0.02, 0.07, 0.4, 0.5], 
    			'23'=>[0.02, 0.03, 0.1, 0.3, 0.55], '33'=>[0.05, 0.1, 0.2, 0.3, 0.35]];
    	$luckdiffRateMap = [0.60, 0.59, 0.58, 0.57, 0.56, 0.55, 0.54, 0.53, 0.52, 0.51, 0.50, 0.49, 0.48, 0.47, 0.46, 0.45, 0.44, 0.43, 0.42, 0.41, 0.40, 0.39, 0.38, 0.37, 0.36, 0.35, 0.34, 0.33, 0.32, 0.31, 0.30, 0.29, 0.28, 0.27];

    	$max = sizeof($luckdiffRateMap) - 1;
    	if($diff > $max){
    		$diff = $max;
    	}
    	$luckdiffInd = $max - $diff;
    	if(self::randomHit($luckdiffRateMap[$luckdiffInd], 2)){
    		$ratearr = [];
    		foreach($luckRateCardLevelMap as $k=>$v){
    			$lvl = intval($k);
    			if($diff <= $lvl){
    				$ratearr = $v;
    				break;
    			}
    		}
    		$idx = self::randomHitRange($ratearr, 2);
    		if($idx == sizeof($ratearr)){
    			$idx = sizeof($ratearr) - 1;
    		}
    		return $cardLevel[$idx];
    	}else{
    		return false;
    	}
    }

    public static function evalPointsValue($poi, $initAdd=0){
        $result = 0;
        $poi = $poi - $initAdd;//DOUBLE CHECK
        $fuwenevl = self::$FUWEN_EVAL;
        if($poi <= $fuwenevl[0]){
            return $poi;
        }
        for($i=0; $i<sizeof($fuwenevl); $i++){
            $va = $fuwenevl[$i];
            if($poi > $va){
                continue;
            }
            for($j=0; $j<$i; $j++){
                $result += ($j + 1) * $fuwenevl[$j];
            }
            $result += ($poi - $fuwenevl[$i - 1]) * ($i + 1);
            break;
        }
        return $result;    	
    }

    public static function evalInitLuck($userheroCtrl){
    	$wintimesLuckMap = [20, 18, 16, 14, 12, 10, 8, 6, 4, 2, 0];
    	$heroLevelLuckMap = [1, 2, 3, 4, 5];

    	$userid = $userheroCtrl->getGameUser()->user_id;
    	$userInfo = MD\UserGameInfoModel::where('user_id', $userid)->get();
    	$wintimes = 0;
    	foreach($userInfo as $one){
    		$wintimes = $one->recent_win_times;
    	}
    	if($wintimes > sizeof($wintimesLuckMap) - 1){
    		$wintimes = sizeof($wintimesLuckMap) - 1;
    	}
    	$luck = $wintimesLuckMap[$wintimes];
    	$luck += $heroLevelLuckMap[$userheroCtrl->getHeros()[0]->level-1];
   		$luck += $heroLevelLuckMap[$userheroCtrl->getHeros()[1]->level-1];
   		$luck += $heroLevelLuckMap[$userheroCtrl->getHeros()[2]->level-1];

   		return $luck;
    }

    public static function evalHeroLuckByBlood($gameHero){
    	if($gameHero->blood<0){
    		return 0;
    	}else{
    		$herobloodLuckMap = ['10'=>3,  '30'=>2,'50'=>1,'100'=>0 ];

    		$rate = intval($gameHero->blood * 100/$gameHero->oriblood);
    		foreach($herobloodLuckMap as $k=>$v){
    			$intk = intval($k);
    			if($rate <= $intk){
    				return $v;
    			}
    		}
    		return 0;
    	}
    }

    public static function getHeroTotalFeaturePoint($gameHero){
        if($gameHero->blood  <= 0){
            return 0;
        }
        return $gameHero->ti + $gameHero->su + $gameHero->gong + $gameHero->fang + $gameHero->ji;
    }

    public static function getMaxFeaturePointHeroIdx($heros) {
        $maxpoint = 0; $maxidx = 0;
        for($i = 0; $i < 3; $i++){
            $point = self::getHeroTotalFeaturePoint($heros[$i]);
            if($maxpoint < $point){
                $maxpoint = $point;
                $maxidx = $i;
            }
        }
        return $maxidx;
    }

	public $gameUser;
	public $gameHero1;
	public $gameHero2;
	public $gameHero3;
    public $gameUserId;

	function __construct($gameUserId, $gameUser=false){
        if($gameUser === false){
            $this->gameUser = MD\Game13UserOnprocessModel::find(intval($gameUserId));            
        }else{
            $this->gameUser = $gameUser;
        }

		$this->gameHero1 = $this->gameUser->gameHero1;
		$this->gameHero2 = $this->gameUser->gameHero2;
		$this->gameHero3 = $this->gameUser->gameHero3;
        $this->gameUserId = $gameUserId;
	}

    public function getUserId(){
        return $this->gameUserId;
    }

    public function setDragon($level){
        switch($level){
            case 12:
                $this->gameHero1 = $this->dragonHero($this->getHero(0));
                $this->gameHero2 = $this->dragonHero($this->getHero(1));
                $this->gameHero3 = $this->dragonHero($this->getHero(2));
                return $this->getAliveHeros();
            case 11:
                $heroidx = self::getMaxFeaturePointHeroIdx($this->getHeros());
                $hero = $this->getHero($heroidx);
                $hero = $this->dragonHero($hero);
                $this->setHero($heroidx, $hero);
                return [$hero];
        }
        return [];
    }

    public function setHero($idx, $hero){
        if($idx == 0){
            for($i = 0; $i<3; $i++){
                if($this->getHero($i)->id == $hero->id){
                    $idx = $i;
                    break;
                }
            }
        }
        switch($idx){
            case 0: $this->gameHero1 = $hero; break;
            case 1: $this->gameHero2 = $hero; break;
            case 2: $this->gameHero3 = $hero; break;
        }  

        return $idx;     
    }

    private function dragonHero($hero){
        if($hero->blood <=0){
            return $hero;
        }
        if($hero->level < 5){
            $hero->level += 1;
        }
        $hero->blood += 300;
        $hero->oriblood += 300;
        $hero->save();

        return $hero;
    }

	public function getGameUser(){
		return $this->gameUser;
	}

	public function getCurrentUserLuck(){
		$luck = $this->gameUser->luck;
		$heros = $this->getHeros();
		foreach($heros as $one){
			$luck += self::evalHeroLuckByBlood($one);
		}
		return $luck;
	}

    public function getNextAliveHero($idx=0){
        $hero = false;
        for($i=0; $i<3; $i++){
            $tmpidx = $idx + $i;
            if($tmpidx >= 3){
                $tmpidx = $tmpidx - 3;
            }
            $hero = $this->getHero($tmpidx);
            if($hero->blood > 0){
                return [$hero, $tmpidx];
            }
        }
        return $hero;
    }

	public function getHero($idx=0){
		switch($idx){
            case 0: return $this->gameHero1;
            case 1: return $this->gameHero2;
            case 2: return $this->gameHero3;
        }
	}

    public function getTotalFangPoints(){
        return self::evalPointsValue($this->gameHero1->fang) + 
           self::evalPointsValue($this->gameHero2->fang) + 
           self::evalPointsValue($this->gameHero3->fang) ;
    }

    public function getTotalBloods(){
        $heros = $this->getHeros();
        $blood = 0;
        foreach($heros as $one){
            if($one->blood > 0){
                $blood += $one->blood;
            }
        }
        return $blood;
    }

    public function getNumberOfAliveHeros(){
         $count = 0;
         for($i=0; $i<3; $i++){
            if($this->isHeroAlive($i)){
                $count ++;
            }
         }
         return $count;
    }

	public function getHeros(){
		return [$this->gameHero1, $this->gameHero2, $this->gameHero3];
	}

    public function getAliveHeros(){
        $heros = [];
        for($i=0; $i<3; $i++){
            if($this->isHeroAlive($i)){
                array_push($heros, $this->getHero($i));
            }
        }
        return $heros;
    }

	public function isHeroAlive($idx=0){
		return $this->getHero($idx)->blood > 0;
	}

	public function isAllAlive(){
		 $isAllAlive = ($this->getNumberOfAliveHeros()==3);
		 return $isAllAlive;
	}

    public function isAllDead(){
         $isAllDead = ($this->getNumberOfAliveHeros()==0);
         return $isAllDead;        
    }

    public function getHeroWithLeastBlood(){
        $blood = 0; $hero = false;
        $heros = $this->getAliveHeros();
        if(sizeof($heros) == 0){
            return false;
        }else if(sizeof($heros) == 1){
            return $heros[0];
        }else{
            foreach($heros as $onehero){
                if($blood == 0 ||$blood > $onehero->blood){
                    $blood = $onehero->blood;
                    $hero = $onehero;
                }
            }
            return $hero;
        }
    }

}