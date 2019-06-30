@extends('layouts.formpage')
@section('head')
<link rel="stylesheet" href="/css/mobiscroll.custom-2.14.2.min.css">
<script src="/js/area.js?a=1"></script>
@stop
@section('scriptrange')
//<script>
    $(function(){
   
	    var $area ='';
	    for (var i in area) {
	        $area += '<optgroup label="'+area[i].p+'" value="'+i+'">';
	        for(var j in area[i].c){
	            $area += '<option value="'+j+'">'+area[i]['c'][j]+'</option>';
	        }
	        $area += '</optgroup>';
	    };
	    $('#citySelect').html($area);
	    $('#pro').click(function(){
			$("#citySelect").click();

		});
	    // 城市联动
	    $('#citySelect').mobiscroll().select({
	        theme: 'mobiscroll',
	        lang: 'zh',
	        mode: 'scroller',
	        display: 'bottom',
	        label: 'City',
	        group: true,
	        groupLabel: 'Country',
	        fixedWidth: [100, 170]
	    }).mobiscroll('setVal','0',true);
	    $('#citySelect_dummy').css('display','none');
	    $("#citySelect").on('change',function(){
	     	var text = '';
	     	var city = $("#citySelect").mobiscroll("getVal");
	     	var pro;
	     	for(var i in area){
		    	for(var j in area[i].c){
		    		if(j == city){
		    			pro = i;
		    			text = area[i].p+'-'+area[i]['c'][j];
		    			break;
		    		}
		    	}

		    	if(text){
		    		break;
		    	}
		    }
	     	$('#province').val(pro);
	     	$('#city').val(city);
	     	$('#pro').val(text);
	     	$('#citySelect_dummy').css('display','none');
	     	$('html,body').css('overflow','auto');
	    });

	    var proId = $('#province').val();
	    var cityId = $('#city').val();
	    //城市已经设置
	    if(proId !='' && cityId != ''){
			//城市初始化
	    	$('#pro').val(area[proId].p+'-'+area[proId]['c'][cityId]);
		}
	    
		
	});
@yield('scriptrangecity', '')
@stop

