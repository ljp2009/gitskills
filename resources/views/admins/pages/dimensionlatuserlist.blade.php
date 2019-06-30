@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['数据管理', '次元', '入驻用户']])
    <div class="am-g">
      <div class="am-u-sm-12">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th>ID</th>
            <th>头像</th>
            <th>姓名</th>
            <th>邮箱</th>
            <th>手机</th>
            <th>是否大神</th>
          </tr>
          </thead>
          <tbody>
          @foreach($items as $item)
              <tr id ="row_{{$item->id}}">
                  <td>{{$item->id}}</td>
                  <td><img name='cover' src="{{$item->user->avatar->getPath(2,'64h_64w_1e_1c')}}" /></td>
                  <td>{{is_null($item->user_id)? '未知':$item->user->display_name}}</td>
                  <td>{{$item->user->email}}</td>
                  <td>{{$item->user->mobile}}</td>
                  <td>{{$item->user->isExpert?'是':'否'}}</td>
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

@include('admins.partviews.modalcontrols')
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js"></script>
<script type="text/javascript" charset="utf-8" >

formatPager($('#pageDiv'));
</script>

@stop
