<?php

namespace App\Http\Controllers\Common;

use App\Common\CommonUtils;
use App\Http\Controllers\Controller;

class CommonFilterController extends Controller
{
    /**
     * 适用于列表的排序筛选  路由里面要配置/{order}/{search} 这两个参数
     *
     * 1.排序的li的a里面添加data-order，值为要排序的字段
     * 2.筛选的li里指定data-search-type，值为搜索的类型
     * scope：范围查询，相应的a里面指定data-search，值为空是不限制,例如data-search="0-500"，
     * equal：等于查询,例如data-search="1"，具体值
     * like:模糊查询,
     *
     * $ORDER $SEARCH 排序或筛选
     */

    public static $ORDER = 'order';
    public static $SEARCH = 'search';
    public static $searchFilter = '<nav class="search-menu"><ul id="collapse-filter" class="am-nav am-collapse">';
    public static function filter()
    {
        $filterHeader = '<div class="am-g ym-filter-bar" style="background-color:#fff">'.
            '<div class="am-u-sm-6 ym-left" data-am-collapse="{target: \'#collapse-nav\'}"><i class="ymicon-puzzle"></i><label>排序</label></div>'.
            '<div class="am-u-sm-6" data-am-collapse="{target: \'#collapse-filter\'}"><i class="ymicon-puzzle-o"></i><label>筛选</label></div>';
        echo $filterHeader;
    }

    public static function order($orderField, $orderLabel, $route, $search, $nowOrder)
    {
        $order = '<nav class="search-menu"><ul id="collapse-nav" class="am-nav am-collapse ym-filter-panel">';
        foreach ($orderField as $k => $v) {
            $class = '';
            if ($nowOrder == $v) {
                $class = 'ym-active';
            }
            $order .= '<li class="ym-filter-panel-item"><a data-order="' . $v . '" class="' . $class . '" href="' . $route . '/' . $v . '/' . $search . '">' . $orderLabel[$k] . '</a></li>';
        }
        $order .= '</ul></nav>';
        echo $order;
    }

    public static function search($searchField, $searchValue, $searchLabel)
    {
//         foreach ($searchField as $key=>$val){
        $search = '<li data-search-field="' . $searchField . '" class="price">';
        foreach ($searchValue as $k => $v) {
            if ($v === '') {
                $search .= '<a class="no-limit" data-search="' . $v . '">' . $searchLabel[$k] . '</a>';
            } else {
                $search .= '<a data-search="' . $v . '">' . $searchLabel[$k] . '</a>';
            }

        }
        $search .= '</li>';
//         }
        self::$searchFilter .= $search;
//         $search .= '</ul></nav>';
        //         echo $search;
    }
    public static function end()
    {

        echo self::$searchFilter . '<li class="confirm"><button type="button" id="confirm-search" class="am-btn am-btn-default am-btn-primary">确定</button></li></ul></nav></div><div class="bg-wrap"></div>

				';
    }

    public static function addFilter($arr)
    {
        self::routeFilter($arr['type'], //类型
            CommonUtils::getValueFromArray($arr, 'orderField', array()), //排序字段
            CommonUtils::getValueFromArray($arr, 'orderLabel', array()), //显示的文本
            CommonUtils::getValueFromArray($arr, 'searchField'), //搜索字段
            CommonUtils::getValueFromArray($arr, 'searchValue', array()), //字段值不限制可以填''空,以数组方式传值
            CommonUtils::getValueFromArray($arr, 'searchLabel', array()), //显示的值
            CommonUtils::getValueFromArray($arr, 'search'), //控制器接收的{search}的值
            CommonUtils::getValueFromArray($arr, 'route'), //排序的基本路由，不用带后边的排序和搜索参数
            CommonUtils::getValueFromArray($arr, 'nowOrder') //当前排序字段
        );
    }

    private static function routeFilter($type = self::ORDER, $orderField, $orderLabel, $searchField, $searchValue = array(), $searchLabel = array(), $search = '', $route = '', $nowOrder = '')
    {
        switch ($type) {
            case 'order':
                self::order($orderField, $orderLabel, $route, $search, $nowOrder);
                break;
            case 'search':
                self::search($searchField, $searchValue, $searchLabel);
                break;
            default:
                break;
        }
    }
}
