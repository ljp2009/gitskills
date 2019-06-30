<?php

namespace App\Http\Controllers\Game\Game13;

use App\Models as MD;
use App\Http\Controllers\Controller;

class Game13WIPCardHandler extends Controller{
    private static $CARD_SIZE = 65;
    private static $CARD_COLORS = ['spade', 'heart', 'club', 'diamond'];
    public static $CARDLEVEL_NAME = array('乌龙', '一对', '两对', '三条', '顺子', '同花', '葫芦', '铁支', '同花顺', '五同', '一条龙', '大青龙');
    private static function JSONEncode($arr){
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }
    private static function cloneArr($arr, $hasKey=false){
    	$newarr = [];
    	if($hasKey){
    		foreach($arr as $k=>$v){
    			$newarr[$k] = $v;
    		}
    	}else{
    		for($i=0; $i<sizeof($arr); $i++){
    			$newarr[$i] = $arr[$i];
    		}
    	}
    	return $newarr;
    }

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

    public static function getRandomCards($cards, $noofcards=16){
    	$ncards = self::cloneArr($cards);
    	$resultcards = [];
    	$n = sizeof($ncards);
    	for($i=0; $i<$noofcards; $i++){
    		$idx = mt_rand(1, $n) - 1;
    		array_push($resultcards, $ncards[$idx]);
    		for($j = $idx; $j < $n - 1; $j ++){
    			$ncards[$j] = $ncards[$j + 1];
    		}
    		$n--;
    	}
    	return $resultcards;
    }

    private static function evalCardData ($idx){
        $cardColor = intval($idx / 13);
        if($cardColor == 4){
            $cardColor = 3;
        }
        $cardColor = self::$CARD_COLORS[$cardColor];
        $cardNo = $idx % 13 + 1;
        if($cardNo == 1){
            $cardNo = 14;
        }
        return ['idx'=>$idx, 'cardNo'=>$cardNo, 'cardColor'=>$cardColor, 'cardNoString'=>'card_'.$cardNo, 
        		'id'=>'card_'.$idx];
    }

    public static function toCardIndex($cardStr){
        $ex = false;
        if(strpos($cardStr, '_2_') > 0){
            $cardStr = str_replace('_2_', '_', $cardStr);
            $ex = true;
        }
    	$strs = explode('_', $cardStr);
    	$clidx = 0;
    	for($i=0; $i<sizeof(self::$CARD_COLORS); $i++){
    		if($strs[0] == self::$CARD_COLORS[$i]){
    			$clidx = $i;
    			break;
    		}
    	}
    	$cno = intval($strs[1]);
    	if($cno == 14){
    		$cno = 1;
    	}
    	$cno --;

    	$cidx =  $clidx * 13 + $cno;
        if($ex){
            $cidx += 13;
        }
        return $cidx;
    }

    private static function _compare_sub($cards1, $oriCards1,  $cards2, $oriCards2, $level){
    	$cmp = 0;
    	switch($level){
    		case 7:
    			$cmp = $cards1[0]['cardNo'] - $cards2[0]['cardNo'];
    			if($cmp == 0){
    				$cmp = $cards1[3]['cardNo'] - $cards2[3]['cardNo'];
    			}
    			break;
    		case 6:
    			return self::compareCards($cards1, 1, $cards2, 1);
    		case 3:
    			$cmp = $cards1[0]['cardNo'] - $cards2[0]['cardNo'];
    			if($cmp == 0){
    				$cmp = $cards1[2]['cardNo'] - $cards2[2]['cardNo'];
    			}
    			break;    			
    		default:
    			$cmp = $cards1[0]['cardNo'] - $cards2[0]['cardNo'];
    			break;    			
    	}
    	if($cmp == 0){
    		if(sizeof($cards1)<5){
	    		$rest1 = self::removeCards($oriCards1, $cards1);
	    		$rest2 = self::removeCards($oriCards2, $cards2);
	    		return self::compareCards($rest1, 1, $rest2, 1);    			
    		}else{
    			return 0;
    		}
    	}else{
    		return ($cmp > 0? 1:-1);
    	}
    }

    public static function compareCards($cards1, $level1, $cards2, $level2){
    	if($level1 == $level2){
    		if($level1 == 1){
    			$cards1 = self::sortCardsByCardNo($cards1);
    			$cards2 = self::sortCardsByCardNo($cards2);
    			$minsz = 0; $szdiff = sizeof($cards1)-sizeof($cards2);
    			if($szdiff > 0){
    				$minsz = sizeof($cards2);
    			}else{
    				$minsz = sizeof($cards1);
    			}
    			$cmp = 0;
    			for($i=0; $i<$minsz; $i++){
    				if($cards1[$i]['cardNo'] != $cards2[$i]['cardNo']){
    					$cmp = (($cards1[$i]['cardNo'] > $cards2[$i]['cardNo'])?1:-1);
    					return $cmp;
    				}
    			}
    			if($szdiff == 0){
    				return 0;
    			}else if($szdiff > 0){
    				return 1;
    			}else{
    				return -1;
    			}
    		}else{
    			$cards1Re = self::evalCardsLevel($level1, $cards1);
    			$cards2Re = self::evalCardsLevel($level2, $cards2);

    			return self::_compare_sub($cards1Re[0], $cards1, $cards2Re[0], $cards2, $level1);
    		}
    	}else{
    		return $level1 - $level2;
    	}
    }

