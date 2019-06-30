@extends('layouts.formpage')
@section('formrange')
<?php if (!isset($isCreate)) {
    $isCreate = false;
} ?>
@if($isCreate)
@include('partview.headerbar',['left'=>'back', 'backText'=>'上一步', 'center'=>'pageTitle',
        'pageTitle'=>$isCreate?'填写任务描述':'修改任务描述', 'right'=>'home' ])
@else
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle',
        'pageTitle'=>$isCreate?'填写任务描述':'修改任务描述', 'right'=>'post', 'postText'=>'保存'])
@endif
<style type="text/css">body{background-color:#f5f5f9;}</style>
<script src="/js/imagefieldcontrol.js?a=2"></script>
<script type="text/javascript" src="/js/selectcontrol.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>
@if($isCreate)
<form id="myForm" method="post" action="/{{$taskMode}}taskwizzard/saverequire" style="margin-bottom:55px;">
@else
<form id="myForm" method="post" action="/pubtask/savedetail" style="margin-bottom:55px;">
@endif
    <div id="taskContainer" style="width:100%">
        <input name="id" type="hidden" value="{{$id or 0}}">
        <input name="_token" type="hidden" value="">
        @if($isCreate)
        <div class="ym_cm_card ym_pub_card">
            <div id ="skillType" class="ym_cm_cardheader no_border">
                <label>任务技能类型</label> 
                <input name="skillType" type="hidden" value=""/>
                <span>(请选择)</span>
            </div>
            <div class="errtext">请选择完成任务需要的技能。</div>
        </div>
        @endif
        <div class="ym_cm_card ym_pub_card" >
            <div class="ym_cm_cardheader">
                <label>任务说明</label>
            </div>
            <textarea maxlength="2000" name="taskIntro" class="ym_taskmg_textarea" style="height:250px" placeholder="填写对任务的文字描述(最多2000字)">@if(!$isCreate){{$task->intro}}@endif</textarea>
            <div class="ym_fp_row" id="imgList1" style="margin:0;padding:0;border:0;padding-bottom:5px;">
            </div>
            <script>
                var imgCtrl1 = new imgFieldCtrl({'container':'#imgList1', 'fieldName':'taskImg'});
            </script>
        </div>
    </div>
    @if($taskMode == 'pk')
        <div id ="assign_solution" class="ym_cm_card ym_pub_card">
            <input type="hidden" name="assign_solution" value="1" />
            <div class="ym_cm_cardheader">
                <label>评审说明</label>
            </div>
            <textarea maxlength="100" name="reviewIntro" class="ym_taskmg_textarea" style="height:100px" placeholder="填写评审说明(最多100字)">@if(!$isCreate){{$task->review_intro}}@endif</textarea>
            <div class="ym_fp_row" id="imgList2" style="margin:0;padding:0;border:0;padding-bottom:5px;">
            </div>
        </div>
        <script>
            var imgCtrl2 = new imgFieldCtrl({'container':'#imgList2', 'fieldName':'reviewImg', 'maxCt':1});
        </script>
    @endif
    @if($isCreate)
    <div class="ym_footerbar ym_pub_control">
        <button class="btn" type="button" onclick="submitForm()" >下一步</button>
    </div>
    @endif
</form>
<script type="text/javascript">
$(document).ready(function(){
@if(!$isCreate)
    var valueArr1 = [];
    @foreach($task->image as $img)
    valueArr1.push({
        'fileName':'{{$img->originName}}',
        'url':'{{$img->getPath()}}'
    });
    @endforeach
    imgCtrl1.setValue(valueArr1);
    var valueArr2 = [];
    <?php
echo $task->reviewImg;
?>
    @if($task->originReviewImg->checkSet())
    valueArr2.push({
        'fileName':'{{$task->originReviewImg->originName}}',
        'url':'{{$task->originReviewImg->getPath()}}'
    });
    @endif
    imgCtrl2.setValue(valueArr2);
@endif
    imgCtrl1.refresh();
    imgCtrl2.refresh();
});
@if($isCreate)
function submitForm() {
    if(validate()){
        $('input[name=_token]').val($.ymFunc.getToken());
        $('#myForm').submit();
    }
}
@else
function postForm() {
    if(validate()){
        $('input[name=_token]').val($.ymFunc.getToken());
        $('#myForm').submit();
    }
}
@endif
function validate(){
    var $skillType = $('input[name=skillType]');
    if($skillType.val()== ''){
        $skillType.parent().parent().addClass('error');
        return false;
    }
    $skillType.parent().parent().removeClass('error');
    return true;
}

@if($isCreate)
$('#skillType').ymSelectControl({
    title:'选择任务类型',
    columns:3,
    dataList:{
        '2001001':'文案',
    @if($taskMode == "appoint")
        '2001002':'编剧', '2001003':'音乐',
        '2001004':'配音',
    @endif
        '2001005':'画师', '2001006':'设计',
        '2001009':'摄影', '2001010':'化妆',
        '2001008':'Coser',
    @if($taskMode == "appoint")
        '2001007':'剪辑', '2001011':'开发',
        '2001012':'策划', '20010l3':'PM',
        '2001014':'资料'
    @endif
    }
}) .bind('getValue',function(){
    return $('input[name=skillType]').val();
}) .bind('setValue',function(value){
    var valueitem =$('input[name=skillType]');
    valueitem.val(value.value);
    var text = (value.text==''?'(未设置)':value.text);
    valueitem.parent().find('span').text(text);
});
@endif

bindImageField('task');
</script>
@stop
