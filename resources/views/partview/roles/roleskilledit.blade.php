@extends('layouts.publish')
@section('title',  '编辑角色技能')
@section('formrange')
<link rel="stylesheet" href="/css/cropper.min.css" />
<link rel="stylesheet" href="/css/ym_publish.css" />
<link rel="stylesheet" href="/css/ym_skill.css">
<link rel="stylesheet" href="/css/formpage.css">
<link rel="stylesheet" href="/assets/cropper/cropper.min.css" />
<link rel="stylesheet" href="/assets/uploadimg/scImageEditer.css" />

<script src="/assets/cropper/cropper.min.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>

<div class="ym-g">
    <form method="post" id="pubForm" onsubmit="return validate()" action="/roleskill/edit">
        <fieldset class="ym-form-set">
        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
        <input type="hidden" name="id" value="{{$id}}"/>
        <input type="hidden" name="imageWidth">
        <input type="hidden" name="imageHeight">

            <div class="ym-form-group ym_fp_row">
<!--                 <label>角色技能名称</label> -->
                <input name="name" class="ym-form-field" value="{{$model->name}}" placeholder="请填写您要发布的角色技能名称..."/>
            </div>

            <div class="ym-imgfield" style="margin-bottom:1rem">
                <input name="addImg" type="hidden" value="{{$model->image}}"
                    maxct = "1" placeholder="请上传形象图片"/>
                <span class="ym-imgfield-addbtn am-btn am-btn-warning am-btn-block" id="addImg">选择技能动画</span>
            </div>
            <div class="ym-form-group ym_fp_row" style="padding:0px">
                <!-- <label>请选择技能属性</label> -->
                <select name="attrcode" value="{{$model->skill_type}}" class="ym-form-field" style="padding: 0rem 0.6rem;font-size:1.4rem;color:#555;" >
                    <?php $i = 0 ?>
                    @foreach($attrArr as $arr)
                     @if($attrCode[$i] == $model->skill_type)
                        <option value="{{$attrCode[$i]}}" selected="selected">{{$arr}}</option>
                     @else
                        <option value="{{$attrCode[$i]}}" >{{$arr}}</option>
                     @endif   
                    <?php $i++?>
                    @endforeach
                </select>
            </div>

            <div class="ym-form-group ym_fp_row" style="margin-bottom: 0px;">
                <!-- <label>角色技能介绍</label> -->
                <textarea name="intro" rows="7" class="ym-form-field" placeholder="请填写角色技能介绍..."
                    maxlength="300" >{{$model->intro}}</textarea>
            </div>
            <span class="ym_fp_font_num"></span>
            <div class="ym_fp_err">
                <span id="err"></span>
            </div>
        </fieldset>
        
    </form>
</div>

@stop
@section('scriptref')

    
@stop
@section('scriptrange')
//<script >

$('textarea[name=intro]').on('change',function(){
    calculateIntroLen();
});

//计算技能简介内容字数
function calculateIntroLen(){
  var $txa = $('textarea[name=intro]');
  var len = $txa.val().length;
  var maxlength = parseInt($txa.attr('maxlength'));
  $('.ym_fp_font_num').text(maxlength - len);
}

calculateIntroLen();

$('#addImg').scUploadImageWork({
      name:'img',
      maxFileSize:10,
      allowAnimation:true,
      useImgEditer:true,
      uploadUrl:'/img/policy',
   })
   .bind('beforeUpload', function(uploadSet, params){

       uploadSet.uploadUrl = '/img/policy/'+uploadSet.fileName+'/img';
       //原图宽度
       $("input[name=imageWidth]").val(uploadSet.imageSize.originWidth);
       //原图高度
       $("input[name=imageHeight]").val(uploadSet.imageSize.originHeight);
   }, null)
   .bind('afterUpload', function(imgInfo, res){
       if(res == null){
           alert('上传失败了。');
       }else{
          addImgValue($('#addImg'), 'addImg', res.field+'/'+res.filename, res.url);
          console.log("res.field:"+res.field+";res.filename:"+res.filename+";res.url"+res.url);
          var imgFullValue = $('#addImg').attr('imgname');
          $('input[name=addImg]').val(imgFullValue);
       }
   }, null);

function imgInit(){
    $img = $("input[name=addImg]");
    if ($img.val() != '') {
        addImgValue($('#addImg'), 'addImg', '{{$model->image->originName}}', '{{$model->image->getPath()}}');
    }   
};
//初始化图片
imgInit();

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
    $shade.on('click',function(){
      $shade.remove();
      $('body').css('overflow', 'scroll');
    });
    $shade.show();
    $view.show();
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

function postForm(){
        $('#pubForm').submit();
    }

function validate(){
    var errStr='';
    //角色名称
    var $name = $('input[name="name"]');
    $name.parent().removeClass('error');
    //角色名称为空
    if ($.trim($name.val()) == '') {
        $name.parent().addClass('error');
        errStr += $name.attr('placeholder')+'.<br/>';
    };

    var $img = $('input[name="addImg"]');
    $img.parent().removeClass('error');
    if($.trim($img.val()) == ''|| $.trim($img.val().replace(/;/g, '')) == ''){
        $img.parent().addClass('error');
        errStr += '请选择技能动画...<br/>';
    } else {
        //原图宽度
       var width = $("input[name=imageWidth]").val();
       //原图高度
       var height = $("input[name=imageHeight]").val();
       var r = width/height;
       //16:9
       if (r < 1.6 || r > 2) {
            $img.parent().addClass('error');
            errStr += '请选择分辨率为16:9的图片...<br/>';
       };
    }


    //属性
    var $attrcode = $('select[name="attrcode"]')
    $attrcode.parent().removeClass('error');
    //属性未选择
    if ($.trim($attrcode.val()) == '0') {
        $attrcode.parent().addClass('error');
        errStr += '请选择技能属性...<br/>';
    };



    //技能介绍
    var $intro = $('textarea[name="intro"]');
    $intro.parent().removeClass('error');
    //技能介绍为空
    if ($.trim($intro.val()) == '') {
        $intro.parent().addClass('error');
        errStr += $intro.attr('placeholder')+'.<br/>';
    };

    if (errStr.length > 0) {
        $('#err').html(errStr);
        return false;
    };
    return true;
}
@stop
