@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['推荐管理', '活动管理'], 'addBtn'=>'addItem();'])
    <div class="am-g" style="min-height:35rem">
        <div class="am-u-sm-12">
            <table class="am-table am-table-bd am-table-striped admin-content-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>图片</th>
                    <th>主题</th>
                    <th>分类</th>
                    <th>时间</th>
                    <th>创建者</th>
                    <th>推荐</th>
                    <th>修改</th>
                    <th>删除</th>
                    <th>启用/禁用</th>
                    <th>操作</th>
                  </tr>
                </thead>
              <tbody>
                @foreach($items as $item)
                <tr id ="row_{{$item->id}}">
                    <td>
                    @if($item->is_recommend)
                    <i class="am-icon-star" style="color:red"></i>
                    @endif
                    {{$item->id}}</td>
                    <td><img name='cover' src="{{$item->cover->getPath(1,'76h_76w_1e_1c')}}" /></td>
                    <td>{{$item->title}}</td>
                    <td>{{$item->is_offline?'线下':'线上'}}</td>
                    <td>{{$item->from_date}}-{{$item->to_date}}</td>
                    <td>{{$item->user->display_name}} </td>
                    <td>
                    @if($item->is_recommend)
                        <button class="am-btn am-btn-primary" onclick="recommendItem('{{$item->id}}','{{$item->title}}','false')" >取消推荐</button>
                    @else
                        <button class="am-btn am-btn-primary" onclick="recommendItem('{{$item->id}}','{{$item->title}}','true')" >推荐</button>
                    @endif
                    </td>
                    <td><button class="am-btn am-btn-success" onclick="modifyItem({{$item->id}})" >修改</button></td>
                    
                    
                    <td><button class="am-btn am-btn-danger" onclick="deleteItem('{{$item->id}}','{{$item->title}}')" >删除</button></td>
                    <td>
                              <button id="btn_{{$item->id}}" class="am-btn {{$item->is_forbidden?'am-btn-danger':'am-btn-success'}}" onclick="set_forbidden('{{$item->id}}')" value="{{$item->is_forbidden}}" >
                          {{$item->is_forbidden?'已禁用':'已启用'}}</button>
                    </td>
                    <td>
                      @if(false)
                      <div class="am-dropdown am-dropdown-up" data-am-dropdown>
                      @else 
                      <div class="am-dropdown " data-am-dropdown>
                      @endif
                        <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle>
                          <span class="am-icon-cog"></span>
                          <span class="am-icon-caret-down"></span>
                        </button>
                        <ul class="am-dropdown-content">
                          <!--<li><a href="javascript:void(0)" onclick="editIntro({{$item->id}})">1. 编辑简介</a></li>-->
                          <li><a href="/admin/act/act-joins-list/{{$item->id}}">参与列表</a></li>
                          <li><a href="/admin/act/act-edit-result/{{$item->id}}">结束活动</a></li>
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
    function set_forbidden(act_id){
        status = $('#btn_'+act_id).val();
        $.ajax({
            type:'POST',
            url:'/admin/act/act-is-forbidden',
            data:{'status':status,'_token':'{{csrf_token()}}','act_id':act_id},
            dataType:'json',
            success:function(data){
                if(data.res == 1){
                    if(status == 1){
                        $('#btn_'+act_id).removeClass('am-btn-danger');
                        $('#btn_'+act_id).addClass('am-btn-success');
                        $('#btn_'+act_id).html('已启用');
                        $('#btn_'+act_id).val(0);
                    }else{
                        $('#btn_'+act_id).removeClass('am-btn-success');
                        $('#btn_'+act_id).addClass('am-btn-danger');
                        $('#btn_'+act_id).html('已禁用');
                        $('#btn_'+act_id).val(1);
                    }
                }
            }
        });
    }
function addItem(){
    window.location='/admin/act/add';
}
function modifyItem(id){
    window.location = '/admin/act/modify/'+id;
}
function deleteItem(id,name){
    if(confirm('确定要删除活动['+name+']吗？')){
        $.post('/admin/act/delete',{
            '_token':'{{csrf_token()}}',
            'id':id
        },function(data){
            if(data.res){
                window.location.reload();
            }
        }).error(function(e){
            alert(e.responseText);
        });
    }
}
function recommendItem(id,name,type){
    var typeName= (type=="true"?"推荐":"取消推荐");
    if(confirm('确定要在首页'+typeName+'活动['+name+']吗？')){
        $.post('/admin/act/recommend',{
            '_token':'{{csrf_token()}}',
            'type':type,
            'id':id
        },function(data){
            if(data.res){
                window.location.reload();
            }
        }).error(function(e){
            alert(e.responseText);
        });
    }
}
</script>
<script type="text/javascript" charset="utf-8">
    formatPager($('#pageDiv'));
</script>
@stop