    public static function toStandardCardStyles($prefered){
        $result = $prefered;
        if(sizeof($prefered['level'])==1){
            if(array_key_exists('body', $prefered)){
                    $result = $prefered;
            }else{
                $body = [];
                $arr = ['top', 'middle', 'bottom'];
                foreach($arr as $k){
                    foreach($prefered[$k] as $one){
                        array_push($body, $one);
                    }
                }
                $result = ['body'=>$body, 'extra'=>$prefered['extra'], 
                                'level'=>$prefered['level'], 'changed'=>'false'];
            }
        }     
        return $result;   
    }
    public static function evalCardStyles($prefered){
    	$result = ['top'=>[], 'middle'=>[], 'bottom'=>[], 'extra'=>$prefered['extra'], 
    				'level'=>[], 'changed'=>'true'];
    	if($prefered['changed']=='false'){
            $result = self::toStandardCardStyles($prefered);
    	}else{
	    	$top = $prefered['top'];
	    	$mid = $prefered['middle'];
	    	$btm = $prefered['bottom'];
	    	$topArr = []; $midArr = []; $btmArr = [];
	    	$totalArr = [];

	    	foreach($top as $one){
	    		$idx = self::toCardIndex($one);
	    		array_push($topArr, $idx);
	    		array_push($totalArr, $idx);
	    	}
	    	foreach($mid as $one){
	    		$idx = self::toCardIndex($one);
	    		array_push($midArr, $idx);
	    		array_push($totalArr, $idx);
	    	}
	    	foreach($btm as $one){
	    		$idx = self::toCardIndex($one);
	    		array_push($btmArr, $idx);
	    		array_push($totalArr, $idx);
	    	}

	    	$totalCards = self::convCardsIndToCards($totalArr);
	    	$totalRe = self::evalBestCards($totalCards, 12);
	    	if($totalRe['level']>10){
	    		$re = [];
	    		foreach($totalRe['result'][0] as $one){
	    			array_push($re, $one['cardColor'].'_'.$one['cardNo']);
	    		}
	    		$result = ['body'=>$re, 'extra'=>$prefered['extra'], 'level'=>[$totalRe['level']]];
	    	}else{
	        	$topCards = self::convCardsIndToCards($topArr);
		    	$midCards = self::convCardsIndToCards($midArr);
		    	$btmCards = self::convCardsIndToCards($btmArr);

		    	$topRe = self::evalBestCards($topCards, 4, true);
		    	if($topRe == false){
		    		$topRe = ['result'=>[self::sortCardsByCardNo($topCards)], 'level'=>1];
		    	}
		    	$midRe = self::evalBestCards($midCards, 10);
		    	if($midRe == false){
		    		$midRe = ['result'=>[self::sortCardsByCardNo($midCards)], 'level'=>1];
		    	}
		    	$btmRe = self::evalBestCards($btmCards, 10);
		    	if($btmRe == false){
		    		$btmRe = ['result'=>[self::sortCardsByCardNo($btmCards)], 'level'=>1];
		    	}
		    	$cmp = self::compareCards($btmRe['result'][0], $btmRe['level'], $midRe['result'][0], $midRe['result']);
		    	$needreorg = false;
		    	if($cmp < 0){
		    		$needreorg = true;
		    	}else if(self::compareCards($midRe['result'][0], $midRe['level'], $topRe['result'][0], $topRe['result']) < 0){
		    		$needreorg = true;
		    	}
		    	if(!$needreorg){
		    		$prefered['changed'] = 'false';
		    		$result = $prefered;
		    	}else{
		    		$btmCards = $totalRe['result'][0]; $btmLevel = $totalRe['level'];
		    		$restCards = self::removeCards($totalCards, $btmCards);
		    		$midRe = self::evalBestCards($restCards, $btmLevel);
		    		if($midRe == false){
		    			$midCards = []; $topCards = []; $midLevel = 1; $topLevel = 1;
		    			$restCards = self::sortCardsByCardNo($restCards);
		    			for($i=0; $i<sizeof($restCards); $i++){
		    				if($i<5){
		    					array_push($midCards, $restCards[$i]);
		    				}else{
		    					array_push($topCards, $restCards[$i]);
		    				}
		    			}
		    		}else{
		    			$midCards = $midRe['result'][0];
		    			$midLevel = $midRe['level'];

		    			$topCards = self::removeCards($restCards, $midCards);
		    			$topRe = self::evalBestCards($topCards, $midLevel, true);
		    			$topLevel = ($topRe==false?1:$topRe['level']);
		    		}

		    		$tmpre = ['top'=>$topCards, 'middle'=>$midCards, 'bottom'=>$btmCards];
		    		foreach($tmpre as $k=>$v){
		    			foreach($v as $m=>$one){
		    				array_push($result[$k], $one['cardColor'].'_'.$one['cardNo']);
		    			}
		    		}
		    		$result['level'] = [$topLevel, $midLevel, $btmLevel];
		    	    $result['changed'] = 'true';
                }

	    	}    		
    	}


    	return $result;

    }

