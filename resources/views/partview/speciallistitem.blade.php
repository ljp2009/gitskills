@foreach($models as $special)
<li class="ym_special_item">
    <a href="/special/detail-{{$special->id}}/0">
        <div class="ym-hall-recommand-box">
            <img src="{{$special->img->getPath(1,'290w_180h_1e_1c')}}" class="ym-hall-recommand-cover-img" />
        </div>
        <div class="ym-hall-recommand-name">{{$special->name}}</div>
        <div class="ym-hall-recommand-intro">{{$special->intro}}</div>
    </a>
</li>
@endforeach
