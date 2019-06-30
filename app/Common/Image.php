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
class Image{
    //默认的图片类型
    const DEF_IMG_NONE = 0; //无
    const DEF_IMG_COVER = 1; //封面图片(纵向图片)
    const DEF_IMG_AVATAR = 2; //头像图片(正方形图片)
    const DEF_IMG_SCENE = 3; //场景图片(横向图片)

    //图片的存储方式
    const STORAGE_TYPE_NONE = 'none';//无图片
    const STORAGE_TYPE_LOCAL = 'local';//本地
    const STORAGE_TYPE_ALIOSS = 'ali';//阿里云
    const STORAGE_TYPE_URL = 'URL';//外部链接

    // 属性
    private $originName; //原始名称(数据库中)
    private $storageType; //图片存储方式
    private $imageDomain; //图片域名

    //阿里云相关的图片属性，以下属性当存储方式不是阿里云的时候无效
    private $fileName; //无格式图片名称（仅供阿里云存储时候使用）
    private $defFormat;//默认格式
    private $fileExt;//文件扩展名

    //设置属性的方法
    public function __get($name){
        return isset($this->$name)?$this->$name:null;
    }
    public function __set($name, $value){
        $this->$name = $value;
    }
    //检查图片是否存在
    public function checkSet(){
        return $this->storageType != self::STORAGE_TYPE_NONE;
    }

    /*
     * 获取图片的显示路径
     * defType: 当图片不存在时候的处理方式
     * format: 图片格式化的样式（仅真对阿里云存储）
     * fileType: 输出的文件类型（仅真对阿里云存储）
     * */
    public function getPath($defType=self::DEF_IMG_COVER, $format='', $fileType=''){
        switch($this->storageType){
        case self::STORAGE_TYPE_URL:
            return $this->originName;
        case self::STORAGE_TYPE_NONE:
            $fileName = self::getDefaultImgPath($defType); 
            return $this->getAliImgPath($fileName, $format, $fileType);
        case self::STORAGE_TYPE_ALIOSS:
            return $this->getAliImgPath($this->fileName, $format, $fileType);
        default:
            return '';
        }
    }
    //旧版直接format图片，此版本关闭alioss对格式化字符串的支持后会失效
    private function getAliImgPath($fileName, $format, $fileType){
        $urlPerfix = Config::get('app.aliOSS')['showUrl'];
        if(substr($urlPerfix, -1) != '/') $urlPerfix .= '/';
        $url = $urlPerfix.$fileName;
        if(!empty($format) && !empty($this->defFormat)){
            $allFormat = $this->defFormat.'|'.$format;
        }else{
            $allFormat = $this->defFormat.$format;
        }
        $ext = $this->fileExt;
        if(!empty($fileType)) $ext = $fileType;
        if(!empty($allFormat)){
            $url .= ('@'.$allFormat.'.'.$ext);
        }else{
            $url .= ('@'.'.'.$ext);
        }
        return $url;
    }
    //新版本，转化为Style模式，不需要修改前端，直接通过前端输入的格式匹配到预设的样式
    private function getAliImgPathNew($fileName, $format, $fileType){
        $urlPerfix = Config::get('app.aliOSS')['showUrl'];
        if(substr($urlPerfix, -1) != '/') $urlPerfix .= '/';
        $url = $urlPerfix.$fileName;
        if(!empty($format) && !empty($this->defFormat)){
            $allFormat = $this->defFormat.'|'.$format;
        }else{
            $allFormat = $this->defFormat.$format;
        }
        $ext = $this->fileExt;
        if(!empty($fileType)) $ext = $fileType;
        if(!empty($allFormat)){
            $url .= ('@'.$allFormat.'.'.$ext);
        }else{
            $url .= ('@'.'.'.$ext);
        }
        return $url;
    }
    private function convertFormatToStyle($format){
        //包含默认样式
        $defF = $this->defFormat;
        $addF = $format;
        if(!empty($format) && !empty($this->defFormat)){
            $allFormat = $this->defFormat.'|'.$format;
        }else{
            //不包含默认样式
            $allFormat = $this->defFormat.$format;
        }

    }