    private static function sortCardsByCardNo($cards, $desc=true){
        $func = false;
        if($desc){
            $func = function($c1, $c2){
                $key = 'cardNo';
                if($c1[$key] == $c2[$key]){
                    return 0;
                }else{
                    return ($c1[$key] > $c2[$key])?-1:1;
                }
            };
        }else{
             $func = function($c1, $c2){
                $key = 'cardNo';
                if($c1[$key] == $c2[$key]){
                    return 0;
                }else{
                    return ($c1[$key] > $c2[$key])?1:-1;
                }
            };
        }   
        usort($cards, $func);
        return $cards;  
    }

    private static function containsCard($cards, $card){
    	foreach($cards as $one){
    		if($one['idx'] == $card['idx']){
    			return true;
    		}
    	}
    }

    private static function groupCards($cards, $byColor=true){
        $result = [];
        if($byColor){
        	$ncards = $cards;
            foreach($ncards as $one){
                $key = $one['cardColor'];
                if(!array_key_exists($key, $result)){
                    $result[$key] = [];
                }
                array_push($result[$key], $one);
            }
            foreach($result as $k=>$v){
                $result[$k] = self::sortCardsByCardNo($v);
            }
        }else{
        	$ncards = self::cloneArr($cards);
            $ncards = self::sortCardsByCardNo($ncards);
            foreach($ncards as $one){
                $key = $one['cardNoString'];
                if(!array_key_exists($key, $result)){
                    $result[$key] = [];
                }
                array_push($result[$key], $one);
            }
        }
        return $result;
    }

    private static function newCardsWithIds($cards){
    	$r = [];
    	foreach($cards as $one){
    		$r[$one['id']] = $one;
    	}
    	return $r;
    }

    public static function removeCards($cardsOwner, $cardsSub){
        $arr = [];
        foreach($cardsOwner as $one){
            array_push($arr, ['card'=>$one, 'visited'=>false]);
        }
        foreach($cardsSub as $o){
            for($i=0; $i<sizeof($arr); $i++){
                $one = $arr[$i];
                if(!$one['visited'] && $one['card']['id'] == $o['id']){
                    $one['visited'] = true;
                    $arr[$i] = $one;
                    break;
                }
            }
        }
        $r = [];

        foreach($arr as $one){
            if(!$one['visited']){
                array_push($r, $one['card']);
            }
        }
    	return $r;
    }

    private static function mergeCards($cardsOwner, $cardsSub){
    	$nowner = self::newCardsWithIds($cardsOwner);
    	$nsub = self::newCardsWithIds($cardsSub);
    	$r = [];
    	foreach($nowner as $k=>$v){
    		array_push($r, $v);
    	}
     	foreach($cardsSub as $k=>$v){
    		if(!array_key_exists($k, $nowner)){
    			array_push($r, $v);
    		}
    	}  
    	return $r; 	
    }


    /* --------------------Card evaluation -------------------------*/

    private static function standardReturnEval($result){
    	if(sizeof($result) > 0){
    		return $result;
    	}else{
    		return false;
    	}
    }

    private static function evalCardsLevel12($cards){
    	$grouped = self::groupCards($cards);
    	$result = [];
    	foreach($grouped as $k=>$v){
    		if(sizeof($v) >= 13){
    			$one = [];
    			$newv = self::groupCards($v, false);
    			for($i=0; $i < 13; $i ++){
    				$cardkey = 'card_'.($i + 2);
    				if(array_key_exists($cardkey, $newv)){
    					$rk = array_rand($newv[$cardkey]);
    					array_push($one, $newv[$cardkey][$rk]);
    				}else{
    					break;
    				}
    			}
    			if(sizeof($one)==13){
    				array_push($result, $one);
    			}
    		}
    	}
    	return self::standardReturnEval($result);
    }

