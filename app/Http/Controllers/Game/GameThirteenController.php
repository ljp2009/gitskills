<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\GameRoomModel;
use App\Models\GameAuditModel;
use App\Models\GameUserHistoryModel;
use App\Models\GameUserPreferenceModel;
use App\Models\GameUserStatusModel;
use CardGame, Input, Utils;
use App\Http\Controllers\Game\RoomGameController;
use Auth;

class GameThirteenController extends RoomGameController
{
    protected $GAME_TYPE = 'thirteen';
    protected $ROOM_SIZE = 2;
    protected static $CARD_TYPES = array('spade', 'heart', 'diamond', 'club', 'diamond');
    protected static $TOTAL_CARDS = 65;
    protected static $CARDS_EACH = 16;
    protected static $CARDS_EACH_LEFT = 3;
    public static $CARDLEVEL_NAME = array('乌龙', '一对', '两对', '三条', '顺子', '同花', '葫芦', '铁支', '同花顺', '五同');
    public static $SPECIALLEVEL_NAME = array('一条龙', '大青龙');

    public static $USER_READY = 1;
    public static $USER_PREPARED = 2;
    public static $USER_NOTREADY = 0;

    public static $INIT_SCORE = 100;

    public static function revertCardsFromKeys($arr){
        $newarr = array();
        foreach($arr as $key=>$value){
            $newarr[$key] = self::revertCardFromKey($value);
        }
        return $newarr;
    }

    public static function revertCardFromKey($cardkey){
        $vs = explode('_', $cardkey);
        $cardno = intval($vs[1]);
        if($cardno == 14)
            $cardno = 1;
        $cardno --;
        $cidx = 0;
        switch($vs[0]){
            case 'spade':
                $cidx = 0; break;
            case 'heart':
                $cidx = 1; break;
            case 'diamond':
                $cidx = 2; break;
            case 'club':
                $cidx = 3; break;
        }
        return $cidx * 13 + $cardno;
    }

    public static function getLevelName($level, $cardlevel=3){
        switch($cardlevel){
            case 1:
                if($level==3){
                    return '冲三';
                }else{
                    return self::$CARDLEVEL_NAME[$level-1];
                }
            case 2:
                if($level==7){
                    return '中墩葫芦';
                }else{
                    return self::$CARDLEVEL_NAME[$level-1];
                }
            case 3:
                return self::$CARDLEVEL_NAME[$level-1];
        }
    }

    public static function duplicateArr($arr){
        $newarr = array();
        foreach($arr as $k=>$v){
            $newarr[$k] = $v;
        }
        return $newarr;
    }
    public static function getRestCard($cardKeyMap, $selected){
        $rest = array();
        $cardKeyMap = self::duplicateArr($cardKeyMap);

        foreach($selected as $onesel){
            if(array_key_exists($onesel, $cardKeyMap)){
                $cardKeyMap[$onesel] = $cardKeyMap[$onesel] - 1;
            }            
        }
        foreach($cardKeyMap as $k=>$v){
            if($v > 0){
                for($i=0; $i<$v; $i++){
                    array_push($rest, $k);
                }
            }
        }
        return $rest;
    }

    public static function returnEvalResult($picked, $rest, $needsrecheck=false, $miss=0){
        return array('picked'=>$picked, 'rest'=>$rest, 'recheck'=>$needsrecheck, 'miss'=>$miss);
    }

    public static function identifyCards($cards){
        $cardNo = array();
        $cardColor = array();
        $max = 0;
        foreach($cards as $onecard){
            $vs = explode('_', $onecard);
            $no = intval($vs[1]);
            array_push($cardNo, $no);
            if(!array_key_exists($vs[0], $cardColor)){
                $cardColor[$vs[0]] = array();
            }
            array_push($cardColor[$vs[0]], $no);
            $sz = sizeof($cardColor[$vs[0]]);
            if($max < $sz){
                $max = $sz;
            }
        }
        $sz = sizeof($cardNo);
        sort($cardNo);
        foreach($cardColor as $k=>$v){
            sort($v);
            $cardColor[$k] = $v;
        }
        if($sz == 3){
            $orv = 0;
            $same = 1;
            $res = 0;
            foreach($cardNo as $one){
                if($orv > 0){
                    if($orv == $one){
                        $same ++;
                        $res = $orv;
                    }else if($same > 1){
                        break;
                    }
                }else{
                    $same = 1;
                }
                $orv = $one;
                $res = $one;
            }
            return array('level'=>$same, 'highest'=>$res, 'cardNo'=>$cardNo, 'cardColor'=>$cardColor);
        }else if($sz == 5){
            $orv = 0;
            $same = 0;
            $shun = true;
            $samearr = array();
            for($i=0; $i<5; $i++){
                $samearr[$i] = array();
            }
            $res = 0;
            $issame = false;
            foreach($cardNo as $one){
                if($orv > 0){
                    if($orv == $one){
                        $same ++;
                        $isame = true;
                        $shun = false;
                    }else{
                        if($one - $orv >1){
                            $shun = false;
                        }
                        $issame = false;
                    }
                    if(!$issame){
                        if($same > 1){
                            array_push($samearr[$same], $orv);
                        }
                        $same = 1;
                    }
                }else{
                    $same = 1;
                }
                $orv = $one; 
                $res = $one;            
            }
            if($same == 5){
                return array('level'=>10, 'highest'=>$res, 'cardNo'=>$cardNo, 'cardColor'=>$cardColor);
            }else if($shun&&$max==5){
                return array('level'=>9, 'highest'=>$res, 'cardNo'=>$cardNo, 'cardColor'=>$cardColor);
            }else if(sizeof($samearr[4])>0){
                return array('level'=>8, 'highest'=>$samearr[4][0], 'cardNo'=>$cardNo, 'cardColor'=>$cardColor);
            }else if(sizeof($samearr[3])==1&&sizeof($samearr[2])==1){
                return array('level'=>7, 'highest'=>$samearr[3][0], 'cardNo'=>$cardNo, 'cardColor'=>$cardColor);
            }else if($max==5){
                return array('level'=>6, 'highest'=>$res, 'cardNo'=>$cardNo, 'cardColor'=>$cardColor);
            }else if($shun){
                return array('level'=>5, 'highest'=>$res, 'cardNo'=>$cardNo, 'cardColor'=>$cardColor);
            }else if(sizeof($samearr[3])>0){
                return array('level'=>4, 'highest'=>$samearr[3][0], 'cardNo'=>$cardNo, 'cardColor'=>$cardColor);
            }else if(sizeof($samearr[2])==2){
                return array('level'=>3, 'highest'=>$samearr[2][1], 'cardNo'=>$cardNo, 'cardColor'=>$cardColor);
            }else if(sizeof($samearr[2])==1){
                return array('level'=>2, 'highest'=>$samearr[2][0], 'cardNo'=>$cardNo, 'cardColor'=>$cardColor);
            }else{
                return array('level'=>1, 'highest'=>$res, 'cardNo'=>$cardNo, 'cardColor'=>$cardColor);
            }
        }
    }

