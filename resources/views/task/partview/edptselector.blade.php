<div class="ym_backheader">
    <ul class="am-avg-sm-3">
        <li style="text-align:left" onclick="back()">
            <i class="am-icon-angle-left"></i>
            <span class="ym_backheader_btn">&nbsp;&nbsp;返回</span>
        </li>
        <li style="text-align:center"><span class="ym_backheader_title">{{$titleLabel}}</span>
        </li>
@if(isset($isCreate) && $isCreate)
        <li style="text-align:right" onclick="finish(getSelectorValue(),'{{$partName}}')">
            <span class="ym_backheader_btn">确定&nbsp;&nbsp;</span>
            <i class="am-icon-save"></i>
        </li>
@else
        <li style="text-align:right" onclick="save(getSelectorValue(),'{{$partName}}')">
            <span class="ym_backheader_btn">保存&nbsp;&nbsp;</span>
            <i class="am-icon-save"></i>
        </li>
@endif
    </ul>
</div>
<div class="ym_taskmg_desc">
    <i class="am-icon-info-circle"></i>&nbsp;&nbsp;{{$infoLabel}}
</div>
<div class='ym_select_ctrl' style="position:initial;">
<input type="hidden" id="ym_value_selector_key" value ="" />
<input type="hidden" id="ym_value_selector_value" value ="" />
<ul class="content am-avg-sm-{{isset($columns)?$columns:1}}" style="margin-top:5px;"></ul>
<div>
<script type="text/javascript">
function selectorItemClick(key){
    $('#ym_value_selector_key').val(key);
    var value = $('#vl_'+key).find('span').text();
    $('#ym_value_selector_value').val(value);
    var $clickLi = $('#vl_'+key);
    $('.item').removeClass('selected');
    $clickLi.addClass('selected');
    // $clickLi.append('<i class="ym_taskmg_selector_icon am-icon-check"></i>');
}
function getSelectorValue(){
    var key = $('#ym_value_selector_key').val();
    var value = $('#ym_value_selector_value').val();
    return {'key':key, 'value':value};
}
function initSelector(){
    var $selectorList = $('.content');
@if(isset($sourceItems))
    @foreach($sourceItems as $key=>$show)
    $selectorList.append(' <li id="vl_{{$key}}" class="item" '+
        'onclick="selectorItemClick(\'{{$key}}\')">'+
        '<span class="">{{$show}}</span> </li>');
    @if(isset($value) && $value == $key)
        // $('#vl_{{$key}}').append('<i class="ym_taskmg_selector_icon am-icon-check"></i>');
        $('#vl_{{$key}}').addClass('selected');
    @endif
    @endforeach
@endif
}
function loadNowValue(){
    var v = $('#ym_param_{{$partName}}_value').val();
    selectorItemClick(v);
}
initSelector();
</script>