    private static function _evalCardsLevel11_sub($cards){
    	$grouped = self::groupCards($cards, false);
    	$result = [];
    	if(sizeof($grouped) < 13){
    		return false;
    	}
		for($i=0; $i < 13; $i ++){
			$cardkey = 'card_'.($i + 2);
    		if(array_key_exists($cardkey, $grouped)){
    			$rk = array_rand($grouped[$cardkey]);
    			array_push($result, $grouped[$cardkey][$rk]);
    		}else{
    			return false;
    		}			
		}
		return [$result];
    }

    private static function evalCardsLevel11($cards){
    	$result = [];
    	while(true){
    		$r = self::_evalCardsLevel11_sub($cards);
    		if($r!==false){
    			$one = $r[0]; 
    			$cards = self::removeCards($cards, $one);
    			array_push($result, $one);
    		}else{
    			break;
    		}
    	}
    	return self::standardReturnEval($result);

    }

    private static function evalCardsLevel10($cards){
    	$grouped = self::groupCards($cards, false);

    	$result = [];
    	foreach($grouped as $k=>$v){
    		if(sizeof($v)==5){
    			array_push($result, $v);
    		}
    	}

    	return self::standardReturnEval($result);
    }

    private static function getShuns($cards){
    	$grouped = self::groupCards($cards, false);

    	$result = [];
    	$last = 0; $start = []; $ct = 0;
    	for($i=14; $i>=2; $i--){
    		$cardkey = 'card_'.$i;
    		if(array_key_exists($cardkey, $grouped)){
    			if($last == 0){
    				$ct = 1;
    			}else{
    				if($last - $i==1){
    					$ct ++;
    				}else{
    					$ct = 1;
    				}
    			}
    			$last = $i;

    			if($ct == 5){
    				array_push($start, $last);
    				$ct = 0;
    			}
    		}
    	}

    	foreach($start as $onest){
    		$r = [];
    		for($i=0; $i<5; $i++){
    			$cardkey = 'card_'.($onest + $i);
    			$rk = array_rand($grouped[$cardkey]);
    			array_push($r, $grouped[$cardkey][$rk]);
    		}
    		array_push($result, $r);
    	}

    	return $result;
    }

    private static function evalCardsLevel9($cards){
    	$grouped = self::groupCards($cards);
    	$result = [];
    	foreach($grouped as $k=>$v){
    		if(sizeof($v)>=5){
    			$r = self::getShuns($v);
    			foreach($r as $one){
    				array_push($result, $one);
    			}
    		}
    	}
    	return self::standardReturnEval($result);
    }

    private static function evalCardsLevel8($cards){
    	$grouped = self::groupCards($cards, false);

    	$result = [];
    	foreach($grouped as $k=>$v){
    		if(sizeof($v) >= 4){
    			$vs = [];
    			for($i=0; $i<4; $i++){
    				$vs[$i] = $v[$i];
    			}
    			array_push($result, $vs);
    		}
    	}
    	return self::standardReturnEval($result);
    }

    private static function evalCardsLevel7($cards){
		$grouped = self::groupCards($cards, false);

		$result3 = [];
		$result2 = [];
		foreach($grouped as $k=>$v){
			if(sizeof($v) == 3){
				array_push($result3, $v);
			}else if(sizeof($v) == 2){
				array_push($result2, $v);
			}
		}
		$s = sizeof($result3);
		if($s > sizeof($result2)){
			$s = sizeof($result2);
		}
		$result = [];
		for($i=0; $i<$s; $i++){
			$r = [];
			foreach($result3[$i] as $one){
				array_push($r, $one);
			}
			foreach($result2[$i] as $one){
				array_push($r, $one);
			}
			array_push($result, $r);
		}
		return self::standardReturnEval($result);
    }

    private static function evalCardsLevel6($cards){
    	$grouped  = self::groupCards($cards);
    	
    	$result = [];
    	foreach($grouped as $k=>$v){
    		if(sizeof($v)>=5){
    			$ra = [];
    			for($i=0; $i<5; $i++){
    				array_push($ra, $v[$i]);
    			}
    			array_push($result, $ra);
    			if(sizeof($v)>=10){
    				$ra = [];
    				for($i=5; $i<10; $i++){
    					array_push($ra, $v[$i]);
    				}
    				array_push($result, $ra);
    			}
    		}
    	}
		return self::standardReturnEval($result);
    }

    private static function evalCardsLevel5($cards){
    	$result = self::getShuns($cards);

    	return self::standardReturnEval($result);
    }

    private static function evalCardsLevel4($cards){
    	$grouped = self::groupCards($cards, false);

    	$result = [];
    	foreach($grouped as $k=>$v){
    		if(sizeof($v)==3){
    			array_push($result, $v);
    		}
    	}
    	return self::standardReturnEval($result);
    }

