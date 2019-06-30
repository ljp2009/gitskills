@extends('admins.layouts.admin')
@section('detailcontent')
<link href="/css/admin/recommend.css" rel="stylesheet" />
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',
        ['titles'=>['推荐管理', '有妹推荐'], 'addBtn'=>'addBatch()', 'addText'=>'发布新推荐'])
    <div class="am-g">
      <div class="am-u-sm-12" sytle="min-height:5rem">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th style="width:50px">ID</th>
            <th style="width:120px">发布日期</th>
            <th>推荐</th>
            <th style="width:120px">创建日期</th>
            <th style="width:80px">删除</th>
            <th style="width:80px">编辑</th>
          </tr>
          </thead>
          <tbody>
            @foreach($items as $item)
            <tr id ="row_{{$item->id}}">
                <td>{{$item->id}}</td>
                <td>{{$item->publish_date}}</td>
                <td>
                   @foreach($item->recommends as $rec)
                    <img src="{{$rec->image->getPath(1,'64w_75h_1e_1c')}}" style="margin:5px"/>
                   @endforeach
                </td>
                <td>{{$item->created_at}}</td>
                <td><button class="am-btn am-btn-danger"  onclick="deleteBatch('{{$item->id}}')" >删除</button></td>
                <td><button class="am-btn am-btn-primary" onclick="editBatch('{{$item->id}}')" >修改</button></td>
            </tr>
            @endforeach
        </tbody>
        </table>
      </div>
    </div>
    <!--分页-->
    <div class="am-container" id="pageDiv">
        @if(!isset($search))
        <?php echo $items->render()?>
        @else
            <span>仅显示排序前30的查询</span>
            <br />
        @endif
    </div>
</div>
<!-- content end -->
@include('admins.partviews.modalcontrols')
<!--upload Image-->
@include('admins.partviews.uploadimage', ['st'=>$uploadParams])
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/admin/uploadimage.js"></script>
<script type="text/javascript" charset="utf-8">
function addBatch(){
    window.location = '/admin/rc/batch/0';
}
function editBatch(id){
    window.location = '/admin/rc/batch/'+id;
}
function deleteBatch(id){
    doConfirm('删除确认','你确定要删除这条记录吗?',id, function(param){
        $.post('/admin/rc/delete-batch',
        {'id':param, '_token':'{{ csrf_token() }}'},
        function(data){
            if(data.res){
                var dataRow = $('#row_'+data.id);
                dataRow.remove();
            }
        }).error(function(a){
            alert(a.responseText);
        });
    });
}
</script>
<script type="text/javascript"   charset="utf-8">
    formatPager($('#pageDiv'));
</script>
@stop