    public static function evalTonghua($cardNos){
        sort($cardNos);
        $v = 0;
        $ct = 0;
        for($i=sizeof($cardNos)-1; $i>=0; $i--){
            $cur = $cardNos[$i];
            if($v > 0 ){
                $off = $v - $cur;
                if($off == 1){
                    $ct ++;
                }else if($off != 0){
                    $ct = 1;
                }
            }else{
                $ct = 1;
            }
            $v = $cur;
            if($ct == 5){
                break;
            }
        }
        if($ct == 5){
            return $v + 4;
        }else{
            return 0;
        }
    }

    public static function evalCard10($cardfNo, $cardfType, $cardKeyMap){
        $match = array();
        for($i=0; $i<13; $i++){
            $idx = 14 - $i;
            $oneno = $cardfNo[self::getIdxNoForCardNo($idx)];
            if(sizeof($oneno)==5){
                array_push($match, $idx);
            }
        }
        if(sizeof($match)==0){
            return false;
        }else{
            $selected = array();
            $pickedgroups = array();
            $ct = 0;
            foreach($match as $ione){
                $pickedgroups[$ct] = array();
                for($i=0; $i<5; $i++){
                    $key = self::$CARD_TYPES[$i].'_'.$ione;
                    array_push($selected, $key);
                    array_push($pickedgroups[$ct], $key);
                }
                $ct ++;
            }
            $rest = self::getRestCard($cardKeyMap, $selected);
            return self::returnEvalResult($pickedgroups, $rest);
        }
    }

    public static function evalCard9($cardfNo, $cardfType, $cardKeyMap){
        $selected = array();
        $pickedgroups = array();
        $ct = 0;
        foreach($cardfType as $key=>$oneType){
            if(sizeof($oneType)>=5){
                $result = self::evalTonghua($oneType);
                if($result > 0){
                    $pickedgroups[$ct] = array();
                    for($i=0; $i<5; $i++){
                        $r = $result - $i;
                        $k = $key.'_'.$r;
                        array_push($selected, $k);
                        array_push($pickedgroups[$ct], $k);
                    }
                    $ct ++;
                }
            }
        }
        if(sizeof($pickedgroups)>0){
            $rest = self::getRestCard($cardKeyMap, $selected);
            return self::returnEvalResult($pickedgroups, $rest);          
        }else{
            return false;
        }

    }

    public static function evalCard8($cardfNo, $cardfType, $cardKeyMap){
         $match = array();
         for($i=0; $i<13; $i++){
            $idx = 14 - $i;
            $oneno = $cardfNo[self::getIdxNoForCardNo($idx)];
            if(sizeof($oneno)==4){
                array_push($match, $idx);
            }
        }
        if(sizeof($match)==0){
            return false;
        }else{
            $selected = array();
            $pickedgroups = array();
            $ct = 0;
            foreach($match as $ione){
                $pickedgroups[$ct] = array();
                $colors = $cardfNo[self::getIdxNoForCardNo($ione)];
                foreach($colors as $color){
                    $key = $color.'_'.$ione;
                    array_push($selected, $key);
                    array_push($pickedgroups[$ct], $key);  
                }
                $ct ++;
            }
            $rest = self::getRestCard($cardKeyMap, $selected);
            return self::returnEvalResult($pickedgroups, $rest, false, 1);
        }
    }