    private static function evalCardsLevel3($cards){
    	$grouped = self::groupCards($cards, false);

    	$result =  [];
    	$result2 = [];
    	foreach($grouped as $k=>$v){
    		if(sizeof($v)==2){
    			array_push($result2, $v);
    		}
    	} 
    	$s = sizeof($result2);
    	if($s % 2 == 1){
    		$s = $s - 1;
    	}   	
    	for($i=0; $i<$s; $i+=2){
    		$ra = [];
    		foreach($result2[$i] as $one){
    			array_push($ra, $one);
    		}

    		foreach($result2[$i + 1] as $one){
    			array_push($ra, $one);
    		}
    		array_push($result, $ra);
    	}
    	return self::standardReturnEval($result);
    }

    private static function evalCardsLevel2($cards){
    	$grouped = self::groupCards($cards, false);

    	$result = [];
    	foreach($grouped as $k=>$v){
    		if(sizeof($v) == 2){
    			array_push($result, $v);
    		}
    	}

    	return self::standardReturnEval($result);
    }

    private static function evalCardsLevel($level, $cards){
    	switch($level){
    		case 12: return self::evalCardsLevel12($cards);
    		case 11: return self::evalCardsLevel11($cards);
    		case 10: return self::evalCardsLevel10($cards);
    		case 9: return self::evalCardsLevel9($cards);
    		case 8: return self::evalCardsLevel8($cards);
    		case 7: return self::evalCardsLevel7($cards);
    		case 6: return self::evalCardsLevel6($cards);
    		case 5: return self::evalCardsLevel5($cards);
    		case 4: return self::evalCardsLevel4($cards);
    		case 3: return self::evalCardsLevel3($cards);
    		case 2: return self::evalCardsLevel2($cards);
    	}
    	return false;
    }

    public static function checkCardsLevel($cards){
    	for($i=12; $i>=2; $i--){
    		if(self::evalCardsLevel($i, $cards)!==false){
    			return $i;
    		}
    	}
    	return 1;
    }

    private static function evalBestCards($cards, $startLevel = 12, $istop = false){
    	if($istop){
    		$res = false; $level = 0;
    		if($startLevel < 4){
    			$res = self::evalCardsLevel(2, $cards);
    			$level = 2;
    		}else{
    			$lvl = [4, 2];
    			for($i=0; $i<2; $i++){
    				$res = self::evalCardsLevel($lvl[$i], $cards);
    				if($res!==false){
    					$level = $lvl[$i];
    					break;
    				}
    			}
    		}
    		if($res!==false){
    			return ['result'=>$res, 'level'=>$level];
    		}
    	}else{
	     	for($i=$startLevel; $i>=2; $i--){
	     		$res = self::evalCardsLevel($i, $cards);
	    		if($res!==false){
	    			return ['result'=>$res, 'level'=>$i];
	    		}
	    	}
    	}
    	return false;   	
    }
    /* ---------------- End card evaluation ------------------------ */

    /* ----------------- Preset Card Dispatch -------------------- */

    private static function presetCardsLevel($level, $cards){
    	$result = self::evalCardsLevel($level, $cards);
    	if($result!==false){
    		$sz = sizeof($result);
    		if($sz == 0){
    			return false;
    		}else if($sz == 1){
    			return $result[0];
    		}else{
    			$idx = mt_rand(0, $sz-1);
    			return $result[$idx];
    		}
    	}

    	return false; //Not possible
    }

    /* ----------------- End Preset Card Dispatch ------------------*/

    private static function convCardsIndToCards($cardInds){
    	$cards = [];
    	for($i=0; $i<sizeof($cardInds); $i++){
    		$cards[$i] = self::evalCardData($cardInds[$i]);
    	}
    	return $cards;
    }

    public static function getCardLevelName($level, $middle=false){
    	$name = self::$CARDLEVEL_NAME[$level - 1];

    	if($middle&&$level==7){
    		$name = '中墩'.$name;
    	}

    	return $name;
    }

    public static function toCardLevelByName($name){
        $ct = 0;
        foreach(self::$CARDLEVEL_NAME as $one){
            $ct ++;
            if($name == $one || $name == '中墩'.$one){
                return $ct;
            }
        }
    }

    private static function cardStrategyToString($cardstra) {
        $func = function($s1, $s2){
        	return strcmp($s1, $s2) * -1;
        }; 
        $str = '';
        $cards = $cardstra['cards'];
        foreach($cards as $k=>$v){
        	usort($v, $func);
        	foreach($v as $one){
        		$str = $str.$one.'_';
        	}
        }
        return $str;
    }

