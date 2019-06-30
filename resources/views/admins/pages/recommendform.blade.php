@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<link href="/css/admin/recommend.css" rel="stylesheet" />
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf">
            <strong class="am-text-primary am-text-lg">推荐管理</strong> /
            <small>有妹推荐</small> /
            <small>{{$id==0?'发布':'修改'}}推荐</small>
        </div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">修改活动</a></li>
        </ul>
    </div>
    <div class="am-tabs-bd">
    <div class="am-container" style="max-width:600px;">
    <input type="hidden" id="batchId" value="{{isset($batch)?$batch->id:0}}">
        @for($i=0; $i<6; $i++)
        <div class="am-u-sm-4 ">
            <div class="yma_perview" id="rec_{{$i}}" title="" itemId="0" intro="" img="" type="" >
                <img  class="rec_cover" src="http://img.umeiii.com/default.jpg@128w_150h_1e_1c" />
                <span class="rec_title">未设置</span>
                <span class="rec_intro">未设置</span>
                <span class="rec_flag">未知</span>
                <span class="rec_add" onclick="selectItem({{$i}})"><i class="am-icon-plus"></i></span>
                <span class="rec_modify" onclick="editItem({{$i}})"><i class="am-icon-edit"></i></span>
                <span class="rec_delete" onclick="removeItem({{$i}})"><i class="am-icon-close"></i></span>
            </div>
        </div>
        @endfor
    </div>
    <hr />
      <input type="hidden" name="_token" value="{{csrf_token()}}">
      <div class="am-tab-panel am-fade am-in am-active" id="tab1">
        <div class="am-g am-margin-top">
          <div class="am-u-sm-4 am-u-md-2 am-text-right">发布日期</div>
          <div class="am-u-sm-8 am-u-md-10">
            <input id="publish_date" name='title' type='date' class="am-form-field" value="{{isset($batch)?$batch->publish_date:date('Y-m-d')}}" />
          </div>
        </div>
    </div>
  <div class="am-margin">
    <button type="button" class="am-btn am-btn-primary am-btn-xs"
        onclick="save()">保存</button>
  </div>
</div>
<div class="am-modal am-modal-prompt" tabindex="-2" id="my-selected">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">选择推荐内容</div>
        <div class="am-modal-bd">
            <div class="yma_rec_form_row">
                <select id="itemType" class="yma_rec_form_select">
                    <option value="cartoon">动漫</option>
                    <option value="story">小说</option>
                    <option value="light">轻小说</option>
                    <option value="game">游戏</option>
                    <option value="coll">同人作品</option>
                    <option value="peri">周边产品</option>
                    <option value="disc">长评论</option>
                    <option value="original">用户作品</option>
                </select>
            </div>
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
<div class="am-modal am-modal-prompt" tabindex="-2" id="my-editinfo">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">修改推荐内容</div>
        <div class="am-modal-bd">
            <div class="yma_rec_form_row am-g">
                <div class="am-u-sm-4" ><label class="yma_rec_form_label">标题</label></div>
                <div class="am-u-sm-8"><input id="editTitle" type="text" class="yma_rec_form_input" /></div>
            </div>
            <div class="yma_rec_form_row am-g">
                <div class="am-u-sm-4"><label class="yma_rec_form_label">推荐语</label></div>
                <div class="am-u-sm-8"><input id="editIntro"type="text" class="yma_rec_form_input" /></div>
            </div>
            <div class="yma_rec_form_row am-g">
                <div class="am-u-sm-4"><label class="yma_rec_form_label">封面图片</label></div>
                <div class="am-u-sm-8">
                    <img src="http://img.umeiii.com/default.jpg" class="yma_rec_form_img" id="editImg" />
                    <button type="button" class="am-btn" onclick="changeImg()">替换图片</button>
                </div>
            </div>
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel>取消</span>
            <span class="am-modal-btn" data-am-modal-confirm>确定</span>
        </div>
    </div>
</div>
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirmRemove">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">移除推荐</div>
        <div class="am-modal-bd">
            你确定要移除这个推荐内容吗？
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn" data-am-modal-cancel>取消</span>
            <span class="am-modal-btn" data-am-modal-confirm>确定</span>
        </div>
    </div>
