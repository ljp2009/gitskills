<?php
/*
 * 枚举类，为了提示访问效率，小的枚举库都定义在枚举类中
* */
namespace App\Common;

use App\Common\Enums;
use Auth;
use Config;
class Enums{
    //---- Static Attribute---
    public static $enumDatabase = [];
    //---- Attributes ----
    private $attrs = [
        'type'   => '',
        'code'   => '',
        'text'   => '',
        'parent' => ''];
    
    public function __get($name){
        if(array_key_exists($name, $this->attrs)){
            return $this->attrs[$name];
        }
        return '';
    }
    public function __set($name, $value){
        if(array_key_exists($name, $this->attrs)){
            $this->attrs[$name] = $value;
        }
    }
    public function __tostring(){
        return $this->attrs['text'];
    }

    //---- Attributes ----
    public static function createByCode($type, $code){
        if(!array_key_exists($type, self::$enumDatabase)){
            self::init($type);   
        }
        if(empty($code)){
            $code = self::$enumDatabase[$type]['code'].'00';
        }
        $enum = new Enums();
        $enum->code = $code;
        $enum->type = $type;
        $enum->text = self::$enumDatabase[$type]['items'][$code];

        if(array_key_exists('parent', self::$enumDatabase[$type])){

        };
        return $enum;
    }
    public static function getItems($type){
        if(!array_key_exists($type, self::$enumDatabase)){
            self::init($type);
        }
        return self::$enumDatabase[$type]['items'];
    }
    public static function Init($type){
        switch($type){
        case 'sex': // 性别
            self::$enumDatabase[$type] = [
                'code'  => '20002',
                'items' => [
                    '2000200' => '保密',
                    '2000201' => '男',
                    '2000202' => '女'
                ]
            ];  
            return true;
        case 'marriage':
            self::$enumDatabase[$type] = [
                'code'  => '20003',
                'items' => [
                    '2000300' => '保密',
                    '2000301' => '未婚',
                    '2000302' => '已婚',
                    '2000303' => '离异'
                ]
            ];  
            return true;
        case 'education': // 教育
            self::$enumDatabase[$type] = [
                'code'  => '20004',
                'items' => [
                    '2000400' => '保密',
                    '2000401' => '小学',
                    '2000402' => '初中',
                    '2000403' => '中转',
                    '2000404' => '高中',
                    '2000405' => '大专',
                    '2000406' => '本科',
                    '2000407' => '硕士',
                    '2000208' => '博士'
                ]
            ];  
            return true;
        case 'job':
            self::$enumDatabase[$type] = [
                'code'  => '20005',
                'items' => [
                    '2000500' => '保密',
                    '2000501' => '学生',
                    '2000502' => '企业职员',
                    '2000503' => '自由职业',
                    '2000504' => '个体经营',
                    '2000505' => '公职人员'
                ]
            ];  
            return true;
        }
        return false;
    }
}
