<div class="ym_shade" id="vote_shade">
    <input id="vote_id" type="hidden" value="{{$vote->id}}">
    <div class="ym_votebox_position">
        <div class="ym_votebox">
    @if($vote->type == 1)
            <div class="alternatives">
                <img src="{{$vote->alternativeObjects[0]->image[0]->getPath(1, '270w_307h')}}" />
            </div>
            <div class="actions">
                <button type="button" value='unlike' class="vote_button like">不喜欢</button>
                <button type="button" value='like' class="vote_button unlike">喜欢</button>
            </div>
    @else
            <div class="alternatives">
                <ul class="ym_avg_3 alternatives_list">
                @foreach($vote->alternativeObjects as $an)
                <li><div value="{{$an->id}}"> <img src="{{$an->image[0]->getPath(1,'159w_202h_4e_255-255-255bgc')}}" /></div> </li>
                @endforeach
                </ul>
            </div> <div class="clear"></div>
            <div class="actions">
                <button value="" class="vote_button">选好啦</button>
            </div>
    @endif
        </div>
        <div class="ym_votebox_quickmsg">感谢您的投票！</div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){ 
    setTimeout(function(){
        var $voteShade = $('#vote_shade');
        $voteShade.find('button.vote_button').on('click',function(){
            var val = $(this).attr('value');
            if(val == ''){
                $('.alternatives_list').find('div.ymicon-heart').each(function(){
                    val += ($(this).attr('value')+';');
                });
            }
            $.post('/vote', {
                value:val,
                voteId:$('#vote_id').val(),
                _token:$.ymFunc.getToken(),
            }, function(data){
                var text = data.info;
                var $box = $voteShade.find('div.ym_votebox');
                var $msg = $voteShade.find('div.ym_votebox_quickmsg');
                $box.hide();
                $msg.show();
                setTimeout(function(){
                    $msg.hide();
                    $voteShade.remove(); 
                }, 1000);
            });
        });
        $('.alternatives_list').find('div').on('click', function(){
            if($(this).hasClass('ymicon-heart')) {
                $(this).removeClass('ymicon-heart');
            }else{
                $(this).addClass('ymicon-heart');
            }
        });
        $voteShade.show();
        $voteShade.css('z-index',2000);
        var $voteBox = $voteShade.find('div.ym_votebox');
        var imgCt = 0;
        var $imgs = $voteBox.find('img');
        $imgs.on('load',function(){ 
            imgCt++; 
            if($imgs.length == imgCt){
                $voteBox.css('top', 20-$voteBox.height()/2);
            }
        });
        $voteBox.css('top', 20-$voteBox.height()/2);
    }, 2000);
});

</script>
