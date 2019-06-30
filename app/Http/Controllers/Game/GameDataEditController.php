<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\GameDataModel as MD;

class GameDataEditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = MD::orderBy('name_description')->get();
        return view('game.game_data_list', array('list'=>$results));
    }

}
