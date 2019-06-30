@extends('layouts.formpage')
@section('formrange')
<?php if (!isset($isCreate)) {
    $isCreate = false;
} ?>
@include('partview.headerbar',['left'=>'back','backText'=>$isCreate?'上一步':'返回', 'center'=>'pageTitle',
        'pageTitle'=>$isCreate?'填写参与条件':'修改参与条件', 'right'=>'home' ])
<style type="text/css">body{background-color:#f5f5f9;}</style>
<form id="myForm" method="post" action="/{{$taskMode}}taskwizzard/savefilter">
    <div id="taskContainer" style="width:100%">
        <input name="id" type="hidden" value="{{$id or 0}}">
        <input name="_token" type="hidden" value="">
        <div class="ym_cm_card ym_pub_card">
            <div id ="skillLevel" class="ym_cm_cardheader no_border">
                <label>用户技能等级</label> 
                <input name="skillLevel" type="hidden" value="0"/>
                <span>无限制(默认)</span>
            </div>
            <span class="description">
                用户技能等级低于您设定的用户将无法申请参与您的任务
            </span>
        </div>
        <div class="ym_cm_card ym_pub_card">
            <div id ="creditLevel" class="ym_cm_cardheader no_border">
                <label>用户信誉限制</label> 
                <input name="creditLevel" type="hidden" value="3"/>
                <span>一般(默认)</span>
            </div>
            <span class="description">
                用户信誉等级低于您设定的用户将无法申请参与您的任务
            </span>
        </div>
    </div>
    <div class="ym_footerbar ym_pub_control">
        <button class="btn" type="button" onclick="submitForm()" >保存任务</button>
    </div>
</form>
<script type="text/javascript" src="/js/selectcontrol.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>
<script src="/js/imagefieldcontrol.js"></script>
<script type="text/javascript">
function submitForm() {
    if(validate()){
        $('input[name=_token]').val($.ymFunc.getToken());
        $('#myForm').submit();
    }
}
function validate(){
    return true;
}
$('#skillLevel').ymSelectControl({
    title:'选择用户技能等级',
    columns:1,
    dataList:{
      '0':'无限制(默认)', '1':'爱好', '2':'达人', '3':'专业', '4':'专家', '5':'大神'
    }
}) .bind('getValue',function(){
    return $('input[name=skillLevel]').val();
}) .bind('setValue',function(value){
    var valueitem =$('input[name=skillLevel]');
    valueitem.val(value.value);
    var text = value.text;
    valueitem.parent().find('span').text(text);
});
$('#creditLevel').ymSelectControl({
    title:'选择用户信用等级',
    columns:1,
    dataList:{
       '1':'一般', '2':'合格', '3':'良好', '4':'优秀', '5':'SSS'
    }
}) .bind('getValue',function(){
    return $('input[name=creditLevel]').val();
}) .bind('setValue',function(value){
    var valueitem =$('input[name=creditLevel]');
    valueitem.val(value.value);
    var text = value.text;
    valueitem.parent().find('span').text(text);
});

</script>
@stop
