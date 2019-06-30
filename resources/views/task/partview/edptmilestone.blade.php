<div class="ym_headerbar">
    <ul class="am-avg-sm-3">
        <li class="ym_headerbar_left" onclick="back()">
            <i class="ymicon-left" style="font-size:14px"></i>
            <span class="ym_backheader_btn">&nbsp;&nbsp;返回</span>
        </li>
        <li class="ym_headerbar_center">
            <span class="ym_headerbar_title">{{$milestone->id==0?'添加里程碑':'修改里程碑'}}</span>
        </li>
        <li class="ym_headerbar_right" onclick="saveMileStone($('#msId').val(), $('#ym_param_ms_date').val(),$('#ym_param_ms_text').val())">
            <span class="ym_backheader_btn">确定</span>
            <i class="ymicon-right" style="font-size:14px"></i>
        </li>
        <input type="hidden" id="msId" value="{{$milestone->id}}" />
    </ul>
</div>
<div class="ym_taskmg_desc">
    <i class="am-icon-info-circle"></i>&nbsp;&nbsp;请设定里程碑的时间和内容
</div>
<div class="ym_taskmg_item_top">
    日期
    <span class="ym_taskmg_item_value" id="ym_param_skill_type_show">
        <input type="date" class="ym_taskmg_info_header_input"
            id="ym_param_ms_date" value="{{$milestone->date}}" />
    </span>
</div>
<div class="ym_taskmg_split"></div>
<div class="ym_taskmg_item">
    <textarea class="ym_taskmg_textarea" style="margin-top:15px;margin-bottom:15px;"
    id="ym_param_ms_text"   rows=8  placeholder="里程碑描述">{{$milestone->text}}</textarea>
</div>
<div class="ym_taskmg_error">
</div>
<div class="ym_taskmg_desc">&nbsp;</div>
