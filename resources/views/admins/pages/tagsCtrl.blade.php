@extends('admins.layouts.admin')
@section('detailcontent')
<link rel="stylesheet" href="/css/admin/admin.css">
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong> / <small>标签库</small></div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">操作</a></li>
        </ul>
    </div>
    <div class="am-tabs-bd">
        <div class="am-g am-margin-top">
          <div class="am-u-sm-4 am-u-md-2 am-text-right">操作</div>
          <div class="am-u-sm-8 am-u-md-10">
        删除和修改标签，并不会影响到已经标记的IP标签名称。<br/>
        <button type="button" class="am-btn am-btn-primary am-btn-xs"
             onclick="addTag()">
            添加标签
        </button>

          </div>
        </div>
        <div class="am-g am-margin-top">
        </div>
        @foreach($tags as $key => $depend)
        <div class="am-g am-margin-top">
          <div class="am-u-sm-4 am-u-md-2 am-text-right">{{$key}}</div>
          <div class="am-u-sm-8 am-u-md-10">
            @foreach($depend as $tag)
            <div class="div_tag">
                <button class="div_tag_text" value="{{ $tag->code }}" onclick="editTag('{{$tag->code}}','{{$tag->name}}')">{{ $tag->name }}</button>
                <button class="div_tag_btn" value="{{ $tag->code }}" onclick="deleteTag('{{$tag->code}}','{{$tag->name}}')">X</button>
            </div>
            @endforeach
          </div>
        </div>
        @endforeach
    </div>
</div>
@include('admins.partviews.modalcontrols')
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/admin/iprelate.js"></script>
<script type="text/javascript" charset="utf-8">
function addTag(){
    doPrompt("添加标签", [
        {'text':'类别','name':'type', 'type':'enum',
            'options':[
                {'name':'cartoon', 'value':'cartoon'},
                {'name':'story', 'value':'story'},
                {'name':'light', 'value':'light'},
                {'name':'game', 'value':'game'},
            ], 'value':''},
        {'text':'名称','name':'tagName', 'type':'text', 'value':''},
    ] ,function(params){
          var postData = {
            'type':params[0].value,
            'tagName':params[1].value,
            '_token':'{{csrf_token()}}'
          };
          $.post('/admin/ctrl/add-tag', postData, function(data){
              if(data.res == false){
                doAlert(data.info);
                return;
              }
              window.location.reload();
          }).error(function(e){
              doAlert("编辑失败。");
          });
    });
}
function editTag(code, name){
    doPrompt("编辑标签", [
        {'text':'名称','name':'tagName', 'type':'text', 'value':name},
        {'text':'','name':'code', 'type':'hidden', 'value':code},
    ] ,function(params){
          var postData = {
            'tagName':params[0].value,
            'code':params[1].value,
            '_token':'{{csrf_token()}}'
          };
          $.post('/admin/ctrl/edit-tag', postData, function(data){
              if(data.res == false){
                doAlert(data.info);
                return;
              }
              window.location.reload();
          }).error(function(e){
              doAlert("编辑失败。");
          });
    });
}
function deleteTag(code, name){
    doConfirm("确认删除标签",'你确定要删除标签"'+name+'"吗？', code, function(param){
          var postData = {
            'code':param,
            '_token':'{{csrf_token()}}'
          };
          $.post('/admin/ctrl/delete-tag', postData, function(data){
              if(data.res == false){
                doAlert(data.info);
                return;
              }
              window.location.reload();
          }).error(function(e){
              doAlert("编辑失败。");
          });
    });
}
</script>
@stop
