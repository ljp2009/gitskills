@extends('admins.layouts.admin')
@section('detailcontent')
<link href="/css/admin/recommend.css" rel="stylesheet" />
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',
        ['titles'=>['资源管理', '任务管理']])
    <div class="am-g" style="min-height:83rem">
      <div class="am-u-sm-12" sytle="min-height:5rem">

        <input type="hidden" id="sort" value="{{$sort}}" >
        <input type="hidden" id="step" value="{{$step}}" >
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th style="width:50px">ID</th>
            <th>任务名</th>
            <th>模式</th>
            <th>技能</th>
            <th>酬金</th>
            <th style="width:120px">发布日期</th>
            <th style="width:120px;cursor:pointer" onclick="sortSubmit()" >
                <span>交付日期</span><i id="sort-icon" class="ymicon-order-down"></i>
            </th>   
            <th style="width:80px;cursor:pointer;">
                <div class="am-dropdown" data-am-dropdown>
                    <div class="am-btn-default am-btn-xs am-dropdown-toggle" style="background-color: #fff;font-size: 1.6rem;" data-am-dropdown-toggle>
                        <span>状态</span><i class="ymicon-order-down"></i>
                    </div>
                    <ul class="am-dropdown-content">
                        <li><a onClick="selectStep('')">全部</a></li>
                        <li><a onClick="selectStep('0')">待审核</a></li>
                        <li><a onClick="selectStep('1')">待发布</a></li>
                        <li><a onClick="selectStep('2')">招募中</a></li>
                        <li><a onClick="selectStep('3')">招募中</a></li>
                        <li><a onClick="selectStep('4')">交付中</a></li>
                        <li><a onClick="selectStep('5')">结算中</a></li>
                        <li><a onClick="selectStep('6')">已完成</a></li>
                        <li><a onClick="selectStep('-1')">已取消</a></li>
                    </ul>
                </div>

            </th>
            <th style="width:80px">操作</th>
          </tr>
          </thead>
          <tbody>
            @foreach($items as $item)
            <tr id ="row_{{$item->id}}">
                <td>{{$item->id}}</td>
                <td>{{$item->title}}</td>
                <td>{{$item->taskTypeName}}</td>
                <td>{{$item->skill}}</td>
                <td>{{$item->amount}}</td>
                <td>{{$item->publish_date}}</td>
                <td>{{$item->delivery_date}}</td>
                <td>{{$item->taskStepName}}</td>
                @if($item->step == '0' || $item->step == '1')
                <!-- <td><button class="am-btn am-btn-primary" onclick="" >取消</button></td> -->
                <td><button class="am-btn am-btn-danger" onclick="deleteTask('{{$item->id}}')" >删除</button></td>
                @else                
                <td></td>
                @endif
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

init();
//初始化
function init(){
    var sort = $('#sort').attr("value");
    //交付日期升序
    if (sort == '1') {
        $('#sort-icon').addClass("ymicon-order-up").removeClass("ymicon-order-down");
    //交付日期降序
    } else {
        $('#sort-icon').addClass("ymicon-order-down").removeClass("ymicon-order-up");
    };
}

//排序
function sortSubmit(){
    // window.location = "/admin/tk/list/1";
    var sort = $('#sort').attr("value");
    var step = $('#step').attr("value");
    if (sort == '1') {
        $('#sort').attr("value", "0");
    } else {
        $('#sort').attr("value", "1");
    };
    var sort = $('#sort').attr('value');
    if(step == ''){
        window.location = "/admin/tk/list/"+sort;
    } else {
        window.location = "/admin/tk/list/"+sort+"/"+step;
    }
    
}

//筛选状态
function selectStep(step){

    var sort = $('#sort').attr('value');
    $('#step').attr("value", step);

    if(step == ''){
        window.location = "/admin/tk/list/"+sort;
    } else {
        window.location = "/admin/tk/list/"+sort+"/"+step;
    }
}

//删除任务
function deleteTask(id){
    doConfirm('删除确认','你确定要删除这条记录吗?',id, function(param){
        $.post('/admin/tk/delete',
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

</script>
<script type="text/javascript" charset="utf-8">
    formatPager($('#pageDiv'));
</script>
@stop
