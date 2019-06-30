<div class="ym_ip_content">
    <ul class="ym_ip_related_avg_4">
@foreach($items as $item)
        <li onclick="$.ymFunc.goTo('{{$item->detailUrl}}')">
            <img src="{{$item->cover->getPath(1, '106w_125h_1e_1c')}}" />
            <label>{{$item->name}}</label>
        </li>
@endforeach
    </ul>
</div>
