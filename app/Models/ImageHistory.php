<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Config;
class ImageHistory extends Model
{
    protected $table = 't_image';
  	protected $guarded = ['id'];
    public function getAvatarAttribute(){
        if(count($this->_pathList)>0){
            return $this->_pathList[0];
        }
        else{
            return self::convertToImgPath('default.jpg');
        }
    }
    public function getCoverAttribute(){
        if(count($this->_pathList)>0){
            return $this->_pathList[0];
        }
        else{
            return self::convertToImgPath('default.jpg');
        }
    }
    public function getImagePathAttribute(){
        return self::convertToImgPath($this->name);
    }
    public function bind($imageName){
        $this->name = $imageName;
    }
    public static function convertToImgPath($imgName){
        //无前缀情况下，说明并不是完整的文件路径，说明文件在AliOSS中
        $prefix = Config::get('app.aliOSS')['showUrl'];
        return $prefix.'/'.$imgName;
    }
    public static function formatImgName($value, $format){
        if(strpos('@',$value) >= 0){
            return $value.'|'.$format;
        }else{
            return $vlaue.'@'.$format;
        }
    }
}

