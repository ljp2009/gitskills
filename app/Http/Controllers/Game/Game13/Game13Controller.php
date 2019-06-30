<?php

namespace App\Http\Controllers\Game\Game13;

use Illuminate\Http\Request;

use App\Models as MD;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Utils, Storage, Auth, Input;


class Game13Controller extends Controller
{
    private static $FUWEN_EVAL = [3, 6, 7, 8, 999];

    private static $RESOURCE = [
        'entry'=>[
            'backgroundPic'=>['portrait'=>'game/game13/portrait.jpg', 
                                'landscape'=>'game/game13/landscape.jpg'],
            'headCover'=>'game/game13/user_head_cover.png',
            'heroCard'=>['1'=>'game/game13/hero_card_1.png',
                            '2'=>'game/game13/hero_card_2.png',
                            '3'=>'game/game13/hero_card_3.png',
                            '4'=>'game/game13/hero_card_4.png',
                            '5'=>'game/game13/hero_card_5.png'
                        ],
            'fuwenPic'=>'game/game13/fuwen.png'
        ]
    ];

    private static function loadLocalPic($path){
        return Storage::disk('local')->get($path);
    }

    public function getGame13Pic($pic, $ext){
        return self::loadLocalPic('game/game13/'.$pic.'.'.$ext);
    }

    public function getFuwenPic(){
        return self::loadLocalPic(self::$RESOURCE['entry']['fuwenPic']);
    }

    public function getPreloadPic(){
        return self::loadLocalPic("game/game13/loading.gif");
    }

    public function getBackgroundPic($orientation){
        return self::loadLocalPic(self::$RESOURCE['entry']['backgroundPic'][$orientation]);
    }

    public function getUserHeadCoverPic(){
        return self::loadLocalPic(self::$RESOURCE['entry']['headCover']);
    }

    public function getUserHeadPic(){
        return self::loadLocalPic("game/game13/sample_head.jpg");
    }

    public function getHeroCardFrontPic($level){
        return self::loadLocalPic(self::$RESOURCE['entry']['heroCard'][$level]);
    }

    public function heroCard(){
        $img = Input::get('img');
        $level = intval(Input::get('level'));
        $imgsz = [intval(Input::get('w')), intval(Input::get('h'))];
        return view('game.game13.game13_herocard', ['level'=>$level, 'scale'=>1, 'heropic'=>$img, 'imgsz'=>$imgsz]);
    }

    public function userHead(){
        $img = Input::get('img');
        $imgsz = [intval(Input::get('w')), intval(Input::get('h'))];
        return view('game.game13.game13_userhead', ['scale'=>1, 'headimg'=>$img, 'imgsz'=>$imgsz]);
    }

    public function getMaxFuwenValue(){
        return 9;
    }

    public function test(){
        return view('game.game13.game_test');
    }

    public function test2(){
        return view('game.game13.game_test2');
    }

