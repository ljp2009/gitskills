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
                    <th>名称</th>
                    <th>图片</th>
                    <th>用户</th>
                    <th>状态</th>
                    <th>发布时间</th>
                    <th style="min-width:80px">访问作品</th>
                    <th >操作</th>
                  </tr>
                </thead>
              <tbody>
                @foreach($items as $item)
                <tr id ="row_{{$item->id}}">
                    <td>{{$item->id}}</td>
                    <td>{{$item->name}}</td>
                    <td><img name='cover' src="{{$item->cover->getPath('1', '64w_64h_1e_1c')}}" /></td>
                    <td style="min-width:80px">{{$item->user->display_name}}</td>
                    <td style="min-width:80px">{{$item->getVerifiedDisplay()}}</td>
                    <td style="min-width:80px">{{$item->created_at}}</td>
                    <td style="min-width:80px"><a href="{{$item->detailUrl}}">访问作品</a></td>
                    <td style="min-width:80px">
                      <div class="am-dropdown " data-am-dropdown>
                        <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle>
                          <span class="am-icon-cog"></span>
                          <span class="am-icon-caret-down"></span>
                        </button>
                        <ul class="am-dropdown-content">
                          <li><a href="javascript:void(0)" onclick="adminDeleteItem({{$item->id}}, 'user_production')">删除</a></li>
                          <li><a href="javascript:void(0)" onclick="adminApproveItem({{$item->id}}, 'user_production')">通过</a></li>
                          <li><a href="javascript:void(0)" onclick="adminRejectItem({{$item->id}}, 'user_production')">拒绝</a></li>
                        </ul>
                      </div>
                    </td>
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
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js?a=1"></script>
<script type="text/javascript" charset="utf-8" src="/js/admin/uploadimage.js"></script>
<script type="text/javascript" charset="utf-8">
</script>
<script type="text/javascript" charset="utf-8">
formatPager($('#pageDiv'));
</script>
@stop