    private static function _compete_sub($turnName, $cards1, $gameUser1, $level1, $cards2, $gameUser2, $level2, $result){
    	$diff = abs($level1-$level2);
    	$result[$turnName] = [
    							$gameUser1=>['cards'=>$cards1, 'level'=>$level1], 
    						  	$gameUser2=>['cards'=>$cards2, 'level'=>$level2],
    						  	'levelDiff'=>$diff
    	];

    	if($level1!=$level2){
    		$user1Win = (($level1 - $level2)>0);
    		$user2Win = !$user1Win;
    	}else{
    		$cardsSta1 = []; $cardsSta2 = [];
    		foreach($cards1 as $one){
    			array_push($cardsSta1, self::toCardIndex($one));
    		} 
     		foreach($cards2 as $one){
    			array_push($cardsSta2, self::toCardIndex($one));
    		}    		

    		$cardsSta1 = self::convCardsIndToCards($cardsSta1);
    		$cardsSta2 = self::convCardsIndToCards($cardsSta2);

    		$cmp = self::compareCards($cardsSta1, $level1, $cardsSta2, $level2);

    		$user1Win = ($cmp > 0);
    		$user2Win = ($cmp < 0);
    	}

    	$result[$turnName][$gameUser1]['isWin'] = $user1Win;
    	$result[$turnName][$gameUser2]['isWin'] = $user2Win;

    	return $result;

    }
    public static function competeCards ($gameUser1, $cardsGroup1, $gameUser2, $cardsGroup2) {
    	$result = [];
    	if(sizeof($cardsGroup1['level'])==1||sizeof($cardsGroup2['level'])==1){
    		$lvl1 = $cardsGroup1['level'][0]; $lvl2 = $cardsGroup2['level'][0];
    		if(sizeof($cardsGroup1['level'])==sizeof($cardsGroup2['level'])){
    			$result = self::_compete_sub('body', $cardsGroup1['body'], $gameUser1, $cardsGroup1['level'][0], 
    				$cardsGroup2['body'], $gameUser2, $cardsGroup2['level'][0], $result);
    		}else if($lvl1 > 10){
    			$result = self::_compete_sub('body', $cardsGroup1['body'], $gameUser1, $cardsGroup1['level'][0], 
    				[], $gameUser2, $cardsGroup2['level'][2], $result);    			
    		}else{
    			$result = self::_compete_sub('body', [], $gameUser1, $cardsGroup1['level'][2], 
    				$cardsGroup2['body'], $gameUser2, $cardsGroup2['level'][0], $result);   
    		}
    	}else{
    		$arr = ['top', 'middle', 'bottom'];
    		for($i=0; $i<sizeof($arr); $i++){
    			$result = self::_compete_sub($arr[$i], $cardsGroup1[$arr[$i]], $gameUser1, $cardsGroup1['level'][$i], 
    				$cardsGroup2[$arr[$i]], $gameUser2, $cardsGroup2['level'][$i], $result);      			
    		}

    	}

    	return $result;
    }

    private $cards = [];
    private $cardsLevel = 0;

    function __construct($cardInds = []){
    	if(sizeof($cardInds)==0){
	        for($i=0; $i<65; $i++){
	            $this->cards[$i] = self::evalCardData($i);
	        }    		
    	}else{
    		$this->cards = self::convCardsIndToCards($cardInds);
    	}

    }

    public function getCards(){
    	return $this->cards;
    }

    public function getCardsLevel(){
    	if($this->cardsLevel===0){
    		$this->cardsLevel = self::checkCardsLevel($this->cards);
    	}
    	return $this->cardsLevel;
    }

