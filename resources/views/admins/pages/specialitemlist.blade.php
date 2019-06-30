@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<link href="/css/admin/recommend.css" rel="stylesheet" />
<div class="admin-content" style="height:100%">
    @include('admins.partviews.titlebar',
        ['titles'=>['推荐管理', '专辑列表','专辑内容管理'],
            'backTo'=>'/admin/sp/list',
            'addBtn'=>'addSpecialItem()', 'addText'=>'添加专辑内容'])
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">修改推荐内容</a></li>
        </ul>
    </div>
    <div style="width:100%;padding:15px;height:100%;">
        <input type="hidden" id="specialId" value="{{$id}}">
        <div class="width:100%;">
            <table style="width:100%;" class="am-table am-table-bd am-table-striped admin-content-table">
              <thead>
              <tr>
                <th style="width:50px">ID</th>
                <th>分类</th>
                <th>名称</th>
                <th>图片</th>
                <th>链接</th>
                <th style="width:120px">推荐语</th>
                <th style="width:80px">编辑</th>
                <th style="width:80px">删除</th>
              </tr>
              </thead>
              <tbody>
                @foreach($items as $item)
                <tr id ="row_{{$item->id}}">
                    <td>{{$item->id}}</td>
                    <td>{{$item->type}}</td>
                    <td>{{$item->name}}</td>
                    <td><img src="{{$item->img->getPath(1,'93w_110h_1e_1c')}}" /></td>
                    <td><a href="{{ $item->url }}">链接</a></td>
                    <td>{{$item->intro}}</td>
                    <td><button class="am-btn am-btn-primary" onclick="editItem('{{$item->id}}')" >编辑</button></td>
                    <td><button class="am-btn am-btn-primary" onclick="removeItem('{{$item->id}}')" >删除</button></td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    <hr />
    <div class="am-tab-panel am-fade am-in am-active" id="tab1">
</div>
<div class="am-modal am-modal-prompt" tabindex="-2" id="my-selected">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">选择推荐内容</div>
        <div class="am-modal-bd">
            <div class="yma_rec_form_row">
                <select id="itemType" class="yma_rec_form_select">
                    <option value="cartoon">动漫</option>
                    <option value="story">小说</option>
                    <option value="game">游戏</option>
                    <option value="light">轻小说</option>
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
            <input id="editId" type="hidden" />
            <div class="yma_rec_form_row am-g">
                <div class="am-u-sm-4" ><label class="yma_rec_form_label">名称</label></div>
                <div class="am-u-sm-8"><input id="editTitle" type="text" class="yma_rec_form_input" /></div>
            </div>
            <div class="yma_rec_form_row am-g">
                <div class="am-u-sm-4"><label class="yma_rec_form_label">推荐语</label></div>
                <div class="am-u-sm-8"><input id="editIntro"type="text" class="yma_rec_form_input" /></div>
            </div>
            <div class="yma_rec_form_row am-g">
                <div class="am-u-sm-4"><label class="yma_rec_form_label">封面图片</label></div>
                <div class="am-u-sm-8">
                    <input type="hidden" id="editImg" />
                    <img src="http://img.umeiii.com/default.jpg" class="yma_rec_form_img" id="editPerviewImg" />
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
    function addSpecialItem(){
        $('#my-selected').attr('index', 0);
        $('#my-selected').modal('open');
    }
    function editItem(id){
        $.get('/admin/sp/special-item/'+id,function(data){
            $('#editId').val(data['id']);
            $('#editTitle').val(data['name']);
            $('#editIntro').val(data['intro']);
            $('#editImg').val(data['img']);
            $('#editPerviewImg').attr('src', data['imgPerview']);
            $('#my-editinfo').modal({onConfirm:function(d){
               $.post('/admin/sp/special-item', {
                    '_token':'{{csrf_token()}}',
                    'id':$('#editId').val(),
                    'name':$('#editTitle').val(),
                    'intro':$('#editIntro').val(),
                    'img':$('#editImg').val(),
                }, function(data){
                    if(data.res){
                        location.reload();
                    }
                }).error(function(e){
                    alert(e.responseText);
                });
            }});
        }).error(function(e){alert(e.responseText)});
    }
    function removeItem(id){
        $('#my-confirmRemove').attr('index', id);
        $('#my-confirmRemove').modal({onConfirm:function(){
            var index = $('#my-confirmRemove').attr('index');
            $.post('/admin/sp/special-item-del',{
                '_token':'{{csrf_token()}}',
                'id'    : id,
            },function(data){
                if (data.res) {
                    location.reload();
                };
            }).error(function(e){
                alert(e.responseText);
            });
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
        $.post('/admin/sp/add-item',{
            '_token':'{{csrf_token()}}',
            'special_id':$('#specialId').val(),
            'id':$obj.attr('itemId'),
            'type':$obj.attr('type')
        },function(data){
            if(data.res){
                location.reload();
                $('#my-selected').modal('close');
            }else{
                alert('添加异常');
            }
        }).error(function(e){
            alert($(e.responseText).find(body).html());
        });
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
    function changeImg(){
        if(!ImageController.checkRegist('changeImg')){
            ImageController.regist('changeImg',function(id,imgName){
                $('#editImg').val(imgName);
                $('#editPerviewImg').attr('src',getImgPath(imgName));
            });
        }
        ImageController.changeImg(0);
    }
    function getImgPath(img){
        return 'http://img.umeiii.com/'+img+'@128w_150h_1e_1c';
    }
</script>
@stop
