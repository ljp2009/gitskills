@foreach($models as $batch)
<div class="ym-line-list-group">
@foreach($batch->recommends as $recommend)
<div class="ym-line-list-item" onclick="$.ymFunc.goTo('{{$recommend->url}}')" >
    <div class="ym-line-list-itemimg">
        <img class="ym-line-list-itemimg-img" src="{{$recommend->image->getPath(1,'130w_154h_1e_1c')}}" />
        <span class="ym-line-list-itemimg-flag">{{$recommend->tag}} </span>
    </div>
    <div class="ym-line-list-iteminfo">
        <div class="ym-line-list-iteminfo-title">{{$recommend->name}}</div>
        @if(!empty($recommend->author))
        <div class="ym-line-list-iteminfo-user">{{$recommend->author}}</div>
        @endif
        <div class="ym-line-list-iteminfo-intro">{{$recommend->intro}}</div>
        <a class="ym-line-list-iteminfo-more" href="{{$recommend->url}}">查看详情<i class="ymicon-right" style="font-size:7px;"></i></a>
    </div>
</div>
@endforeach
</div>
@endforeach