</div>
@include('admins.partviews.uploadimage', ['st'=>$uploadParams])
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/uploadimage.js"></script>
<script type="text/javascript" charset="utf-8">
    function selectItem(index){
        $('#my-selected').attr('index', index);
        $('#my-selected').modal('open');
    }
    function editItem(index){
        var $perview = $('#rec_'+index);
        $('#editImg').attr('src',$perview.find('img.rec_cover').attr('src'));
        $('#editImg').attr('imgValue',$perview.attr('img'));
        $('#editTitle').val($perview.attr('title'));
        $('#editIntro').val($perview.attr('intro'));
        $('#my-editinfo').attr('index', index);
        $('#my-editinfo').modal({onConfirm:function(d){
            var index = $('#my-editinfo').attr('index');
            setItemValue(index, $('#editTitle').val(), $('#editIntro').val(), $('#editImg').attr('imgValue'));
        }});
    }
    function removeItem(id){
        $('#my-confirmRemove').attr('index', id);
        $('#my-confirmRemove').modal({onConfirm:function(){
            var index = $('#my-confirmRemove').attr('index');
           setItemValue(index, '未设置', '未设置', 'default.jpg', 'none', '0');
        }});
    }
    function searchItem(){
       var tp =$('#itemType').val();
       var kw =$('#itemKeyword').val();
           $.post('/admin/rc/search-item',{
        '_token':'{{csrf_token()}}',
        'type':tp,
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
                    $a.attr('intro', '暂无推荐语');
                    $a.attr('type', data[i]['type']);
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
        setItemValue(index, $obj.attr('title'),$obj.attr('intro'), $obj.attr('img'), $obj.attr('type'),  $obj.attr('itemId'));
        $('#my-selected').modal('close');
    }
    function setItemValue(index, title, intro, img, type, itemId){
        var $perview = $('#rec_'+index);
        $perview.find('img.rec_cover').attr('src',getImgPath(img));
        $perview.attr('img', img);
        $perview.find('span.rec_title').text(title);
        $perview.attr('title', title);
        $perview.find('span.rec_intro').text(intro);
        $perview.attr('intro', intro);
        if(typeof(type) != 'undefined'){
            $perview.find('span.rec_flag').text(getFlag(type));
            $perview.attr('type', type);
        }
        if(typeof(itemId) != 'undefined'){
            $perview.attr('itemId', itemId);
        }
    }
    function save(){
        var cc = 6;
        var data = {};
            data['cc'] = cc;
            data['_token'] = '{{csrf_token()}}';
            data['batchId'] = $('#batchId').val();
            data['publish_date'] = $('#publish_date').val();

        for(var i=0;i<cc;i++){
            var $perview = $('#rec_'+i);
            data['title_'+i] = $perview.attr('title');
            data['intro_'+i] = $perview.attr('intro');
            data['img_'+i] = $perview.attr('img');
            data['type_'+i] = $perview.attr('type');
            data['id_'+i] = $perview.attr('itemId');
        }
        $.post('/admin/rc/save-batch',data,function(res){
            if(res.res){
                window.location = '/admin/rc/batch-list';
            }
        }).error(function(e){
            alert($(e.responseText).text());
        });
    }
    function changeImg(){
        if(!ImageController.checkRegist('changeImg')){
            ImageController.regist('changeImg',function(id,imgName){
                $('#editImg').attr('src',getImgPath(imgName));
                $('#editImg').attr('imgValue',imgName);
            });
        }
        ImageController.changeImg(0);
    }
    function getFlag(value){
        switch(value){
            case 'cartoon':
            return '动漫';
            case 'story':
            return '小说';
            case 'game':
            return '游戏';
            case 'coll':
            return '同人';
            case 'peri':
            return '周边';
            case 'disc':
            return '长评论';
            case 'original':
            return '原创';
            default:
            return '未知';
        }
    }
    function getImgPath(img){
        return 'http://img.umeiii.com/'+img+'@128w_150h_1e_1c';
    }
@if(isset($batch))
    @for($i=0;$i<count($batch->recommends);$i++)
    setItemValue('{{$i}}', '{{$batch->recommends[$i]->name}}','{{$batch->recommends[$i]->intro}}','{{$batch->recommends[$i]->image}}','{{$batch->recommends[$i]->type}}','{{$batch->recommends[$i]->resource_id}}');
    @endfor
@endif
</script>
@stop
