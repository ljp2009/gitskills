<?php

namespace App\Http\Controllers\Game\Game13;

use Illuminate\Http\Request;

use App\Models as MD;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Game\Game13\Game13WIPCardHandler;
use App\Http\Controllers\Game\Game13\Game13WIPUserHero;
use App\Http\Controllers\Game\Game13\Game13WIPCompeteHandler;

use Utils, Storage, Auth, Input, CardGame;


class Game13WIPController extends Controller
{

    private static $OFFLINE_TIME = 10;
    private static $PLAY_DEADLINE = 40; //40 seconds
    private static $MAX_ROUND = 15;
    private static $GAME_TYPE = 'game13';

    private static $STAGES = [
        'not_start'=>0,
        'shuffle_card'=>1,
        'compete'=>2
    ];

    private static function JSONEncode($arr){
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
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

    private function saveWIPProcess($inprocess){
        $inprocess->gameUser1->gameHero1->save();
        $inprocess->gameUser1->hero1 = $inprocess->gameUser1->gameHero1->id;
        $inprocess->gameUser1->gameHero2->save();
        $inprocess->gameUser1->hero2 = $inprocess->gameUser1->gameHero2->id;
        $inprocess->gameUser1->gameHero3->save();
        $inprocess->gameUser1->hero3 = $inprocess->gameUser1->gameHero3->id;
        $inprocess->gameUser1->save();
        $inprocess->user1 = $inprocess->gameUser1->id;
        $inprocess->gameUser2->gameHero1->save();
        $inprocess->gameUser2->hero1 = $inprocess->gameUser2->gameHero1->id;
        $inprocess->gameUser2->gameHero2->save();
        $inprocess->gameUser2->hero2 = $inprocess->gameUser2->gameHero2->id;
        $inprocess->gameUser2->gameHero3->save();
        $inprocess->gameUser2->hero3 = $inprocess->gameUser2->gameHero3->id;
        $inprocess->gameUser2->save();
        $inprocess->user2 = $inprocess->gameUser2->id;
        $inprocess->save(); 
    }

    private function composeReturnData($stage, $round, $data = '', $ticketid = '', $encode=true){
        if(empty($ticketid)){
            $ticketid = $this->generateTicket();
        }

        $finaldata = ['stage'=>$stage, 'round'=>$round, 
            'data'=>$data, 'ticket'=>$ticketid];

        if($encode)
            return self::JSONEncode($finaldata);

        return $finaldata;
    }

    private function loadWIPInfo($id){
        $inprocess = MD\Game13OnprocessModel::find(intval($id));
        return $inprocess;
    }

    public function healthCheck($gameId, $gameUserId, $encode=true){
        $tm = time();
        $onm = MD\Game13OnprocessModel::find(intval($gameId));
        $u1 = $onm->user1; $u2 = $onm->user2;
        $mystatus = false; $enemystatus = false;

        if($u1 == intval($gameUserId)){
            $mystatus = $onm->gameUser1OnlineStatus;
            $enemystatus = $onm->gameUser2OnlineStatus;
        }else{
            $mystatus = $onm->gameUser2OnlineStatus;
            $enemystatus = $onm->gameUser1OnlineStatus;
        }

 
        if($enemystatus->online == 1 && $tm - $enemystatus->health_timestamp > self::$OFFLINE_TIME){
            $enemystatus->online = 0;
            $enemystatus->save();
        }
        $offline = ($enemystatus->online == 0);

        $mystatus->health_timestamp = $tm;
        $mystatus->online = 1;

        $mystatus->save();

        $finaldata = ['opp_offline'=>strval($offline)];

        if($encode)
            return self::JSONEncode($finaldata);

        return $finaldata;
    }
        /* Test */

    public function displayPrepareDataPage(){
        return view('game.game13.game13_data_test');
    }

    public function testPlay(){
        $usermodels = MD\Game13UserModel::all();
        $users = [];
        foreach($usermodels as $one){
            array_push($users, $one->id);
        }
        return view('game.game13.game_test_play', ['users'=>$users]);
    }

    public function testFunction(){

        // $cards1 = [['id'=>'card_40'], ['id'=>'card_28'], ['id'=>'card_40'], ['id'=>'card_16']];

        // $cards2 = [['id'=>'card_40'], ['id'=>'card_16']];

        // $cards1 = Game13WIPCardHandler::removeCards($cards1, $cards2);

        // echo self::JSONEncode($cards1);

        echo '<!doctype html>
            <html class="no-js">
                <head>
            <meta charset="utf-8"></head><body style="font-size:10px">';
        $id = $this->prepareTestData();
        $this->cleanForTest();
        
        $this->testCompeteCards($id);
        // echo 'success!';

        echo '<br/>';
        echo '</body></html>';
        // $a = ['a'=>1, 'a'=>2];
        // echo sizeof($a);

        // $this->testPlayData();
        // $this->testDispatchCards();

    }

    private function testCompeteCards($id){
        $this->triggerGame($id);
        $game = MD\Game13OnprocessModel::all()[0];

        $userctrl1 = new Game13WIPUserHero($game->user1);
        $userctrl2 = new Game13WIPUserHero($game->user2);
        $prelevel1 = Game13WIPUserHero::hitSpecialCard($userctrl1, $userctrl2);
        $prelevel2 = Game13WIPUserHero::hitSpecialCard($userctrl2, $userctrl1);
        if($prelevel1 === false){
            $prelevel1 = 0; 
        }
        if($prelevel2 === false){
            $prelevel2 = 0;
        }
        $extracards1 = []; $extracards2 = [];
        if(!empty($game->gameUser1->extracards)){
            if(strpos($game->gameUser1->extracards, ',') > 0){
                $arr1 = explode(',', $game->gameUser1->extracards);
                foreach($arr1 as $one){
                    array_push($extracards1, intval($one));
                }
                $arr2 = explode(',', $game->gameUser2->extracards);
                foreach($arr2 as $one){
                    array_push($extracards2, intval($one));
                }
            }
        }

        $cardctrl = new Game13WIPCardHandler;
        $dispatched = $cardctrl->dispatchCards($prelevel1, $prelevel2, $extracards1, $extracards2);

        $foruser1 = $cardctrl->evalCardStrategies($dispatched[0]);
        $foruser2 = $cardctrl->evalCardStrategies($dispatched[1]);

        $cards1 = json_decode($foruser1, true)[0];
        $cards2 = json_decode($foruser2, true)[0];

        $cards1 = $this->convertToCompeteableCards($cards1);
        $cards2 = $this->convertToCompeteableCards($cards2);

        $competehandler = new Game13WIPCompeteHandler($game, $cards1, $cards2);
        $data = $competehandler->compete();
        $game = $competehandler->getGameObject();
        $re = Game13WIPUserHero::checkWin($game);
        $terminate = $re['terminate'];
        if(!$terminate){
            if($game->round == self::$MAX_ROUND){
                $terminate = true;
            }
        }
        $re['terminate'] = $terminate;  

        $data['wincheck'] = $re;

        $game = $this->getGameData($game);
        $data['userdata'] = $game;

        $datastr = self::JSONEncode($data);

        echo $datastr;

    }

    public function testPost(){
        $data = Input::get('data');
        echo $data;
    }

    private function testPlayData(){
        $gameId = 2; $gameUserId = 6;
        $health = $this->healthCheck($gameId, $gameUserId, false);
        $returnData = $this->next($gameId, $gameUserId, '', false);
        $game = MD\Game13OnprocessModel::find(intval($gameId));
        $userid1 = $game->user1; $userid2 = $game->user2;
        $enemyId = $userid1;
        if($userid1 == intval($gameUserId)){
            $enemyId = $userid2;
        }
        $me = $this->getUserInfo($gameUserId);
        $enemy = $this->getUserInfo($enemyId);

        $returnData['me'] = $me;
        $returnData['enemy'] = $enemy;

        $encodeContent = self::JSONEncode($returnData);

        echo $encodeContent;
    }

    //Can be deleted 
    private function cleanForTest(){
        $re = MD\Game13OnprocessModel::all();
        foreach($re as $one){
            $one->delete();
        }
    }

    /*
                case 0:
                $game = $this->newTurn($game); break;
            case 1:
                $game = $this->dispatchCards($game); break;
            case 2:
                $game = $this->competeCards($game); break;
    */

    private function __fullPlayTest($gameId, $gameUserId){

        $game = MD\Game13OnprocessModel::find(intval($gameId));
        $user1Id = $game->user1; $user2Id = $game->user2;

        echo 'Start One Round <hr/>';
        echo '<b>Round '.$game->round.'</b>';
        echo '<h2>Dispatch Card</h2><br/>';

        $this->gotoNextStage($gameId, $gameUserId); 

        echo '<p>';
        echo '<b>User1 Cards</b><br/>';
        $user1Cards= MD\Game13OnprocessDataModel::where('game_user', $user1Id)->first()->data;
        echo $user1Cards;
        echo '</p>';

        echo '<p>';
        echo '<b>User2 Cards</b><br/>';
        $user2Cards= MD\Game13OnprocessDataModel::where('game_user', $user2Id)->first()->data;
        echo $user2Cards;
        echo '</p>';
        $data1 = json_decode($user1Cards, true)[0];
        $data2 = json_decode($user2Cards, true)[0];

        $arr = ['top', 'middle', 'bottom'];
        $darr = [];
        foreach($arr as $one){
            foreach($data1['cards'][$one] as $key){
                if(!array_key_exists($key, $darr)){
                    $darr[$key] = 1;
                }else{
                    $darr[$key] ++;
                }
            }
            foreach($data2['cards'][$one] as $key){
                if(!array_key_exists($key, $darr)){
                    $darr[$key] = 1;
                }else{
                    $darr[$key] ++;
                }
            }
        }
        $str = '';
        foreach($darr as $key=>$value){
            if($value>1){
                $str = $str.$key.',';
            }
        }
        echo '<p><span style="color:red">'.$str.'</span></p>';

        echo '<h2>Submit Cards</h2><br/>';
        
        $this->_doSubmitCards(self::JSONEncode($data1), $user1Id, $gameId);
        echo '<p>';
        echo '<b>Compete Results</b><br/>';
        $competeResult1= MD\Game13OnprocessDataModel::where('game_user', $user1Id)->first()->data;
        echo $competeResult1;
        echo '</p>';

        $compete = json_decode($competeResult1, true);
        if($compete['wincheck']['terminate'] == true){
            if(intval($compete['wincheck']['win'])==intval($user1Id)){
                echo '<p><b>User 1 Win!</b></p>';
            }else if(intval($compete['wincheck']['win'])==intval($user2Id)){
                echo '<p><b>User 2 Win!</b></p>';
            }
        }else{
            $this->gotoNextStage($gameId, $gameUserId);
            $this->__fullPlayTest($gameId, $gameUserId);
        }

    }

    private function __testCardDispatch(){
        
        $userrest1 = [];
        $userrest2 = [];
        for($idx=0; $idx<15; $idx++){
            $cardctrl = new Game13WIPCardHandler;
            $result = $cardctrl->dispatchCards(9, 8, $userrest1, $userrest2);
            $user1cards = []; $user2cards = [];
            $pre1 = $userrest1;
            $pre2 = $userrest2;
            $userrest1 = [];
            $userrest2 = [];
            $m = 0;
            $chk1 = 0; $chk2 = 0;
            $map1 = []; $map2 = [];
            foreach($result[0] as $one){
                array_push($user1cards, $one['idx']);
                $map1[$one['idx']] = 1;
                if($m >11 && $m < 15){
                    array_push($userrest1, $one['idx']);
                }
                $m ++;
            }
            foreach($pre1 as $one){
                if(array_key_exists($one, $map1)){
                    $chk1 ++;
                }
            }
            $m = 0;
            $dup = [];
            foreach($result[1] as $one){
                array_push($user2cards, $one['idx']);
                if(array_key_exists($one['idx'], $map1)){
                    array_push($dup, $one['idx']);
                }
                $map2[$one['idx']] = 1;
                if($m >11 && $m < 15){
                    array_push($userrest2, $one['idx']);
                }
                $m ++;
            }            
            foreach($pre2 as $one){
                if(array_key_exists($one, $map2)){
                    $chk2 ++;
                }
            }
            sort($user1cards);
            sort($user2cards);
            $chk1 = (sizeof($pre1)==$chk1?'':'<b>FALSE</b>');
            $chk2 = (sizeof($pre2)==$chk2?'':'<b>FALSE</b>');
            echo self::JSONEncode($user1cards).self::JSONEncode($pre1).$chk1.'<br/>';
            echo self::JSONEncode($user2cards).self::JSONEncode($pre2).$chk2.'<br/>';
            echo self::JSONEncode($dup);
            echo '<hr>';            
        }

    }
    public function fullPlayTest(){
        $gameId = Input::get('gameId');
        $gameUserId = Input::get('gameUserId');
        $mode = 'normal';
        if(Input::has('mode')){
            $mode = Input::get('mode');
        }
        $game = MD\Game13OnprocessModel::find(intval($gameId));
        $user1Id = $game->user1; $user2Id = $game->user2;
        if($mode!='normal'){
            $game->mode = 'test';
            $game->save();
        }   

        echo '<!doctype html>
            <html class="no-js">
                <head>
            <meta charset="utf-8"></head><body style="font-size:10px">';

        $arr = array(array("a"=>1, "b"=>2), array("c=>1"));
        echo sizeof($arr).'<br/>';
        // $this->__fullPlayTest($gameId, $gameUserId);
        $this->__testCardDispatch();

        echo '<br/>';
        echo '</body></html>';

        
    }

    public function prepareTestData2(){
        $data = Input::get('data');
        $data = json_decode($data, true);
        MD\UserGameInfoModel::where('user_id', 1)->orWhere('user_id', 2)->delete();
        MD\Game13HeroModel::where('user_id', 1)->orWhere('user_id', 2)->delete();
        MD\Game13UserModel::where('user_id', 1)->orWhere('user_id', 2)->delete();
        $re = MD\Game13OnprocessModel::all();
        foreach($re as $one){
            $one->delete();
        }
        $re = MD\Game13HeroOnprocessModel::all();        
        foreach($re as $one){
            $one->delete();
        }
        $re = MD\Game13UserOnprocessModel::all();        
        foreach($re as $one){
            $one->delete();
        }        

        $fuwenset1 = MD\Game13FuwenSetModel::where('user_id', 1)->first();
        if(!empty($fuwenset1)){
            MD\Game13FuwenModel::where('fuwenset_id', $fuwenset1->id)->delete();
            $fuwenset1->delete();
        }
        $fuwenset2 = MD\Game13FuwenSetModel::where('user_id', 2)->first();
        if(!empty($fuwenset2)){
            MD\Game13FuwenModel::where('fuwenset_id', $fuwenset2->id)->delete();
            $fuwenset2->delete();
        }

        $userid = 0;

        $users = [];
        foreach($data as $username=>$userdata){
            $userid ++;
            MD\UserGameInfoModel::create([
                'user_id'=>$userid,
                'recent_win_times'=>$userdata['wins'],
                'level'=>intval($userdata['level']),
                'status'=>1
            ]);    
            $heros = [];  $fuwens = [];
            $fuwensetname = 'fuwen'.$userid;
            $fuwenset = MD\Game13FuwenSetModel::create([
                'user_id'=>$userid,
                'fuwenset_name'=>$fuwensetname
            ]);
            $idx = 0;
            foreach($userdata['hero'] as $heroname=>$herodata){
                $onehero = MD\Game13HeroModel::create([
                    'user_id'=>$userid,
                    'heroname'=>$userid.'_'.$heroname,
                    'level'=>$herodata['level'],
                    'heroimg'=>'/game13/pic/userhead'
                ]);
                array_push($heros, $onehero);

                $fuwen = MD\Game13FuwenModel::create([
                    'fuwenset_id'=>$fuwenset->id, 
                    'ti'=>$herodata['ti'],
                    'su'=>$herodata['su'],
                    'gong'=>$herodata['gong'],
                    'fang'=>$herodata['fang'],
                    'ji'=>$herodata['ji'],
                    'ind'=>$idx
                ]);

                array_push($fuwens, $fuwen);

                $idx ++;
            }  
            $u = MD\Game13UserModel::create([
                'user_id'=>$userid,
                'headimg'=>'/game13/pic/userhead',
                'hero1'=>$heros[0]->id,
                'hero2'=>$heros[1]->id,
                'hero3'=>$heros[2]->id,
                'fuwen1'=>$fuwens[0]->id,
                'fuwen2'=>$fuwens[1]->id,
                'fuwen3'=>$fuwens[2]->id
            ]);

            array_push($users, $u->id);
        }

        echo self::JSONEncode($users);
    }

    private function prepareTestData(){
        MD\UserGameInfoModel::where('user_id', 1)->orWhere('user_id', 2)->delete();
        MD\Game13HeroModel::where('user_id', 1)->orWhere('user_id', 2)->delete();
        MD\Game13UserModel::where('user_id', 1)->orWhere('user_id', 2)->delete();
        $fuwenset1 = MD\Game13FuwenSetModel::where('user_id', 1)->first();
        if(!empty($fuwenset1)){
            MD\Game13FuwenModel::where('fuwenset_id', $fuwenset1->id)->delete();
            $fuwenset1->delete();
        }
        $fuwenset2 = MD\Game13FuwenSetModel::where('user_id', 2)->first();
        if(!empty($fuwenset2)){
            MD\Game13FuwenModel::where('fuwenset_id', $fuwenset2->id)->delete();
            $fuwenset2->delete();
        }

        MD\UserGameInfoModel::create([
            'user_id'=>1,
            'recent_win_times'=>3,
            'level'=>4,
            'status'=>1
        ]);
        MD\UserGameInfoModel::create([
            'user_id'=>2,
            'recent_win_times'=>2,
            'level'=>3,
            'status'=>1
        ]);

        $hero1_1 = MD\Game13HeroModel::create([
            'user_id'=>1,
            'heroname'=>'name1',
            'level'=>2,
            'heroimg'=>'/game13/pic/userhead'
        ]);
        $hero1_2 = MD\Game13HeroModel::create([
            'user_id'=>1,
            'heroname'=>'name2',
            'level'=>3,
            'heroimg'=>'/game13/pic/userhead'
        ]);
        $hero1_3 = MD\Game13HeroModel::create([
            'user_id'=>1,
            'heroname'=>'name3',
            'level'=>4,
            'heroimg'=>'/game13/pic/userhead'
        ]);

        $fuwenset1 = MD\Game13FuwenSetModel::create([
            'user_id'=>1,
            'fuwenset_name'=>'fuwen1'
        ]);

        $fuwen1_1 = MD\Game13FuwenModel::create([
            'fuwenset_id'=>$fuwenset1->id, 
            'ti'=>8,
            'su'=>7,
            'gong'=>3,
            'fang'=>3,
            'ji'=>6,
            'ind'=>1
        ]);

        $fuwen1_2 = MD\Game13FuwenModel::create([
            'fuwenset_id'=>$fuwenset1->id, 
            'ti'=>6,
            'su'=>6,
            'gong'=>4,
            'fang'=>4,
            'ji'=>8,
            'ind'=>2
        ]);

        $fuwen1_3 = MD\Game13FuwenModel::create([
            'fuwenset_id'=>$fuwenset1->id, 
            'ti'=>5,
            'su'=>4,
            'gong'=>9,
            'fang'=>6,
            'ji'=>3,
            'ind'=>3
        ]);

        $u = MD\Game13UserModel::create([
            'user_id'=>1,
            'headimg'=>'/game13/pic/userhead',
            'hero1'=>$hero1_1->id,
            'hero2'=>$hero1_2->id,
            'hero3'=>$hero1_3->id,
            'fuwen1'=>$fuwen1_1->id,
            'fuwen2'=>$fuwen1_2->id,
            'fuwen3'=>$fuwen1_3->id
        ]);

        $hero2_1 = MD\Game13HeroModel::create([
            'user_id'=>2,
            'heroname'=>'name4',
            'level'=>3,
            'heroimg'=>'/game13/pic/userhead'
        ]);
        $hero2_2 = MD\Game13HeroModel::create([
            'user_id'=>2,
            'heroname'=>'name5',
            'level'=>1,
            'heroimg'=>'/game13/pic/userhead'
        ]);
        $hero2_3 = MD\Game13HeroModel::create([
            'user_id'=>2,
            'heroname'=>'name6',
            'level'=>3,
            'heroimg'=>'/game13/pic/userhead'
        ]);

        $fuwenset2 = MD\Game13FuwenSetModel::create([
            'user_id'=>2,
            'fuwenset_name'=>'fuwen2'
        ]);

        $fuwen2_1 = MD\Game13FuwenModel::create([
            'fuwenset_id'=>$fuwenset2->id, 
            'ti'=>8,
            'su'=>7,
            'gong'=>3,
            'fang'=>3,
            'ji'=>6,
            'ind'=>1
        ]);

        $fuwen2_2 = MD\Game13FuwenModel::create([
            'fuwenset_id'=>$fuwenset2->id, 
            'ti'=>6,
            'su'=>6,
            'gong'=>4,
            'fang'=>4,
            'ji'=>8,
            'ind'=>2
        ]);

        $fuwen2_3 = MD\Game13FuwenModel::create([
            'fuwenset_id'=>$fuwenset2->id, 
            'ti'=>5,
            'su'=>4,
            'gong'=>9,
            'fang'=>6,
            'ji'=>3,
            'ind'=>3
        ]);

        MD\Game13UserModel::create([
            'user_id'=>2,
            'headimg'=>'/game13/pic/userhead',
            'hero1'=>$hero2_1->id,
            'hero2'=>$hero2_2->id,
            'hero3'=>$hero2_3->id,
            'fuwen1'=>$fuwen2_1->id,
            'fuwen2'=>$fuwen2_2->id,
            'fuwen3'=>$fuwen2_3->id
        ]);

        return $u->id;
    }

    private function evalInitFeatureAdd($gameUser){
        $featureAddMap  = ['90'=>5, '75'=>4, '50'=>3, '25'=>2, '10'=>1, '0'=>0];

        $level = $gameUser->gameUserInfo->level;
        $levelCount = MD\UserGameInfoModel::where('level', $level)->count();
        $totalCount = MD\UserGameInfoModel::count();
        if($totalCount == 0){
            return $featureAddMap['0'];
        }else{
            $rate = intval($levelCount / $totalCount * 100);
            foreach($featureAddMap as $k=>$v){
                $intk = intval($k);
                if($rate >= $intk){
                    return $v;
                }
            }
            return $featureAddMap['0'];
        }
    }

    private function createOneProcessHero($game13User, $fuwenId, $heroId, $initFeatureAdd){
        $initFeatureAdd  = 0;
        //Delete previous
        MD\Game13HeroOnprocessModel::where('heroid', $heroId)->delete();

        $fuwen =MD\Game13FuwenModel::find($fuwenId);
        $herodef = MD\Game13HeroModel::find($heroId);

        $ti = min(9, $fuwen->ti + $initFeatureAdd);
        $su = min(9, $fuwen->su + $initFeatureAdd);
        $gong = min(9, $fuwen->gong + $initFeatureAdd);
        $fang = min(9, $fuwen->fang + $initFeatureAdd);
        $ji = min(9, $fuwen->ji + $initFeatureAdd);

        $blood = Game13WIPUserHero::evalHeroBloodValue($ti);
        $oriblood = $blood;

        $hero = MD\Game13HeroOnprocessModel::create(array('level'=>$herodef->level, 'heroid'=>$heroId, 'oriblood'=>$oriblood,
                    'blood'=>$blood, 'ti'=>$ti, 'su'=>$su, 'gong'=>$gong, 'fang'=>$fang, 'ji'=>$ji, 'heroname'=>$herodef->heroname,
                    'pic'=>$herodef->heroimg));

        return $hero->id;
    }

    private function createOnProcessForUser($game13User){
        $initFeatureAdd = $this->evalInitFeatureAdd($game13User);

        $heroWIPId1 = $this->createOneProcessHero($game13User, $game13User->fuwen1, $game13User->hero1, $initFeatureAdd);
        $heroWIPId2 = $this->createOneProcessHero($game13User, $game13User->fuwen2, $game13User->hero2, $initFeatureAdd);
        $heroWIPId3 = $this->createOneProcessHero($game13User, $game13User->fuwen3, $game13User->hero3, $initFeatureAdd);

        //Delete previous
        MD\Game13UserOnprocessModel::where('user_id', $game13User->user_id)->delete();

        $gameUser = MD\Game13UserOnprocessModel::create(array('user_id'=>$game13User->user_id, 
            'hero1'=>$heroWIPId1, 'hero2'=>$heroWIPId2, 'hero3'=>$heroWIPId3, 'fuwen1'=>$game13User->fuwen1, 
            'fuwen2'=>$game13User->fuwen2, 'fuwen3'=>$game13User->fuwen3, 'init_feature_add'=>$initFeatureAdd,
            'stage'=>self::$STAGES['not_start'], 'round'=>1, 'headimg'=>$game13User->headimg
        ));

        $gameUserCtrl = new Game13WIPUserHero($gameUser->id);
        $luck = Game13WIPUserHero::evalInitLuck($gameUserCtrl);

        $gameUser->luck = $luck;
        $gameUser->save();

        return $gameUser;
    }

    private function findMatchedUser($game13UserId){
         $u = MD\Game13UserModel::leftJoin('t_user_game_info', function($join){
            $join->on('game13_user.user_id', '=', 't_user_game_info.user_id');
         })->where('game13_user.id', '<>', $game13UserId)->where('t_user_game_info.status', 1)
         ->first();
         if(empty($u))
            return false;
         return $u;
    }

    private function getUserInfo($gameUserId) {
        $useronprocess = MD\Game13UserOnprocessModel::find(intval($gameUserId));
        $hero1 = $useronprocess->gameHero1;
        $hero2 = $useronprocess->gameHero2;
        $hero3 = $useronprocess->gameHero3;

        return $useronprocess;
    }

    public function play() {
        $gameId = Input::get('gameId');
        $gameUserId = Input::get('gameUserId');
        $mode = 'normal';
        if(Input::has('mode')){
            $mode = Input::get('mode');
        }
        $health = $this->healthCheck($gameId, $gameUserId, false);
        $returnData = $this->next($gameId, $gameUserId, '', false);
        $game = MD\Game13OnprocessModel::find(intval($gameId));
        if($mode!='normal'){
            $game->mode = 'test';
            $game->save();
        }
        $userid1 = $game->user1; $userid2 = $game->user2;
        $enemyId = $userid1;
        if($userid1 == intval($gameUserId)){
            $enemyId = $userid2;
        }
        $me = $this->getUserInfo($gameUserId);
        $enemy = $this->getUserInfo($enemyId);

        $returnData['me'] = $me;
        $returnData['enemy'] = $enemy;
        $returnData['mode'] = $mode;

        if($game->stage == self::$STAGES['shuffle_card']){
            $timeoff = intval((time() - $game->timechecker)/1000);
            $returnData['timediff'] = $timeoff;
        }

        $encodeContent = self::JSONEncode($returnData);

        return view('game.game13.game13_compete_board', ['gameId'=>$gameId, 'gameUserId'=>$gameUserId,
                'content'=>$encodeContent]);
        // return view('game.game13.game13_evaluate_test', ['gameId'=>$gameId, 'gameUserId'=>$gameUserId,
        //         'content'=>$encodeContent]);
    }

    public function triggerGame($game13UserId, $game13User2Id=''){

        $url = $this->isGameStarted($game13UserId);

        if($url !== 'false'){
            return $url;
        }

        if(empty($game13User2Id)){
            $game13User2 = $this->findMatchedUser($game13UserId);
            if($game13User2===false)
                return "false";            
            $game13User2Id = $game13User2->id;
        }

        return $this->startAGame($game13UserId, $game13User2Id);
    }

    private function composeStartUrl($gameId, $gameUserId){
       return self::JSONEncode(['gameId'=>$gameId, 'gameUserId'=>$gameUserId ]);
    }

    private function calculateLuckpoint($gameUser1, $gameUser2){
        $lvl1 = $gameUser1->gameHero1->level - $gameUser2->gameHero1->level ;
        $lvl2 = $gameUser1->gameHero2->level - $gameUser2->gameHero2->level ;
        $lvl3 = $gameUser1->gameHero3->level - $gameUser2->gameHero3->level ;
        
        $arr = [$lvl1, $lvl2, $lvl3];
        $luckpoint1 = 1; $luckpoint2 = 1;
        foreach($arr as $one){
            if($one > 0){
                $luckpoint1 ++;
            }else if($one < 0){
                $luckpoint2 ++;
            }
        }  
        $gameUser1->luckpoint = $luckpoint1;
        $gameUser2->luckpoint = $luckpoint2;   
        $gameUser1->save();
        $gameUser2->save();   
    }

    private function startAGame($game13User1, $game13User2){

        $u1 = MD\Game13UserModel::find(intval($game13User1));
        $u2 = MD\Game13UserModel::find(intval($game13User2));

        if(empty($u1)||empty($u2)){
            return "false";
        }else if($u1->gameUserInfo->status!=1||$u2->gameUserInfo->status!=1){
            return "false";
        }

        $up1 = $this->createOnProcessForUser($u1);
        $up2 = $this->createOnProcessForUser($u2);

        $uWIP1 = $up1->id; $uWIP2 = $up2->id;

        $this->calculateLuckpoint($up1, $up2);

        $onprocess = MD\Game13OnprocessModel::create(array('user1'=>$uWIP1, 'user2'=>$uWIP2, 
                    'timechecker'=>time(), 'round'=>1, 'stage'=>self::$STAGES['not_start'], 
                    'nextround'=>1, 'mode'=>'normal', 'nextstage'=>self::$STAGES['shuffle_card']));

        $u1->gameUserInfo->game_id = $onprocess->id;
        $u1->gameUserInfo->status = 2;
        $u2->gameUserInfo->game_id = $onprocess->id;
        $u2->gameUserInfo->status = 2;

        $u1->gameUserInfo->save();
        $u2->gameUserInfo->save();

        MD\Game13OnlineStatusModel::where('game_user', $uWIP1)->delete();
        MD\Game13OnlineStatusModel::where('game_user', $uWIP2)->delete();

        $user1OnlineStatus = MD\Game13OnlineStatusModel::create(array('game_id'=>$onprocess->id, 'game_user'=>$uWIP1, 
            'health_timestamp'=>time(), 'online'=>1));
        $user2OnlineStatus = MD\Game13OnlineStatusModel::create(array('game_id'=>$onprocess->id, 'game_user'=>$uWIP2, 
            'health_timestamp'=>time(), 'online'=>1));

        $gameData = '';
        $user1Data = MD\Game13OnprocessDataModel::create(['game_user'=>$uWIP1, 'data'=>$gameData]);
        $user2Data = MD\Game13OnprocessDataModel::create(['game_user'=>$uWIP2, 'data'=>$gameData]);

        $result = $this->composeStartUrl($onprocess->id, $uWIP1);

        MD\GameUserHistoryModel::create(['game_id'=>$onprocess->id, 'user_id'=>$u1->user_id, 'enemy_user_id'=>$u2->user_id, 
                'game_type'=>self::$GAME_TYPE]);
        MD\GameUserHistoryModel::create(['game_id'=>$onprocess->id, 'user_id'=>$u2->user_id, 'enemy_user_id'=>$u1->user_id, 
                'game_type'=>self::$GAME_TYPE]);

        return $result;
    }

    public function isGameStarted($game13UserId){
        $u = MD\Game13UserModel::find(intval($game13UserId));

        $gu = MD\Game13UserOnprocessModel::where('user_id', $u->user_id)->first();

        if(empty($gu)){
            return 'false';
        }else{
            $pr = MD\Game13OnprocessModel::where('user1', $gu->id)->orWhere('user2', $gu->id)->first();
            
            if(empty($pr)){
                return 'false';
            }else{
                return $this->composeStartUrl($pr->id, $gu->id);
            }
        }
    }


    public function getUserStatus($userid){
        $u = MD\UserGameInfoModel::find(intval($userid));
        return $u->status;
    }

    private function convertToCompeteableCards($assignedData){
            $cards = $assignedData['cards']; 
            $result = [];
            if(sizeof($assignedData['level'])==1){
                $result = ['body'=>[], 'extra'=>$cards['extra'], 'level'=>$assignedData['level'], 
                            'changed'=>'false'];
                if(array_key_exists('body', $cards)){
                    $result['body'] = $cards['body'];
                }else{
                    $arr = ['top', 'middle', 'bottom'];
                    $body = [];
                    foreach($arr as $k){
                        foreach($cards[$k] as $one){
                            array_push($body, $one);
                        }
                    }
                     $result['body'] = $body;
                }
            }else{
                $result = ['top'=>$cards['top'], 'middle'=>$cards['middle'],
                        'bottom'=>$cards['bottom'], 'extra'=>$cards['extra'],
                        'changed'=>'false'];
            }
            $result['level'] = $assignedData['level'];

            return $result;        
    }
    private function autosubmitCardsForEnemy($gameId, $gameUserId){
        $game = MD\Game13OnprocessModel::find(intval($gameId));
        $anotheruser = $game->user1;

        if($game->user1 == intval($gameUserId)){
            $anotheruser = $game->user2;
        }
        $online = MD\Game13OnlineStatusModel::where('game_user', $anotheruser)->where('game_id', intval($gameId))->first();
        if($online->online == 0||$game->mode=='test'){
            $enemy = MD\Game13UserOnprocessModel::find($anotheruser); 
            $assignedData = json_decode($enemy->userData->data, true);
            if(!array_key_exists(0, $assignedData)){
                return;
            }else{
                $assignedData = $assignedData[0];
            }

            $result = $this->convertToCompeteableCards($assignedData);
            $enemy = $this->saveCardsToGameUser($enemy, $result);

        }

    }

    private function saveCardsToGameUser($gameUser, $cardStyles, $heros=''){
        $forsave = $this->evalForSaveCards($cardStyles);

        $gameUser->topcards = $forsave['top'];
        $gameUser->middlecards = $forsave['middle'];
        $gameUser->bottomcards = $forsave['bottom'];
        $gameUser->extracards = $forsave['extra'];
        if(strlen($heros) > 0){
            $arr = explode(',', $heros);
            if(sizeof($arr) >= 3){
                $gameUser->hero1 = intval($arr[0]);
                $gameUser->hero2 = intval($arr[1]);
                $gameUser->hero3 = intval($arr[2]);
            }
        }
        $gameUser->save();

        $gameUser->userData->data = self::JSONEncode($cardStyles);
        $gameUser->userData->save();        

        return $gameUser;
    }

    private function evalForSaveCards($cardStyles) {
        if(!array_key_exists('top', $cardStyles)){
            $lenlimit = [3, 8, 13];
            $keys = ['top', 'middle', 'bottom'];
            $newarr = [];
            for($i = 0; $i<3; $i++){
                $small = ($i>0?$lenlimit[$i-1]:0);
                $big = $lenlimit[$i];
                $newarr[$keys[$i]] = [];
                for($j=$small; $j<$big; $j++){
                    array_push($newarr[$keys[$i]], $cardStyles['body'][$j]);
                }
            }
            $newarr['extra'] = $cardStyles['extra'];
            $cardStyles = $newarr;
        }

        $forsave = ['top'=>'', 'middle'=>'', 'bottom'=>'', 'extra'=>''];
        $tot = [];
        $ct = 0;
        foreach($forsave as $k=>$v){
            $ct = 0; $re = '';
             foreach($cardStyles[$k] as $one){
                $ct ++;
                $idxvalue = Game13WIPCardHandler::toCardIndex($one);
                if(array_key_exists($one, $tot)){
                    $idxvalue += 13;
                }else{
                    $tot[$one] = 1;
                }
                $re=$re.strval($idxvalue);
                if($ct < sizeof($cardStyles[$k])){
                    $re = $re.',';
                }
            }
            $forsave[$k] = $re;           
        }  
        
        return $forsave;     
    }

    private function toStandardPreferedCards($prefered){
        $alt = [];
        foreach($prefered as $k=>$v){
            if($k == 'cards'){
                foreach($prefered[$k] as $m=>$n){
                    $alt[$m] = $n;
                }
            }else{
                $alt[$k] = $v;
            }
        }
        return $alt;
    }
    //post 
    public function submitCards(){
        $data = Input::get('data');
        $gameuserId = Input::get('gameUserId'); //Change hero??
        $gameId = Input::get('gameId');   

        return $this->_doSubmitCards($data, $gameuserId, $gameId);
    }

    private function _doSubmitCards($data, $gameuserId, $gameId){
        $prefered = json_decode($data, true);

        if(!array_key_exists('changed', $prefered)){
            $prefered['changed'] = 'false';
        }

        $prefered = $this->toStandardPreferedCards($prefered);

        $cardStyles = Game13WIPCardHandler::evalCardStyles($prefered);
        $gameUser = MD\Game13UserOnprocessModel::find(intval($gameuserId));

        $gameUser = $this->saveCardsToGameUser($gameUser, $cardStyles, $prefered['heros']);  

        $this->autosubmitCardsForEnemy($gameId, $gameuserId);      

        return $this->gotoNextStage($gameId, $gameuserId);
    }

    //This function will be called in the very begining, for user to fetch user id and game id.
    //All after operations shouldn't use userid to communicate, but game user id alternatively. 
    public function getUserOnprocessInfo($userid){
        $id = intval($userid);
        $u = MD\UserGameInfoModel::find($id);
        $game = $u->playingGame;
        $gameUserId = 0;
        $gameUser1 = $game->gameUser1;
        if($gameUser1->user_id == $id){
            $gameUserId = $gameUser1->id; 
        }else{
            $gameUserId = $game->user2;
        }
        return self::JSONEncode(array('gameId'=>$game->id, 'gameUserId'=>$gameUserId));
    }

    public function getGameUserInfo($gameUserId){
        return self::JSONEncode(MD\Game13UserOnprocessModel::find(intval($gameUserId)));
    }

    //post
    public function reorgHeroPosition(){
        $gameUserId = intval(Input::get("gameUserId"));
        $order = explode(',', Input::get("order"));
        $gameUser = $this->getGameUserInfo($gameUserId);
        $oriorder = array($gameUser->hero1, $gameUser->hero2, $gameUser->hero3);
        $neworder = [$oriorder[intval($order[0])], $oriorder[intval($order[1])], $oriorder[intval($order[2])]];
        $gameUser->hero1 = $neworder[0];
        $gameUser->hero2 = $neworder[1];
        $gameUser->hero3 = $neworder[2];
        $gameUser->save();
    }

    private function testDispatchCards() {
        $game = MD\Game13OnprocessModel::all();
        $game = $game[0];
        $this->dispatchCards($game);
    }

    private function dispatchCards($game) {
        $userctrl1 = new Game13WIPUserHero($game->user1);
        $userctrl2 = new Game13WIPUserHero($game->user2);
        $prelevel1 = Game13WIPUserHero::hitSpecialCard($userctrl1, $userctrl2);
        $prelevel2 = Game13WIPUserHero::hitSpecialCard($userctrl2, $userctrl1);
        if($prelevel1 === false){
            $prelevel1 = 0; 
        }
        if($prelevel2 === false){
            $prelevel2 = 0;
        }
        $extracards1 = []; $extracards2 = [];
        if(!empty($game->gameUser1->extracards)){
            if(strpos($game->gameUser1->extracards, ',') > 0){
                $arr1 = explode(',', $game->gameUser1->extracards);
                foreach($arr1 as $one){
                    array_push($extracards1, intval($one));
                }
                $arr2 = explode(',', $game->gameUser2->extracards);
                foreach($arr2 as $one){
                    array_push($extracards2, intval($one));
                }
            }
        }

        $cardctrl = new Game13WIPCardHandler;
        $dispatched = $cardctrl->dispatchCards($prelevel1, $prelevel2, $extracards1, $extracards2);

        $foruser1 = $cardctrl->evalCardStrategies($dispatched[0]);
        $foruser2 = $cardctrl->evalCardStrategies($dispatched[1]);

        $game->gameUser1Data->data = $foruser1;
        $game->gameUser2Data->data = $foruser2;
        $game->gameUser1Data->save();
        $game->gameUser2Data->save();

        return $game;
    }


    private function evalGameData($game){
        $gameData = json_decode(self::JSONEncode( $this->getGameData($game) ), true);
        $arr = ['game_user1_data', 'game_user2_data'];
        foreach($arr as $one){
            $gameData[$one] = [];
        }
        $arrheros = ['game_hero1', 'game_hero2','game_hero3'];
        foreach($arrheros as $one){
            $gameData['game_user1'][$one] = [];
            $gameData['game_user2'][$one] = [];
        }

        return $gameData;
    }
    private function competeCards($game) {

        $competeHandler = new Game13WIPCompeteHandler($game);

        $data = $competeHandler->compete();

        $game = $competeHandler->getGameObject();

        $re = Game13WIPUserHero::checkWin($game);
        $terminate = $re['terminate'];
        if(!$terminate){
            if($game->round == self::$MAX_ROUND){
                $terminate = true;
            }
        }
        $re['terminate'] = $terminate;        

        $data['wincheck'] = $re;

        $gameData = $this->evalGameData($game);

        $data['userdata'] = $gameData;

        $datastr = self::JSONEncode($data);

        $game->gameUser1Data->data = $datastr;
        $game->gameUser2Data->data = $datastr;
        $game->gameUser1Data->save();
        $game->gameUser2Data->save();

        return $game;
    }

    private function checkWin($game){
        $re = Game13WIPUserHero::checkWin($game);
        $terminate = $re['terminate'];
        if(!$terminate){
            if($game->round == self::$MAX_ROUND){
                $terminate = true;
            }
        }
        $re['terminate'] = $terminate;

        $data = self::JSONEncode($re);

        $game->gameUser1Data->data = $data;
        $game->gameUser2Data->data = $data;
        $game->gameUser1Data->save();
        $game->gameUser2Data->save();  

        return $game;      
    }

    private function getGameData($game){
        $user1 = $game->gameUser1;
        $user2 = $game->gameUser2;
        $game->gameUser1->gameHero1;
        $game->gameUser1->gameHero2;
        $game->gameUser1->gameHero3;
        $game->gameUser2->gameHero1;
        $game->gameUser2->gameHero2;
        $game->gameUser2->gameHero3;

        return $game;
    }

    private function newTurn($game){
        $game->gameUser1Data->data = '';
        $game->gameUser2Data->data = '';
        $game->gameUser1Data->save();
        $game->gameUser2Data->save(); 
        return $game;         
    }

    private function nextTurn($game) {
        $game->round = $game->nextround;
        $game->stage = $game->nextstage;
        $nextturn= $this->evalNextTurn($game->round, $game->stage);
        $game->nextround = $nextturn[0];
        $game->nextstage = $nextturn[1];
        $game->timechecker = time();
        $game->save();

        switch($game->stage){
            case 0:
                $game = $this->newTurn($game); break;
            case 1:
                $game = $this->dispatchCards($game); break;
            case 2:
                $game = $this->competeCards($game); break;
        }

        return $game;
    }

    private function evalNextTurn($curround, $curstage) {
        $nextround = $curround; $nextstage = $curstage;
        if($curstage == self::$STAGES['compete']){
            $nextstage = self::$STAGES['not_start'];
            $nextround += 1;
        }else{
            $nextstage += 1;
        }
        return [$nextround, $nextstage];
    }
    //post
    public function gotoNextStage ($gameId, $gameUserId) {
         $game = MD\Game13OnprocessModel::find(intval($gameId));
         if($game->busy == 1){
            return 'false';
         }else{
            $game->busy = 1;
            $game->save();
         }

         $result = 'false';
         $user1 = $game->user1; $user2 = $game->user2;
         $me = MD\Game13UserOnprocessModel::find(intval($gameUserId));
         if($me->round <= $game->round && $me->stage <= $game->stage){
             $me->round = $game->nextround;
             $me->stage = $game->nextstage;
             $me->save();            
         }
         $anotheruser = $user1;
         if($user1 == intval($gameUserId)){
            $anotheruser = $user2;
         }

         $goNext = false;
         $enemy = MD\Game13UserOnprocessModel::find($anotheruser); 
         if($enemy->round > $game->round||$enemy->stage > $game->stage||$game->mode== 'test'){
                $goNext = true;
         }else{
                $online = MD\Game13OnlineStatusModel::where('game_user', $anotheruser)->where('game_id', intval($gameId))->first();
                if($online->online == 0){
                    $goNext = true;
                }
         }
         if($goNext){
                $game = $this->nextTurn($game);
                $me->round = $game->round;
                $me->stage = $game->stage;
                $me->save();
                $enemy->round = $game->round;
                $enemy->stage = $game->stage;
                $enemy->save();
         }  

         $game->busy = 0;
         $game->save();

         if($goNext){
             $result = $this->next($gameId, $gameUserId);
         }
         return $result;
    }

    //Card play .............................................................

    private function generateTicket(){
        return strval(time()).Utils::createRandomStr(5);
    }

    //Key function false=wait
    public function next($gameId, $gameUserId, $ticket='', $encode=true){
       $onm = MD\Game13OnprocessModel::find(intval($gameId));
       if($onm->busy == 1){
            return 'false';
       }
       $curstage = $onm->stage;
       $curround = $onm->round;

       $iswaiting = 1;
       $gameUser = MD\Game13UserOnprocessModel::find(intval($gameUserId));

       $gameStage = $gameUser->stage;
       $gameRound = $gameUser->round;

       $refreshTicket = ($ticket=='');
       $enemy = 0;
       if(!$refreshTicket&&$curstage==$gameStage&&$curround==$gameRound){
           if($onm->user1 == intval($gameUserId)){
                $enemy = $onm->gameUser2;
           }else{
                $enemy = $onm->gameUser1;
           }
           if($enemy->round==$curround&&$enemy->stage==$curstage){
                $refreshTicket = true;
           }       
       }

       $result = 'false';
       if($refreshTicket){
            if($enemy == 0){
               if($onm->user1 == intval($gameUserId)){
                    $enemy = $onm->gameUser2;
               }else{
                    $enemy = $onm->gameUser1;
               }
            }
            if($enemy->is_waiting!=0){
                $enemy->is_waiting = 0;
                $enemy->save();
            }

            $iswaiting = 0;
            $data = '';
            if($onm->user1 == intval($gameUserId)){
                if(!empty($onm->gameUser1Data->data)){
                    $data = json_decode($onm->gameUser1Data->data, true);
                }
                
            }else{
                if(!empty($onm->gameUser2Data->data)){  
                    $data = json_decode($onm->gameUser2Data->data, true);
                }
            }

            $result = self::composeReturnData($curstage, $curround, $data, '', $encode);
       }
       if($gameUser->is_waiting!=$iswaiting ){
            $gameUser->is_waiting = $iswaiting;
            $gameUser->save();
       }

       return $result;

    }

}
