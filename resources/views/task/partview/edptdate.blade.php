<div class="ym_backheader">
    <ul class="am-avg-sm-3">
        <li style="text-align:left" onclick="back()">
            <i class="am-icon-angle-left"></i>
            <span class="ym_backheader_btn">&nbsp;&nbsp;返回</span>
        </li>
        <li style="text-align:center"><span class="ym_backheader_title">{{$titleLabel}}</span>
        </li>
@if(isset($isCreate) && $isCreate)
        <li style="text-align:right" onclick="finish($('#ym_value_date').val(),'{{$partName}}')">
            <span class="ym_backheader_btn">确定&nbsp;&nbsp;</span>
            <i class="am-icon-save"></i>
        </li>
@else
        <li style="text-align:right" onclick="save($('#ym_value_date').val(),'{{$partName}}')">
            <span class="ym_backheader_btn">保存&nbsp;&nbsp;</span>
            <i class="am-icon-save"></i>
        </li>
@endif
    </ul>
</div>
<div class="ym_taskmg_desc">
    <i class="am-icon-info-circle"></i>&nbsp;&nbsp;{{$infoLabel}}
</div>
<div class="ym_taskmg_texteditor">
    <input class="ym_taskmg_textarea" id="ym_value_date" type="date" value="{{$value}}" />
</div>
<script type="text/javascript">
function loadNowValue(){
    var v = $('#ym_param_{{$partName}}_show').text();
    $('#ym_value_date').val(v);
}
</script>
