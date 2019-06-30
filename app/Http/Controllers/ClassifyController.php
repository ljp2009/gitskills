<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SysTag;
use App\Models\Ip;


/**
 * 分类
 * @author admin
 *
 */
class ClassifyController extends Controller{

	//分类画面
	public function show(){
		// SysTag::where('depend', 'cartoon')
		$cartoons = SysTag::where('depend', 'cartoon')->orderBy('id', 'ASC')->get();
		$storys   = SysTag::where('depend', 'story')->orderBy('id', 'ASC')->get();
		$lights   = SysTag::where('depend', 'light')->orderBy('id', 'ASC')->get();
		$games    = SysTag::where('depend', 'game')->orderBy('id', 'ASC')->get();

		return view('classifyinfo',array('cartoons'=>$cartoons, 'storys'=>$storys, 'lights'=>$lights, 'games'=>$games));
	}
	//根据分类查询
	public function search($key, $name, $page)
    {
        return view('searchlist', array('type' => 'classify/'.$key, 'page' => $page, 'listName' => $name));
    }
	
	public function searchData($key, $name, $from, $to)
    {
        $objs = Ip::where('type', '=', $key)->where('tags', 'like', '%' . $name . '%')->orderBy('updated_at', 'DECS')->skip($from)
            ->take($to - $from + 1)->get();
        return view('partview.searchresitem', array('models' => self::getSearchRes($objs)));
    }
    private static function getSearchRes($valueArr)
    {
        $res = array();
        foreach ($valueArr as $value) {
            array_push($res, [
                        'image' => $value->cover,
                        'name'  => $value->name,
                        'type'  => $value->ipTypeLabel,
                        'intro' => $value->shotIntro,
                        'url'   => '/ip/' . $value->id,
                        'tags'  => array($value->ipTypeLabel),
                    ]);
        }
        return $res;
    }
}


?>