@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['数据管理', '作品', '场景']])
    <div class="am-g">
      <div class="am-u-sm-12">
        <table class="am-table am-table-bd am-table-striped admin-content-table" style="margin-bottom:200px">
          <thead>
          <tr>
            <th>ID</th><th>图片</th>
            <th>描述</th>
            <th style="width:120px">时间</th>
            <th style="width:80px">状态</th>
            <th style="width:80px">操作</th>
          </tr>
          </thead>
          <tbody>
          @foreach($items as $item)
              <tr id ="row_{{$item->id}}">
                  <td>{{$item->id}}</td>
                  <td><img src="{{$item->cover->getPath(1,'64h_100w_1e_1c')}}"/></td>
                  <td>{{$item->text}}</td>
                  <td style="width:120px">{{$item->created_at}}</td>
                  <td style="width:80px">{{$item->getVerifiedDisplay()}}</td>
                  <td style="width:80px">
                      <div class="am-dropdown " data-am-dropdown>
                        <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle>
                          <span class="am-icon-cog"></span>
                          <span class="am-icon-caret-down"></span>
                        </button>
                        <ul class="am-dropdown-content">
                          <li><a href="javascript:void(0)" onclick="modifyTextItem('{{$item->id}}')">修改文字</a></li>
                          <li><a href="javascript:void(0)" onclick="adminDeleteItem({{$item->id}}, 'ip_scene')">删除</a></li>
                          <li><a href="javascript:void(0)" onclick="adminApproveItem({{$item->id}}, 'ip_scene')">通过</a></li>
                          <li><a href="javascript:void(0)" onclick="adminRejectItem({{$item->id}}, 'ip_scene')">拒绝</a></li>
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
        @if(!isset($search))
        <?php echo $items->render()?>
        @else
            <span>仅显示排序前30的查询结果，如果结果多于30，请尽量输入详细作品名称</span>
            <br />
        @endif
    </div>
</div>
<!-- content end -->

@include('admins.partviews.modalcontrols')
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js?a=1"></script>
<script type="text/javascript" charset="utf-8" src="/js/admin/iprelate.js"></script>
<script type="text/javascript" charset="utf-8" >
formatPager($('#pageDiv'));
var objName = 'scene';
var token = '{{csrf_token()}}';
var paramsArr =  [
        {'text':'场景描述','name':'text', 'type':'textarea', 'value':''},
        {'text':'id','name':'id', 'type':'hidden', 'value':''},
    ];

</script>
@stop
