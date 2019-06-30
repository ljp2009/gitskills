<?php
namespace App\Common;

use App\Models as MD;
use Config;
class CommonUtils{
	private static $FULL_SPECHARS = '！￥……—（）——；‘“：”，。？〈〉《》、';

	public static function isSpecialChar($char){
		if(strlen($char)==0){
			return true;
		}else if(mb_strlen($char)>0){
			$char = mb_substr($char, 0, 1);
		}
		if(preg_match("/[a-zA-Z0-9]/", $char)){
			return false;
		}
		if(preg_match("/[\x7f-\xff]/", $char)){
			if(mb_strpos(self::$FULL_SPECHARS, $char)===false){
				return false;
			}
		}
		return true;
	}

	public static function getMD5Value($str){
		return md5($str);
	}

	public static function cutString($str, $length = 0, $charset='utf-8', $append = true){
	    $str = trim($str);
	    $strlength = (function_exists('mb_substr')?mb_strlen($str):strlen($str));

	    if ($length == 0 || $length >= $strlength){
	        return array('content'=>$str, 'canExpand'=>false);
	    }elseif ($length < 0) {
	        $length = $strlength + $length;
	        if ($length < 0) {
	            $length = $strlength;
	        }
	    }

	    if (function_exists('mb_substr')){
	        $newstr = mb_substr($str, 0, $length, $charset);
	    }elseif (function_exists('iconv_substr')){
	        $newstr = iconv_substr($str, 0, $length, $charset);
	    }else{
	        //$newstr = trim_right(substr($str, 0, $length));
	        $newstr = substr($str, 0, $length);
	    }

	    if ($append && $str != $newstr){
	        $newstr .= '...';
	    }

	    return array('content'=>$newstr, 'canExpand'=>true);
	}

	public static function getValueFromArray($arr, $key, $defaultValue=''){
		if (array_key_exists($key, $arr)){
			return $arr[$key];
		}
		return $defaultValue;
	}

	public static function handleListDetails($modelbuilder, $from, $to, $desc = false, $orderField='created_at'){
		if($desc)
			$modelbuilder = $modelbuilder->orderBy($orderField, "desc");
		else
			$modelbuilder = $modelbuilder->orderBy($orderField);
		if($from > 0)
	    	return $modelbuilder->skip($from)->take($to - $from + 1)->get();
	   	else
	   		return $modelbuilder->take($to - $from + 1)->get();
	}

	public static function escapeSpecialChars($text){
		return htmlspecialchars($text);
	}

	public static function evalPics($pics){
		return $pics;
		/*
		暂时不需要处理PIC输入
		if(is_array($pics)){
			if(sizeof($pics)==0){
				return '';
			}else if(sizeof($pics)==1){
				return $pics[0];
			}else{
				$str = '';
				foreach($pics as $p){
					$str = $str.$p.';';
				}
				return $str;
			}
		}
		return $pics;*/
	}

	public static function getPics($pics){
		if(strpos($pics, ';')){
			return explode($pics);
		}else{
			$res = array();
			if(strlen(trim($pics,' '))>0){
				array_push($res, $pics);
			}
			return $res;
		}

	}
	public static function generatePicName($modelName, $type,$userid,$pid,$index=0)
	{
		$arr = array([
			CommonUtils::getModelCode($modelName),
			CommonUtils::gettype($type),
			$userid,
			$pid,
			$index
			]);
		return implode($array, '-');
	}

	public static function createRandomId($name='') {
		$time = time();
		return $name.'-'.$time.'-'.self::createRandomStr(5);
	}
	public static function createRandomStr($length = 16) {
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    $str = "";
	    for ($i = 0; $i < $length; $i++) {
	      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	    }
	    return $str;
	}

	public static function getImagePaths($modelName,$type,$images)
	{
        if(is_null($images)) return array();
		$arr = explode(';',$images);
        $resArr = [];
		foreach ($arr as $key => $image) {
            if(trim($image) == '') Continue;
            array_push($resArr,CommonUtils::GetImagePath($modelName,$type,$image));
		}
		return $resArr;
	}
	public static function GetImagePath($modelName,$type, $imgAttr)
	{

		$image = str_replace(';', '', $imgAttr);//去掉储存记录中的分号
        if($image == ""){
            if($modelName == 'user' && $type = 'avatar'){
                 $image = "test/default.jpg";//设置默认的图片
            }else{
                 $image = "default.jpg";//设置默认的图片
            }
        }
		return self::getAliUrl('show').'/'.$image;
	}

	private static function getModelCode($modelName)
	{
		$code = '';
		switch ($modelName) {
			case 'ip':
				$code = '9001';
				break;
			case 'user':
				$code = '9002';
				break;
			case 'role':
				$code = '9011';
				break;
			case 'scene':
				$code = '9012';
				break;
			case 'skill':
				$code = '9111';
				break;
			case 'production':
				$code = '9021';
				break;
			default:
				$code = '9000';
				break;
		}
		return $code;
	}
	private static function getImgTypeCode($type)
	{
		$code = "";
		switch ($type) {
			case 'avatar':
				$code = '001';
				break;
			case 'background':
				$code = '002';
				break;
			case 'cover':
				$code = '003';
				break;
			case 'header':
				$code = '004';
				break;
			case 'image':
				$code = '101';
				break;
			default:
				$code = '000';
				break;
		}
		return $code;
	}

