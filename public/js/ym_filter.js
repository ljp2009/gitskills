$(function(){
		$('.ym-filter-bar>div').on('click',function(e){
			$this = $(this);
			var index = $this.index();
			if(!$this.hasClass('ym-active')){
				$('.search-menu').eq(index).css('display','').siblings('nav').css('display','none').find('ul').removeClass('am-in').css('height','0');
				$this.addClass('ym-active').siblings('div').removeClass('ym-active');
				$('.bg-wrap').addClass('wrap-active');
				
			}else{
				$this.removeClass('ym-active').siblings('div').removeClass('ym-active');
				$('.bg-wrap').removeClass('wrap-active');
				$('.search-menu').eq(index).css('display','').siblings('nav').css('display','none').find('ul').removeClass('am-in').css('height','0');
			}
			//e.stopPropagation();//阻止冒泡 
		});
		$('.bg-wrap').click(function(){
	    	$('.ym-filter-bar>div').removeClass('ym-active').siblings('div').removeClass('ym-active');
			$('.bg-wrap').removeClass('wrap-active');
			$('.search-menu ul').removeClass('am-in').animate({height:0},200);
	    });
	    $('.ym-filter-bar .search-menu a').on('click',function(e){
	    	$(this).addClass('ym-active').siblings('a').removeClass('ym-active');
	    });
	    $('#collapse-nav a').on('click',function(){
	    	$(this).addClass('ym-active').parent().siblings('li').find('a').removeClass('ym-active');
	    });
	    var order = '';
	    var search = '';
	    $('#confirm-search').on('click',function(){
	    	$('#collapse-nav a.ym-active').each(function(){
	    		order = $(this).attr('data-order');
	    	});
	    	
	    	$('#collapse-filter a.ym-active').each(function(){
	    		var filterType = $(this).parent().attr('data-search-field');
	    		search += filterType + ':' + $(this).attr('data-search')+';';
	    	});
	    	location.href = '/taskhall/0/'+order+'/'+search;
	    });
	   
	    
	});