@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'post', 'pageTitle'=>$title])

<link rel="stylesheet" href="/assets/cropper/cropper.min.css" />
<link rel="stylesheet" href="/assets/uploadimg/scImageEditer.css" />

<script src="/js/imagefieldcontrol.js"></script>
<script src="/assets/cropper/cropper.min.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>

<div class="ym_fp_container ym_active" style="margin-top:14px;padding-top:0;height:auto">
    <form id='pubForm' method="post" action="{{$postUrl}}" onsubmit="return validate()">
        
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="id" value="{{$id}}" />
        <input type="hidden" name="showUrl" value = "{{$showUrl or ''}}">
        <input type="hidden" name="pid" value="{{$fields['pid']['value'] or 0}}" />
        <div class="ym_fp_row">
            <input name="title" type="text"
                maxlength="{{$fields['title']['max'] or '50'}}"
                placeholder="{{$fields['title']['placeholder'] or '请填写标题'}}"
                value="{{$fields['title']['value'] or '请填写标题'}}" />
        </div>
        <div class="ym_fp_row">
            <textarea placeholder="{{$fields['content']['placeholder'] or '请填写内容'}}"
                maxlength="{{$fields['content']['max'] or '140'}}"
                name='content' rows="{{$fields['content']['rows'] or 5}}" >{{$fields['content']['value'] or ''}}</textarea>
        </div>
        <span class="ym_fp_font_num">{{$fields['content']['max'] or '140'}}</span>
        <div>
            <div style="display:inline-block;padding:0;border:none;margin-top:1rem">
                
                <input name="addAvater" type="hidden" value="{{$fields['addAvater']['value']}}"
                    maxct = "1" placeholder="请上传头像"/>
                <div  id="addAvater">
                    <img src="/imgs/imgbtn.jpg" />
                </div>
                <span style="font-size:14px;display:block;text-align:center;margin-top:0.5rem;">头像</span>
                
            </div>
            <div style="display:inline-block;padding:0;border:none;margin-top:1rem">
            
                <input name="addImg" type="hidden" value="{{$fields['addImg']['value']}}"
                    maxct = "1" placeholder="请上传形象图片"/>
                
                <div  id="addImg">
                    <img src="/imgs/imgbtn.jpg" />
                </div>
                <span style="font-size:14px;display:block;text-align:center;margin-top:0.5rem;">形象</span>
                
            </div>
        </div>
        <div class="ym_fp_err">
            <span id="err"></span>
        </div>
    </form>
</div>

<script type="text/javascript">
function postForm(){
    //$('#pubForm').submit();
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
  @if(array_key_exists('addAvater', $fields) && array_key_exists("require", $fields['addAvater']))
    var $img = $('input[name="addAvater"]');
    $img.parent().removeClass('error');
    if($.trim($img.val()) == ''|| $.trim($img.val().replace(/;/g, '')) == ''){
        $img.parent().addClass('error');
        errStr += $img.attr('placeholder')+"。<br/>";
    }
  @endif
  @if(array_key_exists('addImg', $fields) && array_key_exists("require", $fields['addImg']))
    var $img = $('input[name="addImg"]');
    $img.parent().removeClass('error');
    if($.trim($img.val()) == ''|| $.trim($img.val().replace(/;/g, '')) == ''){
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


$('#addAvater').scUploadImageWork({
      name:'avater',
      maxFileSize:10,
      allowAnimation:false,
      useImgEditer:true,
      uploadUrl:'/img/policy',
   })
   .bind('beforeUpload', function(uploadSet, params){
       uploadSet.uploadUrl = '/img/policy/'+uploadSet.fileName+'/avater';
   }, null)
   .bind('afterUpload', function(imgInfo, res){
       if(res == null){
           alert('上传失败了。');
       }else{
          addImgValue($('#addAvater'), 'addAvater', res.field+'/'+res.filename, res.url);

          var imgFullValue = $('#addAvater').attr('imgname');
          $('input[name=addAvater]').val(imgFullValue);
       }
   }, null);


   $('#addImg').scUploadImageWork({
      name:'img',
      maxFileSize:10,
      allowAnimation:false,
      useImgEditer:false,
      uploadUrl:'/img/policy',
   })
   .bind('beforeUpload', function(uploadSet, params){
       uploadSet.uploadUrl = '/img/policy/'+uploadSet.fileName+'/img';
   }, null)
   .bind('afterUpload', function(imgInfo, res){
       if(res == null){
           alert('上传失败了。');
       }else{
          addImgValue($('#addImg'), 'addImg', res.field+'/'+res.filename, res.url);

          var imgFullValue = $('#addImg').attr('imgname');
          $('input[name=addImg]').val(imgFullValue);
       }
   }, null);


function addImgValue(addBtn,imgId, name, url){
    var $addBtn = addBtn;
    var fileUrl= url;
    if(fileUrl.indexOf('@')>=0){
        fileUrl += ('|'+"64w_64h_1e_1c");
    }else{
        fileUrl += ('@'+"64w_64h_1e_1c");
    }
    var $pvDiv = $('<div class="imgValue"><img id="'+imgId+'" imgname="'+name+'" origin="'+url+'" src="'+fileUrl+'"></div>');
    $addBtn.before($pvDiv);
    $addBtn.hide();
    $pvDiv.on('click', function(){
       var $img = $(this).find('img');
       previewImg(addBtn, imgId, $img.attr('imgname'), $img.attr('origin'));
    });
  console.log('imgId:'+imgId+";name"+name+";url"+url);
}


$('textarea[name=content]').on('change',function(){
    var $txa = $('textarea[name="content"]');
    var len = $txa.val().length;
    var maxlength = parseInt($txa.attr('maxlength'));
    $('.ym_fp_font_num').text(maxlength-len);
});

//编辑画面初始化
function editInit(){
  //初始头像
  var avaterVal = $('input[name=addAvater]').val();
  var imgVal    = $('input[name=addImg]').val();
  var showUrl   = $('input[name=showUrl]').val();
  if (avaterVal != '') {
      addImgValue($('#addAvater'), 'addAvater', avaterVal, showUrl + '/' + avaterVal);
      
  };
  if (imgVal != '') {
      addImgValue($('#addImg'), 'addImg', imgVal, showUrl + '/' + imgVal);
  };

}
//检查添加按钮
function checkAddBtn(addBtn, imgname){

    var $addBtn = addBtn;
    var $name = $('input[name="'+imgname+'"]');
    var maxlength = $name.attr('maxct');
    var $values = $('.imgValue');
    if(maxlength <= $values.length-1){
        $addBtn.hide();
    }else{
        $addBtn.show();
        //清除图片
        $('input[name="'+imgname+'"]').val('');
    }
}
//预览
function previewImg(addBtn, imgId, imgname, url){
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
        checkAddBtn(addBtn, imgId);
        
    });
    $shade.append($btndel);
    var $btncancel = $('<button type="button" class="cancel">关闭</button>');
    $shade.append($btncancel);
    $('body').css('overflow', 'hidden');
    $shade.on('click',function(){$shade.remove();});
    $shade.show();
    $view.show();
}
//编辑画面初始化
editInit();
</script>
@stop