    public static function evalCard7($cardfNo, $cardfType, $cardKeyMap){
         $match3 = array();
         $match2 = array();
         for($i=0; $i<13; $i++){
            $idx = 14 - $i;
            $oneno = $cardfNo[self::getIdxNoForCardNo($idx)];
            if(sizeof($oneno)==3){
                array_push($match3, $idx);
            }else if(sizeof($oneno)==2){
                array_push($match2, $idx);
            }
         }        
         if(sizeof($match3)==0||sizeof($match2)==0){
            return false;
         }else{
            $selected = array();
            $pickedgroups = array();
            $ct = 0;
            foreach($match3 as $ione){
                 $pickedgroups[$ct] = array();
                 if(sizeof($match2)>$ct){
                    $colors = $cardfNo[self::getIdxNoForCardNo($ione)];
                    foreach($colors as $color){
                        $key = $color.'_'.$ione;
                        array_push($selected, $key);
                        array_push($pickedgroups[$ct], $key); 
                    }
                   
                    $cno = $match2[$ct];
                    $colors = $cardfNo[self::getIdxNoForCardNo($cno)];
                    foreach($colors as $color){
                        $key = $color.'_'.$cno;
                        array_push($selected, $key);
                        array_push($pickedgroups[$ct], $key); 
                    }                                     
                }
                $ct ++;                
            }
            $rest = self::getRestCard($cardKeyMap, $selected);
            return self::returnEvalResult($pickedgroups, $rest);
         }
    }

    public static function evalCard6($cardfNo, $cardfType, $cardKeyMap){
        $selected = array();
        foreach($cardfType as $k=>$v){
            $sz = sizeof($v);
            if($sz>=5){
                for($i=$sz-1; $i>=$sz-5; $i--){
                    $key = $k.'_'.$v[$i];
                    array_push($selected, $key);
                }
                break;
            }
        }
        if(sizeof($selected)>0){
            $rest = self::getRestCard($cardKeyMap, $selected);
            return self::returnEvalResult(array($selected), $rest, true);          
        }else{
            return false;
        }
    }

    public static function evalCard5($cardfNo, $cardfType, $cardKeyMap){
         $cardNoTmp = array();
         for($i=0; $i<13; $i++){
            $idx = 14 - $i;
            if(sizeof($cardfNo[self::getIdxNoForCardNo($idx)])>0){
                array_push($cardNoTmp, $idx);
            }
         }           
         $result = self::evalTonghua($cardNoTmp);
         if($result > 0){
            $selected = array();
            for($i=$result - 4; $i<=$result; $i++){
                $key = $cardfNo[self::getIdxNoForCardNo($i)][0].'_'.$i;
                array_push($selected, $key);
            }
            $rest = self::getRestCard($cardKeyMap, $selected);
            return self::returnEvalResult(array($selected), $rest, true);    
         }else{
            return false;
         }

    }
    public static function evalCard4($cardfNo, $cardfType, $cardKeyMap, $istop=false){
         $match3 = array();
         for($i=0; $i<13; $i++){
            $idx = 14 - $i;
            $oneno = $cardfNo[self::getIdxNoForCardNo($idx)];
            if(sizeof($oneno)==3){
                array_push($match3, $idx);
            }
         }    
         if(sizeof($match3)>0){
            $selected = array();
            $ct = 0;
            $pickedgroups = array();
            foreach($match3 as $ione){
                $pickedgroups[$ct] = array();
                $color = $cardfNo[self::getIdxNoForCardNo($ione)];
                foreach($color as $c){
                    $key = $c.'_'.$ione;
                    array_push($pickedgroups[$ct], $key);
                    array_push($selected, $key);
                }
                $ct ++;
            }
            $rest = self::getRestCard($cardKeyMap, $selected);
            return self::returnEvalResult($pickedgroups, $rest, false, ($istop?0:2)); 
         }else{
            return false;
         }     
    }
    public static function evalCard3($cardfNo, $cardfType, $cardKeyMap){
         $match2 = array();
         for($i=0; $i<13; $i++){
            $idx = 14 - $i;
            $oneno = $cardfNo[self::getIdxNoForCardNo($idx)];
            if(sizeof($oneno)==2){
                array_push($match2, $idx);
            }
         }    
         if(sizeof($match2)>=2){
            $selected = array();
            $pickedgroups = array();
            $pairs = intval(sizeof($match2)/2);
            for($i=0; $i<$pairs; $i++){
                $pickedgroups[$i] = array();
                $idx1 = $i * 2;
                $idx2 = $idx1 + 1;
                $p1 = $match2[$idx1];
                $p2 = $match2[$idx2];
                $colors1 = $cardfNo[self::getIdxNoForCardNo($p1)];
                $colors2 = $cardfNo[self::getIdxNoForCardNo($p2)];
                foreach($colors1 as $c){
                    $key = $c.'_'.$p1;
                    array_push($selected, $key);
                    array_push($pickedgroups[$i], $key);
                }
                foreach($colors2 as $c){
                    $key = $c.'_'.$p2;
                    array_push($selected, $key);
                    array_push($pickedgroups[$i], $key);
                }
            }
            $rest = self::getRestCard($cardKeyMap, $selected);
            return self::returnEvalResult($pickedgroups, $rest, false, 1); 
         }else{
            return false;
         }
    }
    public static function evalCard2($cardfNo, $cardfType, $cardKeyMap, $istop=false){
        $match2 = 0;
        $singles = array();
        $found = false;
        $lft = ($istop?1:3);
        for($i=0; $i<13; $i++){
            $idx = 14 - $i;
            $oneno = $cardfNo[self::getIdxNoForCardNo($idx)];
            if(sizeof($oneno)==2){
                $match2 = $idx;
                $found = true;
            }else if(sizeof($oneno)==1){
                if(sizeof($singles)<$lft)
                    array_push($singles, $idx);
            }
            if($found&&sizeof($singles)==$lft){
                break;
            }
        }
        if($match2 > 0){
            $clrs = $cardfNo[self::getIdxNoForCardNo($match2)];
            $selected = array();
            foreach($clrs as $c){
                $key = $c.'_'.$match2;
                array_push($selected, $key);
            }
            foreach($singles as $s){
                $key = $cardfNo[self::getIdxNoForCardNo($s)][0].'_'.$s;
                array_push($selected, $key);
            }
            $rest = self::getRestCard($cardKeyMap, $selected);
            return self::returnEvalResult(array($selected), $rest); 
        }else{
            return false;
        }     
    }
    
