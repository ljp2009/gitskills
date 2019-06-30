@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'post', 'pageTitle'=>$title])
@if(array_key_exists('image', $fields))
<script src="/assets/cropper/cropper.min.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>
<link rel="stylesheet" href="/assets/cropper/cropper.min.css" />
<link rel="stylesheet" href="/assets/uploadimg/scImageEditer.css" />
@endif
<div class="ym_fp_container ym_active" style="margin-top:14px;padding-top:0;height:auto">
    <form id='pubForm' method="post" action="{{$postUrl}}" onsubmit="return validate()">
        
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
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
                name='content' rows='15' >{{$fields['content']['value'] or ''}}</textarea>
        </div>
        <span class="ym_fp_font_num">{{$fields['content']['max'] or '140'}}</span>
        @endif
        @if(array_key_exists('image', $fields))
        <div class="ym_fp_row" style="padding:0;border:none;margin-top:0">
            <input name="image" type="hidden"
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
    $('#pubForm').submit();
}
function validate(){
    var errStr = '';
  @if(array_key_exists('title', $fields))
    var $title = $('input[name="title"]');
    $title.parent().removeClass('error');
    if($.trim($title.val()) == ''){
        $title.parent().addClass('error');
        errStr += "请填写标题。<br/>";
    }
  @endif
  @if(array_key_exists('content', $fields))
    var $content = $('textarea[name="content"]');
    $content.parent().removeClass('error');
    if($.trim($content.val()) == ''){
        $content.parent().addClass('error');
        errStr += "请填写内容。<br/>";
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
function getImgValue(){
    var imgFullValue = '';
    $('.imgValue:not(#addImg)')
        .each(function(){
            imgFullValue += ($(this).find('img').first().attr('imgname')+';');
        });
    $('input[name=image]').val(imgFullValue);
}
function addImgValue(name, url){
    var $addBtn = $('#addImg');
    var $pvDiv = $('<div class="imgValue"><img imgname="'+name+'" origin="'+url+'" src="'+url+'@60h_60w_1e_1c"></div>');
    $addBtn.before($pvDiv);
    $pvDiv.on('click', function(){
       var $img = $(this).find('img');
       previewImg($img.attr('imgname'), $img.attr('origin'));
    });
    var $name = $('input[name="name"]');
    var maxlength = $name.attr('maxct');
    var $values = $('.imgValue');
    if(maxlength <= $values.length-1){
        $addBtn.hide();
    }
}
function previewImg(imgname, url){
    var $shade = $('#detailImgShade');
    $shade.remove();
    $shade = $('<div id="#detailImgShade" class="ym_fp_shade"></div>');
    $('body').append($shade);
    var $view = $('<div class="ym_cm_imgpreview"></div>');
    $view.css({'background-image':"url("+url+")"})
    $shade.append($view);

    var $btndel = $('<button type="button" class="delete">删除</button>');
    $btndel.on('click',function(){
        $('img[imgname="'+imgname+'"]').parent().remove();
    });
    $shade.append($btndel);

    var $btncancel = $('<button type="button" class="cancel">关闭</button>');
    $shade.append($btncancel);

    $('body').css('overflow', 'hidden');
    $shade.on('click',function(){$shade.remove();});
    $shade.show();
    $view.show();
}
$('#addImg').scUploadImageWork({
    name:'background',
    maxFileSize:10,
    allowAnimation:false,
    useImgEditer:false,
    uploadUrl:'/img/policy',
 })
 .bind('beforeUpload', function(uploadSet, params){
     uploadSet.uploadUrl = '/img/policy/'+uploadSet.fileName+'/{{$fieldName or "detail"}}';
 }, null)
 .bind('afterUpload', function(imgInfo, res){
     if(res == null){
         alert('上传失败了。');
     }else{
        addImgValue(res.filename, res.url);
     }
 }, null);
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
