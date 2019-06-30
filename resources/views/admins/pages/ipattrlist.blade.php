@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['数据管理', '作品', '属性'],'addBtn'=>'addItem('.$ip_id.');'])
    <div class="am-g">
      <div class="am-u-sm-12">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th>ID</th>
            <th>属性编号</th>
            <th>属性名称</th>
            <th>属性从属</th>
            <th>属性值</th>
            <th>删除</th>
            <th>修改</th>
          </tr>
          </thead>
          <tbody>
          @foreach($items as $item)
              <tr id ="row_{{$item->id}}">
                  <td>{{$item->id}}</td>
                  <td>{{$item->code}}</td>
                  <td>{{$item->attrSet->name}}</td>
                  <td>{{$item->attrSet->depend}}</td>
                  <td>{{$item->value}}</td>
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
var objName = 'attr';
var token = '{{csrf_token()}}';
var paramsArr =  [
        {'text':'属性值','name':'value', 'type':'text', 'value':''},
        {'text':'编号','name':'code', 'type':'hidden', 'value':''},
        {'text':'id','name':'id', 'type':'hidden', 'value':''}
    ];
function addItem(pid){
    $.get('/admin/ip/add-'+objName+'/'+pid,{},function(attrs){
        var addParams =  [
            {'text':'属性','name':'code', 'type':'enum',
                'options':attrs, 'value':'1002'},
            {'text':'取值','name':'value', 'type':'text', 'value':''},
            {'text':'pid','name':'pid', 'type':'hidden', 'value':'{{$ip_id}}'}
        ];
        doPrompt("添加属性", addParams, function(params){
              var postData = {'_token':token};
              for(var i = 0;i<params.length;i++){
                  var v = params[i];
                  postData[v.name] = v.value;
              }
              $.post('/admin/ip/add-'+objName+'',postData,function(data){
                      window.location.reload();
                  }).error(function(a,b,c){
                      doAlert("编辑失败。");
                  });
          });
    });
}
</script>
@stop
