@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['数据管理', '用户管理', '用户发布的'.(isset($type)? $type:'IP')]])
    <div class="am-g" style="min-height:35rem">
        <div class="am-u-sm-12">
            <table class="am-table am-table-bd am-table-striped admin-content-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    @if(isset($type) && $type == '台词')
                    <th>台词</th>
                    <th>角色</th>
                    @else
                    <th>名称</th>
                    @endif
                    <th>图片</th>
                    <th style="min-width:80px">访问网站</th>
                  </tr>
                </thead>
              <tbody>
                @foreach($itemShow as $item)
                <tr id ="row_{{$item['id']}}">
                    <td>{{$item['id']}}</td>
                    @if(isset($type) && $type == '台词')
                    <td>{{$item['name']}}</td>
                    <td>{{$item['author']}}</td>
                    @else
                    <td>{{$item['name']}}</td>
                    @endif
                    <td><img name='cover' src="{{$item['img']}}" /></td>
                    <td style="min-width:80px"><a href="{{$prefix.$item['id']}}">访问数据</a></td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    <div class="am-container" id="pageDiv">
        <?php echo $items->render()?>
    </div>
</div>
  <!-- content end -->
<!--confirm window-->
@include('admins.partviews.modalcontrols')

@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/admin/uploadimage.js"></script>
<script type="text/javascript" charset="utf-8">
</script>
<script type="text/javascript" charset="utf-8">
    formatPager($('#pageDiv'));
</script>
@stop
