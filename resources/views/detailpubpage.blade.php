@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'post', 'pageTitle'=>$title])
@if(array_key_exists('image', $fields))
<link rel="stylesheet" href="/assets/cropper/cropper.min.css" />
<link rel="stylesheet" href="/assets/uploadimg/scImageEditer.css" />
@endif
<script src="/js/imagefieldcontrol.js"></script>
<script src="/assets/cropper/cropper.min.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>

<div class="ym_fp_container ym_active" style="margin-top:14px;padding-top:0;height:auto">
    <form id='pubForm' method="post" action="{{$postUrl}}" onsubmit="return validate()">
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="id" value="{{$id}}" />
        @if(array_key_exists('imageedit', $fields))
        <input type="hidden" name="imageedit" value = "{{$fields['imageedit'] or 0}}" />
        @endif
        @if(array_key_exists('act_id', $fields))
        <input type="hidden" name="act_id" value="{{$fields['act_id']['value'] or 0}}" />
        @endif
        @if(array_key_exists('pid', $fields))
        <input type="hidden" name="pid" value="{{$fields['pid']['value'] or 0}}" />
        @endif
        @if(array_key_exists('title', $fields))
        <div class="ym_fp_row">
            <input name="title" type="text"
                maxlength="{{$fields['content']['max'] or '50'}}"
                placeholder="{{$fields['title']['placeholder'] or '请填写标题'}}"
                value="{{$fields['title']['value'] or '请填写标题'}}" />
        </div>
        @endif
        @if(array_key_exists('content', $fields))
        <div class="ym_fp_row">
            <textarea placeholder="{{$fields['content']['placeholder'] or '请填写内容'}}"
                maxlength="{{$fields['content']['max'] or '140'}}"
                name='content' rows="{{$fields['content']['rows'] or 14}}" >{{$fields['content']['value'] or ''}}</textarea>
        </div>
        <span class="ym_fp_font_num">{{$fields['content']['max'] or '140'}}</span>
        @endif
        @if(array_key_exists('image', $fields))
        <div class="ym_fp_row" style="padding:0;border:none;margin-top:0">
            <input name="image" type="hidden" value=""
                placeholder="{{$fields['image']['placeholder'] or '请选择图片.'}}"
                maxct = "{{$fields['image']['max'] or '1'}}" />
            <div class="imgValue" id="addImg">
                <img src="/imgs/imgbtn.jpg" />
            </div>
        </div>
        @endif
        <div class="ym_fp_err">
            <span id="err"></span>
        </div>
    </form>
</div>

<script type="text/javascript">
function postForm(){
@if(array_key_exists('image', $fields))
    getImgValue();
@endif
    var postData = {};
    $('form').find('input').each(function(){
        var name = $(this).attr('name');
        if(name != ''){
            postData[name] = $(this).val();
        }
    });
    $('form').find('textarea').each(function(){
        var name = $(this).attr('name');
        if(name != ''){
            postData[name] = $(this).val();
        }
    });
    $.post($('form').attr('action'), postData, function(data){
        if(data.res){
            $.ymFunc.goTo(data.url)
        }else{
            $.ymNotice.show(data.info);
        }
    });
}
function validate(){
    var errStr = '';
  @if(array_key_exists('title', $fields))
    var $title = $('input[name="title"]');
    $title.parent().removeClass('error');
    if($.trim($title.val()) == ''){
        $title.parent().addClass('error');
        errStr += "请填写"+$title.attr('placeholder')+"。<br/>";
    }
  @endif
  @if(array_key_exists('content', $fields))
    var $content = $('textarea[name="content"]');
    $content.parent().removeClass('error');
    if($.trim($content.val()) == ''){
        $content.parent().addClass('error');
        errStr += "请填写"+$content.attr('placeholder')+"。<br/>";
    }
  @endif
  @if(array_key_exists('image', $fields) && array_key_exists("require", $fields['image']))
    var $img = $('input[name="image"]');
    $img.parent().removeClass('error');
    if($.trim($img.val().replace(/;/g, '')) == ''){
        $img.parent().addClass('error');
        errStr += $img.attr('placeholder')+"。<br/>";
    }
  @endif
    if(errStr.length > 0){
        $('#err').html(errStr);
        return false;
    }
    return true;
}
@if(array_key_exists('image', $fields))
bindImageField('{{$fieldName or "detail"}}');
@foreach($fields['image']['value'] as $name=>$url)
addImgValue('{{$name}}','{{$url}}');
@endforeach
@endif
$('textarea[name=content]').on('change',function(){
    var $txa = $('textarea[name="content"]');
    var len = $txa.val().length;
    var maxlength = parseInt($txa.attr('maxlength'));
    $('.ym_fp_font_num').text(maxlength-len);
});
</script>
@stop
