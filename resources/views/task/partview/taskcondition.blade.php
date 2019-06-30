<div class="info_text">
@if(count($list) > 0)
    @foreach($list as $condition)
    <p>
        @if($condition['type'] == 5)
            {{$condition['label']}}:<a href="http://static.umeiii.com/{{$condition['value']}}">下载附件</a>
        @else
            {{$condition['label']}} {{is_null($condition['value'])?'':(':'.$condition['value'])}}
        @endif
        <br />
        {{$condition['text']}}
    </p>
    @endforeach
@else
    <p class="msg"> 未设置交付条件 </p>
@endif
</div>
