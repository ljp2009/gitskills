@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle',
'right'=>'post', 'postText'=>$id==0?'交付':'更新', 'pageTitle'=>'任务交付'])
<link rel="stylesheet" href="/assets/uploadimg/scImageEditer.css" />
<script src="/js/imagefieldcontrol.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>

<div class="ym_fp_container ym_active" style="margin-top:14px;padding-top:0;height:auto">
    <form id='pubForm' method="post" action="/taskdelivery/{{$id==0?'add':'edit'}}" onsubmit="return validate()">
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="id" value="{{$id or 0}}" />
        <input type="hidden" name="taskid" value="{{$taskId or 0}}" />
        <div class="ym_fp_row">
            <textarea placeholder="请填写交付说明(不超过400字)"
                maxlength="400"
                name='text' rows="7" >{{$text}}</textarea>
        </div>
        <span class="ym_fp_font_num">400</span>
        <div class="ym_fp_row" style="padding:0;border:none;margin-top:0">
            <span>图片</span>
            <input name="image" type="hidden" value=""
                placeholder="" maxct = "9" />
            <div class="imgValue" id="addImg">
                <img src="/imgs/imgbtn.jpg" />
            </div>
        </div>
        <br />
        <div id="attach_box" class="ym_fp_row" style="padding:0;border:none;margin-top:0">
            <span>附件</span>
            <input name="attachments" type="hidden" value="" placeholder="" maxct = "9" />
            <input id="fileCtrl" type="file" style="display:none" onchange="uploadFile(this)" />
            <button id="addAttach" type="button"><i class="ymicon-add" onclick="$('#fileCtrl').click()"
                style="font-size:30px"></i></button>
        </div>
        <div class="ym_fp_row" style="border:none;padding-left:30px">
        <span style="border:none; height:auto">
            说明:<br />
            1. 图片最多可以上传9张。<br />
            2. 附件可以上传3个。<br />
            3. 附件支持jpg, bmp, gif, txt, zip文件。<br />
            4. 单个附近大小不要超过10M。<br />
            5. 附近名称不要包含特殊字符。<br />
            5. 交付物仅在提交后24小时内可以编辑或者删除。<br />

        </span>
        </div>
        <div class="ym_fp_err">
            <span id="err"></span>
        </div>
    </form>
</div>

<script type="text/javascript">
function postForm(){
    getImgValue();
    getAttachValue();
    $('#pubForm').submit();
}
function getAttachValue(){
    var $attachCtrls = $('#attach_box').find('button.attach');
    var value = '';
    $attachCtrls.each(function(){
       var fname =  $(this).attr('fname');
       var fsize =  $(this).attr('fsize');
       var dname =  $(this).text();
       value += (fname+":"+fsize+":"+dname+";");
    });
    $('input[name=attachments]').val(value);
}
function uploadFile(ctrl){
    var fr = new FileReader;
    if(ctrl.files.length == 0) return;
    var file = ctrl.files[0];
    var fileType = file.type;
    var fileSize = file.size;
    var originName = file.name;
    var ext = /\.[^\.]+$/.exec(file.name);
    if(ext == null)
        ext = '';
    else
        ext = ext[0].toLowerCase();
    if(containSpecial(name)){
        alert('文件名称中包含特殊字符');
        return;
    }
    if(!checkSize(fileSize)){
        alert('文件超过限制的大小');
        return;
    }
    if(!checkExt(ext)){
        alert('文件类型错误');
        return;
    }
    $.ymFunc.showLoading('文件上传中');
    fr.onload = function(e){
    var fileName = 'delivery-'+(new Date()).getTime()
        +'-'+Math.random().toString(36).substr(16)+ext;
      var aliUpload = new scAliOssHandler({policyUrl:'/img/policy/'+fileName+'/delivery'})
      .bind('uploadSucessful', function(res, callInfo){
        console.log('通过接口上传文件成功。');
        $.ymFunc.changeLoading('文件上传完成');
        $.ymFunc.hideLoading();
        addAttachValue(fileName, originName, fileSize);
      })
      .bind('uploadFailed', function(res, callInfo){
        $.ymFunc.changeLoading('文件上传失败');
        $.ymFunc.hideLoading();
        console.log('通过接口上传文件失败。');
      });

      aliUpload.uploadFile(ctrl.files[0], fileName, fileType, {'f':1});
    }
    fr.readAsDataURL(file);
}
function containSpecial( s )      
{      
    var containSpecial = RegExp(/[(\\)(\~)(\!)(\@)(\#)(\$)(\%)(\^)(\&)(\*)(\()(\))(\-)(\_)(\+)(\=)(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\<)(\>)(\?)(\)]+/);      
    return ( containSpecial.test(s) );      
} 
function checkSize(size, ext){
    return size < 10*1024*1024;
}
function checkExt(ext){
    var extArr = ['.jpg', '.bmp', '.gif', '.zip', '.txt'];
    for(var i=0;i<extArr.length;i++){
       if(ext == extArr[i])  return true;
    }
    return false;
}
function validate(){
    @if($id == 0)
    if(!confirm('交付物仅可以在提交后在24小时内可以进行修改或者删除，您确定要提交吗？')){
        return false;
    }
    @endif
    var errStr = '';
    var $content = $('textarea[name="text"]');
    $content.parent().removeClass('error');
    if($.trim($content.val()) == ''){
        $content.parent().addClass('error');
        errStr += "请说明您的交付物。<br/>";
    }
    if(errStr.length > 0){
        $('#err').html(errStr);
        return false;
    }
    return true;
}
function addAttachValue(fileName, originName, fileSize){
    $('#addAttach').before('<button class="attach" type="button" fsize="'+fileSize+'" fname="'+fileName+'">'+originName+'</button>');
}

bindImageField('deliveryimg');
@foreach($images as $name=>$url)
addImgValue('{{$name}}','{{$url}}');
@endforeach
@foreach($attachments as $attach=>$info)
addAttachValue("{{$attach}}","{{$info['name']}}","{{$info['size']}}");
@endforeach
$('textarea[name=content]').on('change',function(){
    var $txa = $('textarea[name="content"]');
    var len = $txa.val().length;
    var maxlength = parseInt($txa.attr('maxlength'));
    $('.ym_fp_font_num').text(maxlength-len);
});
</script>
@stop
