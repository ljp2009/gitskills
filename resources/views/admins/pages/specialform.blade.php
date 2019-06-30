@extends('admins.layouts.admin')
@section('detailcontent')
<?php $isCreate = !isset($model) ?>
<!-- content start -->
<style type="text/css">
    .imgShow{
        width:200px;
        display:inline-block;
        margin-left:1rem;
    }
    .moveItem{
        border:solid 3px yellow;
    }
</style>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">推荐管理</strong> / <small>活动管理</small> / <small>修改活动</small></div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">{{$isCreate?'创建':'修改'}}专辑</a></li>
        </ul>
    </div>
    <div class="am-tabs-bd">
      <form id='form1' method="post" action="/admin/sp/special" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <input type="hidden" name="id" value="{{$isCreate?0:$model->id}}">
          <div class="am-tab-panel am-fade am-in am-active" id="tab1">
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">专辑名称</div>
              <div class="am-u-sm-8 am-u-md-10">
                <input  name='name' type='text' class="am-form-field" value="{{$isCreate?'':$model->name}}" />
              </div>
            </div>
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">专辑简介</div>
              <div class="am-u-sm-8 am-u-md-10">
                <textarea name='intro' rows='5' class="am-form-field">{{$isCreate?'':$model->intro}}</textarea>
              </div>
            </div>
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">发布日期</div>
              <div class="am-u-sm-8 am-u-md-10">
                <input  name='publish_date' type='date' class="am-form-field"
                    value="{{$isCreate?date('Y-m-d'): date('Y-m-d', strtotime($model->publish_date))}}" />
              </div>
            </div>
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">图片</div>
              <div class="am-u-sm-8 am-u-md-10">
                @if($isCreate)
                <div id ='imgShow' style="margin-bottom:1rem">
                    <img id="imgPerview" src="http://img.umeiii.com/default.jpg@145w_90h_1e_1c"
                        style="height:90px;width:145px;border:solid 1px #e2e2e2;" />
                </div>
                <input type="hidden" name='image' value='' />
                @else
                <div id ='imgShow' style="margin-bottom:1rem">
                    <img id="imgPerview" src="{{$model->img->getPath(1,'145w_90h_1e_1c')}}"
                        style="height:90px;width:145px;border:solid 1px #e2e2e2;" />
                </div>
                <input type="hidden" name='image' value='{{$model->img}}' />
                @endif
                <span class="am-btn am-btn-success am-btn-xs" onclick="changeImg()"> 设置封面 </span>
              </div>
            </div>
      </form>
    </div>
  <div class="am-margin">
    <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick="subUpdate()">
        提交保存
    </button>
  </div>
</div>
@include('admins.partviews.uploadimage', ['st'=>$uploadParams])
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/uploadimage.js"></script>
<script type="text/javascript" charset="utf-8">
    var selectedItem = '';
    function subUpdate(){
        $('#form1').submit();
    }
    function changeImg(){
        if(!ImageController.checkRegist('addImg')){
            ImageController.regist('addImg',function(id,imgName){
                var imgValue = $('input[name="image"]');
                imgValue.val(imgName);
                $('#imgPerview').attr('src', 'http://img.umeiii.com/'+imgName+'@145w_90h_1e_1c');
            });
        }
        ImageController.addImg(0);
    }
</script>
@stop