    private function _dispatchCards_sub($user1LuckCardLevel, $user2LuckCardLevel, 
    	$user1RemainCardInds, $user2RemainCardInds){

    	$user1dispatch = []; $user2dispatch = [];

    	$user1UseCards = self::cloneArr($this->cards);
    	$user2UseCards = self::cloneArr($this->cards);
    	$orisize = sizeof($user1UseCards);
    	$avacardsUser1 = [];$avacardsUser2 = [];

    	
    	$user1ActualRemainCards=  [];$user2ActualRemainCards=  [];
    	if(sizeof($user1RemainCardInds)>0){
    		$user1ActualRemainCards = self::convCardsIndToCards($user1RemainCardInds);
    		for($i=0; $i<sizeof($user1ActualRemainCards); $i++){
                array_push($user1dispatch, $user1ActualRemainCards[$i]);
    		}
    		$user2UseCards = self::removeCards($user2UseCards, $user1ActualRemainCards);
            $user1UseCards = self::removeCards($user1UseCards, $user1ActualRemainCards);
    	}

    	if(sizeof($user2RemainCardInds)>0){
    		$user2ActualRemainCards = self::convCardsIndToCards($user2RemainCardInds);
    		for($i=0; $i<sizeof($user2ActualRemainCards); $i++){
                array_push($user2dispatch, $user2ActualRemainCards[$i]);
    		}
    		$user1UseCards = self::removeCards($user1UseCards, $user2ActualRemainCards);
            $user2UseCards = self::removeCards($user2UseCards, $user2ActualRemainCards);
    	}
    	$user1Preset = false; $user2Preset = false; 

    	if($user1LuckCardLevel > 0){
     			$user1Preset = self::presetCardsLevel($user1LuckCardLevel, $user1UseCards);
    			if($user1Preset!==false&&sizeof($user1Preset)>0){
    				$user1dispatch = self::mergeCards($user1dispatch, $user1Preset);
    				$user2UseCards = self::removeCards($user2UseCards, $user1Preset);	
                    $user1UseCards = self::removeCards($user1UseCards, $user1Preset);
	    		}   		
    	}

    	if($user2LuckCardLevel > 0){
     			$user2Preset = self::presetCardsLevel($user2LuckCardLevel, $user2UseCards);
    			if($user2Preset!==false&&sizeof($user2Preset)>0){
	    			$user2dispatch = self::mergeCards($user2dispatch, $user2Preset);
	    			$user1UseCards = self::removeCards($user1UseCards, $user2Preset);
                    $user2UseCards = self::removeCards($user2UseCards, $user2Preset);
	    		}       			
    	}

    	$user1dispatchnum = 16 - sizeof($user1dispatch);
    	$nr1 = self::getRandomCards($user1UseCards, $user1dispatchnum);
    	for($i=0; $i<sizeof($nr1); $i++){
    		array_push($user1dispatch, $nr1[$i]);
    	}
    	$user2UseCards = self::removeCards($user2UseCards, $nr1);

    	$user2dispatchnum = 16 - sizeof($user2dispatch);
    	$nr2 = self::getRandomCards($user2UseCards, $user2dispatchnum);
    	for($i=0; $i<sizeof($nr2); $i++){
    		array_push($user2dispatch, $nr2[$i]);
    	}

    	return [$user1dispatch, $user2dispatch];
    }

    public function dispatchCards($user1LuckCardLevel=0, 
    		$user2LuckCardLevel=0, $user1RemainCardInds=[], $user2RemainCardInds=[]){

    	if($user1LuckCardLevel!=$user2LuckCardLevel){
    		if($user1LuckCardLevel > $user2LuckCardLevel){
    			$whofirst = 'user1';
    		}else{
    			$whofirst = 'user2';
    		}
    	}else{
    		$whofirst = (self::randomHit(0.5)?'user1':'user2');
    	}

    	if($whofirst==='user1'){
    		return $this->_dispatchCards_sub($user1LuckCardLevel, $user2LuckCardLevel, $user1RemainCardInds, $user2RemainCardInds);
    	}else{
    		$re = $this->_dispatchCards_sub($user2LuckCardLevel, $user1LuckCardLevel, $user2RemainCardInds, $user1RemainCardInds);
    		return [$re[1], $re[0]];
    	}

    }


    private function _evalCardsStrategyResults ($resultCards, $oricards) {
    	if(sizeof($resultCards)==13){
    		$top = [$resultCards[12], $resultCards[11], $resultCards[10]];
    		$mid = [$resultCards[9], $resultCards[8], $resultCards[7], $resultCards[6], $resultCards[5]];
   		    $btm = [$resultCards[4], $resultCards[3], $resultCards[2], $resultCards[1], $resultCards[0]];
   		    $extra = self::removeCards($oricards, $resultCards);

   		    return ['top'=>$this->_evalCardsStrategyResults($top, $oricards), 
   		    			'middle'=>$this->_evalCardsStrategyResults($mid, $oricards),
   		    				'bottom'=>$this->_evalCardsStrategyResults($btm, $oricards), 
   		    					'extra'=>$this->_evalCardsStrategyResults($extra, $oricards)];
   	    }elseif(array_key_exists('top', $resultCards)){
            $restCards  = $oricards;
            foreach($resultCards as $k=>$v){
                $restCards = self::removeCards($restCards, $v);
            }

   	    	$st = 0;
   	    	$restBottomNo = 5 - intval(sizeof($resultCards['bottom']));
   	    	for($i=0; $i<$restBottomNo; $i++){
   	    		array_push($resultCards['bottom'], $restCards[$st]);
   	    		$st ++;
   	    	}

   	    	$restMiddleNo = 5 - intval(sizeof($resultCards['middle']));
   	    	for($i=0; $i<$restMiddleNo; $i++){
   	    		array_push($resultCards['middle'], $restCards[$st]);
   	    		$st ++;
   	    	}   

   	    	$restTopNo = 3 -intval(sizeof($resultCards['top']));
   	    	for($i=0; $i<$restTopNo; $i++){
   	    		array_push($resultCards['top'], $restCards[$st]);
   	    		$st ++;
   	    	}
            $restNo = 3; 
            if(array_key_exists('extra', $resultCards)){
                $restNo = 3 - intval(sizeof($resultCards['extra']));
            }else{
                $resultCards['extra'] = [];
            }
    	    for($i=0; $i<$restNo; $i++){
   	    		array_push($resultCards['extra'], $restCards[$st]);
   	    		$st ++;
   	    	}  	   
   		    return ['top'=>$this->_evalCardsStrategyResults($resultCards['top'], $oricards), 
   		    			'middle'=>$this->_evalCardsStrategyResults($resultCards['middle'], $oricards),
   		    				'bottom'=>$this->_evalCardsStrategyResults($resultCards['bottom'], $oricards), 
   		    					'extra'=>$this->_evalCardsStrategyResults($resultCards['extra'], $oricards)];   	    	 	

   	    }else{
   	    	$re = []; 
   	    	foreach($resultCards as $one){
   	    		if(is_array($one)&&array_key_exists('cardColor', $one)){
   	    			array_push($re, $one['cardColor'].'_'.$one['cardNo']);
   	    		}else{
   	    			array_push($re, $one);
   	    		}   	    		
   	    	}
   	    	return $re;
   	    }
    }

