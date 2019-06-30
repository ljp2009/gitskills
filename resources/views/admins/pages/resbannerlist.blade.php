@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['推荐管理', '资源大厅封面'], 'addBtn'=>'addBanner()'])
    <div class="am-g">
      <div class="am-u-sm-12" sytle="min-height:5rem">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th>ID</th>
            <th>图片</th>
            <th>链接</th>
            <th>文字</th>
            <th>创建时间</th>
            <th>删除</th>
            <th>编辑</th>
            <th>替换图片</th>
          </tr>
          </thead>
          <tbody>
            @foreach($items as $item)
            <tr id ="row_{{$item->id}}">
                <td>{{$item->id}}</td>
                <td><img name='image' src="{{$item->image->getPath(1,'64h_100w_1e_1c')}}"/></td>
                <td name='url'>{{$item->url}}</td>
                <td name='description'>{{$item->description}}</td>
                <td>{{$item->created_at}}</td>
                <td><button class="am-btn am-btn-danger"  onclick="deleteBanner('{{$item->id}}')" >删除</button></td>
                <td><button class="am-btn am-btn-primary" onclick="editBanner('{{$item->id}}')" >修改</button></td>
                <td><button class="am-btn am-btn-primary" onclick="editBannerImage('{{$item->id}}')" >替换图片</button></td>
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
function addBanner(){
    doPrompt('添加大厅封面推荐', [
          {'type':'text','text':'链接', 'name':'url', 'value':''},
          {'type':'textarea','text':'描述', 'name':'description', 'value':''} ],
        function(params){
            var str = '';
            var postData = {'_token':'{{ csrf_token() }}'};
            for(var i=0;i<params.length;i++){
                postData[params[i].name] = params[i].value;
            }
           $.post('/admin/rc/add-banner', postData, function(data){
                window.location = '/admin/rc/banner-list';
            })
            .error(function(a){
                alert(a);
            });
        });
}
function editBanner(id){
   $.get('/admin/rc/banner/'+id,{},function(data){
        doPrompt('修改大厅封面推荐', [
          {'type':'text','text':'链接', 'name':'url', 'value':data['url']},
          {'type':'textarea','text':'描述', 'name':'description', 'value':data['description']},
          {'type':'hidden','text':'id', 'name':'id', 'value':data['id']}],
        function(params){
            var postData = {'_token':'{{ csrf_token() }}', 'attr':''};
            for(var i=0;i<params.length;i++){
                postData[params[i].name] = params[i].value;
                postData['attr'] += (','+params[i].name);
            }
            $.post('/admin/rc/edit-banner', postData, function(data){
                window.location = '/admin/rc/banner-list';
            }) .error(function(a){
                alert(a);
            });
        });
    })
    .error(function(a){
        alert(a);
    });
}
function editBannerImage(id){
    if(!ImageController.checkRegist('replaceBannerImg')){
        ImageController.regist('replaceBannerImg',function(id,imgName){
            $.post('/admin/rc/edit-banner',
            {'id':id, 'image':imgName,'attr':'image', '_token':'{{ csrf_token() }}'},
            function(data){
                var dataRow = $('#row_'+data.id);
                var img = dataRow.find("img[name='image']").attr('src', data.image+'@64h_100w_1e_1c');
            }).error(function(a){
                alert(a.responseText);
            });
        });
    }
    ImageController.replaceBannerImg(id);
}
function deleteBanner(id){
    doConfirm('删除确认','你确定要删除这条记录吗?',id, function(param){
        $.post('/admin/rc/delete-banner',{'id':param, '_token':'{{ csrf_token() }}'},function(data){
            var dataRow = $('#row_'+data);
            dataRow.remove();
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
