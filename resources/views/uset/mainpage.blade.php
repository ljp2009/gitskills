@extends('layouts.submit')

@section('scriptref')
<script src="/assets/cropper/cropper.min.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>
<link rel="stylesheet" href="/assets/cropper/cropper.min.css" />
<link rel="stylesheet" href="/assets/uploadimg/scImageEditer.css" />
@stop
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'用户设置'])
<link rel="stylesheet" href="/css/ym_survey_create.css">
<link rel="stylesheet" href="/css/ym_publish.css" />
<div class = "ym_cm_card ym_uset_default">
    <div class="ym_uset_avatar">
        <img src= "{{$user->avatar->getPath(2)}}" id="modifyAvatar"/>
    </div>
    <button id="setDisplayName" class="ym_uset_button" type="button" >{{$user->display_name}}</button>
</div>
<div class = "ym_cm_card" style="padding-bottom:20px;margin-bottom:0;">
    <div class="ym_taskmg_item" onclick="$.ymFunc.goTo('/uset/attr')">
        编辑用户属性<i class="am-icon-angle-right ym_taskmg_gotoicon">&nbsp;&nbsp;</i>
        <span class="ym_taskmg_item_value" id="ym_param_delivery_date_show">(性别，年龄，职业等)</span>
    </div>
    <div class="ym_taskmg_item" onclick="$.ymFunc.goTo('/uset/skill')">
        编辑用户技能<i class="am-icon-angle-right ym_taskmg_gotoicon">&nbsp;&nbsp;</i>
        <span class="ym_taskmg_item_value" id="ym_param_delivery_date_show">{{count($user->getAttrSkill)==0?"(未绑定)":"(已绑定)"}}</span>
    </div>
    <div class="ym_taskmg_item" onclick="$.ymFunc.goTo('/uset/mobile')">
        绑定手机<i class="am-icon-angle-right ym_taskmg_gotoicon">&nbsp;&nbsp;</i>
        <span class="ym_taskmg_item_value" id="ym_param_delivery_date_show">{{$user->mobile==""?"(未绑定)":"(已绑定)"}}</span>
    </div>
    <div class="ym_taskmg_item" onclick="$.ymFunc.goTo('/uset/email')">
        绑定邮箱<i class="am-icon-angle-right ym_taskmg_gotoicon">&nbsp;&nbsp;</i>
        <span class="ym_taskmg_item_value" id="ym_param_delivery_date_show">{{$user->email==""?"(未绑定)":"(已绑定)"}}</span>
    </div>
@if($user->wx_open_id != "")
    <div class="ym_taskmg_item">
        绑定微信<i class="am-icon-angle-right ym_taskmg_gotoicon" style="color:#fff">&nbsp;&nbsp;</i>
        <span class="ym_taskmg_item_value" id="ym_param_delivery_date_show">{{$user->wx_open_id==""?"(请使用微信登录并绑定当前帐号)":"(已绑定)"}}</span>
    </div>
@endif
    <div class="ym_taskmg_item" onclick="$.ymFunc.goTo('/uset/pwd')">
        修改密码<i class="am-icon-angle-right ym_taskmg_gotoicon">&nbsp;&nbsp;</i>
        <span class="ym_taskmg_item_value" id="ym_param_delivery_date_show">{{$user->password==""?"(未设置)":"(已设置)"}}</span>
    </div>
</div>

@section('scriptrange')
//<script >
$('#setDisplayName').ymEditField({
    'title'      : '编辑昵称',
    'valueField' : '#setDisplayName',
    'maxLength'  : 16,
    'callback'   : function(newValue, ev){
        if(newValue == ''){
            ev.error('昵称不能为空');
        }else{
            $.post('/uset/display',{
                '_token':$.ymFunc.getToken(),
                'display_name': newValue,
            },function(data){
                if(data.res){
                    $('#setDisplayName').text(newValue);
                    ev.finish();
                }
                else{
                    ev.error(data.info);
                }
            }).error(function(err){
                ev.error(err);
            });
            
        }
    }
});
$('#modifyAvatar').scUploadImageWork({
    name:'avatar',
    maxFileSize:10,
    allowAnimation:false,
    uploadUrl:'/img/policy',
 })
 .bind('beforeUpload', function(uploadSet, params){
     uploadSet.uploadUrl = '/img/policy/'+uploadSet.fileName+'/avatar';
 }, null)
 .bind('afterUpload', function(imgInfo, res){
     if(res == null){
         alert('上传失败了。');
     }else{
         $.post('/uset/avatar', {
             '_token':$.ymFunc.getToken(),
             'fileName': 'avatar/'+imgInfo.name,
         }, function(data){
             if(data.res){
                 var $img = $('.ym_uset_avatar').find('img');
                 $img.attr('src', data.info);
             }
         }).error(function(e){});
     }
 }, null);

@stop
@stop
