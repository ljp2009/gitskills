@extends('layouts.city')
@section('title', $title)
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'post', 'pageTitle'=>$title])
<div class="ym_fp_container ym_active" style="margin-top:14px;padding-top:0;height:auto;">
    <form id='pubForm' method="post" action="/invite/publishRange">
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="inviteUserId" value="{{$inviteUserId}}"/>
        <input type="hidden" name="resourceId" value="{{$resourceId}}"/>
        <input type="hidden" name="resourceType" value="{{$resourceType}}"/>
        <div class="ym_fp_row">
            <input name="inviteNum" type="text" maxlength="7" placeholder="请填写邀请人数" />
        </div>
        <div class="ym_fp_row">
            <input type="hidden" name="province" id="province" value="0"/>
            <input type="hidden" name="city" id="city" value="0"/>
            <input type="text" name="cityName" id="pro" placeholder="请选择城市"/>
            <select style="display:none" name="citySelect" id="citySelect" class="am-form-field ym-publish-field"> </select>
        </div>
        <div class="ym_fp_row" style="padding:0 0.8rem ">
            <select name="inviteSkillName" class="">
                <option value="">请选择技能</option>
                @foreach($skills as $skillKey=>$skillName)
                <option value="{{$skillKey}}">{{$skillName}}</option>
                @endforeach
            </select>
        </div>
        <div class="ym_fp_row" style="padding:0 0.8rem ">
            <select name="inviteSkillLevel" class="">
                <option value="0">无限制</option>
                <option value="1">爱好或更高</option>
                <option value="2">达人或更高</option>
                <option value="3">专业或更高</option>
                <option value="4">专家或更高</option>
                <option value="5">大神</option>
            </select>
        </div>
        <div class="ym_fp_row" style="padding:0 0.8rem ">
            <select name="inviteCreditLevel" class="">
                <option value="1">无限制</option>
                <option value="2">合格或更高</option>
                <option value="3">良好或更高</option>
                <option value="4">优秀或更高</option>
                <option value="5">SSS</option>
            </select>
        </div>
        <div class="ym_fp_err">
            <span id="err"></span>
        </div>
    </form> 
    
</div>

<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
  <div class="am-modal-dialog">
    <div class="am-modal-bd">
      邀请成功！
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn">确定</span>
    </div>
  </div>
</div>
@stop
@section('scriptrangecity')
//<script>
function postForm(){
    if (validate()) {
        var url = $('#pubForm').attr('action');
        var formData = $('#pubForm').serialize();
        $.ajax({
            type:"post",
            url:url,
            data:formData,
            dataType:"json",
            success:function(data){
                if (data.code < 0) {
                    $('#_errorMessageDialog').modal();
                    $('#_errorMessage').val('邀请失败');
                } else if (data.code == 100) {
                    $('#my-alert').modal();
                }
            }
        });
    }
    
}
<!-- 邀请结果显示框 -->
$('.am-modal-footer').click(function(){

    <!-- 任务邀请 -->
    location.href = "/task/"+{{$resourceId}};
});

function validate(){
    var errStr = '';
    var $inviteNum = $('input[name=inviteNum]');
    $inviteNum.parent().removeClass('error');
    if ($.trim($inviteNum.val()) == '') {
        $inviteNum.parent().addClass('error');
        errStr+=$inviteNum.attr('placeholder')+".<br/>";
    } else if (parseInt($inviteNum.val()) == 0) {
        $inviteNum.parent().addClass('error');
        errStr+="邀请人数需要大于0.<br/>";
    };
    var $inviteCity = $('input[name=cityName]');
    $inviteCity.parent().removeClass('error');
    if ($.trim($inviteCity.val()) == '') {
        $inviteCity.parent().addClass('error');
        errStr+="请选择城市.<br/>";
    };

    var $inviteSkillName = $('select[name=inviteSkillName]');
    $inviteSkillName.parent().removeClass('error');
    if ($.trim($inviteSkillName.val()) == '') {
        $inviteSkillName.parent().addClass('error');
        errStr+="请选择技能.<br/>";
    };

    var $inviteSkillLevel = $('select[name=inviteSkillLevel]');
    $inviteSkillLevel.parent().removeClass('error');
    if ($.trim($inviteSkillLevel.val()) == '') {
        $inviteSkillLevel.parent().addClass('error');
        errStr+='请选择技能等级.<br/>';
    };

    var $inviteCreditLevel = $('select[name=inviteCreditLevel]');
    $inviteCreditLevel.parent().removeClass('error');
    if ($.trim($inviteCreditLevel.val()) == '') {
        $inviteCreditLevel.parent().addClass('error');
        errStr+='请选择信誉等级.<br/>';
    };
    if(errStr.length > 0){
        $('#err').html(errStr);
        return false;
    }
    return true;

}



@stop


