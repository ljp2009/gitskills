@extends('layouts.master')

@section('content')


@yield('listheader','')


<div id="listDataDiv">
@yield('listcontent','')
</div>
<div>
	<button class="am-btn am-btn-default am-btn-block ym-transparent-btn" id="listControlBtn">加载更多</button>
</div>
<script src="/assets/js/amazeui.min.js"></script>
<script src="/js/listload.js"></script>
<script src="/js/lasyload.js"></script>
<script src="/js/ym_public.js"></script>
<script src="/js/ym_animate.js"></script>
<script src="/js/ym_filter.js"></script>
<script type="text/javascript">
	var list = new listLoad();
	@yield('bindlist','');
	list.begin();
	
	var search1 = "{{ $search or '' }}";
    var searchArr = search1.split(';');
    for(var i in searchArr){
    	if(searchArr[i]){
    		fieldArr = searchArr[i].split(':');
    		$("#collapse-filter").find('li[data-search-field="'+fieldArr[0]+'"]').find('a[data-search="'+fieldArr[1]+'"]').addClass('ym-active');
    	}
    	
    }
	
</script>
@stop
