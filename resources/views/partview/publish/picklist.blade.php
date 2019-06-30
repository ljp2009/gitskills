<div class="ym_backheader">
    <ul class="am-avg-sm-3">
        <li style="text-align:left" onclick="back()">
            <i class="am-icon-angle-left"></i>
            <span class="ym_backheader_btn">&nbsp;&nbsp;返回</span>
        </li>
        <li style="text-align:center"><span class="ym_backheader_title">{{$titleLabel}}</span>
        </li>
        <li style="text-align:right" onclick="save(getSelectorValue(),'{{$partName}}')">
            <span class="ym_backheader_btn">保存&nbsp;&nbsp;</span>
            <i class="am-icon-save"></i>
        </li>
    </ul>
</div>
<div class="ym_taskmg_desc">
    <i class="am-icon-info-circle"></i>&nbsp;&nbsp;{{$infoLabel}}
</div>
<div class='ym_taskmg_selector'>
<input type="hidden" id="ym_value_selector_key" value ="" />
<input type="hidden" id="ym_value_selector_value" value ="" />
<ul class="ym_taskmg_selector_list am-avg-sm-{{isset($columns)?$columns:1}}"></ul>
<div>
<script type="text/javascript">
function selectorItemClick(key, value){
    $('#ym_value_selector_key').val(key);
    $('#ym_value_selector_value').val(value);
    var $clickLi = $('#vl_'+key);
    $('.ym_taskmg_selector_icon').remove();
    $clickLi.append('<i class="ym_taskmg_selector_icon am-icon-check"></i>');
}
function getSelectorValue(){
    var key = $('#ym_value_selector_key').val();
    var value = $('#ym_value_selector_value').val();
    return {'key':key, 'value':value};
}
function initSelector(){
    var $selectorList = $('.ym_taskmg_selector_list');
@if(isset($sourceItems))
    @foreach($sourceItems as $key=>$show)
    $selectorList.append(' <li id="vl_{{$key}}" class="ym_taskmg_selector_item" '+
        'onclick="selectorItemClick(\'{{$key}}\',\'{{$show}}\')">'+
        '<span class="ym_taskmg_selector_title">{{$show}}</span> </li>');
    @if(isset($value) && $value == $key)
        $('#vl_{{$key}}').append('<i class="ym_taskmg_selector_icon am-icon-check"></i>');
    @endif
    @endforeach
@endif
}
initSelector();
</script>
