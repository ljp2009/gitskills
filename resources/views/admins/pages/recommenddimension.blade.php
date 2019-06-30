
@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<link href="/css/admin/recommend.css" rel="stylesheet" />
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf">
            <strong class="am-text-primary am-text-lg">推荐管理</strong> /
            <small>有妹推荐</small> /
            <small>次元推荐</small>
        </div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">次元推荐</a></li>
        </ul>
    </div>
    <div class="am-tabs-bd">
    <div class="am-container" style="max-width:600px;">
    <input type="hidden" id="batchId" value="{{isset($batch)?$batch->id:0}}">
        @for($i=0; $i<3; $i++)
        <div class="am-u-sm-4 ">
            <div class="yma_perview" id="rec_{{$i}}" title="" itemId="0" intro="" img="" type="" >
                <div class="dim_box">
                    <img  class="dim_avatar" src="http://img.umeiii.com/default.jpg@128w_150h_1e_1c" />
                </div>
                <span class="dim_text">未选择</span>
                <span class="rec_modify" onclick="selectItem({{$i}})"><i class="am-icon-plus"></i></span>
                <span class="rec_delete" onclick="removeItem({{$i}})"><i class="am-icon-close"></i></span>
            </div>
        </div>
        @endfor
    </div>
    <hr />
  <div class="am-margin">
    <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick="save()">保存</button>
  </div>
</div>
<div class="am-modal am-modal-prompt" tabindex="-2" id="my-selected">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">选择用户</div>
        <div class="am-modal-bd">
            <div class="yma_rec_form_row">
                <input id="itemKeyword" type="text" placeholder="输入关键字" class="yma_rec_form_input" />
                <button type="button" class="yma_rec_form_btn" onclick="searchItem()">
                     <i class="am-icon-search"></i>
                </button>
            </div>
            <div class="yma_rec_form_row">
                <span class="yma_info">暂无结果。</span>
                <div class="yma_rec_form_list_container">
                <ul class="yma_rec_form_list">
                </ul>
                </div>
            </div>
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel>取消</span>
        </div>
    </div>
</div>
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirmRemove">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">移除推荐</div>
        <div class="am-modal-bd">
            你确定要移除这个推荐吗？
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel>取消</span>
            <span class="am-modal-btn" data-am-modal-confirm>确定</span>
        </div>
    </div>
</div>
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8">
    function selectItem(index){
        $('#my-selected').attr('index', index);
        $('#my-selected').modal('open');
    }
    function removeItem(id){
        $('#my-confirmRemove').attr('index', id);
        $('#my-confirmRemove').modal({onConfirm:function(){
            var index = $('#my-confirmRemove').attr('index');
           setItemValue(index, '未设置',  'default.jpg','0');
        }});
    }
    function searchItem(){
       var tp =$('#itemType').val();
       var kw =$('#itemKeyword').val();
           $.post('/admin/rc/search-item',{
        '_token':'{{csrf_token()}}',
        'type':'dimension',
        'keyword':kw
       }, function(data){
            var $list = $('.yma_rec_form_list');
            $list.html('');
            if(data.length>0){
                var ct = data.length;
                if(ct > 10){
                    $('yma_info').text('查询结果可能大于10条，请填写更多搜索内容以缩小搜索范围。');
                   ct = 10;
                }else{
                    $('yma_info').text('找到了'+ct+'个结果。');
                }
                for(var i=0;i<ct;i++){
                    var $li = $('<li></li>');
                    var $a = $('<a href="javascript:void(0)" onclick="selectedItem(this)"></a>');
                    $li.append($a);
                    $a.attr('title', data[i]['title']);
                    $a.attr('itemId', data[i]['id']);
                    $a.attr('img', data[i]['img']);
                    $a.append('<img src="'+data[i]['imgPerview']+'"/>');
                    $a.append('<span>'+data[i]['title']+'</span>');
                    $list.append($li);
                }
            }else{
                    $('yma_info').text('暂无结果。');
            }
       }).error(function(e){
          alert($(e.responseText).text());
       });
    };
    function selectedItem(obj){
        var $obj = $(obj);
        var index = $('#my-selected').attr('index');
        setItemValue(index, $obj.attr('title'), $obj.attr('img'), $obj.attr('itemId'));
        $('#my-selected').modal('close');
    }
    function setItemValue(index, title,  img, itemId){
        var $perview = $('#rec_'+index);
        $perview.find('img.dim_avatar').attr('src',getImgPath(img));
        $perview.attr('img', img);
        $perview.find('span.dim_text').text(title);
        $perview.attr('title', title);
        if(typeof(itemId) != 'undefined'){
            $perview.attr('itemId', itemId);
        }
    }
    function save(){
        var cc = 3;
        var data = {};
            data['cc'] = cc;
            data['_token'] = '{{csrf_token()}}';
        for(var i=0;i<cc;i++){
            var $perview = $('#rec_'+i);
            data['title_'+i] = $perview.attr('title');
            data['img_'+i] = $perview.attr('img');
            data['id_'+i] = $perview.attr('itemId');
        }
        $.post('/admin/rc/dimension',data,function(res){
            if(res.res){
                window.location = '/admin/rc/dimension';
            }
        }).error(function(e){
            alert($(e.responseText).text());
        });
    }
    function getImgPath(img){
        if(img == '') img = 'default.jpg';
        return 'http://img.umeiii.com/'+img+'@128w_150h_1e_1c';
    }
@if(isset($dims))
    @for($i=0;$i<count($dims);$i++)
    setItemValue('{{$dims[$i]->order}}', '{{$dims[$i]->name}}','{{$dims[$i]->img}}','{{$dims[$i]->dimension_id}}');
    @endfor
@endif
</script>
@stop