    public function evalCardStrategies($cards=[]){
    	if(sizeof($cards)==0){
    		$cards = $this->cards;
    	}
    	$result = [];

    	$startlevel = 12;

    	while(true){
    		$ncards = self::cloneArr($cards);

    		$re = self::evalBestCards($ncards, $startlevel);

    		if($re!==false){
    			if($re['level'] >10){
    				$fre = $this->_evalCardsStrategyResults($re['result'][0], $ncards);
    				$one = ['name'=>[self::getCardLevelName($re['level'])], 'cards'=>$fre];
    				array_push($result, $one);
    				$startlevel = 10;
    			}else{
    				$there= $re['result'];
    				$btm = $re['result'][0];
    				$lvl = $re['level'];
    				$btmname = self::getCardLevelName($lvl);

    				$restCards = self::removeCards($ncards, $btm);

    				if(sizeof($re['result']) > 1){
    					//option1
    					$mid = $re['result'][1];
    					$midRest = self::removeCards($restCards, $mid);
    					$midname = self::getCardLevelName($lvl, true);
    					$toplvl = ((sizeof($re['result']) > 2)?$lvl:($lvl-1));
     					$topres = self::evalBestCards($midRest, $toplvl, true);
    					if($topres === false){
    							$top = [];
    							$topname = self::getCardLevelName(1);
    					}else{
    							$top = $topres['result'][0];
    							$topname = self::getCardLevelName($topres['level']);
    					}

    					array_push($result, ['name'=>[$topname, $midname, $btmname], 'cards'=>self::_evalCardsStrategyResults(
    						['top'=>$top, 'middle'=>$mid, 'bottom'=>$btm], $ncards
    					)]);
    				}

    				//option2
    				$midre = self::evalBestCards($restCards, $lvl - 1);
    				if($midre === false){
    					$lvlnm = self::getCardLevelName(1);
    					array_push($result, ['name'=>[$lvlnm, $lvlnm, $btmname], 'cards'=>self::_evalCardsStrategyResults(
    							['top'=>[], 'middle'=>[], 'bottom'=>$btm], $ncards
    					)]);
    				}else{
    					$mid = $midre['result'][0];
    					$midname = self::getCardLevelName($midre['level']);
    					$midRest = self::removeCards($restCards, $mid);

    					$topre = self::evalBestCards($midRest, $midre['level'], true);
    					if($topre === false){
    						$top = []; $topname = self::getCardLevelName(1);
    					}else{
    						$top = $topre['result'][0]; $topname= self::getCardLevelName($topre['level']);
    					}
    					array_push($result, ['name'=>[$topname, $midname, $btmname], 'cards'=>self::_evalCardsStrategyResults(
    							['top'=>$top, 'middle'=>$mid, 'bottom'=>$btm], $ncards
    					)]);    					
    				}

    				if($lvl > 4){
    					$startlevel --;
    				}else{
    					break;
    				}
    			}
    		}else{
    			break;
    		}
    	}

    	$fresult = []; $keys = [];
    	foreach($result as $one){
    		$str = self::cardStrategyToString($one);
    		if(!array_key_exists($str, $keys)){
    			$keys[$str] = $str;

                //add level
                $level = [];
                foreach($one['name'] as $name){
                    array_push($level, self::toCardLevelByName($name));
                }
                $one['level'] = $level;

    			array_push($fresult, $one);
    		}
    	}

    	return self::JSONEncode($fresult);

    }

}