    public static function evalCard1($cardfNo, $cardfType, $cardKeyMap, $istop=false){
        $selected = array();
        $needs = ($istop?3:5);
        for($i=0; $i<13; $i++){
            $idx = 14 - $i;
            $oneno = $cardfNo[self::getIdxNoForCardNo($idx)];
            if(sizeof($oneno)==1){
                $key = $oneno[0].'_'.$idx;
                array_push($selected, $key);
                if(sizeof($selected)==$needs){
                    break;
                }
            }
        }
        $rest = self::getRestCard($cardKeyMap, $selected);
        return self::returnEvalResult(array($selected), $rest, true); 
    }

    private function compareCard($cards1, $cards2){
        $cardPh1 = self::identifyCards($cards1);
        $cardPh2 = self::identifyCards($cards2);
        if($cardPh1['level']==$cardPh2['level']){
            if($cardPh1['highest']==$cardPh2['highest']){
                $highest = $cardPh1['highest'];
                $level = $cardPh1['level'];
                $cardNo1 = $cardPh1['cardNo'];
                $cardNo2 = $cardPh2['cardNo'];
                for($i=4; $i>=0; $i--){
                    $v1 = $cardNo1[$i];
                    $v2 = $cardNo2[$i];
                    if($v1 == $v2){
                        continue;
                    }else{
                        return $v1 - $v2;
                    }
                }
            }else{
                return $cardPh1['highest'] - $cardPh2['highest'];
            }
        }else{
            return $cardPh1['level'] - $cardPh2['level'];
        }
    }

    public function validCard($cardPairs){
        $cardTop = $cardPairs[0];
        $cardMiddle = $cardPairs[1];
        $cardBottom = $cardPairs[2];
        if($this->compareCard($cardTop, $cardMiddle)>0)
            return false;
        if($this->compareCard($cardMiddle, $cardBottom)>0)
            return false;
        return true;
    }


    public static function dispatchCards($cards){
        $user1Cards = array();
        $user2Cards = array();
        for($i=0; $i<self::$CARDS_EACH; $i++){
            $user1Cards[$i] = $cards[$i];
            $user2Cards[$i] = $cards[$i + self::$CARDS_EACH];
        }
        return array($user1Cards, $user2Cards);
    }

    public function newTurn($roomId){
        $cards = self::shuffleCard();
        $dispatchedCards = $this->dispatchCards();
    }

    public static function shuffleCard(){
        $cards = array();
        for($i=0; $i<self::$TOTAL_CARDS; $i++){
            $cards[$i] = $i;
        }
        return CardGame::shuffleRestCard($cards);
    }

    private static function chaseCurResult($level, $result){
        $result['fetchLevel'] = $level;
        if(!$result['recheck']){
            $level = $level - 1;
        }

        $result['startLevel'] = $level;
        return $result;
    }

    private static function evalOne($startLevel, $cardfNo, $cardfType, $cardKeyMap, $isTop=false){
        if($isTop){
            if($startLevel > 4){
                $startLevel = 4;
            }
        }
        switch($startLevel){
            case 10:
                $result = self::evalCard10($cardfNo, $cardfType, $cardKeyMap);
                if($result!==false){
                    return self::chaseCurResult(10, $result);
                }
            case 9:
                $result = self::evalCard9($cardfNo, $cardfType, $cardKeyMap);
                if($result!==false){
                    return self::chaseCurResult(9, $result);
                }
            case 8:
                $result = self::evalCard8($cardfNo, $cardfType, $cardKeyMap);
                if($result!==false){
                    return self::chaseCurResult(8, $result);
                }
            case 7:
                $result = self::evalCard7($cardfNo, $cardfType, $cardKeyMap);
                if($result!==false){
                    return self::chaseCurResult(7, $result);
                }
            case 6:
                $result = self::evalCard6($cardfNo, $cardfType, $cardKeyMap);
                if($result!==false){
                    return self::chaseCurResult(6, $result);
                }
            case 5:
                $result = self::evalCard5($cardfNo, $cardfType, $cardKeyMap);
                if($result!==false){
                    return self::chaseCurResult(5, $result);
                }
            case 4:
                $result = self::evalCard4($cardfNo, $cardfType, $cardKeyMap, $isTop);
                if($result!==false){
                    return self::chaseCurResult(4, $result);
                }
            case 3:
                if(!$isTop){
                    $result = self::evalCard3($cardfNo, $cardfType, $cardKeyMap);
                    if($result!==false){
                        return self::chaseCurResult(3, $result);
                    }
                }
            case 2:
                $result = self::evalCard2($cardfNo, $cardfType, $cardKeyMap, $isTop);
                if($result!==false){
                    return self::chaseCurResult(2, $result);
                }
            case 1:
                $result = self::evalCard1($cardfNo, $cardfType, $cardKeyMap, $isTop);
                return self::chaseCurResult(1, $result);
        }

    }

