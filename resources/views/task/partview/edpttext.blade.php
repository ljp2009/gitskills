<div class="ym_backheader">
    <ul class="am-avg-sm-3">
        <li style="text-align:left" onclick="back()">
            <i class="am-icon-angle-left"></i>
            <span class="ym_backheader_btn">&nbsp;&nbsp;返回</span>
        </li>
        <li style="text-align:center"><span class="ym_backheader_title">{{$titleLabel}}</span>
        </li>
        <li style="text-align:right" onclick="save($('#ym_value_text').val(),'{{$partName}}')">
            <span class="ym_backheader_btn">保存&nbsp;&nbsp;</span>
            <i class="am-icon-save"></i>
        </li>
    </ul>
</div>
<div class="ym_taskmg_desc">
    <i class="am-icon-info-circle"></i>&nbsp;&nbsp;{{$infoLabel}}
</div>
<div class="ym_taskmg_texteditor">
    <input id="ym_value_text" type="text" class="ym_taskmg_textarea"
        value="{{$value}}" {{isset($maxlength)?('maxlength='.$maxlength.''):''}} />
</div>
<div class="ym_taskmg_error"> </div>

