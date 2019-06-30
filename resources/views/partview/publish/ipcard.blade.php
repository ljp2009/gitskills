<div class="ym-pub-ipcard" onclick="window.location = '/ip/{{$ip->id}}'">
    <img src="{{$ip->cover->getPath(1,'58h_58w_1e_1c')}}" class="ym-pub-ipcard-img" />
    <div class="ym-pub-ipcard-info">
       <span class="ym-pub-ipcard-info-title">{{$ip->name}}</span>
       <span class="ym-pub-ipcard-info-text">{{$ip->cardInfo}}</span>
    </div>
</div>
