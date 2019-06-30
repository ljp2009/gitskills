@foreach($models as $value)
<div class="ym-line-list-item" onclick="document.location='{{$value->url}}'">
    <div class="ym-line-list-itemimg">
        <img class="ym-line-list-itemimg-img" src="{{$value->img->getPath(1,'130w_154h_1e_1c')}}" />
        <span class="ym-line-list-itemimg-flag">{{$value->tag}}</span>
    </div>
    <div class="ym-line-list-iteminfo">
        <div class="ym-line-list-iteminfo-title">{{$value->name}}</div>
        <div class="ym-line-list-iteminfo-intro">{{$value->intro}}</div>
        <a class="ym-line-list-iteminfo-more" href="{{$value->url}}">查看详情<i class="ymicon-right" style="font-size:13px"></i></a>
    </div>
</div>
@endforeach
