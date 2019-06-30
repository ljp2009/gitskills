<?php
/* 任务过滤器
 * order: 当前的过滤条件
 * search：当前的查询条件
 * filter: 当前的过滤条件
 *
* */
 ?>
<div class="ym_filterbar">
    <div class="ym_filterbar_order" onclick="$.ymTaskFilterBar.show('order')">
        <i class="ymicon-order"></i>&nbsp;排序
    </div>
    <div class="ym_filterbar_filter" onclick="$.ymTaskFilterBar.show('filter')">
        <i class="ymicon-filter"></i>&nbsp;过滤
    </div>
<input id="orderField" type="hidden" value="" />
<input id="filterField" type="hidden" value="" />
</div>
<div class="ym_order_panel">
    <ul class="ym_order_list">
        <li value="publish_date">最新发布</li>
        <li value="delivery_date">即将到期</li>
        <li value="amount">金币最多</li>
    </ul>
</div>
<div class="ym_filter_panel">
    <div class="ym_filter_group" id="amountGroup">
        <span class="ym_first_column" value="0">金额不限</span>
        <span value="1">0~500</span>
        <span value="2">500~1000</span>
        <span value="3">1000~2000</span>
        <span value="4">2000以上</span>
    </div>
    <div class="ym_filter_group" id="modelGroup">
        <span class="ym_first_column" value="0">模式不限</span>
        <span value="1">PK模式</span>
        <span value="2">约定模式</span>
    </div>
    <div class="ym_filter_group" id="skillGroup">
        <span class="ym_first_column" value="0">技能不限</span>
        <span value="2001001">文案</span>
        <span value="2001002">编剧</span>
        <span value="2001003">音乐</span>
        <span value="2001004">配乐</span>
        <span value="2001005">画师</span>
        <span value="2001006">设计</span>
        <span value="2001007">剪辑</span>
        <span value="2001009">摄影</span>
        <span value="2001010">化妆</span>
        <span value="2001011">开发</span>
        <span value="2001012">策划</span>
        <span value="2001014">资料</span>
        <span value="2001008">Coser</span>
        <span value="2001013">PM</span>
    </div>
    <button type="button" class="ym_filter_btn" onclick="$.ymTaskFilterBar.submit()">确定</button>
</div>
<script type="text/javascript">
//taskfilter bar
jQuery.ymTaskFilterBar = {};
jQuery.ymTaskFilterBar.show = function(name){
    $('.ym_filterbar_active').removeClass('ym_filterbar_active');
    $('.ym_filterbar_'+name).addClass('ym_filterbar_active')
    $('.ym_order_panel').hide();
    $('.ym_filter_panel').hide();
    var $panel = $('.ym_'+name+'_panel');
    $panel.slideDown('fast');
    $panel.css('z-index','600');
    $.ymHeaderBar.hideIcon(true);
    $.ymShade.show('half', function(){
        $.ymTaskFilterBar.hide();
        $.ymHeaderBar.showIcon();
        $('.ym_filterbar_active').removeClass('ym_filterbar_active');
    });
};
jQuery.ymTaskFilterBar.hide = function(){
    $('.ym_order_panel').hide();
    $('.ym_filter_panel').hide();
};
jQuery.ymTaskFilterBar.filterChangeValue = function(type, value){
    var filterValue  = $('#filterField').val();
    filterValue = (filterValue=='')?'0-0-0':filterValue;
    var filterArr = filterValue.split('-');
    filterArr[type] = value;
    $('#filterField').val(filterArr[0]+'-'+filterArr[1]+'-'+filterArr[2]);
};
jQuery.ymTaskFilterBar.orderChangeValue = function(value){
    $('#orderField').val(value);
};
jQuery.ymTaskFilterBar.submit = function(){
    var orderValue = $('#orderField').val();
    var filterValue = $('#filterField').val();
    $.ymFunc.goTo('/taskhall/0/'+orderValue+'/'+filterValue);
};
jQuery.ymTaskFilterBar.bindClick = function(){
    $('#amountGroup').find('span').on('click',function(){
        $('#amountGroup').find('span').removeClass('ym_active');
        $(this).addClass('ym_active');
        $.ymTaskFilterBar.filterChangeValue(0,$(this).attr('value'));
    });
    $('#modelGroup').find('span').on('click',function(){
        $('#modelGroup').find('span').removeClass('ym_active');
        $(this).addClass('ym_active');
        $.ymTaskFilterBar.filterChangeValue(1,$(this).attr('value'));
    });
    $('#skillGroup').find('span').on('click',function(){
        $('#skillGroup').find('span').removeClass('ym_active');
        $(this).addClass('ym_active');
        $.ymTaskFilterBar.filterChangeValue(2,$(this).attr('value'));
    });
    $('.ym_order_list').find('li').on('click', function(){
        $('.ym_order_list').find('li').removeClass('ym_active');
        $(this).addClass('ym_active');
        $.ymTaskFilterBar.orderChangeValue($(this).attr('value'));
        $.ymTaskFilterBar.submit();
    });
};
jQuery.ymTaskFilterBar.bindValue = function(order, filter){
    $('#orderField').val(order);
    $('#filterField').val(filter);
    if(order == ''){ order = 'publish_date'; }
    if(filter == ''){ filter = '0-0-0'; }
    $('.ym_order_list').find('li[value="'+order+'"]').addClass('ym_active');
    var filterArr = filter.split('-');
    $('#amountGroup').find('span[value="'+filterArr[0]+'"]').addClass('ym_active');
    $('#modelGroup').find('span[value="'+filterArr[1]+'"]').addClass('ym_active');
    $('#skillGroup').find('span[value="'+filterArr[2]+'"]').addClass('ym_active');
};
jQuery.ymTaskFilterBar.getOrderValue = function(){
    return $('#orderField').val();
};
jQuery.ymTaskFilterBar.getFilterValue = function(){
    return $('#filterField').val();
};
$.ymTaskFilterBar.bindClick();
$.ymTaskFilterBar.bindValue("{{isset($order)?$order:'publish_date'}}","{{isset($filter)?$filter:'0-0-0'}}");
</script>