    public function __tostring(){
        return $this->originName;
    }
    //获取原始图片值
    public function getOriginName(){
        return $this->originName;
    }
    /*
     * Image的静态工厂，用于生成Image对象
     * */
    public static function makeImage($value){
        //去除无效的字段
        $fileName =trim(str_replace(';','',$value));
        //判断存储类型
        $type = self::getStorageType($fileName);
        switch($type){
        case self::STORAGE_TYPE_NONE:
            return self::makeEmptyImage();
        case self::STORAGE_TYPE_ALIOSS:
            return self::makeAliImage($fileName);
        case self::STORAGE_TYPE_URL:
            return self::makeUrlImage($fileName);
        default:
            return null;
        }
    }
    /*
     * 针对存储多个image数据的静态工厂
     * */
    public static function makeImages($value){
        $fileNames = explode(';', $value);
        $arr = [];
        foreach($fileNames as $fileName){
            if(empty($fileName)) continue;
            array_push($arr, self::makeImage($fileName));
        }
        return $arr;
    }
    /*
     * 获取提交图片到阿里云的必要参数
     * * */
    public static function getUploadAliImageParams($seed='default'){
		$arr['accessId'] = CommonUtils::getAliOssAccessId();
		$arr['ossAccessKeyId'] =$arr['accessId'];//use for admin
		$arr['policy'] = CommonUtils::getAliOSSPostPolicy();
		$arr['signature'] = Commonutils::getAliOSSSignature($arr['policy']);
		$arr['postUrl'] = CommonUtils::getAliUrl('post');
		$arr['showUrl'] = CommonUtils::getAliUrl('show');
		$arr['nameSeed'] = Commonutils::createRandomId($seed);
		$arr['token'] = Commonutils::createUserToken(Auth::check()?Auth::user()->id:0);
        return $arr;
    }

    //私有的static方法
    private static function getStorageType($fileName){
        //空数据
        if(empty($fileName)){
            return self::STORAGE_TYPE_NONE;
        }
        //外部存储
        if(substr($fileName,0,7) == 'http://'){
            return self::STORAGE_TYPE_URL;
        }
        //阿里云存储
        return self::STORAGE_TYPE_ALIOSS;
    }

    //生成一个空Image对象
    public static function makeEmptyImage(){
            $img = new Image();
            $img->originName = '';
            $img->storageType = self::STORAGE_TYPE_NONE;
            return $img;
    }
    //根据AliOSS规则解析图片名称
    private static function makeAliImage($originFileName){
        $img = new Image;
        $img->originName = $originFileName;
        $img->storageType = self::STORAGE_TYPE_ALIOSS;
        $atIndex =  strpos($originFileName, '@');
        //图片已经被格式化了
        if($atIndex){
            $img->fileName = substr($originFileName, 0, $atIndex);
            $img->defFormat = substr($originFileName, $atIndex+1);
            $img->fileExt =  self::getFileExt($img->fileName);
        }
        else{
            $img->fileName = $originFileName;
            $img->defFormat = '';
            $img->fileExt =  self::getFileExt($img->fileName);
        }
        return $img;
    }
    //根据url生产image对象，进当value是url时候有效（外部图片)
    private static function MakeUrlImage($originFileName){
        $img = new Image;
        $img->originName = $originFileName;
        $img->storageType = self::STORAGE_TYPE_URL;
        return $img;
    }
    private static function getFileExt($fileName){
        if(strpos($fileName, '.gif')>0) return 'gif';
        if(strpos($fileName, '.png')>0) return 'png';
        return 'jpg';
    }
    private static function getDefaultImgPath($defType){
        switch($defType){
        case self::DEF_IMG_COVER:
            return 'default.jpg';
        case self::DEF_IMG_AVATAR:
            return 'test/default.jpg';
        case self::DEF_IMG_SCENE:
            return 'defalut.jpg';
        default:
            return '';
        }
    }
}
