@extends('layouts.block')
@section('title','有妹社区')
@section('content')
@section('serverLoad')
<link rel="stylesheet" href="/css/cropper.min.css" />
<link rel='stylesheet' href='/css/ym_publish.css'>
<input type="hidden" id="taskId" value="{{$task->id}}"/>
<div class="ym_taskmg_body">
    <div class="ym_taskmg_page" id="mainPage">
        <div class="ym_backheader">
            <ul class="am-avg-sm-3">
                <li style="text-align:left" onclick="backToMainEditPage()">
                    <i class="am-icon-angle-left"></i>
                    <span class="ym_backheader_btn">&nbsp;&nbsp;返回</span>
                </li>
                <li style="text-align:center"><span class="ym_backheader_title">任务描述</span>
                </li>
                <li style="text-align:right" onclick="saveDetail($('#ym_value_number').text())">
                    <span class="ym_backheader_btn">保存&nbsp;&nbsp;</span>
                    <i class="am-icon-save"></i>
                </li>
            </ul>
        </div>
        <div class="ym_taskmg_desc">
           <i class="am-icon-info-circle"></i>&nbsp;&nbsp;任务：{{$task->title}}
        </div>
        <div class="ym_taskmg_error" style="display:none"> </div>
        <div class="ym_taskmg_item_top">
            <textarea class="ym_taskmg_textarea" rows=12 placeholder="填写对任务的文字描述" id="taskIntro">{{$task->intro}}</textarea>
            <div class="ym_taskmg_imgbtn_content" id="imgContainer">
            </div>
        </div>
    </div>
    <div class="ym_taskmg_page" id="editPage">
    </div>
</div>
@include('partview.publish.imagecontrols')
@show
@parent
@section('runScript')
<script type="text/javascript" src="/js/ym_publishtask.js"></script>
<script type="text/javascript" src="/js/ym_imageupload2.js"></script>
<script src="/js/cropper.min.js"></script>
<script type="text/javascript">
showEditMainPage(true);
var defValue = [];
@foreach($task->image as $img)
defValue.push('{{$img}}');
@endforeach
$('#imgContainer').ymImgField({
  'fieldName':'taskImage',
  'maxCount':9,
  'previewFormat':'64w_64h_1e_1c',
  'btnText':'添加图片',
  'prefix':'http://img.umeiii.com/',
  'value':defValue,
  'customBtn':'<div class="ym-imgfield-perview-item"><img src="/imgs/imgbtn.jpg" /></div>',
  'btnInPreview':true,
  'descLabel':'',
  'gifOnly':false,
  'onValueChange': function(type, field, fileName){
   }
  });
function saveDetail(){
    var text = $.trim($('#taskIntro').val());
    var img = $('input[name=taskImage]').val();
    if(text.length == 0){
        showError('请填写文字描述。');
        return;
    }
    $.post('/pubtask/savedetail',{
        'taskId':getId(),
        'text':text,
        'img':img,
        '_token':getToken(),
    },function(data){
        if(data.res){
            window.location = '/pubtask/manage-main/{{$task->id}}';
        }
    }).error(function(e){
        alert(e.responseText);
    });
}
</script>
@stop
@stop
