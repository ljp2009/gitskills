<?php

function Pe($data) {
    echo '<pre>';
    print_r($data);
    echo '<pre>';
    exit;
}

function P($data) {
    echo '<pre>';
    print_r($data);
    echo '<pre>';
}

function Ped() {
    $array = debug_backtrace();
    $html = '';
    unset($array [0]);
    foreach ($array as $row) {
        $html .= $row ['file'] . ':' . $row ['line'] . '行,调用方法:' . $row ['function'] . "<p>\n";
    }
    Pe($html);
}

function f_log($key,$info){
    if(is_array($info) || is_object($info)){
        $info = json_encode($info);
    }
    \Log::info('key='.$key.',info='.$info);
}

//检测消耗时间 单位毫秒

function calc_used_time() {
    return (microtime(true) - LARAVEL_START) * 100;
}

/**
 * 返回格式format
 * @param array|string $data 返回结果集
 * @param int $status   状态值. 0失败, 1成功
 * @param string $message   消息
 * @param array $option 设置返回消息头等
 * @return \Illuminate\Http\JsonResponse    json
 * @author zhaoduo
 * @date 2016-05-01 14:47:42
 */
function send_back($data = '', $status = 1, $message = '', $option = []) {
    $http_status = 200;
    $header = [];
    if ($option) {
        isset($option['status']) && ($http_status = $option['status']);
        isset($option['header']) && ($header = $option['header']);
    }
    return response()->json(['status' => $status, 'message' => $message, 'data' => $data], $http_status, $header);
}

/**
 * @param $data 数据
 * @param string $secret 密钥
 * @param bool $undate 是否加时间加密
 * @return string sn
 * @date 2016-05-09 20:26:33
 */
function create_sn($data, $secret = '', $undate = false) {
    $debug = isset($data['debug']) ? $data['debug'] : false;
    unset($data ['sn'], $data['debug']);
    ksort($data);
    $str = '';
    foreach ($data as $key => $value) {
        $str .="&$key=$value";
    }
    $str = trim($str, "&") . $secret;
    if (!$undate) {
        $str .= date('Y-m-d');
    }
    if ($debug && $debug == $secret) {
        echo "md5 str:", $str;
        echo '<br>';
    }
    return strtolower(md5($str));
}
function noBackUrl($url, $backStep=2){
    return '/loading.php?bs='.$backStep.'&url='.$url;
}
function errorPage($type = 0, $content = null){
    if($type == 0){
        return view('errors.noauthority', ['content'=>$content]);
    }
    return view('errors.noauthority', ['content'=>$content]);
}
/**
 * @param $data 数据
 * @param string $secret  密钥
 * @param bool $undate  是否加时间
 * @return bool 校验结果
 * @date 2016-05-09 20:26:47
 */
function check_sn($data, $secret = '', $undate = false) {
    $user_info = session('user_info');
    if (isset($data['debug']) && $data['debug'] == 1) {
        $user_info = session('user_info');
        if (in_array($user_info['telephone'], config('auth.admin'))) {
            return true;
        }
    }
    if (!is_array($data) || !isset($data ['sn'])) {
        return false;
    }
    $sn = strtolower($data ['sn']);
    $chsn = create_sn($data, $secret, $undate);
    return $sn == $chsn;
}

if(!function_exists('create_token')) {
    /**
     * 创建token
     * @param $data
     * @param string $secret
     * @return string
     * @author zhaoduo
     * @date 2016-07-20 13:59:12
     */
    function create_token($data, $secret = '') {
        unset($data['token']);
        ksort($data);
        $str = urldecode(http_build_query($data, '', '&', 2)).$secret;
        return md5($str);
    }
}


if(!function_exists('check_token')) {
    /**
     * 校验token
     * @param $data
     * @param string $secret
     * @return bool
     * @author zhaoduo
     * @date 2016-07-20 13:59:55
     */
    function check_token($data, $secret = '') {
        return $data['token'] == create_token($data, $secret);
    }
}

if(!function_exists('create_token_query')) {
    /**
     * @param $data
     * @param $secret
     * @return string
     * @author zhaoduo
     * @date 2016-07-20 14:16:52
     */
    function create_token_query($data, $secret) {
        $data['token'] = create_token($data, $secret);
        return http_build_query($data);
    }
}



/**
 * 生成二维码
 * @param $info 二维码信息
 * @param int $size 图片大小
 * @return mixed base64字符串
 * @author zhaoduo
 * @date 2016-05-20 13:39:30
 */
function generate_qrcode($info, $size = 200) {
    return QrCode::errorCorrection('L') //容错
                    ->format('png') //格式
                    ->size($size)   //大小
//            ->color(244,202,44) //二维码颜色
//            ->merge(public_path('assets/image/car_1.png'), 0.3, true)   //合并图片
                    ->generate($info);
}

/**
 * 过滤掉不属于table的值
 * @param $table table名字或者model对象
 * @param $data 数据
 * @return array 表中Column对应的数据
 * @date 2016-05-10 20:23:52
 */
function get_table_data($table, $data) {
    if (is_object($table)) {
        $table = $table->getTable();
    }
    $rules = \Schema::getColumnListing($table);
    $need_data = array();
    foreach ($data as $key => $val) {
        if (in_array($key, $rules)) {
            $need_data[$key] = $val;
        }
    }
    return $need_data;
}

/**
 * 日志
 * @param $key 日志key
 * @param $data 日志数据, [] | string
 * @param string $type
 * @date 2016-05-10 20:30:36
 */
function w_log($key, $data, $type = '') {
    $key = 'qd_' . $key;
    dispatch(new App\Jobs\WLog(['key' => $key, 'data' => $data, 'type' => $type]));
}

/**
 * 获取指定数据
 * @param type $the指定的模板数据
 * @param type $data所有数据
 * @return type需要的数据
 * @author wuhaoliang
 */
function get_the_data($the, $data) {
    $arr = array();
    foreach ($the as $val) {
        if (isset($data[$val])) {
            $arr[$val] = $data[$val];
        }
    }
    return $arr;
}
/**
 * 记录执行的sql
 */
function w_sql($key='no_key') {
    $queries = DB::getQueryLog();
    foreach ($queries as $key => $val) {
        foreach ($val['bindings'] as $a_val) {
            $queries[$key]['query'] = str_replace_first('?', $a_val, $queries[$key]['query']);
        }
        \Log::info(json_encode(['key'=>$key,'sql'=>$queries[$key]['query'],'times'=>$val['time']]));
    }
}

function todate($time){
    if($time == 0){
        return '--';
    }
    return date('Y-m-d H:i',$time);
}
//获取锁防止并发！
function get_lock($key){
    $val = \Predis::get($key);
    if($val){
        return false;
    }else{
        \Predis::setex($key,1,1);
        return true;
    }
}
