<?php

namespace App\Http\Controllers\Game\Game13;

use Illuminate\Http\Request;

use App\Models as MD;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Game\Game13\Game13WIPCardHandler;
use App\Http\Controllers\Game\Game13\Game13WIPUserHero;

use Utils, Storage, Auth, Input, CardGame;

class Game13WIPCompeteHandler extends Controller{
    private static $ATTACK_TYPE = ['super'=>'super', 
        'mana'=>'mana', 'normal'=>'normal'];

    private static $POSITIVE_EFF = ['enhancement_all'=>'enhancement_all', 
        'increase_perc'=>'increase_perc', 'enhancements'=>'enhancements', 
        'add_blood'=>'add_blood'];

    private static $NEGATIVE_EFF = ['weaken_all'=>'weaken_all',
        'decrease_perc'=>'decrease_perc', 'weakens'=>'weakens',
        'minus_blood'=>'minus_blood', 'freeze'=>'freeze'];

    private static function randomHit($rv, $le=1){
        $rate = 1;
        for($i=0; $i<$le; $i++){
            $rate = $rate * 10;
        }
        $ran = mt_rand(0, $rate);
        $rvr = intval($rv * $rate);
        return $ran <= $rvr;
    }

    private static function JSONEncode($arr){
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    private $game;
    private $cards1;
    private $cards2;

    function __construct($game, $cards1=false, $cards2=false){
        $this->game = $game;
        if($cards1===false){
            $this->cards1 = json_decode($game->gameUser1Data->data, true);
            $this->cards2 = json_decode($game->gameUser2Data->data, true);
        }else{
            $this->cards1 = $cards1;
            $this->cards2 = $cards2;
        }
    }

    public function getGameObject(){
        return $this->game;
    }

    public function compete(){
        return $this->doCompete($this->game);
    }

    private function doCompete($game){
        $user1Cards = $this->cards1;
        $user2Cards = $this->cards2;
        $competeResult = Game13WIPCardHandler::competeCards($game->user1, $user1Cards, 
            $game->user2, $user2Cards);

        $data = [
            'luckpoint'=>[
                strval($game->user1)=>$game->gameUser1->use_luckpoint, 
                strval($game->user2)=>$game->gameUser2->use_luckpoint               
            ],
            'cards'=>[
                strval($game->user1)=>$user1Cards, 
                strval($game->user2)=>$user2Cards
            ],
            'compete'=>$this->handleCompeteResults($competeResult, $game) 
        ];

        return $data;        
    }

    /** Compete Action Start -------------------------------------**/

    private function _addToSummary($summaryArr, $key, $value){
        if(!array_key_exists($key, $summaryArr)){
            $summaryArr[$key] = [];
        }

        array_push($summaryArr[$key], $value);
        return $summaryArr;
    }

    private function _recoverHeroStatusDescription($hero, $summary) {
        if(sizeof($summary) == 0){
            return '';
        }
        $description = $this->_heroDesKey($hero);
        $featuremap = ['blood'=>'生命', 'su'=>'速度', 'ji'=>'技能', 
                'fang'=>'防御', 'gong'=>'攻击', 'minus'=>'下降', 'add'=>'提升',
                'freeze'=>'被冻结', 'curse'=>'受到诅咒', 'bless'=>'获得祝福'];
        $hascurse = false; $hasbless = false;
        $ct = 0; $sz = sizeof($summary);
        foreach($summary as $k=>$v){
            $ct ++; $grow = false;
            if($k == 'freeze'){
                $description = $description.$featuremap[$k];
                $grow = true;
            }else{
                if($v > 0){
                    if(!$hasbless){
                        $description = $description.$featuremap['bless'].',';
                        $hasbless = true;
                    }
                    $description = $description.$featuremap[$k].$featuremap['add'].strval($v).'点';
                    $grow = true;
                }else if($v < 0){
                    if(!$hascurse){
                        $description = $description.$featuremap['curse'].',';
                        $hascurse = true;
                    }
                    $description = $description.$featuremap[$k].$featuremap['minus'].strval($v).'点';
                    $grow = true;
                }
            }
            if($grow && $ct < $sz){
                $description = $description.',';
            }
        }

        return $description;

    }

    private function _recoverHeroStatusForOneHero_pos($hero, $effkey, $effvalue, $dosummary=true){
        $poseffkeys = self::$POSITIVE_EFF;
         $negeffkeys = self::$NEGATIVE_EFF;
        $postmp = []; $negtmp = []; $summary = [];
        $nextturn = 'nextturn';
        if(array_key_exists('disable', $effvalue)){}
        elseif(array_key_exists($nextturn, $effvalue)&&$effvalue[$nextturn]){
            $postmp = [$effkey=>$effvalue];
        }else{
            $needsrecover = (array_key_exists('recover', $effvalue)&&$effvalue['recover']);
            switch($effkey){
                case $poseffkeys['enhancement_all']:
                    $hero->su += intval($effvalue['value']);
                    $hero->gong += intval($effvalue['value']);
                    $hero->fang += intval($effvalue['value']);
                    $hero->ji += intval($effvalue['value']);
                    if($needsrecover){
                        $negtmp[$negeffkeys['weaken_all']] = ['value'=>intval($effvalue['value']), 'nextturn'=>true];
                    }
                    if($dosummary){
                        $summary['su'] = intval($effvalue['value']);
                        $summary['gong'] = intval($effvalue['value']);
                        $summary['fang'] = intval($effvalue['value']);
                        $summary['ji'] = intval($effvalue['value']);
                    }
                    break;
                case $poseffkeys['increase_perc']:
                    $su = $hero->su; $gong = $hero->gong;
                    $fang = $hero->fang; $ji = $hero->ji;
                    $rate = 1 + floatval($effvalue['value']);
                    $hero->su = intval($rate * $su);
                    $hero->gong = intval($rate * $gong);
                    $hero->fang = intval($rate * $fang);
                    $hero->ji = intval($rate * $ji);
                    if($needsrecover){
                        $negtmp[$negeffkeys['weakens']]= ['su'=>($hero->su - $su), 'gong'=>($hero->gong - $gong), 'fang'=>($hero->fang - $fang), 'ji'=>($hero->ji - $ji), 'nextturn'=>true];
                    }
                    if($dosummary){
                        $summary['su'] = $hero->su - $su;
                        $summary['gong'] = $hero->gong - $gong;
                        $summary['fang'] = $hero->fang - $fang;
                        $summary['ji'] = $hero->ji - $ji;
                    }
                    break;                
                case $poseffkeys['enhancements']:
                    $hero->su += intval($effvalue['su']);
                    $hero->gong += intval($effvalue['gong']);
                    $hero->fang += intval($effvalue['fang']);
                    $hero->ji += intval($effvalue['ji']);
                    if($needsrecover){
                         $negtmp[$negeffkeys['weakens']] = ['su'=>intval($effvalue['su']), 'gong'=>intval($effvalue['gong']), 'fang'=>intval($effvalue['fang']), 'ji'=>intval($effvalue['ji']), 'nextturn'=>true];
                    }
                    if($dosummary){
                        $summary['su'] = intval($effvalue['su']);
                        $summary['gong'] = intval($effvalue['gong']);
                        $summary['fang'] = intval($effvalue['fang']);
                        $summary['ji'] = intval($effvalue['ji']);
                    }
                    break;                
                case $poseffkeys['add_blood']:
                    $bblood = $hero->blood;
                    $fblood = $hero->blood + intval($effvalue['value']);
                    if($fblood > $hero->oriblood){
                        $hero->blood = $hero->oriblood;
                    }else{
                        $hero->blood = $fblood;
                    }
                    
                    $offblood = $hero->blood - $bblood;
                    
                    if($needsrecover&&$offblood > 0){
                        //加血不可恢复
                        //$negtmp[$negeffkeys['minus_blood']] = ['value'=>$offblood, 'nextturn'=>true];
                    }
                    if($dosummary){
                        $summary['blood'] = $offblood;
                    }
                    break;
            }              
        }

        $result = ['hero'=>$hero, 'pos'=>$postmp, 'neg'=>$negtmp, 'summary'=>$summary, 'description'=>$this->_recoverHeroStatusDescription($hero, $summary)];   
        return $result;  
    }

    private function _recoverHeroStatusForOneHero_neg($hero, $effkey, $effvalue, $dosummary=true){
        $negeffkeys = self::$NEGATIVE_EFF; $poseffkeys = self::$POSITIVE_EFF;
        $postmp = []; $negtmp = []; $summary = [];
        $nextturn = 'nextturn';
        if(array_key_exists('disable', $effvalue)){}
        elseif(array_key_exists($nextturn, $effvalue)&&$effvalue[$nextturn]){
            $negtmp = [$effkey=>$effvalue];
        }else{
            $needsrecover = (array_key_exists('recover', $effvalue)&&$effvalue['recover']);
            switch($effkey){
                case $negeffkeys['weaken_all']:
                    $hero->su -= intval($effvalue['value']);
                    $hero->gong -= intval($effvalue['value']);
                    $hero->fang -= intval($effvalue['value']);
                    $hero->ji -= intval($effvalue['value']);
                    if($needsrecover){

                        $postmp[$poseffkeys['enhancement_all']] = ['value'=>intval($effvalue['value']), 'nextturn'=>true];
                    }
                    if($dosummary){
                        $summary['su'] = -1 * intval($effvalue['value']);
                        $summary['gong'] = -1 * intval($effvalue['value']);
                        $summary['fang'] = -1 * intval($effvalue['value']);
                        $summary['ji'] = -1 * intval($effvalue['value']);
                    }
                    break;
                case $negeffkeys['decrease_perc']:
                    $su = $hero->su; $gong = $hero->gong;
                    $fang = $hero->fang; $ji = $hero->ji;
                    $rate = 1 - floatval($effvalue['value']);
                    $hero->su = intval($rate * $su);
                    $hero->gong = intval($rate * $gong);
                    $hero->fang = intval($rate * $fang);
                    $hero->ji = intval($rate * $ji);
                    if($needsrecover){
                        $postmp[$poseffkeys['enhancements']]= ['su'=>($su - $hero->su), 'gong'=>($gong - $hero->gong), 'fang'=>($fang - $hero->fang), 'ji'=>($ji - $hero->ji), 'nextturn'=>true];
                    }
                    if($dosummary){
                        $summary['su'] = $hero->su - $su;
                        $summary['gong'] = $hero->gong - $gong;
                        $summary['fang'] = $hero->fang - $fang;
                        $summary['ji'] = $hero->ji - $ji;
                    }
                    break;
                case $negeffkeys['weakens']:
                    $hero->su -= intval($effvalue['su']);
                    $hero->gong -= intval($effvalue['gong']);
                    $hero->fang -= intval($effvalue['fang']);
                    $hero->ji -= intval($effvalue['ji']);
                    if($needsrecover){
                         $postmp[$poseffkeys['enhancements']] = ['su'=>intval($effvalue['su']), 'gong'=>intval($effvalue['gong']), 'fang'=>intval($effvalue['fang']), 'ji'=>intval($effvalue['ji']), 'nextturn'=>true];
                    }
                    if($dosummary){
                        $summary['su'] = -1 * intval($effvalue['su']);
                        $summary['gong'] = -1 * intval($effvalue['gong']);
                        $summary['fang'] = -1 * intval($effvalue['fang']);
                        $summary['ji'] = -1 * intval($effvalue['ji']);
                    }
                    break;
                case $negeffkeys['minus_blood']:
                    $hero->blood -= intval($effvalue['value']);
                    if($needsrecover){
                        $postmp[$poseffkeys['add_blood']] = ['value'=>intval($effvalue['value']), 'nextturn'=>true];
                    }
                    if($dosummary){
                        $summary['blood'] = -1 * intval($effvalue['value']);
                    }
                    break;
                case $negeffkeys['freeze']:
                    $negtmp[$negeffkeys['freeze']] = $effvalue;
                    break;
            }              
        }

        $result = ['hero'=>$hero, 'pos'=>$postmp, 'neg'=>$negtmp, 'summary'=>$summary, 'description'=>$this->_recoverHeroStatusDescription($hero, $summary)];   
        return $result;  
    }

    private function _generateOneProcess($userkey, $herokey, $change, $description){
        return ['userkey'=>$userkey, 'herokey'=>$herokey, 'change'=>$change, 'description'=>$description];
    }

    private function _addToEffectRecord($record, $k, $v){
        if(!array_key_exists($k, $record)){
            $record[$k] = [];
        }
        array_push($record[$k], $v);
        return $record;
    }
    private function recoverHeroStatusForOneHero($userkey, $hero, $process, $neg=false, $pos=false) {
        if($hero->blood < 0){
            return [$hero, $process, $pos, $neg];
        }

        $changed = false;
        $postmp = []; $negtmp = [];
        $reviseHeroEff = ($neg===false);
        if($neg === false){
            if(!empty($hero->negativeeff)){
                $neg = json_decode($hero->negativeeff, true);
            }else{
                $neg = [];
            }
        }
        if($pos === false){
            if(!empty($hero->positiveeff)){
                $pos = json_decode($hero->positiveeff, true);
            }else{
                $pos = [];
            }
        }
        if(sizeof($neg) > 0){
            foreach($neg as $key=>$e){
                foreach($e as $eff){
                    $result = $this->_recoverHeroStatusForOneHero_neg($hero, $key, $eff);
                    $hero = $result['hero'];
                    if(is_array($result['pos'])&&sizeof($result['pos'])>0){
                        foreach($result['pos'] as $k=>$v){
                            $postmp = $this->_addToEffectRecord($postmp, $k, $v);
                        }
                    }
                    if(is_array($result['neg'])&&sizeof($result['neg'])>0){
                        foreach($result['neg'] as $k=>$v){
                            $negtmp = $this->_addToEffectRecord($negtmp, $k, $v);
                        }
                    }
                    if(sizeof($result['summary']) > 0){
                        $oneprocess = $this->_generateOneProcess($userkey, strval($hero->id), $result['summary'], $result['description']);
                        array_push($process, $oneprocess);
                    }
                }
            }
            if($reviseHeroEff)
                $hero->negativeeff = '';

            $changed = true;
            
        }
        if(sizeof($pos) > 0){
            foreach($pos as $key=>$e){
                foreach($e as $eff){
                    $result = $this->_recoverHeroStatusForOneHero_pos($hero, $key, $eff);
                    $hero = $result['hero'];
                    if(is_array($result['pos'])&&sizeof($result['pos'])>0){
                        foreach($result['pos'] as $k=>$v){
                           $postmp = $this->_addToEffectRecord($postmp, $k, $v);
                        }
                    }
                    if(is_array($result['neg'])&&sizeof($result['neg'])>0){
                        foreach($result['neg'] as $k=>$v){
                           $negtmp = $this->_addToEffectRecord($negtmp, $k, $v);
                        }
                    }   
                    if(sizeof($result['summary']) > 0){
                        $oneprocess = $this->_generateOneProcess($userkey, strval($hero->id), $result['summary'], $result['description']);
                        array_push($process, $oneprocess);
                    }     
                }      
            }
            if($reviseHeroEff)
                $hero->positiveeff = '';

            $changed = true;
        }

        if($changed){
            if(sizeof($postmp) > 0){
                if($reviseHeroEff)
                    $hero->positiveeff = self::JSONEncode($postmp);
            }
            if(sizeof($negtmp) > 0){
                if($reviseHeroEff)
                    $hero->negativeeff = self::JSONEncode($negtmp);
            }
            $hero->save();
        }

        return [$hero, $process, $postmp, $negtmp];
    }

    private function recoverHeroStatus($game, $process) {
        $userkey1 = strval($game->gameUser1->id);
        $userkey2 = strval($game->gameUser2->id);
        $re = $this->recoverHeroStatusForOneHero($userkey1, $game->gameUser1->gameHero1, $process);
        $game->gameUser1->gameHero1 = $re[0]; $process = $re[1];
        $re = $this->recoverHeroStatusForOneHero($userkey1, $game->gameUser1->gameHero2, $process);
        $game->gameUser1->gameHero2 = $re[0]; $process = $re[1];
        $re = $this->recoverHeroStatusForOneHero($userkey1, $game->gameUser1->gameHero3, $process);
        $game->gameUser1->gameHero3 = $re[0]; $process = $re[1];
        $re = $this->recoverHeroStatusForOneHero($userkey2, $game->gameUser2->gameHero1, $process);
        $game->gameUser2->gameHero1 = $re[0]; $process = $re[1];
        $re = $this->recoverHeroStatusForOneHero($userkey2, $game->gameUser2->gameHero2, $process);
        $game->gameUser2->gameHero2 = $re[0]; $process = $re[1];
        $re = $this->recoverHeroStatusForOneHero($userkey2, $game->gameUser2->gameHero3, $process);
        $game->gameUser2->gameHero3 = $re[0]; $process = $re[1];
        return [$game, $process];
    }

    private function _addChangesToSummary($summaryArr, $change) {
        foreach($change as $k=>$v){
            $summaryArr = $this->_addToSummary($summaryArr, $k, $v);
        }
        return $summaryArr;
    }

    private function _heroDesKey($hero) {
        return '{'.$hero->id.'}';
    }

    private function _doOneNormalAttack($userkeyfrom, $herofrom, $userkeyto, $heroto, $gun, $process=[]){
        $lian = Game13WIPUserHero::willAttackLian($herofrom, $heroto, $gun);
        $bao = Game13WIPUserHero::willAttackBao($herofrom, $heroto, $gun);
        $miss = Game13WIPUserHero::willAttackMiss($herofrom, $heroto);
        if($miss){
            array_push($process, ['description'=>$this->_heroDesKey($heroto).'轻轻躲过']);
        }else{
            $hurt = Game13WIPUserHero::evalFinalHurtWithProtect($heroto, Game13WIPUserHero::evalGongHurt($herofrom, $bao));
            if($hurt > 0){
                $heroto->blood -= $hurt;
                $pro = $this->_generateOneProcess($userkeyto, strval($heroto->id), ['blood'=>(-1 * $hurt)], $this->_heroDesKey($heroto).'遭到来自'.$this->_heroDesKey($herofrom).'的攻击，受到'.$hurt.'点伤害');

                array_push($process, $pro);
                $willfanji = self::randomHit(0.5);
                if($willfanji){
                    $miss = Game13WIPUserHero::willAttackMiss($heroto, $herofrom);
                    if($miss){
                        array_push($process, ['description'=>$this->_heroDesKey($heroto).'反击，但被'.$this->_heroDesKey($herofrom).'轻松躲过']);
                    }else{
                        $hurt = Game13WIPUserHero::evalFinalHurtWithProtect($herofrom, Game13WIPUserHero::evalFanjiHurt($heroto));
                        if($hurt > 0){
                            $herofrom->blood -= $hurt;
                            $pro = $this->_generateOneProcess($userkeyfrom, strval($herofrom->id), ['blood'=>(-1 * $hurt)], $this->_heroDesKey($heroto).'拼死反击，'.$this->_heroDesKey($herofrom).'受到'.$hurt.'点伤害');
                            array_push($process, $pro);
                        }
                    }
                }
            }

        }
        if($heroto->blood < 0){
            $lian = false;
            array_push($process, ['description'=>$this->_heroDesKey($heroto).'死亡']);
        }
        if($herofrom->blood < 0){
            $lian = false;
            array_push($process, ['description'=>$this->_heroDesKey($herofrom).'死亡']); 
        }
        if($lian){
            array_push($process, ['description'=>$this->_heroDesKey($herofrom).'发动连击']);
            return $this->_doOneNormalAttack($userkeyfrom, $herofrom, $userkeyto, $heroto, false, $process);
        }
        return ['herofrom'=>$herofrom, 'heroto'=>$heroto, 'process'=>$process];
    }

    private function _doOneSuperJiAttackAOE($userkeyfrom, $herofrom, $userkeyto, $usertoctrl, $gun, $fangtogehter, $jitype, $process=[]){
        $enemyheros = $usertoctrl->getHeros();
        $ct = 0;
        foreach($enemyheros as $onehero){
            $ct ++;
            if($onehero->blood <= 0){
                continue;
            }
            $idx = $ct - 1;
            $hurt = Game13WIPUserHero::evalBishaHurt($herofrom, $gun, true, $jitype);
            $hurtfinal = Game13WIPUserHero::evalFinalHurtWithProtect($onehero, $hurt, $fangtogehter);
            if($hurtfinal > 0){
                $onehero->blood -= $hurtfinal;
                $pro = $this->_generateOneProcess($userkeyto, strval($onehero->id), ['blood'=>(-1 * $hurtfinal)], $this->_heroDesKey($onehero).'遭到来自'.$this->_heroDesKey($herofrom).'的攻击，受到'.$hurtfinal.'点伤害');
                array_push($process, $pro);               
            }


            $usertoctrl->setHero($idx, $onehero);

        }

        return ['herofrom'=>$herofrom, 'enemyctrl'=>$usertoctrl, 'process'=>$process];
    }

    private function _doOneManaAttackAOE($userkeyfrom, $herofrom, $userkeyto, $usertoctrl, $fangtogehter, $jitype, $process=[]){
        $enemyheros = $usertoctrl->getHeros();
        $ct = 0;
        foreach($enemyheros as $onehero){
            $ct ++;
            if($onehero->blood <= 0){
                continue;
            }
            $idx = $ct - 1;
            $hurt = Game13WIPUserHero::evalJiHurt($herofrom, $onehero, true, $jitype);
            $hurtfinal = Game13WIPUserHero::evalFinalHurtWithProtect($onehero, $hurt, $fangtogehter);
            if($hurtfinal > 0){
                $onehero->blood -= $hurtfinal;
                $pro = $this->_generateOneProcess($userkeyto, strval($onehero->id), ['blood'=>(-1 * $hurtfinal)], $this->_heroDesKey($onehero).'遭到来自'.$this->_heroDesKey($herofrom).'的攻击，受到'.$hurtfinal.'点伤害');
                array_push($process, $pro);
            }

            $usertoctrl->setHero($idx, $onehero);

        }

        return ['herofrom'=>$herofrom, 'enemyctrl'=>$usertoctrl, 'process'=>$process];
    }

    private function competetionBalance($competeResult, $game, $oneprocess) {
        $userkey1 =strval($game->user1); $userkey2 = strval($game->user2); 
        $userctrls = [$userkey1=>new Game13WIPUserHero($game->user1, $game->gameUser1), $userkey2=>new Game13WIPUserHero($game->user2, $game->gameUser2)];

        $herosmap = []; $positive = [];
        $negative = []; $summary = []; $process = [$oneprocess];
        $freezeHeros = [];

        foreach($userctrls as $userkey=>$userctrl){
            $heros = $userctrl->getHeros();
            $herosmap[$userkey] = [];
            $positive[$userkey] = [];
            $negative[$userkey] = [];
            $summary[$userkey] = [];
            foreach($heros as $onehero){
                $summary[$userkey][strval($onehero->id)] = [];
                $herosmap[$userkey][strval($onehero->id)] = $onehero;
                if(empty($onehero->negativeeff)){
                    $negative[$userkey][strval($onehero->id)] = [];
                }else{
                    $negtmp = json_decode($onehero->negativeeff, true);
                    if(array_key_exists('freeze', $negtmp)&&is_array($negtmp['freeze'])&&sizeof($negtmp['freeze']) > 0){
                        $freezeHeros[strval($onehero->id)] = 0;
                        $negtmp['freeze'] = [];
                    }
                    $negative[$userkey][strval($onehero->id)] = $negtmp;
                }
                if(empty($onehero->positiveeff)){
                    $positive[$userkey][strval($onehero->id)] = [];
                }else{
                    $positive[$userkey][strval($onehero->id)] = json_decode($onehero->positiveeff, true);
                }
            }
        }

        if(sizeof($oneprocess) > 0){
            foreach($oneprocess as $one){
                $summary[$one['userkey']][$one['herokey']] = $this->_addChangesToSummary($summary[$one['userkey']][$one['herokey']], $one['change']);
            }
        }

        //======= Summary ============================================
        $bishaheros = [];
        $actions = $competeResult['actions'];

        foreach($actions as $oneaction){
            if(array_key_exists('type', $oneaction)&&$oneaction['type']==self::$ATTACK_TYPE['super']){
                $bishaheros[$oneaction['herokey']] = 1;
            }
        }
        
        $lastheroid = 0;
        foreach($actions as $oneaction){
            $userkey = $oneaction['userkey'];
            foreach($userctrls as $key=>$ctrl){
                if($userkey == $key){
                    $userctrl = $ctrl;
                }else{
                    $enemyctrl = $ctrl;
                }
            }
            $oneprocess = [];
            //Dragon
            if(array_key_exists('dragon', $oneaction)){
                $affheros = $userctrl->setDragon($oneaction['dragon']);
                foreach($affheros as $curhero){
                    $pro = $this->_generateOneProcess($userkey, strval($curhero->id), ['level'=>1, 'blood'=>300], $this->_heroDesKey($curhero).'等级提升1阶，加血300点');
                    array_push($oneprocess, $pro);
                    $summary[$userkey][strval($curhero->id)] = $this->_addChangesToSummary($summary[$userkey][strval($curhero->id)], $pro['change']);
                }
            }else if(array_key_exists('type', $oneaction)){
                $actiontype = $oneaction['type'];
                $curhero = $herosmap[$oneaction['userkey']][$oneaction['herokey']];
                if($curhero->blood <= 0){
                    continue;
                }
                if($lastheroid!=$curhero->id){
                    $result = $this->recoverHeroStatusForOneHero($userkey, $curhero, [], $negative[$userkey][strval($curhero->id)], $positive[$userkey][strval($curhero->id)]);
                    $curhero = $result[0];
                    $userctrl->setHero($oneaction['idx'], $result[0]);
                    $herosmap[$oneaction['userkey']][$oneaction['herokey']] = $result[0];
                    if(sizeof($result[1]) > 0){
                        $oneprocess = $result[1];
                    }
                    $positive[$userkey][strval($curhero->id)] = $result[2];
                    $negative[$userkey][strval($curhero->id)] = $result[3];
                }

                $lastheroid = $curhero->id;

                if(array_key_exists('enhancement', $oneaction)&&$oneaction['enhancement'] > 0){
                    $result = $this->_recoverHeroStatusForOneHero_pos($curhero, self::$POSITIVE_EFF['enhancement_all'], ['value'=>$oneaction['enhancement'], 'recover'=>true]);
                    $userctrl->setHero($oneaction['idx'], $result['hero']);
                    $herosmap[$oneaction['userkey']][$oneaction['herokey']] = $result['hero'];
                    $curhero = $result['hero'];
                    $pro = $this->_generateOneProcess($userkey, strval($curhero->id), $result['summary'], $result['description']);
                    array_push($oneprocess, $pro);
                    $summary[$userkey][strval($curhero->id)] = $this->_addChangesToSummary($summary[$userkey][strval($curhero->id)], $pro['change']);
                    $negtmp = $result['neg'];
                    if(sizeof($negtmp) > 0){
                        foreach($negtmp as $k=>$v){
                            $negative[$userkey][strval($curhero->id)] = $this->_addToEffectRecord($negative[$userkey][strval($curhero->id)], $k, $v);
                        }
                    }
                }

                if(array_key_exists(strval($curhero->id), $freezeHeros)){
                    $freezeHeros[strval($curhero->id)] = 1;
                    array_push($oneprocess, ['description'=>$this->_heroDesKey($curhero).'被封，什么也干不了']);
                    continue;
                }

                $gongtogether = [];
                $startdescription = $this->_heroDesKey($curhero);
                $enemyherores = $enemyctrl->getNextAliveHero($oneaction['idx']);
                if($enemyherores == false){
                    $competeResult['process'] = $process;

                    $competeResult['summary'] = $summary;
            //      $competeResult['actions'] = ''; 
                    return $competeResult;                    
                }
                $enemyheroidx = $enemyherores[1];
                $enemyhero = $enemyherores[0];

                switch($actiontype){
                    case self::$ATTACK_TYPE['super']:
                    /*-------------Super Handling --------------------*/
                        if($oneaction['is_aoe']){
                            $startdescription.='对敌方所有英雄启动';
                            if($oneaction['superji_type'] == Game13WIPUserHero::$BISHA_TYPE['control']){
                                $startdescription.='控制型必杀技';
                            }else{
                                $startdescription.='攻击型必杀技';
                            }
                         }else{
                            $startdescription.='对敌方英雄'.$this->_heroDesKey($enemyhero).'启动';
                            if($oneaction['superji_type'] == Game13WIPUserHero::$BISHA_TYPE['control']){
                                $startdescription.='控制型必杀技';
                            }else{
                                $startdescription.='攻击型必杀技';
                            }
                        }
                        array_push($oneprocess, ['description'=>$startdescription]);
                        if($oneaction['is_aoe']){
                            $gongtogether = Game13WIPUserHero::getFangTogetherRate($userctrl->getGameUser(), $enemyctrl->getGameUser());
                            $attackresult = $this->_doOneSuperJiAttackAOE(strval($userctrl->getGameUser()->id), $curhero, strval($enemyctrl->getGameUser()->id), $enemyctrl, $oneaction['gun'], $gongtogether[0], $oneaction['superji_type']);
                            $enemyctrl = $attackresult['enemyctrl'];   
                            for($i=0; $i<3; $i++){
                                $onehero = $enemyctrl->getHero($i);
                                $herosmap[strval($enemyctrl->getGameUser()->id)][strval($onehero->id)] = $onehero;                              
                            }
                            foreach($attackresult['process'] as $one){
                                array_push($oneprocess, $one);
                                if(array_key_exists('change', $one)){
                                    $summary[$one['userkey']][$one['herokey']] = $this->_addChangesToSummary($summary[$one['userkey']][$one['herokey']], $one['change']);
                                }
                            }
                            if($oneaction['superji_type'] == Game13WIPUserHero::$BISHA_TYPE['control']){
                                $enemyheros = $enemyctrl->getAliveHeros();
                                foreach($enemyheros as $onehero){
                                    if(!array_key_exists(strval($onehero->id), $bishaheros)){
                                        $freezeHeros[strval($onehero->id)] = 0;
                                        array_push($oneprocess, ['description'=>$this->_heroDesKey($onehero).'被封']);                     
                                    }
                                }
                            }
                         }else{              
                            $hurt = Game13WIPUserHero::evalBishaHurt($curhero, $oneaction['gun'], false, $oneaction['superji_type']);
                            $hurtfinal = Game13WIPUserHero::evalFinalHurtWithProtect($enemyhero, $hurt);
                            $enemyhero->blood -= $hurtfinal;
                            $pro = $this->_generateOneProcess(strval($enemyctrl->getGameUser()->id), strval($enemyhero->id), ['blood'=>(-1 * $hurtfinal)], $this->_heroDesKey($enemyhero).'受到'.$hurtfinal.'点伤害');
                            array_push($oneprocess, $pro);
                            $summary[$pro['userkey']][$pro['herokey']] = $this->_addChangesToSummary($summary[$pro['userkey']][$pro['herokey']], $pro['change']);
                            
                            $enemyctrl->setHero($enemyheroidx, $enemyhero);
                            $herosmap[strval($enemyctrl->getGameUser()->id)][strval($enemyhero->id)] = $enemyhero;

                            if($oneaction['superji_type'] == Game13WIPUserHero::$BISHA_TYPE['control']){
                                if(!array_key_exists(strval($enemyhero->id), $bishaheros)){
                                    $freezeHeros[strval($enemyhero->id)] = 0;
                                    array_push($oneprocess, ['description'=>$this->_heroDesKey($enemyhero).'被封']);                     
                                }
                            }
                        }
                    /*------------ End -------------------------------*/
                        break;
                    case self::$ATTACK_TYPE['mana']:
                     /*-------------Mana Handling --------------------*/
                        if($oneaction['is_aoe']){
                            if($oneaction['mana_type'] == 'attack'){
                                $startdescription.='对敌方所有英雄启动攻击技能';
                            }else if($oneaction['mana_type'] == 'weak'){
                                $startdescription.='对敌方所有英雄启动削弱技能';
                            }else if($oneaction['mana_type'] == 'enhance'){
                                $startdescription.='对己方所有英雄使用增益技能';
                            }else if($oneaction['mana_type'] == 'blood'){
                                $startdescription.='对己方所有英雄补血';
                            }
                        }else{
                            if($oneaction['mana_type'] == 'attack'){
                                $startdescription.='对敌方英雄'.$this->_heroDesKey($enemyhero).'启动攻击技能';
                            }else if($oneaction['mana_type'] == 'weak'){
                                $startdescription.='对敌方英雄'.$this->_heroDesKey($enemyhero).'启动削弱技能';
                            }else if($oneaction['mana_type'] == 'enhance'){
                                $startdescription.='对自己使用增益技能';
                            }else if($oneaction['mana_type'] == 'blood'){
                                $startdescription.='对自己补血';
                            }
                        }
                        array_push($oneprocess, ['description'=>$startdescription]);

                        if($oneaction['is_aoe']){
                            $gongtogether = Game13WIPUserHero::getFangTogetherRate($userctrl->getGameUser(), $enemyctrl->getGameUser());
                            $attackresult = $this->_doOneManaAttackAOE(strval($userctrl->getGameUser()->id), $curhero, strval($enemyctrl->getGameUser()->id), $enemyctrl, $gongtogether[0], $oneaction['mana_type']);
                            $enemyctrl = $attackresult['enemyctrl'];   
                            for($i=0; $i<3; $i++){
                                $onehero = $enemyctrl->getHero($i);
                                $herosmap[strval($enemyctrl->getGameUser()->id)][strval($onehero->id)] = $onehero;                              
                            }
                            foreach($attackresult['process'] as $one){
                                array_push($oneprocess, $one);
                                if(array_key_exists('change', $one)){
                                    $summary[$one['userkey']][$one['herokey']] = $this->_addChangesToSummary($summary[$one['userkey']][$one['herokey']], $one['change']);
                                }
                            }   
                            if($oneaction['mana_type'] == 'weak'){
                                $enemyuserkey = strval($enemyctrl->getGameUser()->id);
                                for($i=0; $i<3; $i++){
                                    $onehero = $enemyctrl->getHero($i);
                                    if($onehero->blood <= 0){
                                        continue;
                                    }
                                    if(Game13WIPUserHero::willJiMiss($curhero, $onehero)){
                                        array_push($oneprocess, ['description'=>$this->_heroDesKey($onehero).'轻松躲过']);
                                        continue;
                                    }

                                    $effectkey = self::$NEGATIVE_EFF['decrease_perc'];

                                    $negative[$enemyuserkey][strval($onehero->id)] = $this->_addToEffectRecord($negative[$enemyuserkey][strval($onehero->id)], $effectkey, ['value'=>0.15, 'recover'=>true]);
                                   
                                   array_push($oneprocess, ['description'=>$this->_heroDesKey($onehero).'中招，各方面属性将会受到削弱']);
                                }                                  
                            }else if($oneaction['mana_type'] == 'enhance'){
                                $userkey = strval($userctrl->getGameUser()->id);
                                for($i=0; $i<3; $i++){
                                    $onehero = $userctrl->getHero($i);
                                    if($onehero->blood <= 0){
                                        continue;
                                    }     
                                    if($i == $oneaction['idx']){
                                        $lastheroid = 0;
                                    }                              

                                    $effectkey = self::$POSITIVE_EFF['increase_perc'];

                                    $positive[$userkey][strval($onehero->id)] = $this->_addToEffectRecord($positive[$userkey][strval($onehero->id)], $effectkey, ['value'=>0.15, 'recover'=>true]);
                                   
                                   array_push($oneprocess, ['description'=>$this->_heroDesKey($onehero).'各方面属性将会得到提升']);
                                }
                                    
                            }else if($oneaction['mana_type'] == 'blood'){
                                $userkey = strval($userctrl->getGameUser()->id);
                                for($i=0; $i<3; $i++){
                                    $onehero = $userctrl->getHero($i);
                                    if($onehero->blood <= 0){
                                        continue;
                                    }     
                                    if($i == $oneaction['idx']){
                                        $lastheroid = 0;
                                    }                              

                                    $effectkey = self::$POSITIVE_EFF['add_blood'];

                                    $v = intval($onehero->blood * 0.15);

                                    $positive[$userkey][strval($onehero->id)] = $this->_addToEffectRecord($positive[$userkey][strval($onehero->id)], $effectkey, ['value'=>$v, 'recover'=>true]);
                                   
                                   array_push($oneprocess, ['description'=>$this->_heroDesKey($onehero).'血量将会增加']);
                                }                                    
                            }                 
         

                        }else{            
                            $hurt = Game13WIPUserHero::evalJiHurt($curhero, $enemyhero, false, $oneaction['mana_type']);
                            $hurtfinal = Game13WIPUserHero::evalFinalHurtWithProtect($enemyhero, $hurt);
                            $enemyhero->blood -= $hurtfinal;
                            $pro = $this->_generateOneProcess(strval($enemyctrl->getGameUser()->id), strval($enemyhero->id), ['blood'=>(-1 * $hurtfinal)], $this->_heroDesKey($enemyhero).'遭到来自'.$this->_heroDesKey($curhero).'的攻击，受到'.$hurtfinal.'点伤害');
                            array_push($oneprocess, $pro);
                            $summary[$pro['userkey']][$pro['herokey']] = $this->_addChangesToSummary($summary[$pro['userkey']][$pro['herokey']], $pro['change']);
                            
                            $enemyctrl->setHero($enemyheroidx, $enemyhero);
                            $herosmap[strval($enemyctrl->getGameUser()->id)][strval($enemyhero->id)] = $enemyhero;
                            
                            if($oneaction['mana_type'] == 'weak'){
                                if(!Game13WIPUserHero::willJiMiss($curhero, $enemyhero)){
                                    $effectkey = self::$NEGATIVE_EFF['decrease_perc'];
                                    $enemyuserkey = strval($enemyctrl->getGameUser()->id);

                                    $negative[$enemyuserkey][strval($enemyhero->id)] = $this->_addToEffectRecord($negative[$enemyuserkey][strval($enemyhero->id)], $effectkey, ['value'=>0.3, 'recover'=>true]);
                                   
                                   array_push($oneprocess, ['description'=>$this->_heroDesKey($enemyhero).'中招，各方面属性将会受到削弱']);                                    
                                }else{
                                    array_push($oneprocess, ['description'=>$this->_heroDesKey($enemyhero).'轻松躲过']);                                    
                                }
                            }else if($oneaction['mana_type'] == 'enhance'){
                                $userkey = strval($userctrl->getGameUser()->id);                                    
                                $effectkey = self::$POSITIVE_EFF['increase_perc'];
                                $positive[$userkey][strval($curhero->id)] = $this->_addToEffectRecord($positive[$userkey][strval($curhero->id)], $effectkey, ['value'=>0.3, 'recover'=>true]);
                                   
                                array_push($oneprocess, ['description'=>$this->_heroDesKey($curhero).'血量将会增加']);
                                $lastheroid = 0;
                                 
                            }else if($oneaction['mana_type'] == 'blood'){
                                $userkey = strval($userctrl->getGameUser()->id);
                                $v = intval($curhero->blood * 0.3);                                    
                                $effectkey = self::$POSITIVE_EFF['add_blood'];
                                $positive[$userkey][strval($curhero->id)] = $this->_addToEffectRecord($positive[$userkey][strval($curhero->id)], $effectkey, ['value'=>$v, 'recover'=>true]);
                                   
                                array_push($oneprocess, ['description'=>$this->_heroDesKey($curhero).'血量将会增加']);
                                $lastheroid = 0;  
                            }
                        }

                    /*------------ End -------------------------------*/
                        break;
                    case self::$ATTACK_TYPE['normal']:
                    /*-------------Normal attach handling--------------------*/
                        $startdescription.= '对敌方英雄'.$this->_heroDesKey($enemyhero).'发动攻击';
                        array_push($oneprocess, ['description'=>$startdescription]);

                        $attackresult = $this->_doOneNormalAttack($userkey, $curhero, strval($enemyctrl->getGameUser()->id), $enemyhero, $oneaction['gun']);

                        $curhero = $attackresult['herofrom'];
                        $userctrl->setHero($oneaction['idx'], $curhero);
                        $herosmap[$oneaction['userkey']][$oneaction['herokey']] = $curhero;
                        $enemyctrl->setHero($enemyheroidx, $attackresult['heroto']);
                        $herosmap[strval($enemyctrl->getGameUser()->id)][strval($enemyhero->id)] = $attackresult['heroto'];

                        if(sizeof($attackresult['process'])>0){
                            foreach($attackresult['process'] as $one){
                                array_push($oneprocess, $one);
                                if(array_key_exists('change', $one)){
                                    $summary[$one['userkey']][$one['herokey']] = $this->_addChangesToSummary($summary[$one['userkey']][$one['herokey']], $one['change']);
                                }
                            }
                        }
                    /*------------ End -------------------------------*/
                        break;
                }

                if($enemyctrl->isAllDead()){
                    array_push($oneprocess, ['description'=>'敌方全灭']);    
                    break;
                }
                if($userctrl->isAllDead()){
                    array_push($oneprocess, ['description'=>'我方全灭']);    
                    break;                    
                }

                $alldead = false;
                if(sizeof($gongtogether) > 0){
                    array_push($oneprocess, ['description'=>'敌方发动合击']);
                    $userkey = strval($userctrl->getGameUser()->id);    
                    $enemyuserkey = strval($enemyctrl->getGameUser()->id);    
                    for($i=0; $i<3; $i++){
                        $enemyhero = $enemyctrl->getHero($i);
                        $targethero = $userctrl->getHeroWithLeastBlood();
                        $attackresult = $this->_doOneNormalAttack($enemyuserkey, $enemyhero, $userkey, $targethero, false);

                        $enemyhero = $attackresult['herofrom'];
                        $heroidx = $enemyctrl->setHero(0, $enemyhero);
                        $herosmap[$enemyuserkey][strval($enemyhero->id)] = $enemyhero;
                        $targethero = $attackresult['heroto'];
                        $userctrl->setHero(0, $targethero);
                        $herosmap[$userkey][strval($targethero->id)] = $targethero;

                        if(sizeof($attackresult['process'])>0){
                            foreach($attackresult['process'] as $one){
                                array_push($oneprocess, $one);
                                if(array_key_exists('change', $one)){
                                    $summary[$one['userkey']][$one['herokey']] = $this->_addChangesToSummary($summary[$one['userkey']][$one['herokey']], $one['change']);
                                }
                            }
                        }
                                                
                        if($enemyctrl->isAllDead()){
                            array_push($oneprocess, ['description'=>'敌方全灭']); 
                            $alldead = true;   
                            break;
                        }
                        if($userctrl->isAllDead()){
                            array_push($oneprocess, ['description'=>'我方全灭']);  
                            $alldead = true;  
                            break;                    
                        }                        
                    }
                }
                if($alldead){
                    break;
                }
            }

            array_push($process, $oneprocess);
        }

        foreach($positive as $userkey=>$set){
            foreach($set as $herokey=>$one){
                $hero = $herosmap[$userkey][$herokey];
                $hero->positiveeff= $this->_stringifyEffArr($one);
                $hero->negativeeff= $this->_stringifyEffArr($negative[$userkey][$herokey]);
                $hero->save();           
            }
        }

        $competeResult['process'] = $process;

        $competeResult['summary'] = $summary;
//      $competeResult['actions'] = ''; 
        return $competeResult;
    }

 //[eff:[{a:}]}
    //Should be called in the very end of competetion
    private function _stringifyEffArr($arr) {
        $newarr = [];
        foreach($arr as $key=>$effarr){
            foreach($effarr as $one){
                //actually there is no disable cases
                if(!array_key_exists('disable', $one)){
                    if(!array_key_exists($key, $newarr)){
                        $newarr[$key] = [];
                    }
                    $tmparr = [];
                    foreach($one as $k=>$v){
                        if($k != 'nextturn'){
                            $tmparr[$k] = $v;
                        }
                    }
                    array_push($newarr[$key], $tmparr);
                }
            }
        }
        if(sizeof($newarr) == 0){
            return '';
        }
        return self::JSONEncode($newarr);
    }

    /** Compete Action End -------------------------------------**/

    private function evalActionType($diff){
        $type = 0;
        if($diff >= 2){
            $type = self::$ATTACK_TYPE['super'];
        }else if($diff == 1){
            $type = self::$ATTACK_TYPE['mana'];
        }else if($diff == 0){
            $type = self::$ATTACK_TYPE['normal'];
        }
        $enh = $diff - 3;
        return [$type, $enh > 0?$enh:0];
    }

    private function toFormatedActions($actions){
        $newaction = [];
        foreach($actions as $userkey=>$useractions){
            $idx = 0;
            $isgun = $useractions['gun'];
            if(array_key_exists('dragon', $useractions)){
                $one = ['userkey'=>$userkey, 'dragon'=>$useractions['dragon']];
                array_push($newaction, $one);
            }
            foreach($useractions as $herokey=>$heroactions){
                if(is_array($heroactions)){
                    $enhancement = 0;
                    if(array_key_exists('enhancement', $heroactions)){
                        $enhancement = $heroactions['enhancement'];
                    }
                    $ct = 0;
                    foreach($heroactions['actions'] as $one){
                        if(array_key_exists('type', $one)){
                            $one['userkey'] = $userkey;
                            $one['herokey'] = $herokey;
                            $one['idx'] = $idx;
                            $one['gun'] = $isgun;
                            if($ct == 0){
                                $one['enhancement'] = $enhancement;
                                $ct ++;
                            }
                            array_push($newaction, $one);
                        }
                    }
                    $idx ++;
                }
            }
        }

        $func = function($a1, $a2){
            if(array_key_exists('dragon', $a1)){
                return -1;
            }else if(array_key_exists('dragon', $a2)){
                return 1;
            }else if(!array_key_exists('type', $a1)){
                return -1;
            }else if(!array_key_exists('type', $a2)){
                return 1;
            }else{
                if(array_key_exists('superji_type', $a1)&&$a1['superji_type']==Game13WIPUserHero::$BISHA_TYPE['control']&&$a1['is_aoe']){
                    return -1;
                }else if(array_key_exists('superji_type', $a2)&&$a2['superji_type']==Game13WIPUserHero::$BISHA_TYPE['control']&&$a2['is_aoe']){
                    return 1;
                }else{
                    $idx1 = $a1['idx']; $idx2 = $a2['idx'];
                    $atttype = [self::$ATTACK_TYPE['mana']=>0, self::$ATTACK_TYPE['normal']=>1, self::$ATTACK_TYPE['super']=>2];
                    if($idx1==$idx2){
                        $usercomp =  strcmp($a1['userkey'], $a2['userkey']);
                        if($usercomp == 0){
                            $type1 = $atttype[$a1['type']];
                            $type2 = $atttype[$a2['type']];
                            return $type1 - $type2;                            
                        }else{
                            return $usercomp;
                        }

                    }else{
                        return $idx1 - $idx2;
                    }
                }
            }
        };

        usort($newaction, $func);

        return $newaction;

    }

    private function handleCompeteResults($competeResult, $game){
        // echo self::JSONEncode($competeResult).'<br/>';

        $process = [];
        $re = $this->recoverHeroStatus($game, $process);
        $game = $re[0];
        $process = $re[1];

        $gameUser1 = $game->gameUser1;
        $gameUser2 = $game->gameUser2;
        $diff = $gameUser1->use_luckpoint - 
                    $gameUser2->use_luckpoint;
        $userkey1 = strval($gameUser1->id);
        $userkey2 = strval($gameUser2->id);
        $heros1 = [$gameUser1->gameHero1, 
                   $gameUser1->gameHero2, 
                   $gameUser1->gameHero3];

        $heros2 = [$gameUser2->gameHero1, 
                   $gameUser2->gameHero2, 
                $gameUser2->gameHero3 ];
        $cv1 = 0; $cv2 = 0;

        $actions = [$userkey1=>['gun'=>false], $userkey2=>['gun'=>false]];
        for($i=0; $i<3; $i++){
            $actions[$userkey1][strval($heros1[$i]->id)] = ['actions'=>[]];
            $actions[$userkey2][strval($heros2[$i]->id)] = ['actions'=>[]];
        }

        if(array_key_exists('body', $competeResult)){
            $body = $competeResult['body'];
            if($body[$userkey1]['level']>10){
                $actions[$userkey1]['dragon'] = $body[$userkey1]['level'];
                for($i = 0; $i < 3; $i++){
                    $actions[$userkey1][strval($heros1[$i]->id)]['actions'] = $this->_addSuperJi([], $heros1[$i], $heros2[$i]);
                }
            }
            if($body[$userkey2]['level']>10){
                $actions[$userkey2]['dragon'] = $body[$userkey1]['level'];
                for($i = 0; $i < 3; $i++){
                    $actions[$userkey2][strval($heros2[$i]->id)]['actions'] = $this->_addSuperJi([], $heros2[$i], $heros1[$i]);
                }
            }
        }else{
            $idx = 0;
            foreach($competeResult as $k=>$v){
                // echo self::JSONEncode($v);
                if($v[$userkey1]['isWin']===true){
                    $thediff = $v['levelDiff'] + $diff;                  
                }else{
                    $thediff = -1 * $v['levelDiff'] + $diff;
                }
                $oppdiff = -1 * $thediff;
                $v['levelDiff'] = $thediff;
                $action1 = [];
                $action2 = [];
                $re = false; $herokey = 0; $userkey = 0; $heros = false; $opheros= false;
                $hasch = true;

                $awin = ($thediff > 0?true:false);
                if($thediff == 0){
                    $awin = ($v[$userkey1]['isWin']===true?true:false);
                    if(!$awin&&!$v[$userkey2]['isWin']){
                        $hasch = false;
                    }
                }

                if($awin === true){
                    $v[$userkey1]['isWin'] = true;
                    $v[$userkey2]['isWin'] = false;
                    $re = $this->evalActionType($thediff);
                    $herokey = $heros1[$idx]->id;
                    $userkey = $userkey1;
                    $heros = $heros1;
                    $opheros = $heros2;
                    $cv1 ++;
                }else if($awin === false && $hasch){
                    $v[$userkey1]['isWin'] = false;
                    $v[$userkey2]['isWin'] = true;   
                    $re = $this->evalActionType($oppdiff);
                    $herokey = $heros2[$idx]->id;
                    $userkey = $userkey2;
                    $heros = $heros2;
                    $opheros = $heros1;
                    $cv2 ++;             
                }
        
                // echo '<br/>userkey1='.$userkey1.' iswin:'.($v[$userkey1]['isWin']===true);
                // echo '<br/>userkey2='.$userkey2.' iswin:'.($v[$userkey2]['isWin']===true);
                // echo '<br/>cv1='.$cv1;
                // echo '<br/>cv2='.$cv2;
                // echo '<br/>-----------<br/>';
                if($re!==false){
                    $actions[$userkey][$herokey]['enhancement'] = $re[1];
                    if($v[$userkey]['level'] == 10){
                        for($i=0; $i<3; $i++){
                            $act = $this->_addSuperJi([], $heros[$i], $opheros[$i]);
                            foreach($act as $one){
                                array_push($actions[$userkey][strval($heros[$i]->id)]['actions'], $one);
                            }
                        }
                    }else{
                        $act = [];
                        if($re[0] == self::$ATTACK_TYPE['super']){
                            $act = $this->_addSuperJi([], $heros[$idx], $opheros[$idx]);
                        }else if($re[0] == self::$ATTACK_TYPE['mana']){
                            $act = $this->_addManaJi([], $heros[$idx], $opheros[$idx]);
                        }else{
                            $act = $this->_addNormal([], $heros[$idx], $opheros[$idx]);
                        }
                        foreach($act as $one){
                            array_push($actions[$userkey][strval($herokey)]['actions'], $one);
                        }
                    }
                }

                $idx ++;
            }            
        }


    
        $competeResult['gun'] = ($cv1==3?$userkey1:($cv2==3?$userkey2:'0'));

        if($cv1 == 3){
            $actions[$userkey1]['gun'] = true;
        }else if($cv2 == 3){
            $actions[$userkey2]['gun'] = true;
        }



        $competeResult['actions'] = $this->toFormatedActions($actions);
        
        return $this->competetionBalance($competeResult, $game, $process);
    }


    private function _superjiAction($hero1, $hero2){
        $superjitype = $hero1->bisha_skill;
        $isaoe = false;
        if(empty($superjitype)){
            $superjitype = Game13WIPUserHero::$BISHA_TYPE['attack'];
        }else{
            if(strpos($superjitype, ',') > 0){
                $exp = explode(',' , $superjitype);
                $superjitype= $exp[0];
                if($exp[1] == 'aoe'){
                    $isaoe = true;
                }
            }
        }
        return ['type'=>self::$ATTACK_TYPE['super'], 
                'is_aoe'=>$isaoe, 
                'superji_type'=>$superjitype];
    }

    private function _manaAction($hero1, $hero2){
         return ['type'=>self::$ATTACK_TYPE['mana'], 
                'is_aoe'=> Game13WIPUserHero::willManaAOE($hero1, $hero2), 
                'mana_type'=>Game13WIPUserHero::evalJiType()];       
    }

    private function _normalAction($hero1, $hero2){
        return ['type'=>self::$ATTACK_TYPE['normal']];
    }

    private function _addSuperJi($action, $hero1, $hero2){
        $h1 = $hero1; $h2 = $hero2;
        array_push($action, $this->_superjiAction($h1, $h2));
        array_push($action, $this->_manaAction($h1, $h2));
        array_push($action, $this->_normalAction($h1, $h2));
        return $action;
    }

    private function _addManaJi($action, $hero1, $hero2){
        $h1 = $hero1; $h2 = $hero2;
        array_push($action, $this->_manaAction($h1, $h2));
        array_push($action, $this->_normalAction($h1, $h2));
        return $action;
    }

    private function _addNormal($action, $hero1, $hero2){
        $h1 = $hero1; $h2 = $hero2;
        array_push($action, $this->_normalAction($h1, $h2));
        return $action;
    }
}