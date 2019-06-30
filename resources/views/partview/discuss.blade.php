<link href="/css/ym_dialog.css" rel="stylesheet" />
<script src="/assets/common/commonDialog.js"></script>
@if(count($popular) > 0)
@include('partview.discussitem', ['items'=>$popular])
<div class="ym_comment_split" id="popComment">
    <a href="javascript:void(0)" onclick="$.ymFunc.goTo('/discussion/list/popular-{{$resource}}-{{$resource_id}}/0')">
        查看更多精彩评价</a>
    <hr />
</div>
@endif
@if(count($newest) > 0)
@include('partview.discussitem', ['items'=>$newest])
<div class="ym_comment_split" style="margin-bottom:50px" id='newComment'>
    <a href="javascript:void(0)" onclick="$.ymFunc.goTo('/discussion/list/newest-{{$resource}}-{{$resource_id}}/0')">
        查看更多最新评价</a>
</div>
@endif
@if(count($newest) == 0 && count($popular) == 0)
<div style="width:100%" id="newest_comment" style="min-height:70px;display:none">
    <div class="ym_comment_none">还没有人评论哦！~~</div>
</div>
@endif
<input id="discuss_id" style="display:none" />
<script type="text/javascript">
function switchLikeIcon(resourceName, resourceId, isLike){
    var $items = $('i.ym_comment_box_like[discid='+resourceId+']'); 
    var $span = $('span.ym_comment_box_like_count[discid='+resourceId+']'); 
    var ct = parseInt($span.html());
    if(isLike){
        $items.removeClass('ymicon-heart-o');
        $items.addClass('ymicon-heart');
        $span.html(ct+1);
    }
    else{
        $items.removeClass('ymicon-heart');
        $items.addClass('ymicon-heart-o');
        $span.html(ct-1);
    }
}
function deleteDiscuss(id){
    $('#discuss_id').val(id);
    $('#discuss_id').click();
}
//绑定删除按钮
$('#discuss_id').commonDialog({
        type:'confirmAndCancelDialog',
        content:'您确定要删除这条评论吗?'
    })
    .bind('confirmAndCancelDialog', function(res){
        var id = $("#discuss_id").val();
        //调用删除函数
        $.post('/discussion/delete', {
           '_token':$.ymFunc.getToken(),
           'id':id
        }, function(data){
          if(data.res){
            $('.discuss_flag_'+id).remove();
          }
        }).error(function(e){
          alert(e.responseText);
        });
    }, null);
</script>
