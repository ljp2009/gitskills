<ul class="ym_avg_2 ym_matrix_list">
    @foreach($models as $value)
    <li> <div class="ym_matrix_list_item">        
        <img class="preview" src="{{$value->image[0]->getPath(1,'220h_186w_4e_255-255-255bgc')}}" >
        <div class="infobox" id="delivery_{{$value->id}}">
            <img src="{{$value->user->avatar->getPath(2,'32w_32h_1e_1c')}}">
            <span onclick="$.ymFunc.switchLike('task_delivery', {{$value->id}}, afterLike);">{{$value->like_sum}}</span>
            <i class="{{$value->checkLike()?'ymicon-heart':'ymicon-heart-o'}}" 
                onclick="$.ymFunc.switchLike('task_delivery', {{$value->id}}, afterLike);"></i>
        </div>
    </div> </li>
    @endforeach
</ul>
<div style="clear:both"></div>
<script type="text/javascript">
function afterLike(res, id, isLike){
    var $likeHeart = $('#delivery_'+id).find('i');
    var $likeCount =$('#delivery_'+id).find('span');
    if(isLike){
        $likeHeart.addClass('ymicon-heart');
        $likeHeart.removeClass('ymicon-heart-o');
        $likeCount.text(parseInt($likeCount.text()+1));
    }else{
        $likeHeart.addClass('ymicon-heart-o');
        $likeHeart.removeClass('ymicon-heart');
        $likeCount.text(parseInt($likeCount.text()-1));
    }
}
</script>
