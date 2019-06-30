@extends('layouts.admin')
@section('detailcontent')
<!-- content start -->
  <div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong> / <strong>作品</strong>/ <small>场景</small></div>
      <div class="am-fr">
        <button onclick="addItem()" class="am-btn am-btn-success">添加</button>
      </div>
      <div class="am-fr" style="display:none">
        <form action="/ip/list" method="post">
        <input type="text" name="search" value="{{isset($search)?$search:''}}" />
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="submit" class="am-btn am-btn-default" value="查询"/>
        </form>
      </div>

    </div>
    <div class="am-g">
      <div class="am-u-sm-12">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            @for($i=0;$i<count($settings);$i++)
            <th>{{$settings[$i]['text']}}</th>
            @endfor
          </tr>
          </thead>
          <tbody>
            @foreach($items as $item)
                <tr id ="row_{{$item->id}}">
                @for($i=0;$i<count($settings);$i++)
                    @if($settings[$i]['type']=='text')
                    <td>{{$item->$settings[$i]['name']}}</td>
                    @endif
                    @if($settings[$i]['type']=='image')
                    <td>
                        <img src="{{$item->$settings[$i]['name']}}@64h_100w_1e_1c"/>
                    </td>
                    @endif
                    @if($settings[$i]['type']=='btn')
                    <td><button class="am-btn am-btn-{{isset($settings[$i]['color'])?$settings[$i]['color']:'default'}}" 
                        onclick="{{isset($settings[$i]['func'])?$settings[$i]['func']:''}}" >{{$settings[$i]['text']}}</button></td>
                    @endif
                @endfor
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
        <span>仅显示排序前30的查询</span>
        <br />
    @endif
</div>
  </div>
  <!-- content end -->
<!--confirm window-->
    <div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
        <div class="am-modal-dialog">
            <div class="am-modal-hd"></div>
            <div class="am-modal-bd">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>确定</span>
            </div>
        </div>
    </div>
    <div class="am-modal am-modal-alert" tabindex="-2" id="my-alert">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">提示</div>
            <div class="am-modal-bd">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn">确定</span>
            </div>
        </div>
    </div>
    <div class="am-modal am-modal-prompt" tabindex="-2" id="my-prompt">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">修改文字</div>
            <div class="am-modal-bd">
                <textarea class="am-modal-prompt-input" style="text-align:justify" rows=10></textarea>
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>确定</span>
            </div>
        </div>
    </div>
     <div class="am-modal am-modal-prompt" tabindex="-2" id="my-add">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">添加</div>
            <div class="am-modal-bd">
                <input class="am-modal-prompt-input" type="text" placeholder="链接"/>
                <input class="am-modal-prompt-input" type="text" placeholder="描述"/>
                <input class="am-modal-prompt-input" type="hidden"/>
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>确定</span>
            </div>
        </div>
    </div>
</div>
</div>
<iframe style="display:none" name='hiddenFrame'></iframe>
<form style="display:none" id="_uploadImageForm" target="hiddenFrame"
      action="" method="post" enctype="multipart/form-data">
    <input name="key"                     type="hidden" value="" />
    <input name="content-Type"            type="hidden" value="" />
    <input name="OSSAccessKeyId"          type="hidden" value="" />
    <input name="policy"                  type="hidden" value="" />
    <input name="signature"               type="hidden" value="" />
    <input name="success_action_redirect" type="hidden" value="" />
    <input name="success_action_status"   type="hidden" value="201" />
    <input name="file"                    type="file"   id="_uploadAttachFile"
           accept="aplication/zip" onchange="attachChange(this,false)" />
</form>
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8">
    $('#pageDiv').find('ul').addClass('am-pagination');
    $('#pageDiv').find('ul').addClass('admin-content-pagination');
    $('#pageDiv').find('li[class=disabled]').addClass('am-disabled');
    $('#pageDiv').find('li[class=active]').addClass('am-active');
    function deleteScene(id)
    {
        var cfControl = $("#my-confirm");
        cfControl. find('div[class=am-modal-hd]').html('删除确认');
       cfControl.find('div[class=am-modal-bd]').html('你确定要删除 <b>'+name+'</b> 吗？');
        cfControl.modal({
            relatedTarget:id,
            onConfirm:function(){
                $.post('/ip/delete-scene/',{'id':id,  "_token":"{{ csrf_token() }}"},
                function(data){
                    $('#row_'+data).remove();
                    doAlert('删除成功。');
                }).error(function(a,b,c){
                    doAlert('删除失败。');
                });
            }
        });
    }
    function doAlert(str)
    {
        var atControl = $("#my-alert");
        atControl.find('div[class=am-modal-hd]').html('提示');
        atControl.find('div[class=am-modal-bd]').html(str);
        atControl.modal('open');
    }
    function modifyScene(id)
    {
        $.get('/ip/edit-scene/'+id,function(data){
        var prControl = $("#my-prompt");
        prControl.find('div[class=am-modal-hd]').html('编辑简介');
        prControl.find('textarea[class=am-modal-prompt-input]').html(data.text);
        prControl.modal({
            relatedTarget:data.id,
            onConfirm:function(d){
                $.post('/ip/edit-scene',
                {'id':this.relatedTarget,
                 'text':d.data,
                 '_token':'{{ csrf_token() }}'},
                function(data){
                    if(data == "true"){
                        doAlert("编辑成功。");
                        window.location.reload();
                    }
                }).error(function(a,b,c){
                    doAlert("编辑失败。");
                });
            }
        });
        });
    }
    function addItem(){
        var ac = $('#my-add');
        ac.find('input').val('');
        ac.modal({
            onConfirm:function(e){
                doAlert(e.data[0]+"||"+e.data[1]+"||"+e.data[2]);
            }
        });
    }
    function uploadImg()
    {
      var fm = $("#_uploadImageForm");
    }
</script>
@stop
