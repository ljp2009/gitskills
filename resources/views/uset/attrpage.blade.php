@extends('layouts.submit')
@section('scriptref')
@stop
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'用户设置'])
<link rel="stylesheet" href="/css/ym_survey_create.css">
<link rel="stylesheet" href="/css/ym_publish.css" />
<div class = "ym_cm_card ym_main">
    <div class="ym_title">
        <span>用户属性</span>
    </div>
    <div class="am-form-group" style="margin-top:25px">
      <label for="doc-ta-1">生日</label>
      <input name="birthday" type="date" class="am-form-field ym-publish-field ym_input"
            value="{{$info->birthday}}" style="font-size:12px;height:40px;color:#929292"  validate="required"  placeholder="输入生日" style="font-size:12px; color:#929292;" id="birth">
    </div>
    <div class ="my_option" style="height:120px">
        <div class="am-form-group my_option_left">
          <select id="doc-select-1" name="sex">
            @foreach($items['sex'] as $code=>$text)
                <option value="{{$code}}" {{$info->sex->code == $code?'selected':''}}>
                {{$text}}
                </option>
            @endforeach
          </select>
          <span class="am-form-caret "></span>
        </div>
        <div class="am-form-group my_option_right">
          <select id="doc-select-1" name="marriage">
            @foreach($items['marriage'] as $code=>$text)
                <option value="{{$code}}" {{$info->marriage->code == $code?'selected':''}}>
                {{$text}}
                </option>
            @endforeach
          </select>
          <span class="am-form-caret"></span>
        </div>
        <div class="am-form-group my_option_left">
          <select id="doc-select-1" name="education">
            @foreach($items['education'] as $code=>$text)
                <option value="{{$code}}" {{$info->education->code == $code?'selected':''}}>
                {{$text}}
                </option>
            @endforeach
          </select>
          <span class="am-form-caret "></span>
        </div>
        <div class="am-form-group my_option_right">
          <select id="doc-select-1" name="job">
            @foreach($items['job'] as $code=>$text)
                <option value="{{$code}}" {{$info->job->code == $code?'selected':''}}>
                {{$text}}
                </option>
            @endforeach
          </select>
          <span class="am-form-caret"></span>
        </div>
    </div>
    <div class ="my_option">
        <button class="am-btn am-btn-warning am-btn-block am-radius ym_btn"
           style="clear:both;" type="submit" onclick="saveAttrs();">提交</button>
        <label id="showInfo"></label>
    </div>
</div>
@section('scriptrange')
//<script >
function saveAttrs(){
    $.post('/uset/attr',{
        '_token':$.ymFunc.getToken(),
        'sex':$('select[name=sex]').val(),
        'marriage':$('select[name=marriage]').val(),
        'education':$('select[name=education]').val(),
        'job':$('select[name=job]').val(),
        'birthday':$('input[name=birthday]').val()},
    function(data){
        if(data.res){ $.ymFunc.back(); }
        else{ $('#showInfo').html('保存失败'); }
    }).error(function(e){});
}
@stop
@stop

