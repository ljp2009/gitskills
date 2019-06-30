@extends('admins.layouts.admin')
@section('detailcontent')
<style>
    .join_img{width: 100px;height: 50px;}
    .big_red{color: red;}
</style>
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['推荐管理', '活动管理', '参与作品']])
    <div class="am-g">
      <div class="am-u-sm-12">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th>ID</th>
            <th>图片</th>
            @if($model->type == 11)
            <th>标题</th>
            @endif
            <th>文字</th>
            <th><a id="created_at" style="color:blue;" href="#" onclick="set_sort('created_at')"><b>参与时间>></b></a></th>
            <th><a id="like_sum" style="color:blue;" href="#" onclick="set_sort('like_sum')"><b>like数量>></b></a></th>
            <th>详情</th>
            <th>私信</th>
            <th>
                <select id="dis_part">
                    <option value="all">全部</option>
                    <option value="unforbidden">已禁用</option>
                    <option value="forbidden">已启用</option>
                </select>
            </th>
          </tr>
          </thead>
          <tbody>
          @foreach($items as $item)
              <tr id ="row_{{$item->id}}">
                  <td>{{$item->id}}</td>
                  <td><img class="join_img" src="{{$item->image}}"/></td>
                  @if($model->type == 11)
                  <td>{{$item->name}}</td>
                  @endif
                  <td>{{str_limit($item->intro,40,'...')}}</td>
                  <td>{{$item->created_at}}</td>
                  <td>{{$item->like_sum}}</td>
                  <td><a href="{{$item->detailUrl}}">点击详情</a></td>
                  <td><a href="#" style="color:blue" onclick="send_info({{$item->user_id}})"><b>{{$item->display_name}}</b></a></td>
                  <td><button id="btn_{{$item->id}}" class="am-btn {{$item->is_forbidden_par?'am-btn-danger':'am-btn-success'}}" onclick="set_forbidden('{{$item->id}}')" value="{{$item->is_forbidden_par}}" >
                          {{$item->is_forbidden_par?'已禁用':'已启用'}}</button></td>
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
<script>
    function set_forbidden(join_id){
        status = $('#btn_'+join_id).val();
        $.ajax({
            type:'POST',
            url:'/admin/act/join-is-forbidden',
            data:{'status':status,'_token':'{{csrf_token()}}','join_id':join_id},
            dataType:'json',
            success:function(data){
                if(data.res == 1){
                    if(status == 1){
                        $('#btn_'+join_id).removeClass('am-btn-danger');
                        $('#btn_'+join_id).addClass('am-btn-success');
                        $('#btn_'+join_id).html('已启用');
                        $('#btn_'+join_id).val(0);
                    }else{
                        $('#btn_'+join_id).removeClass('am-btn-success');
                        $('#btn_'+join_id).addClass('am-btn-danger');
                        $('#btn_'+join_id).html('已禁用');
                        $('#btn_'+join_id).val(1);
                    }
                }
            }
        });
    }
    
    $('#dis_part').change(function(){
        localStorage.setItem('dis_part',$('#dis_part').val());
        reload_page();
    });
    $('#dis_part').val(localStorage.getItem('dis_part'));
    var set_sort_val = localStorage.getItem('set_sort');
    $('#'+ set_sort_val).css("color","red");
    function reload_page(){
        var dis_part = localStorage.getItem('dis_part');
        var set_sort = localStorage.getItem('set_sort');
        window.location = "/admin/act/act-joins-list/"+{{$model->id}}+'?dis_part='+dis_part+'&set_sort='+set_sort;
    }
    
    function set_sort(sort){
        localStorage.setItem('set_sort',sort);
        reload_page();
    }
    function send_info(act_id){
        doPrompt('发送私信', [
//              {'type':'text','text':'题目', 'name':'url', 'value':''},
              {'type':'textarea','text':'内容', 'name':'description', 'value':''},
              {'type':'hidden','text':'用户id', 'name':'user_id', 'value':act_id}],
            function(params){
                var str = '';
                var postData = {'_token':'{{ csrf_token() }}'};
                for(var i=0;i<params.length;i++){
                    postData[params[i].name] = params[i].value;
                }
               $.post('/admin/act/send-info', postData, function(data){
                   alert('成功！');
                })
                .error(function(a){
                    alert(a);
                });
            });
    }
</script>
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js"></script>
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