    private function getFirstNoOfCards($oricards, $number=3){
        $tmpcards = array();
        $restcards = array();
        $number = ($number > sizeof($oricards)?sizeof($oricards):$number);
        for($i=0; $i<$number; $i++){
            $tmpcards[$i] = $oricards[$i];
        }
        for($i=$number; $i<sizeof($oricards); $i++){
            $restcards[$i - $number] = $oricards[$i];
        }
        return array($tmpcards, $restcards);
    }

    private static function mergeTwoArrays ($arr1, $arr2, $withkey=false){
        if($withkey){
            foreach($arr2 as $k=>$v){
                $arr1[$k] = $v;
            }
        }else{
            foreach($arr2 as $v){
                array_push($arr1, $v);
            }           
        }

        return $arr1;
    }

    private static function evalCardsStrategies_Sub($cardkeys, $startlevel=10, $level=3){
        $v = self::evalCardsFeaturesWithKeys($cardkeys);
        $cardfNo = $v[0];
        $cardfType = $v[1];
        $cardKeyMap = $v[2];  

        $max = $startlevel;
        $min = 1;
        
        $combine = array();

        for($i=$max; $i>=$min; $i--){
            $result = self::evalOne($i, $cardfNo, $cardfType, $cardKeyMap, $level==1);
            $mylevel = $result['fetchLevel'];
            $i = $mylevel;
            $picked = $result['picked'];
            $rest = $result['rest'];
            $sz = sizeof($picked);
            switch($level){
                case 3:
                if($sz > 1){
                    for($m =0; $m<$sz; $m++){
                        $newrest = self::duplicateArr($rest);
                        if($m > 0){
                            for($k= 0; $k<$m; $k++){
                                $newrest= self::mergeTwoArrays($newrest, $picked[$k]);
                            }
                        }
                        $cur = array();
                        $cur['bottom'] = $picked[$m];
                        $cur['bottomLevel'] = $mylevel;
                        $cur['middlearr'] = array();
                        for($n = $m+1; $n<$sz; $n++){
                            $middlearr = array();
                            $middlearr['middle'] = $picked[$n];
                            $middlearr['middleLevel'] = $mylevel;
                            $myrest = self::duplicateArr($newrest);
                            if($n - $m == 2){
                                $myrest = self::mergeTwoArrays($myrest, $picked[$m+1]);
                            }else if($n - $m == 1&&$m+2<$sz){
                                $myrest = self::mergeTwoArrays($myrest, $picked[$m+2]);
                            }

                            $middlearr['toparr'] = array();
                            $middlearr['toparr'] = self::mergeTwoArrays($middlearr['toparr'], self::evalCardsStrategies_Sub($myrest, $mylevel, 1));

                            array_push($cur['middlearr'], $middlearr);

                        }

                        if($m+1 < $sz){
                            for($k = $m+1; $k<$sz; $k++){
                                $newrest = self::mergeTwoArrays($newrest, $picked[$k]);
                            }
                        }
                        $cur['middlearr'] = self::mergeTwoArrays($cur['middlearr'], self::evalCardsStrategies_Sub($newrest, $mylevel - 1, 2));

                        array_push($combine, $cur);
                    }
                }else{
                    $cur = array();
                    $cur['bottom'] = $picked[0];
                    $cur['bottomLevel'] = $mylevel;
                    $cur['middlearr'] = array();
                    $cur['middlearr'] = self::mergeTwoArrays($cur['middlearr'], self::evalCardsStrategies_Sub($rest, $mylevel - 1, 2)); 

                    array_push($combine, $cur);                
                }
                break;

                case 2:
                for($m = 0; $m < $sz; $m ++){
                    $cur = array();
                    $cur['middle'] = $picked[$m];
                    $cur['middleLevel'] = $mylevel;
                    $cur['toparr'] = array();
                    $newrest = $rest;
                    if($sz > 1&&$m<2){
                        $newrest = self::duplicateArr($rest);
                        $newrest = self::mergeTwoArrays($newrest, $picked[1-$m]);
                    }
                    $cur['toparr'] = self::mergeTwoArrays($cur['toparr'], self::evalCardsStrategies_Sub($newrest, $mylevel, 1));

                    array_push($combine, $cur);  
                }
                break;

                case 1:
                $cur = array();
                $cur['top'] = $picked[0];
                $cur['topLevel'] = $mylevel;
                $cur['rest'] = $rest;

                array_push($combine, $cur);
                break;
            }
        }  
        return $combine;
    }

