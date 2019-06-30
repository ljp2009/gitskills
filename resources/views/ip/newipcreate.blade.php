@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'post', 'pageTitle'=>$title])
<link rel="stylesheet" href="/css/ym_ipcreate.css" />
<div class="ym_cm_card">
    <div class="ym_cm_cardheader">
        基本信息 
    </div>
    <div class="ym_ipc_cover_box">
        <img src="http://img.umeiii.com/cover-1482821354-q0yyZ0.jpg@186w_220h_1e_1c.jpg">
    </div>
    <div class="ym_ipc_name">
        <label>作品名称</label>
        <textarea ></textarea>
    </div>
    <div class="ym_ipc_intro">
        <textarea ></textarea>
    </div>
</div>
<div class="ym_cm_card">
    <div class="ym_cm_cardheader">
        属性 
    </div>
    <div style="height:150px">&nbsp;</div>
</div>
<div class="ym_cm_card">
    <div class="ym_cm_cardheader">
        标签 
    </div>
    <div style="height:150px">&nbsp;</div>
</div>
<script src="/js/imagefieldcontrol.js?a=2"></script>
<script type="text/javascript" src="/js/selectcontrol.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    //imgCtrl1.refresh();
    //imgCtrl2.refresh();
});
function submitForm() {
    if(validate()){
        $('input[name=_token]').val($.ymFunc.getToken());
        $('#myForm').submit();
    }
}
function validate(){
    var $skillType = $('input[name=skillType]');
    if($skillType.val()== ''){
        $skillType.parent().parent().addClass('error');
        return false;
    }
    $skillType.parent().parent().removeClass('error');
    return true;
}
$('#ipModel').ymSelectControl({
    title:'选择IP类型',
    columns:1,
    dataList:{
        'cartoon' : '动漫',
        'story'   : '小说',
        'light'   : '轻小说',
        'game'    : '游戏'
    }
}) .bind('getValue',function(){
    return $('input[name=ipModel]').val();
}) .bind('setValue',function(value){
    var valueitem =$('input[name=ipModel]');
    valueitem.val(value.value);
    var text = (value.text==''?'(未设置)':value.text);
    valueitem.parent().find('span').text(text);
});
</script>
@stop
