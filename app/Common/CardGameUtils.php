<?php
namespace App\Common;
use App\Models as MD;
class CardGameUtils
{
	public static $CARD_TYPE = array('Spade', 'Heart', 'Diamond', 'Club', 'King', 'Kinglet');

	public static function shuffleRestCard($cards){
		$cardNo = sizeof($cards);
        $no = $cardNo;
        $arr = array();
        $temparr = $cards;
        while($no > 0){
        	$theidx = $cardNo - $no;
        	if($no == 1){
        		$arr[$theidx] = $temparr[0];
        	}else{
        		$leg = rand(1, $no) - 1;
        		$arr[$theidx] = $temparr[$leg];
        		for($i=$leg; $i < $no-1; $i++){
        			$temparr[$i] = $temparr[$i + 1];
        		}
        	}
        	$no--;	        
        }
        return $arr;		
	}

    public static function shuffleCard($noOfPairs = 1, $hasKing = false){
    	$oneCardNo = ($hasKing?54:52);
        $cardNo = $oneCardNo * $noOfPairs;
        $temparr = array();
        for($i=0; $i<$cardNo; $i++){
        	$temparr[$i] = $i;
        }
        return self::shuffleRestCard($temparr);
    }

    public static function getCardWithoutKing($idx, $cardTypes = array()){
        if(sizeof($cardTypes)==0){
            $cardTypes = self::$CARD_TYPE;
        }
    	$cardNo = $idx%13 + 1;
        if($cardNo == 1)
            $cardNo = 14;
    	$cardType = $idx/13;
    	return array('type'=>$cardTypes[$cardType], 'cardNo'=>$cardNo);
    }

    public static function getCard($idx, $hasKing = false){
    	$oneCardNo = ($hasKing?54:52);
    	$idx = $idx % $oneCardNo;
    	if($hasKing){
    		switch($idx){
    			case 0:
    				return array('type'=>self::$CARD_TYPE[4], 'cardNo'=>0);
    			case 1:
    				return array('type'=>self::$CARD_TYPE[5], 'cardNo'=>1); 
    			default:
    				return self::getCardWithoutKing($idx - 2);
    		}
    	}else{
    		return self::getCardWithoutKing($idx);
    	}

    }
}

?>	 