    private static function evalCardsStrategies($cardkeys){
        $result = self::evalCardsStrategies_Sub($cardkeys);

        $finalResult = array();
        $convarr = ['bottom', 'middle', 'top', 'rest'];

        foreach($result as $one){
            $cur = array();
            $cur['bottom'] = $one['bottom'];
            $cur['bottomLevel'] = $one['bottomLevel'];
            $cur['bottomLevelName'] = self::getLevelName($one['bottomLevel']);
            foreach($one['middlearr'] as $onemid){
                $curmid = self::duplicateArr($cur);
                $curmid['middle'] = $onemid['middle'];
                $curmid['middleLevel'] = $onemid['middleLevel'];
                $curmid['middleLevelName'] = self::getLevelName($onemid['middleLevel'], 2);
                foreach($onemid['toparr'] as $onetop){
                    $curtop = self::duplicateArr($curmid);
                    $curtop['top'] = $onetop['top'];
                    $curtop['topLevel'] = $onetop['topLevel'];
                    $curtop['topLevelName'] = self::getLevelName($onetop['topLevel'], 3);
                    $rest = $onetop['rest'];
                    $missedCt = 0;
                    $missedBottom = 5 - sizeof($curtop['bottom']);
                    for($i=0; $i<$missedBottom; $i++){
                        array_push($curtop['bottom'], $rest[$missedCt]);
                        $missedCt ++;
                    }
                    $missedMiddle = 5 - sizeof($curtop['middle']);
                    for($i=0; $i<$missedMiddle; $i++){
                        array_push($curtop['middle'], $rest[$missedCt]);
                        $missedCt ++;
                    }    
                    $missedTop = 3 - sizeof($curtop['top']);
                    for($i=0; $i<$missedTop; $i++){
                        array_push($curtop['top'], $rest[$missedCt]);
                        $missedCt ++;
                    }   
                    $newrest = $rest;
                    if($missedCt > 0){
                        $newrest = array();
                        for($i=$missedCt; $i<sizeof($rest); $i++){
                            array_push($newrest, $rest[$missedCt]);
                        }                         
                    }
             
                    $curtop['rest'] = $newrest;       

                    //conversion
                    foreach($convarr as $onek){
                        $curtop[$onek] = self::revertCardsFromKeys($curtop[$onek]);
                    }

                    array_push($finalResult, $curtop);
                }
            }
        }
        return $finalResult;
    }
    private static function evalCardsStrategies_0($cardkeys){
        $bottomCards = array();
        $middleCards = array();
        $topCards = array();
        $restCards = array();

        $bottomLevel = 0;
        $middleLevel = 0;
        $topLevel = 0;

        $v = self::evalCardsFeaturesWithKeys($cardkeys);
        $cardfNo = $v[0];
        $cardfType = $v[1];
        $cardKeyMap = $v[2];

        $result = self::evalOne(10, $cardfNo, $cardfType, $cardKeyMap);

        $picked = $result['picked'];
        $rest = $result['rest'];

        $bottomCards = $picked[0];
        $bottomLevel = $result['fetchLevel'];

        if(sizeof($picked)>1){
            $middleCards = $picked[1];
            $middleLevel = $result['fetchLevel'];
            if(sizeof($picked)>2){
                foreach($picked[2] as $onekey){
                    array_push($rest, $onekey);
                }
            }
        }else{
            $v = self::evalCardsFeaturesWithKeys($result['rest']);
            $cardfNo = $v[0];
            $cardfType = $v[1];
            $cardKeyMap = $v[2];               
            $result = self::evalOne($result['startLevel'], $cardfNo, $cardfType, $cardKeyMap);
            $middleCards = $result['picked'][0];
            $middleLevel = $result['fetchLevel'];
            $rest = $result['rest'];   
            $picked = $result['picked'];   

            if(sizeof($picked)>1){
                foreach($picked[1] as $onekey){
                    array_push($rest, $onekey);
                }
            }               
        }

        $v = self::evalCardsFeaturesWithKeys($rest);
        $cardfNo = $v[0];
        $cardfType = $v[1];
        $cardKeyMap = $v[2];               
        $result = self::evalOne($result['startLevel'], $cardfNo, $cardfType, $cardKeyMap, true);
        $topCards = $result['picked'][0];
        $topLevel = $result['fetchLevel'];
        $rest = $result['rest'];

        $missedCount = 0;
        $off = 5 - sizeof($bottomCards);
        for($i=0; $i<$off; $i++){
            array_push($bottomCards, $rest[$i]);
        }
        $missedCount += $off;

        $off = 5 - sizeof($middleCards);
        for($i=$missedCount; $i<$off + $missedCount; $i++){
            array_push($middleCards, $rest[$i]);
        }
        $missedCount += $off;

        $off = 3 - sizeof($topCards);
        for($i=$missedCount; $i<$off + $missedCount; $i++){
            array_push($topCards, $rest[$i]);
        }     

        for($i=sizeof($rest)-3; $i<sizeof($rest); $i++){
            array_push($restCards, $rest[$i]);
        }   

        return ['bottom'=> self::revertCardsFromKeys($bottomCards), 'bottomLevel'=>$bottomLevel, 'bottomLevelName'=>self::getLevelName($bottomLevel),
                'middle'=>self::revertCardsFromKeys($middleCards), 'middleLevel'=>$middleLevel, 'middleLevelName'=>self::getLevelName($middleLevel, 2),
                'top'=>self::revertCardsFromKeys($topCards), 'topLevel'=>$topLevel, 'topLevelName'=>self::getLevelName($topLevel, 1),
                'rest'=>self::revertCardsFromKeys($restCards)];

    }

