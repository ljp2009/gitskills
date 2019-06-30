@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['数据管理', '作品', '角色']])
    <div class="am-g">
      <div class="am-u-sm-12">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th>ID</th>
            <th>名字</th>
            <th>头像</th>
            <th>图片</th>
            <th>描述</th>
            <th>删除</th>
            <th>修改</th>
          </tr>
          </thead>
          <tbody>
          @foreach($items as $item)
              <tr id ="row_{{$item->id}}">
                  <td>{{$item->id}}</td>
                  <td>{{$item->name}}</td>
                  <td><img src="{{$item->header->getPath(1,'64h_64w_1e|64x64-2rc')}}"/></td>
                  <td><img src="{{$item->image->getPath(1,'96w_64h_1e|96x64-2rc')}}"/></td>
                  <td>{{$item->intro}}</td>
                  <td><button class="am-btn am-btn-danger" onclick="deleteItem('{{$item->id}}')" >删除</button></td>
                  <td><button class="am-btn am-btn-primary" onclick="modifyTextItem('{{$item->id}}')" >修改</button></td>
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
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/admin/iprelate.js"></script>
<script type="text/javascript" charset="utf-8" >
formatPager($('#pageDiv'));
var objName = 'role';
var token = '{{csrf_token()}}';
var paramsArr =  [
        {'text':'名字','name':'name', 'type':'text', 'value':''},
        {'text':'描述','name':'intro', 'type':'text', 'value':''},
        {'text':'id','name':'id', 'type':'hidden', 'value':''},
    ];

</script>
@stop
