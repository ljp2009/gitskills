<?php
$get = [
    'status'=>'ok',
    'filename'=>$_POST['filename'],
    'field'=>$_POST['field'],
    'url'=>'http://img.umeiii.com/'.$_POST['field'].'/'.$_POST['filename'],
];
$res = json_encode($get);
header('HTTP/1.1 200 OK');  
header('Content-Type', 'application/json');  
header('Content-Length', strlen($res));  
echo $res;
?>
