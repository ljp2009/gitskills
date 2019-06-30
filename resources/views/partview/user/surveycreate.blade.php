@extends('layouts.submit')

@section('scriptref')
<script src="/js/cropper.min.js"></script>
<script src="/js/ym_imageupload3.js"></script>
@stop

@section('formrange')

@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'用户设置'])
<link rel="stylesheet" href="/css/ym_survey_create.css">
<link rel="stylesheet" href="/css/cropper.min.css" />
<link rel="stylesheet" href="/css/ym_publish.css" />
<div class = "am-container ym_main">
    <div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal">
      <div class="am-modal-dialog" style="position:relative;">
        <a id="delete-img" onclick="deleteName()"><i class="am-icon-remove ym-c-red"></i></a>
        <div class="am-modal-bd">
          <img id="showImg" style="width:100%;" src="" />

        </div>
      </div>
    </div>
    <form class="am-form" method="post" id="formLogin" action="/auth/survey">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="isAjaxPost">
        
        <div class="ym_title">
            <span>基本信息</span>
        </div>
        <div >
          
            <div class="am-form-group">
                <div id="headerContainer" class="ym_imageupload_div" style="float:left;"></div>
                <div id="backgroundContainer" class="ym_imageupload_div" style="margin-left:1rem;float:left;"></div>
            </div>
        </div>
        <div class="am-form-group" style="margin-top:125px">
          <label for="doc-ipt-email-1">昵称</label>
          <input type="text" name="display_name" class="am-form-field ym-publish-field ym_input"  
                 value="{{$default['display_name']}}"   style="font-size:12px;height:40px; " validate="required" placeholder="输入昵称">
        </div>
        <div class="am-form-group">
          <label for="doc-ta-1">个性签名</label>
          <textarea class="am-form-field ym-publish-field" name="text" rows="5" id="doc-ta-1" placeholder="输入个性签名">{{$default['sign']}}</textarea>
        </div>
        <div class ="my_option" name="sex">
            <div class="am-form-group my_option_left">
              <select id="doc-select-1" name="sex">
                <?php $index = 0; ?>
                @foreach($attr['20002']['name'] as  $item)
                    <option value="{{$attr['20002']['code'][$index]}}"
                    @if (in_array($attr['20002']['code'][$index], $default)) {
                            selected 
                    @endif
                    >{{$attr['20002']['name'][$index]}}</option>
                    <?php ++$index; ?>
                @endforeach
              </select>
              <span class="am-form-caret "></span>
            </div>
            <div class="am-form-group my_option_right">
              <select id="doc-select-1" name="record">
                <?php $index = 0; ?>
                @foreach($attr['20004']['name'] as  $item)
                    <option value="{{$attr['20004']['code'][$index]}}"
                    @if (in_array($attr['20004']['code'][$index], $default))
                            selected 
                    @endif
                    >{{$attr['20004']['name'][$index]}}</option>
                    <?php ++$index; ?>
                @endforeach
              </select>
              <span class="am-form-caret"></span>
            </div>
            <div class="am-form-group my_option_left">
              <select id="doc-select-1" name="job">
                <?php $index = 0; ?>
                @foreach($attr['20005']['name'] as  $item)
                    <option value="{{$attr['20005']['code'][$index]}}"
                    @if (in_array($attr['20005']['code'][$index], $default))
                            selected 
                    @endif
                    >{{$attr['20005']['name'][$index]}}</option>
                    <?php ++$index; ?>
                @endforeach
              </select>
              <span class="am-form-caret "></span>
            </div>
            <div class="am-form-group my_option_right">
              <select id="doc-select-1" name="merage">
                <?php $index = 0; ?>
                @foreach($attr['20003']['name'] as  $item)
                    <option value="{{$attr['20003']['code'][$index]}}"
                    @if (in_array($attr['20003']['code'][$index], $default))
                            selected 
                    @endif
                    >{{$attr['20003']['name'][$index]}}</option>
                    <?php ++$index; ?>
                @endforeach
              </select>
              <span class="am-form-caret"></span>
            </div>
        </div>

        <div class="am-form-group" style="margin-top:125px">
          <label for="doc-ta-1">生日</label>
          <input name="age" type="date" class="am-form-field ym-publish-field ym_input"
                value="{{$default['age']}}" style="font-size:12px;height:40px;color:#929292"  validate="required"  placeholder="输入生日" style="font-size:12px; color:#929292;" id="birth">
        </div>
    </form>
    <button class="am-btn am-btn-warning am-btn-block am-radius ym_btn" type="submit" onclick="$YN_VALIDATOR.submitForm();">提交</button>
    @include('partview.publish.imagecontrols')




    
</div>

@section('scriptrange')
//<script >
    //圆形缩略图
   $('#headerContainer').ymImgField({
  'fieldName':'avatar',
  'maxCount':1,
  'aspectRatio':1/1,
  'previewFormat':'90w_90_1e_1c',
  'btnText':'上传头像',
  'prefix':'http://img.umeiii.com/',
  'value':['{{$user->avatar}}'],
  'descLabel':'请为角色选定设定头像区域',
  'gifOnly':false,
  'onValueChange': function(type, field, fileName){
        var fileOriginName = fileName.substr(0,fileName.indexOf('@'));
        var valueStr = $('input[name="image"]').val();
        if(type == 'add') {
            valueStr += (fileOriginName+';');
        }else {
            valueStr = valueStr.replace((fileOriginName+';'), '');
        }
        $('input[name="image"]').val(valueStr);
   }
  });
    $('#backgroundContainer').ymImgField({
  'fieldName':'background',
  'maxCount':1,
  'aspectRatio':1/1,
  'previewFormat':'90w_90_1e_1c',
  'btnText':'上传背景',
  'prefix':'http://img.umeiii.com/',
  'value':['{{$user->background}}'],
  'descLabel':'请为角色选定设定头像区域',
  'gifOnly':false,
  'onValueChange': function(type, field, fileName){
        var fileOriginName = fileName.substr(0,fileName.indexOf('@'));
        var valueStr = $('input[name="image"]').val();
        if(type == 'add') {
            valueStr += (fileOriginName+';');
        }else {
            valueStr = valueStr.replace((fileOriginName+';'), '');
        }
        $('input[name="image"]').val(valueStr);
   }
  });



   

@stop
@stop
