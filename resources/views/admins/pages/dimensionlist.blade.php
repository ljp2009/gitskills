@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['数据管理', '次元管理'], 'searchControl'=>'/admin/dc/list'])
    <div class="am-g" style="min-height:83rem">
        <div class="am-u-sm-12">
            <table class="am-table am-table-bd am-table-striped admin-content-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>封面</th>
                    <th>作品名称</th>
                    <th>创建者</th>
                    <th>修改</th>
                    <th>删除</th>
                  </tr>
                </thead>
              <tbody>
                <?php $i = 0; ?>
                @foreach($dims as $dim)
                <tr id ="row_{{$dim->id}}">
                    <?php ++$i; ?>
                    <td>{{$dim->id}}</td>
                    <td><img name='cover' src="{{$dim->cover->getPath(1,'76h_57w_1e_1c')}}" /></td>
                    <td>{{$dim->name}}</td>
                    <td>{{is_null($dim->user_id)?'未知':$dim->user->display_name}}</td>
                    <td>
                      @if($num > 5 && $num - $i < 3)
                      <div class="am-dropdown am-dropdown-up" data-am-dropdown>
                      @else 
                      <div class="am-dropdown " data-am-dropdown>
                      @endif
                        <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle>
                          <span class="am-icon-cog"></span>
                          <span class="am-icon-caret-down"></span>
                        </button>
                        <ul class="am-dropdown-content">
                          <li><a href="/admin/dc/lately-user-list/{{$dim->id}}">1. 查看入驻</a></li>
                          <li><a href="/admin/dc/publish-list/{{$dim->id}}">2. 查看帖子</a></li>
                          <li><a href="javascript:void(0)" onclick="editDimCover({{$dim->id}})">3. 替换封面</a></li>
                        </ul>
                      </div>
                    </td>
                    <td><button class="am-btn am-btn-danger" onclick="deleteDim('{{$dim->id}}','{{$dim->name}}')" >删除</button></td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    <div class="am-container" id="pageDiv">
        <?php echo $dims->render()?>
    </div>
</div>
  <!-- content end -->
<!--confirm window-->
@include('admins.partviews.modalcontrols')

@include('admins.partviews.uploadimage', ['st'=>$uploadParams])
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/admin/uploadimage.js"></script>
<script type="text/javascript" charset="utf-8">
    function deleteDim(id,name) {
        doConfirm('确认删除','你确定要删除 <b>'+name+'</b> 吗？',id, function(recordId){
            $.post('/admin/dc/delete',{'id':recordId,  "_token":"{{ csrf_token() }}"},
             function(data){
                 $('#row_'+data).remove();
                 doAlert('删除成功。');
             }).error(function(a,b,c){
                 doAlert('删除失败。');
             });
        });
    }
    function editIntro(id) {
        $.get('/admin/dim/edit-intro/'+id,function(data){
            doPrompt('编辑简介',[
              {'type':'textarea','text':'链接', 'name':'intro', 'value':data['intro']},
              {'type':'hidden','text':'id', 'name':'id', 'value':data['id']}
            ],function(params){
                var postData = {'_token':'{{ csrf_token() }}'};
                for(var i=0;i<params.length;i++){
                    postData[params[i].name] = params[i].value;
                }
                $.post('/admin/dim/edit-intro',postData, function(data){
                    if(data == "true"){
                        doAlert("编辑成功。");
                    }
                }).error(function(a){
                    doAlert("编辑失败。");
                });
            });
        });
    }
    function editDimCover(id){
    if(!ImageController.checkRegist('replaceCover')){
        ImageController.regist('replaceCover',function(id,imgName){
            $.post('/admin/dc/edit-cover',
            {'id':id, 'cover':imgName, '_token':'{{ csrf_token() }}'},
            function(data){

                var dataRow = $('#row_'+data.id);
                var img = dataRow.find("img[name='cover']").attr('src', data.cover+'@76h_57w_1e_1c');
            }).error(function(a){
                alert(a.responseText);
            });
        });
    }
    ImageController.replaceCover(id);
}
</script>
<script type="text/javascript" charset="utf-8">
    formatPager($('#pageDiv'));
</script>
@stop
