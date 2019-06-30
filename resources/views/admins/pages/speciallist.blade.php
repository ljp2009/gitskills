@extends('admins.layouts.admin')
@section('detailcontent')
<link href="/css/admin/recommend.css" rel="stylesheet" />
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',
        ['titles'=>['推荐管理', '专辑管理'], 'addBtn'=>'addSpecial()', 'addText'=>'创建新专辑'])
    <div class="am-g">
      <div class="am-u-sm-12" sytle="min-height:5rem">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th style="width:50px">ID</th>
            <th style="width:120px">发布日期</th>
            <th>封面</th>
            <th>标题</th>
            <th>介绍</th>
            <th style="width:120px">创建日期</th>
            <th style="width:80px">状态</th>
            <th style="width:80px">编辑内容</th>
            <th style="width:80px">编辑</th>
            <th style="width:80px">删除</th>
          </tr>
          </thead>
          <tbody>
            @foreach($items as $item)
            <tr id ="row_{{$item->id}}">
                <td>{{$item->id}}</td>
                <td>{{$item->publish_date}}</td>
                <td><img src="{{$item->img->getPath(1,'145w_90h_1e_1c')}}" /></td>
                <td>{{$item->name}}</td>
                <td>{{$item->intro}}</td>
                <td>{{$item->created_at}}</td>
                <td><button id="statusBtn_{{$item->id}}" class="am-btn am-btn-primary" onclick="changeStatus('{{$item->id}}')"
                        status="{{$item->status}}" > {{$item->status==0?'启用':'停用'}}</button></td>
                <td><button class="am-btn am-btn-primary" onclick="editSpecialItem('{{$item->id}}')" >编辑内容</button></td>
                <td><button class="am-btn am-btn-primary" onclick="editSpecial('{{$item->id}}')" >编辑信息</button></td>
                <td><button class="am-btn am-btn-danger" onclick="deleteSpecial('{{$item->id}}')" >删除</button></td>
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
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/admin/uploadimage.js"></script>
<script type="text/javascript" charset="utf-8">
function addSpecial(){
    window.location = '/admin/sp/special/0';
}
function editSpecial(id){
    window.location = '/admin/sp/special/'+id;
}
function editSpecialItem(id){
    window.location = '/admin/sp/item-list/'+id;
}
function deleteSpecial(id){
    doConfirm('删除确认','你确定要删除这条记录吗?',id, function(param){
        $.post('/admin/sp/delete-special',
        {'id':param, '_token':'{{ csrf_token() }}'},
        function(data){
            if(data.res){
                var dataRow = $('#row_'+data.id);
                dataRow.remove();
            }
        }).error(function(a){
            alert($(a.responseText).find('body').html);
        });
    });
}
function changeStatus(id){
    var $status = $('#statusBtn_'+id);
    var status =$status.attr('status');
    var newStatus = (status==0?1:0);
    var label = newStatus==0?'停用':'启用';
    doConfirm('','你确定要'+label+'这条记录吗?',id, function(param){
        $.post('/admin/sp/change-status',
        {'id':id, 'status':newStatus,  '_token':'{{ csrf_token() }}'},
        function(data){
            if(data.res){
                $status.attr('status', newStatus);
                $status.text(newStatus==0?'启用':'停用');
            }
        }).error(function(a){
            alert($(a.responseText).find('body').html);
        });
    });

}
</script>
<script type="text/javascript" charset="utf-8">
    formatPager($('#pageDiv'));
</script>
@stop
