@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['数据管理', '用户管理'], 'searchControl'=>'/admin/user/list'])
    <div class="am-g" style="min-height:35rem">
        <div class="am-u-sm-12">
            <table class="am-table am-table-bd am-table-striped admin-content-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>姓名</th>
                    <th>头像</th>
                    <th>邮箱</th>
                    <th>手机</th>
                    <th>是否大神</th>
                    <th>操作</th>
                  </tr>
                </thead>
              <tbody>
                <?php $i = 0; ?>
                @foreach($items as $item)
                <tr id ="row_{{$item->id}}">
                   <?php ++$i; ?>
                    <td>{{$item->id}}</td>
                    <td>{{$item->display_name}}</td>
                    <td><img name='cover' src="{{$item->avatar->getPath(2,'64h_64w_1e_1c')}}" style="width:64px;height:64px;border-radius:50%" /></td>
                    <td>{{$item->mobile}}</td>
                    <td>{{$item->email}}</td>
                    <td>{{$item->isExpert?'是':'否'}}</td>
                    <td>
                      @if(15- $i < 4)
                      <div class="am-dropdown am-dropdown-up" data-am-dropdown>
                      @else 
                      <div class="am-dropdown " data-am-dropdown>
                      @endif
                        <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle>
                          <span class="am-icon-cog"></span>
                          <span class="am-icon-caret-down"></span>
                        </button>
                        <ul class="am-dropdown-content">
                          <li><a href="/admin/user/ip-list/{{$item->id}}">1. 发布的IP</a></li>
                          <li><a href="/admin/user/role-list/{{$item->id}}">2. 发布的角色</a></li>
                          <li><a href="/admin/user/dim-list/{{$item->id}}">3. 发布的次元</a></li>
                          <li><a href="/admin/user/scene-list/{{$item->id}}">4. 发布场景</a></li>
                          <li><a href="/admin/user/dial-list/{{$item->id}}">5. 发布台词</a></li>
                          <li><a href="/admin/user/prod-list/{{$item->id}}">6. 发布作品</a></li>
                          <li><a href="/admin/user/coll-list/{{$item->id}}">7. 发布同人</a></li>
                          <li><a href="/admin/user/peri-list/{{$item->id}}">8. 发布周边</a></li>
                          <li><a href="/admin/user/disc-list/{{$item->id}}">9. 发布长评论</a></li>
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
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/admin/uploadimage.js"></script>
<script type="text/javascript" charset="utf-8">
</script>
<script type="text/javascript" charset="utf-8">
    formatPager($('#pageDiv'));
</script>
@stop
