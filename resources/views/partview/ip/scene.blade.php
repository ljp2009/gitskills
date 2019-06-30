<div class="ym_ip_content">
@foreach($items as $item)
		<img src="{{ $item->firstImage->getpath(1) }}" onclick="$.ymFunc.goTo('{{$item->detailUrl}}')"/>
    	<span style="overflow: hidden; max-height:6.5rem;" onclick="$.ymFunc.goTo('{{$item->detailUrl}}')">{{$item->text}}</span>
    
@endforeach
</div>

