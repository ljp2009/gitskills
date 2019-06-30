@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['数据管理', '次元', '帖子']])
    <div class="am-g">
      <div class="am-u-sm-12">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th>ID</th>
            <th>描述</th>
            <th>创建者</th>
            <th>删除</th>
          </tr>
          </thead>
          <tbody>
          @foreach($items as $item)
              <tr id ="row_{{$item->id}}">
                  <td>{{$item->id}}</td>
                  <td>{{$item->text}}</td>
                  <td>{{is_null($item->user_id)? '未知':$item->user->display_name}}</td>
                  <td><button class="am-btn am-btn-danger" onclick="deleteDim('{{$item->id}}','{{$item->user->display_name}}')" >删除</button></td>
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
function deleteDim(id,name) {
    doConfirm('确认删除','你确定要删除 <b>'+name+'</b> 发布的帖子吗？',id, function(recordId){
        $.post('/admin/dc/delete-publish',{'id':recordId,  "_token":"{{ csrf_token() }}"},
         function(data){
             $('#row_'+data).remove();
             doAlert('删除成功。');
         }).error(function(a,b,c){
             doAlert('删除失败。');
         });
    });
}
formatPager($('#pageDiv'));
</script>

@stop
