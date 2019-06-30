@extends('admins.layouts.admin')
@section('detailcontent')
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
            <li class="am-active"><a href="#tab1">修改活动</a></li>
        </ul>
    </div>
    <div class="am-tabs-bd">
      <form id='form1' method="post" action="/admin/act/submit" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <input type="hidden" name="id" value="{{$model->id}}">
          <div class="am-tab-panel am-fade am-in am-active" id="tab1">
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">标题</div>
              <div class="am-u-sm-8 am-u-md-10">
                <input  name='title' type='text' class="am-form-field" value="{{$model->title}}" />
              </div>
            </div>
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">简介</div>
              <div class="am-u-sm-8 am-u-md-10">
                <textarea name='text' rows='5' class="am-form-field">{{$model->text}}</textarea>
              </div>
            </div>
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">开始日期</div>
              <div class="am-u-sm-8 am-u-md-10">
                <input  name='from_date' type='date' class="am-form-field"
                    value="{{is_null($model->from_date)?date('Y-m-d'): date('Y-m-d', strtotime($model->from_date))}}" />
              </div>
            </div>
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">结束日期</div>
              <div class="am-u-sm-8 am-u-md-10">
                <input  name='to_date' type='date' class="am-form-field"
                    value="{{is_null($model->to_date)?date('Y-m-d'):date('Y-m-d', strtotime( $model->to_date))}}" />
              </div>
            </div>
             <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">活动类型</div>
              <div class="am-u-sm-8 am-u-md-10">
                <select class="am-form-field" name='is_offline' readonly="readonly">
                    <!--<option value='1' {{$model->is_offline?'selected':''}}>线下活动</option>-->
                    <option value='0' {{$model->is_offline?'':'selected'}}>线上活动</option>
                </select>
              </div>
            </div>
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">活动链接</div>
              <div class="am-u-sm-8 am-u-md-10">
                <input  name='linkText' type='text' class="am-form-field" value="{{$model->join_link}}" />
              </div>
            </div>
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">图片</div>
              <div class="am-u-sm-8 am-u-md-10">
                <div id ='imgShow' style="margin-bottom:1rem">
               </div>
                <input type="hidden" name='image' value='{{";".implode(";",$model->image)}}' />
                <span id="add_image" class="am-btn am-btn-success am-btn-xs" onclick="changeImg()"> 添加图片 </span>
                <!--<span id="add_image2">点击两个图片可以交换图片位置，第一张图片会作为活动的封面。</span>-->
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
                imgValue.val(imgValue.val()+';'+imgName);
                appendImg(imgName);
            });
        }
        ImageController.addImg(0);
    }
    function appendImg(imageName){
        var imgDiv = $('<div class="imgShow" id="'+imageName+'"></div>');
        imgDiv.append('<img src="http://img.umeiii.com/'+imageName+'" style="width:100%;display:block" onclick="moveImg(\''+imageName+'\')"/>');
        imgDiv.append('<span class="am-btn am-btn-danger" style="width:100%;display:block" onclick="deleteImg(\''+imageName+'\')">删除</span>');
        $('#imgShow').append(imgDiv);
        $('#add_image').hide();
    }
    function moveImg(imgName){
        var item =  $('.moveItem');
        if(item.length > 0){
            var nowItem = $('.imgShow[id="'+imgName+'"]');
            var itemImg = item.find('img');
            var nowItemImg = nowItem.find('img');
            var tmp = itemImg.attr('src');
            itemImg.attr('src', nowItemImg.attr('src'));
            nowItemImg.attr('src', tmp);
            $('.imgShow').removeClass('moveItem');
            var imgValue =$('input[name="image"]').val();
            var itemValue = item.attr('id');
            imgValue = imgValue.replace(';'+imgName,'[[nowvalue]]');
            imgValue = imgValue.replace(';'+itemValue,'[[itemvalue]]');
            imgValue = imgValue.replace('[[itemvalue]]',';'+imgName);
            imgValue = imgValue.replace('[[nowvalue]]',';'+itemValue);
            $('input[name="image"]').val(imgValue);
        }else{
            $('.imgShow[id="'+imgName+'"]').addClass('moveItem');
        }
    }
    function deleteImg(imgName){
        if(confirm('确认删除？')){
            $('.imgShow[id="'+imgName+'"]').remove();
            var imgValue =$('input[name="image"]');
            imgValue.val(imgValue.val().replace(';'+imgName,''));
            $('#add_image').show();
        }
    }
    var imgNames =$('input[name="image"]').val().split(';');
    for(var i=0; i<imgNames.length; i++){
        if(imgNames[i] != ''){
            appendImg(imgNames[i]);
        }
    }
</script>
@stop
