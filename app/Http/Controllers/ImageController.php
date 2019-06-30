<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ImageHistory;
use App\Common\CommonUtils;
use Auth, Config, Input;
use \DateTime;
class ImageController extends Controller
{
    public function getHistory(){
        $arr = [];
        if(Auth::check()){
            $imgs = ImageHistory::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->take(3)->get();
            for($i=0; $i<count($imgs); $i++){
                array_push($arr, ['name'=>$imgs[$i]->name, 'url'=>$imgs[$i]->imagePath]);
            }
        }
        return response()->json($arr);
    }
    public function getGifHistory(){
        $arr = [];
        if(Auth::check()){
            $imgs = ImageHistory::where('user_id', Auth::user()->id)
                ->where('name', 'like', '%.gif%')
                ->orderBy('created_at','desc')->take(3)->get();
            for($i=0; $i<count($imgs); $i++){
                array_push($arr, ['name'=>$imgs[$i]->name, 'url'=>$imgs[$i]->imagePath]);
            }
        }
        return response()->json($arr);
    }
    public function getImgCallback($name,$imgName,$token){
        $img = new ImageHistory();
        $img->name = $imgName;
        $img->user_id = Auth::user()->id;
        $img->intro =CommonUtils::getUserIdFromToken($token);
        $img->save();
        return '<script type="text/javascript">window.parent.uploadImgCallBack("'
	.$name.'","'.$imgName.'","'.$token.'");</script>';
    }
    public function getFileCallback($name,$imgName,$token){
        return '<script type="text/javascript">window.parent.uploadAttachmentCallBack("'
	.$name.'","'.$imgName.'","'.$token.'");</script>';
    }

    public function getPolicy($fileName, $field){

        $id = Config::get('app.aliOSS')['ossAccessId'];
        $key = Config::get('app.aliOSS')['ossAccessKey']; 
        $host = Config::get('app.aliOSS')['postUrl'];
        $dir = $field.'/';

        $callback_param = array(
            //'callbackUrl'=>'http://test.umeiii.com/callback.php', 
            'callbackUrl'=>'http://test.umeiii.com/callback.php', 
            'callbackHost'=>'test.umeiii.com', 
            'callbackBody'=>'filename='.$fileName.'&field='.$field, 
            'callbackBodyType'=>"application/x-www-form-urlencoded");

        $callback_string = json_encode($callback_param);
        $base64_callback_body = base64_encode($callback_string);

        $now = time();
        $expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>20971520);
        $conditions[] = $condition; 

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
        $conditions[] = $start; 

        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['callback'] = $base64_callback_body;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;
        return response()->json($response);
    }

    private function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }

}
