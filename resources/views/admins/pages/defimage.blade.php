@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['资源管理', '默认图片管理']])
<div class="am-container">
    <h1>默认图片</h1>
</div>
<div class="am-g">
    <div class="am-u-sm-4 md-u-md-2">
        <img class="am-img-thumbnail" src="http://img.umeiii.com/default.jpg" />
    </div>
    <div class="am-u-sm-8 md-u-md-l0">
        <button class="am-btn am-btn-primary" onclick='editDefImage()'>修改图片</button>
    </div>
</div>
<br />
<div class="am-g">
    <div class="am-u-sm-4">
        <img class="am-img-thumbnail" src="http://img.umeiii.com/default.jpg@100h_100w_1e_1c" />
    </div>
    <div class="am-u-sm-4">
        <img class="am-img-thumbnail" src="http://img.umeiii.com/default.jpg@75h_100w_1e_1c" />
    </div>
    <div class="am-u-sm-4">
        <img class="am-img-thumbnail" src="http://img.umeiii.com/default.jpg@100h_75w_1e_1c" />
    </div>
</div>
<div class="am-g">
    <div class="am-u-sm-4">
 <h3>样式一</h3>
    </div>
    <div class="am-u-sm-4">
 <h3>样式二</h3>
    </div>
    <div class="am-u-sm-4">
  <h3>样式三</h3>
    </div>
</div>
<!-- content end -->
@include('admins.partviews.modalcontrols')
<!--upload Image-->
@include('admins.partviews.uploadimage', ['st'=>$uploadParams])
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/uploadimage.js"></script>
<script type="text/javascript" charset="utf-8">

function editDefImage(id){
    if(!ImageController.checkRegist('replaceDefImg')){
        ImageController.regist('replaceDefImg',function(id,imgName){
            alert('已经修改了。');
            window.location.reload();
        });
    }
    ImageController.replaceDefImg(id,'default.jpg');
}

</script>
@stop