    public static function evalCardsFeaturesWithKeys($cardkeys){
        $cardfeature = array();
        $cardfeature2 = array();
        $cardfeature3 = array();
        for($i=2; $i<=14; $i++){
            $cardfeature[self::getIdxNoForCardNo($i)] = array();
        }
        for($i=0; $i<4; $i++){
            $cardfeature2[self::$CARD_TYPES[$i]] = array();
        }

        foreach($cardkeys as $key3){
            $v = explode('_', $key3);
            $cardNo = intval($v[1]); 
            $type = $v[0];
            array_push($cardfeature[self::getIdxNoForCardNo($cardNo)], $type);
            array_push($cardfeature2[$type], $cardNo);
            if(array_key_exists($key3, $cardfeature3)){
                $cardfeature3[$key3] = $cardfeature3[$key3] + 1;
            }else{
                $cardfeature3[$key3] = 1;
            }
        }

        foreach($cardfeature as $k=>$v){
            sort($v);
            $cardfeature[$k] = $v;
        }
        foreach($cardfeature2 as $k=>$v){
            sort($v);
            $cardfeature2[$k] = $v;
        }

        $arr = [$cardfeature, $cardfeature2, $cardfeature3, $cardkeys];  
        return $arr;    
    }

    public static function evalCardsFeatures($cards){
        $cardkeys = array();
        foreach($cards as $onecard){
            $cardphase = CardGame::getCardWithoutKing($onecard, self::$CARD_TYPES);
            $key3 = $cardphase['type'].'_'.$cardphase['cardNo'];
            array_push($cardkeys, $key3);
        }
        sort($cardkeys);
        return self::evalCardsFeaturesWithKeys($cardkeys);   
    }

    private function submitCards_sub0($cards){
        $sz = sizeof($cards);
        $ct = 0;
        $str = '';
        foreach($cards as $card){
            $str.=$card;
            if($ct < $sz - 1)
                $str.=',';
            $ct ++;
        }
        return $str;
    }

    public function readyForNextTurn(){
        $statusid = intval(Input::get('statusid'));
        $userid = Auth::user()->id;
        $statusModel = GameUserStatusModel::find($statusid);
        $statusModel->status = self::$USER_READY;
        $statusModel->save();
        return 'true';
    }

    public function checkAllPrepared($roomid){
        return strval($this->checkBothStatus($roomid, self::$USER_PREPARED));
    }

    public function checkAllForNextTurn($roomid){
        return strval($this->checkBothStatus($roomid, self::$USER_READY));
    }

    private function checkBothStatus($roomid, $thestatus){
         $room = GameRoomModel::find($roomid);
        $users = explode(',', $room->users);
        $ct = 0;
        foreach($users as $user){
            $status = GameUserStatusModel::where('user_id', intval($user))->where('game_name', $this->GAME_TYPE)->first();
            if($status==$thestatus)
                $ct++;
        }
        if($ct == 2)
            return true;
        return false;       
    }

    private function auditing($userid, $roomid, $data){}

    public function getMyCards($statusid){
        $status = GameUserStatusModel::find(intval($statusid));
        return $status->curdata;
    }

    private function updateUserCards($statusid, $topcards, $middlecards, $bottomcards, $extracards, $roomid=''){
       $data = $this->submitCards_sub0($topcards).';'.$this->submitCards_sub0($middlecards).';'
                    .$this->submitCards_sub0($bottomcards).';'.$this->submitCards_sub0($extracards);
       $statusModel = GameUserStatusModel::find(intval($statusid));
       $statusModel->status = self::$USER_PREPARED;
       if(strlen($roomid)>0)
            $statusModel->room_id = $roomid;
       $statusModel->curdata = $data;
       $statusModel->save();
    }

    public function submitCards(){
       $topcards = Input::get('top'); 
       $middlecards = Input::get('middle');
       $bottomcards = Input::get('bottom');
       $extracards = Input::get('extra');
       $statusid = Input::get('statusid');
       $roomid = Input::get('roomid');

       $this->updateUserCards($statusid, $topcards, $middlecards, $bottomcards, $extracards, $roomid);

       return 'true';
    }

    private function prepareNewRoom($userid, $userscore){
        $room = new GameRoomModel();
        $room->opened_by = $userid;
        $room->allow_players = 2;
        $room->users = strval($userid);
        $room->score_low = $userscore - 5;
        $room->score_high = $userscore + 5;
        $room->number_users = 1;
        $room->game_name = $this->GAME_TYPE;
        $room->tranid = 0;
        $room->save();
        return $room->id;
    }

    //Not complete
    public function postGamePreference(){
        $userid = Auth::user()->id;
        $preference = GameUserPreferenceModel::where('user_id', $userid)->first();
        $score = 30;
        if(!$preference){
            $preference = new GameUserPreferenceModel();
            $preference->user_id = $userid;
        }
        $preference->preference = json_encode(['a'=>'a'],JSON_UNESCAPED_UNICODE);
        $preference->user_score = $score;
        $preference->save();
        return 'true';
    }

    public function leaveRoom($roomid){
        $room = GameRoomModel::find($roomid);
        if($room->number_users == 2){
            return 'false';
        }
        $room->delete();
        return 'true';
    }

