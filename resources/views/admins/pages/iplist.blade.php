@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['数据管理', '作品管理'], 'searchControl'=>'/admin/ip/list'])
    <div class="am-g" style="min-height:83rem">
        <div class="am-u-sm-12">
            <table class="am-table am-table-bd am-table-striped admin-content-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>封面</th>
                    <th>作品名称</th>
                    <th>分类</th>
                    <th>like数量</th>
                    <th>创建者</th>
                    <th>修改</th>
                    <th>删除</th>
                  </tr>
                </thead>
              <tbody>
                <?php $i = 0; ?>
                @foreach($ips as $ip)
                <tr id ="row_{{$ip->id}}">
                    <?php ++$i; ?>
                    <td>{{$ip->id}}</td>
                    <td><img name='cover' src="{{$ip->cover->getPath(1,'76h_57w_1e_1c')}}" /></td>
                    <td>{{$ip->name}}</td>
                    <td>{{$ip->type}}</td>
                    <td>{{$ip->likeCount}}</td>
                    <td>{{is_null($ip->user_id)?'未知':$ip->user->display_name}}</td>
                    <td>
                      @if($num - $i < 4)
                      <div class="am-dropdown am-dropdown-up" data-am-dropdown>
                      @else 
                      <div class="am-dropdown " data-am-dropdown>
                      @endif
                        <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle>
                          <span class="am-icon-cog"></span>
                          <span class="am-icon-caret-down"></span>
                        </button>
                        <ul class="am-dropdown-content">
                          <li><a href="javascript:void(0)" onclick="editIntro({{$ip->id}})">编辑简介</a></li>
                          <li><a href="javascript:void(0)" onclick="editTitle({{$ip->id}})">编辑标题</a></li>
                          <li><a href="/admin/ip/attr-list/{{$ip->id}}">编辑属性</a></li>
                          <li><a href="/admin/ip/role-list/{{$ip->id}}">编辑角色</a></li>
                          <li><a href="/admin/ip/scene-list/{{$ip->id}}">编辑场景</a></li>
                          <li><a href="/admin/ip/dial-list/{{$ip->id}}">编辑台词</a></li>
                          <li><a href="/admin/ip/coll-list/{{$ip->id}}">编辑同人</a></li>
                          <li><a href="/admin/ip/peri-list/{{$ip->id}}">编辑周边</a></li>
                          <li><a href="/admin/ip/disc-list/{{$ip->id}}">编辑长评论</a></li>
                          <li><a href="javascript:void(0)" onclick="editIpCover({{$ip->id}})">替换封面</a></li>
                          <li><a href="/admin/ip/tag-list/{{$ip->id}}">编辑标签</a></li>
                        </ul>
                      </div>
                    </td>
                    <td><button class="am-btn am-btn-danger" onclick="deleteIp('{{$ip->id}}','{{$ip->name}}')" >删除</button></td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    <div class="am-container" id="pageDiv">
        <?php echo $ips->render()?>
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
    function deleteIp(id,name) {
        doConfirm('确认删除','你确定要删除 <b>'+name+'</b> 吗？',id, function(recordId){
            $.post('/admin/ip/delete/',{'id':recordId,  "_token":"{{ csrf_token() }}"},
             function(data){
                 $('#row_'+data).remove();
                 doAlert('删除成功。');
             }).error(function(a,b,c){
                 doAlert('删除失败。');
             });
        });
    }
    function editIntro(id) {
        $.get('/admin/ip/edit-intro/'+id,function(data){
            doPrompt('编辑简介',[
              {'type':'textarea','text':'简介', 'name':'intro', 'value':data['intro']},
              {'type':'hidden','text':'id', 'name':'id', 'value':data['id']}
            ],function(params){
                var postData = {'_token':'{{ csrf_token() }}'};
                for(var i=0;i<params.length;i++){
                    postData[params[i].name] = params[i].value;
                }
                $.post('/admin/ip/edit-intro',postData, function(data){
                    if(data == "true"){
                        doAlert("编辑成功。");
                    }
                }).error(function(a){
                    doAlert("编辑失败。");
                });
            });
        });
    }
    function editTitle(id) {
        $.get('/admin/ip/edit-title/'+id,function(data){
            doPrompt('编辑标题',[
              {'type':'textarea','text':'标题', 'name':'title', 'value':data['title']},
              {'type':'hidden','text':'id', 'name':'id', 'value':data['id']}
            ],function(params){
                var postData = {'_token':'{{ csrf_token() }}'};
                for(var i=0;i<params.length;i++){
                    postData[params[i].name] = params[i].value;
                }
                $.post('/admin/ip/edit-title',postData, function(data){
                    if(data == "true"){
                        doAlert("编辑成功。");
                        window.location.reload();
                    }
                }).error(function(a){
                    doAlert("编辑失败。");
                });
            });
        });
    }
    function editIpCover(id){
    if(!ImageController.checkRegist('replaceCover')){
        ImageController.regist('replaceCover',function(id,imgName){
            $.post('/admin/ip/edit-cover',
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
