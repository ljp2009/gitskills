<?php
/*
 * 图片类，用来处理图片
 * 系统中输出的图片都使用这个类
 * 目前这个类仅处理来自AliOss的图片数据，数据中存储的是图片的名字
* */
namespace App\Common;

use App\Common\CommonUtils;
use Auth;
use Config;
class Text{
    public static function shotText($value, $length){
       return mb_substr($value, 0, $length,'utf-8');
       $str_cut = $value;
       if (strlen($str_cut) > $length)
       {
           for($i=0; $i < $length; $i++)
               if (ord($str_cut[$i]) > 128)  $i++;
           $str_cut = substr($str_cut,0,$i)."..";
       }
       return $str_cut;
    }
}
