<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;
class SceneController extends Controller
{
	public function showList($page, $pid)
	{
		return view('detaillist', array('pid'=>$pid,'page'=>$page));
	}

	public function loadListData($from, $to, $pid)
	{
		$models = array();
		for ($i=$from; $i <=$to ; $i++) { 

			if($i > 29) break;
			array_push($models, 
				array('img'  => '/pic/pic_scene'.(($i%4)+1).'_md',
					  'text' => '这里是一个描述'.$i)
			); 
		} 
		return view('partview.detailitem', array('models'=>$models));
	}
}
?>