	/**
     * 计算俩时间戳相隔天数
     *
     * @param unknown $time1
     * @param unknown $time2
     * @return number
     */
    public static function calculateDays($time1, $time2)
    {
        if (empty($time1) || empty($time2)) {
            return;
        }
        $datetime1 = date_create(date('Ymd', intval($time1)));
        $datetime2 = date_create(date('Ymd', intval($time2)));
        $interval  = date_diff($datetime1, $datetime2);
        $result    = $interval->format('%a');
        return $result;
    }

    /**
     * 时间格式
     * @param unknown $time1
     * @param unknown $time2
     * @return void unknown
     */
    public static function dateFormatting($time1)
    {
        if (empty($time1)) {
            return;
        }
        $time2 = time();
        if ($time1 <= $time2) {
            $date = self::calculateDays($time1, $time2);
            if ($date == '0') {
                return date('H:i', $time1);
            } else {
                return date('Y-m-d H:i', $time1);
            }
        }

    }

    /**
     * 返回ajax数据
     * @param unknown $code
     * @param string $msg
     * @param unknown $parm
     */
    public static function ajaxReturn($code, $msg = '', $parm = array())
    {
        $data         = array();
        $data['code'] = $code;
        $data['msg']  = $msg;
        $data['parm'] = $parm;
        return json_encode($data);
    }
    /**
     * 生成AliOSS中POST的Policy
     * @param int $expireMinute
     * @param int $maxSize 默认10M
     * @return String
     */
    public static function getAliOSSPostPolicy($expireMinute = 10, $maxSize = 10485760)
    {
        $time       = mktime(date('H'), date('i') + $expireMinute, date('s'), date('m'), date('d'), date('Y'));
        $expiration = gmdate("Y-m-d\TH:i:s\Z", $time);
        $policy     = base64_encode('{"expiration": "' . $expiration . '","conditions": [["content-length-range", 0, ' . $maxSize . ']]}');
        return $policy;
    }

    /**1
     * 生成AliOSS中的签名
     * @param String $content
     * @param String $ossAccessKey
     * @return String
     */
    public static function getAliOSSSignature($content)
    {
        $signature = base64_encode(hash_hmac('sha1', $content,
            Config::get('app.aliOSS')['ossAccessKey'], true));
        return $signature;
    }

    public static function getAliOssAccessId()
    {
        return Config::get('app.aliOSS')['ossAccessId'];
    }
    public static function getAliUrl($type)
    {
        if ($type == 'show') {
            return Config::get('app.aliOSS')['showUrl'];
        } else if ($type == 'post') {
            return Config::get('app.aliOSS')['postUrl'];
        } else {
            return '';
        }

    }

    /**
     *
     * @param 发给谁 $to
     * @param 发信息的用户 $from
     * @param string $startMsg 变量之前的内容，可以为空
     * @param unknown $linkMsg 变量内容
     * @param string $endMsg 变量之后的内容，可以为空
     * @param unknown $link 链接的路由
     * @param string $type
     */
    public static function createPrivateLetter($to, $from, $msg, $variable = array(), $type = "system")
    {
        $data             = [];
        $data['user_id']  = $to;
        $data['send_id']  = $from;
        $data['type']     = $type;
        $data['msg']      = $msg;
        $data['variable'] = json_encode($variable);
        MD\UserPrivateLetter::create(
            ['user_id' => $data['user_id'], 'send_id' => $data['send_id'], 'type' => $data['type'], 'msg' => $data['msg'], 'variable' => $data['variable']]
        );
    }
    public static function createUserToken($userId){
        return $userId;
    }
    public static function getUserIdFromToken($token){
       return $token;
    }

    /**
     * 系统任务消息配置
     * @return multitype:string
     */
    public static $userPrivateTaskLang =
        [
        'task' => '您有一条来自%s的任务邀请，点击%s查看详情%s',
        'singlevote' => '投票开始了，点击%s查看详情%s',
        'multiplevote' => '第二轮投票开始了，点击%s查看详情%s',
    ];

    public static function readPrivateLetter($msg, $type = 'personal', $variable = '')
    {
        if ($type == 'personal') {
            return $msg;
        } else if ((($type == 'system') || ($type == 'invite')) && $variable) {
            $variableArr = json_decode($variable, true);
            $sysmsg      = self::$userPrivateTaskLang[$msg];
            switch ($msg) {
                case 'task': //任务
                    $msg = sprintf($sysmsg, $variableArr['display_name'], '<a href="' . $variableArr['link'] . '">', '</a>');
                    break;
                case 'singlevote':
                	$msg = sprintf($sysmsg, '<a href="' . $variableArr['link'] . '">', '</a>');
                	break;
                case 'multiplevote':
                	$msg = sprintf($sysmsg, '<a href="' . $variableArr['link'] . '">', '</a>');
                	break;
            }
            return $msg;
        }
        return $msg;
    }
    /**
     * 图文混排取第一张图片和第一段文字
     * @param unknown $json
     * @return Ambigous <string, unknown>
     */
    public static function explainJsonReturnFirstImageText($json){
    	$intro = json_decode($json,true);
    	$result['intro'] = '';
    	foreach ($intro as $k=>$val){
    		if($val['type']=='text'){
    			$result['intro'] = $val['text'];
    			break;
    		}
    	}
    	return $result;
    }
}