    public function fuwenTest(){
        return view('game.game13.game_fuwen_test');
    }
    /**Page load field */
    public function loadEntryData(){
        // $userid = Auth::user->id;
        $userid = 1;
        $userlevel = MD\UserLevelModel::where('user_id', $userid)->first();

        if(empty($userlevel)){
            $userlevel = MD\UserLevelModel::Create(array("user_id"=>$userid, "level"=>1));
        }

        $gameuserheros = MD\Game13HeroModel::where('user_id', $userid)->get();
        $gameuserfuwensets = MD\Game13FuwenSetModel::where('user_id', $userid)->get();
        $fuwenct = 0;
        foreach($gameuserfuwensets as $fuwenset){
            $fuwenct ++;
            $fuwens = MD\Game13FuwenModel::where('fuwenset_id', $fuwenset->id)->orderBy('ind')->get();
            $fuwenset->fuwens = $fuwens;
        }
        $gametimes = MD\Game13UserModel::where('user_id', $userid)->count();
        $gamewintimes = MD\Game13UserModel::where('user_id', $userid)->where('iswin', 1)->count();

        $userlevelsset = MD\GameDataModel::where('name', 'userLevels')->where('diff', $userlevel->level)->first();

        $result = array("user_level"=>$userlevel->level, "user_point"=>intval($userlevelsset->data), "user_summon"=>intval($userlevelsset->extra_data1), 
            "user_game_time"=>$gametimes, "user_win"=>$gamewintimes, "game_heros"=>$gameuserheros, 
            "fuwen_sets"=>$gameuserfuwensets);

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function displayEntryUserInfo(){
        return view('game.game13.game13_entry_userinfo', ['game13Ctrl'=>$this]);
    }

    /**Fuwen handling */
    //Post
    public function changeNameForFuwenSet(){
        $fuwensetId = Input::get("fuwen_set_id");
        $fuwensetName = Input::get("fuwen_set_name");

        $fuwenset = MD\Game13FuwenSetModel::find(intval($fuwensetId));
        if(!empty($fuwenset)){
            $fuwenset->fuwenset_name = $fuwensetName;
            $fuwenset->save();
        }
    }
    //Post
    public function checkNameForFuwenSet(){
        // $userid = Auth::user->id;
        $userid = 1;
         
        $fuwensetName = Input::get("fuwen_set_name");

        $fuwenset = MD\Game13FuwenSetModel::where('user_id', $userid)->where('fuwenset_name', $fuwensetName)->find();

        return (empty($fuwenset)?"false":"true");
    }
    //Post
    public function editFuwen(){
        // $userid = Auth::user->id;
        $userid = 1;
    
        $fuwensetId = Input::get("fuwenset_id");
        $fuwensetName = Input::get("fuwen_set_name");

        if(empty($fuwensetId)){
            $fuwenset = MD\Game13FuwenSetModel::Create(array('user_id'=>$userid, 'fuwenset_name'=>$fuwensetName));
            $fuwensetId = $fuwenset->id;
        }else{
            $fuwensetId = intval($fuwensetId);
        }

        $fuwenid1 = Input::get("fuwen_id1");
        $ti1= intval(Input::get("ti1"));
        $fang1 = intval(Input::get("fang1"));
        $su1 = intval(Input::get("su1"));
        $gong1 = intval(Input::get("gong1"));
        $ji1 = intval(Input::get("ji1"));
        $ind1 = intval(Input::get("ind1"));

        $fuwenid2 = Input::get("fuwen_id2");
        $ti2= intval(Input::get("ti2"));
        $fang2 = intval(Input::get("fang2"));
        $su2 = intval(Input::get("su2"));
        $gong2 = intval(Input::get("gong2"));
        $ji2 = intval(Input::get("ji2"));
        $ind2 = intval(Input::get("ind2"));

        $fuwenid3 = Input::get("fuwen_id3");
        $ti3= intval(Input::get("ti3"));
        $fang3 = intval(Input::get("fang3"));
        $su3 = intval(Input::get("su3"));
        $gong3 = intval(Input::get("gong3"));
        $ji3 = intval(Input::get("ji3"));
        $ind3 = intval(Input::get("ind3"));

        $id1 = $this->editOneFuwen($fuwensetId, $fuwenid1, $ti1, $fang1, $su1, $gong1, $ji1, $ind1);
        $id2 = $this->editOneFuwen($fuwensetId, $fuwenid2, $ti2, $fang2, $su2, $gong2, $ji2, $ind2);
        $id3 = $this->editOneFuwen($fuwensetId, $fuwenid3, $ti3, $fang3, $su3, $gong3, $ji3, $ind3);

        return json_encode(array($id1, $id2, $id3));
    }

    private function editOneFuwen($fuwenSetId, $fuwenId, $ti, $fang, $su, $gong, $ji, $ind){
        if(empty($fuwenId)){
            $fuwen = new MD\Game13FuwenModel;
        }else{
            $fuwen = MD\Game13FuwenModel::find($fuwenId);
        }

        $fuwen->ti = $ti;
        $fuwen->fang = $fang;
        $fuwen->su = $su;
        $fuwen->gong = $gong;
        $fuwen->ji  = $ji;
        $fuwen->ind = $ind;
        $fuwen->fuwenset_id = $fuwenSetId;

        $fuwen->save();
        return $fuwen->id;
    }

    //get
    public function checkFuwenSetUsable($fuwensetId){
        // $userid = Auth::user->id;
        $userid = 1;
        $fuwens = MD\Game13FuwenModel::where('fuwenset_id', intval($fuwensetId))->get();

        $userlevel = MD\UserLevelModel::where('user_id', $userid)->first();

        if(empty($userlevel)){
            $userlevel = MD\UserLevelModel::Create(array("user_id"=>$userid, "level"=>1));
        }
        $userlevelsset = MD\GameDataModel::where('name', 'userLevels')->where('diff', $userlevel->level)->first();
        $points = intval($userlevelsset->data);

        foreach($fuwens as $onefuwen){
            $arr = array($onefuwen->ti, $onefuwen->fang, $onefuwen->su, $onefuwen->gong, $onefuwen->ji);
            foreach($arr as $onecl){
                $points -= $this->calculatePointFromFuwen($onecl);
            }
            if($points < 0){
                break;
            }
        }

        return (($points < 0)?"false":"true");
    }

    private function calculatePointFromFuwen($poi){
        $result = 0;

        $fuwenevl = self::$FUWEN_EVAL;

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

    /** Board */
    public function displayPlayBoard () {
        return view('game.game13.game13_compete_board', ['gameInfoUserId'=>1]);
    }

}