    public function isReady($roomid){
        $room = GameRoomModel::find($roomid);
        if(!$room){
            return $this->ready(false);
        }
        if($room->number_users==2){
            return json_encode(['true', $roomid],JSON_UNESCAPED_UNICODE);
        }
        return json_encode(['false', $roomid],JSON_UNESCAPED_UNICODE);
    }

    public function getGameUserStatusId(){
        $userid = Auth::user()->id;
        $preference = GameUserPreferenceModel::where('user_id', $userid)->first();
        $userscore = $preference->user_score;

        $userstatusinfo = GameUserStatusModel::where('user_id', $userid)->where('game_name', $this->GAME_TYPE)->first();
        if(!$userstatusinfo){
            $userstatusinfo = new GameUserStatusModel();
            $userstatusinfo->user_id = $userid;
            $userstatusinfo->game_name =  $this->GAME_TYPE;
        }
        $userstatusinfo->user_score = $userscore;
        $userstatusinfo->game_score = 0;
        $userstatusinfo->status = self::$USER_READY;
        $userstatusinfo->room_id = $roomid;
        $userstatusinfo->save();

        $status = GameUserStatusModel::where('user_id', $userid)->where('game_name', $this->GAME_TYPE)->first();
        if($status)
            return strval($status->id);
        else
            return '0';
    }

    private function updateRoom($roomid, $userid, $userscore){
        $room = GameRoomModel::find($roomid);
        $isfull = false;
        if((!$room)||$room->number_users==2){
            $roomid = $this->prepareNewRoom($userid, $userscore);
            $isfull = false;
        }else{
            $room->number_users = 2;
            $room->tranid = $room->tranid + 1;
            $room->users = $room->users.','.$userid;
            $room->save();
            $isfull = true;
        }
        return [$roomid, $isfull];
    }

    public function ready($willcheck=true){
        $userid = Auth::user()->id;
        $userscore = 0;
        $preference = GameUserPreferenceModel::where('user_id', $userid)->first();
        $userscore = $preference->user_score;
        $room = false;
        if($willcheck){
            $room = GameRoomModel::where('game_name', $this->GAME_TYPE)->where('number_users', 1)
                    ->where('score_low', '<', $userscore)->where('score_high', '>', $userscore)->first();                  
        }
        $roomid = '';
        $isReady = false;
        if($willcheck&&$room){
            $roomid = $room->id;
            $info = $this->updateRoom($roomid, $userid, $userscore);
            $roomid = $info[0];
            $isReady = $info[1];
        }else{
            $roomid = $this->prepareNewRoom($userid, $userscore);
            $willcheck = false; 
        }

        return json_encode([strval($isReady), $roomid], JSON_UNESCAPED_UNICODE);

    }

    public function getGameData($roomid){
        $room = GameRoomModel::find(intval($roomid));
        return $room->data;
    }

    public function getStrategy(){
        //$cards = Input::get('cards');
        $cards = self::shuffleCard();
        $cardOne = self::dispatchCards($cards)[0];       
        return json_encode(self::identifyCardsFeatures($cardOne), JSON_UNESCAPED_UNICODE);
    }

    private static function getIdxNoForCardNo($no){
        return 'idx_'.$no;
    }

    public static function identifyCardsFeatures($cards){
        $result = array();
        $v = self::evalCardsFeatures($cards);
        $cardfeature = $v[0];
        $cardfeature2 = $v[1];
        $cardfeature3 = $v[2];
        $cardkeys = $v[3];

        for($i=0; $i<4; $i++){
            sort($cardfeature2[self::$CARD_TYPES[$i]]);
        }
        $bigdragon = '';
        $selected = array();
        $rest = array();
        for($i=0; $i<4; $i++){
            $color = self::$CARD_TYPES[$i];
            if(sizeof($cardfeature2[$color])>=13){
                if($color == 'diamond'){
                    $istrue = true;
                    for($j=2; $j<=14; $j++){
                        $key = $color.'_'.$j;
                        if(!array_key_exists($key, $cardfeature3)){
                            $istrue = false;
                            break;
                        }
                    }
                    if($istrue){
                        $bigdragon = $color;
                    }
                }else{
                    $bigdragon = $color;
                }
                break;
            }
        }
        if(strlen($bigdragon)==0){
            $smalldragon = true;
            for($i=2; $i<=14; $i++){
                if(sizeof($cardfeature[self::getIdxNoForCardNo($i)])==0){
                    $hasAllShun = false;
                    break;
                }
            }            
            if($hasAllShun){
                for($i=2; $i<14; $i++){
                   $key = $cardfeature[self::getIdxNoForCardNo($i)][0].'_'.$i;
                    array_push($selected, $key);
                }
            }
        }else{
            for($i=2; $i<=14; $i++){
                $key = $bigdragon.'_'.$i;
                array_push($selected, $key);
            }

        }
        $isspecial = (sizeof($selected)==13);
        if($isspecial){
            $result['specialCards'] = self::revertCardsFromKeys($selected);
            $result['specialRest'] =  self::revertCardsFromKeys(self::getRestCard($cardfeature3, $selected));
        }
        $result['isspecial'] = $isspecial;

        $result['strategies'] = self::evalCardsStrategies($cardkeys);

        return $result;
    }

}
