<div class="ym_backheader">
    <ul class="am-avg-sm-3">
        <li style="text-align:left" onclick="back()">
            <i class="am-icon-angle-left"></i>
            <span class="ym_backheader_btn">&nbsp;&nbsp;返回</span>
        </li>
        <li style="text-align:center"><span class="ym_backheader_title">{{$titleLabel}}</span>
        </li>
        @if($isCreate)
        <li style="text-align:right" onclick="finish($('#ym_value_number').text(),'{{$partName}}')">
            <span class="ym_backheader_btn">确定&nbsp;&nbsp;</span>
            <i class="am-icon-save"></i>
        </li>
        @else
        <li style="text-align:right" onclick="save($('#ym_value_number').text(),'{{$partName}}')">
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
    <span id="ym_value_number" class="ym_taskmg_moneyspan ym_taskmg_moneyspan_value" >{{$value}}</span>
    <span class="ym_taskmg_moneyspan">{{$postfixLabel}}</span>
</div>
<div class="ym_taskmg_split"></div>
<div class="ym_taskmg_numpad">
    <ul class="am-avg-sm-3">
        <li class="ym_taskmg_numpad_left"><button onclick="padClick(1)">1</button></li>
        <li class="ym_taskmg_numpad_center"><button onclick="padClick(2)">2</button></li>
        <li class="ym_taskmg_numpad_right"><button onclick="padClick(3)">3</button></li>
    </ul>
    <ul class="am-avg-sm-3">
        <li class="ym_taskmg_numpad_left"><button onclick="padClick(4)">4</button></li>
        <li class="ym_taskmg_numpad_center"><button onclick="padClick(5)">5</button></li>
        <li class="ym_taskmg_numpad_right"><button onclick="padClick(6)">6</button></li>
    </ul>
    <ul class="am-avg-sm-3">
        <li class="ym_taskmg_numpad_left"><button onclick="padClick(7)">7</button></li>
        <li class="ym_taskmg_numpad_center"><button onclick="padClick(8)">8</button></li>
        <li class="ym_taskmg_numpad_right"><button onclick="padClick(9)">9</button></li>
    </ul>
    <ul class="am-avg-sm-3">
        <li class="ym_taskmg_numpad_left"><button onclick="padClick(0)">0</button></li>
        <li class="ym_taskmg_numpad_center isdisable" >.</li>
        <li class="ym_taskmg_numpad_right"><button onclick="padClick('back')"><i class="am-icon-arrow-left"></i></button></li>
    </ul>
</div>
<script type="text/javascript">
function padClick(pressValue){
    var oldvalue =  $('.ym_taskmg_moneyspan_value').text();
    var newvalue ='';
    if(pressValue == 'back') {
        newvalue = oldvalue.substr(0, oldvalue.length - 1);
        if(newvalue.length == 0){
            newvalue = '0';
        }
    }else{
        if(oldvalue.length > 6) return;
        if(oldvalue == '0'){
            newvalue = pressValue;
        }
        else{
            newvalue = oldvalue + pressValue;
        }
    }
    $('.ym_taskmg_moneyspan_value').text(newvalue);
}

function loadNowValue(){
    var v = $('#ym_param_{{$partName}}_show').text();
    $('#ym_value_number').text(v);
}
</script>
