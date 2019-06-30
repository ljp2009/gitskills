var ym_head_handler ={
    drawUserHead : function(headimg, w, h, container, callback) {
    	var html = '<div class="game13_userhead" style="background:url('+ headimg +
    			');">'+
	            '<img src="/game13/pic/user_head_cover" class="game13_userhead_pic" width='+ w +
	            ' height='+ h +'/>'+
	            '<div style="display:none"><img class="game13_userhead_srcpic"></div>'+
				'</div>';
		container.append(html);
		var imgobj = container.find('.game13_userhead_srcpic');
		imgobj[0].onload = function(){
			ym_head_handler.decorateUserCard(container, w, h);
			if(typeof(callback)=='function'){
				callback();
			}
		};
		imgobj.attr('src', headimg);
	},
	decorateUserCard : function(container, w, h) {
		var img = container.find('.game13_userhead_srcpic');
		var imgw = img[0].width; var imgh = img[0].height;
		var szbase = w / h;
		var imgbase = imgw / imgh;
		var marginTop = 0, marginLeft = 0, flscale = 0;
		if(imgbase > szbase){
			flscale = h / imgh;
			imgh = h;
			imgw = parseInt(imgw * flscale);
			marginLeft = -1 * parseInt((imgw - w) / 2);
		}else{
			flscale = w / imgw;
			imgw = w;
			imgh = parseInt(imgh * flscale);
			marginTop = -1 * parseInt((imgh  - h)/2);
		}
		container.find('.game13_userhead').css({'width':w + 'px', 'height':h + 'px', 
				'-moz-background-size':imgw +'px ' + imgh + 'px',
				'background-size': imgw +'px ' + imgh + 'px',
				'-moz-background-position':marginLeft + 'px ' + marginTop + 'px',
				'background-position':marginLeft + 'px ' + marginTop + 'px'});
	},
	drawHeroCard : function(img, w, h, level, container, callback, margin){
		if(typeof(margin) == 'undefined'){
			margin = 5;
		}
		if(margin > 0){
			w = w - margin * 2; h = h - margin * 2;
		}
		var html = '<div class="game13_hero_card" style="width:'+w+'px; height:'+h+'px;">'+
						'<div class="game13_hero_card_inner"><img class="game13_hero_card_pic" />'+ 
						'<img class="game13_hero_card_front" src="/game13/pic/front_hero_card/'+level
								+'" width='+ w +' height='+ h +' style="margin-top:-'+ h +'px"/></div>'+
					'</div>';

		container.append(html);
		var imgobj = container.find('.game13_hero_card_pic');
		imgobj[0].onload = function(){
			ym_head_handler.decorateHeroCard(container, w, h, margin);
			if(typeof(callback)=='function'){
				callback();
			}			
		};
		imgobj.attr('src', img);
	},
	decorateHeroCard : function(container, w, h, margin){
		var img = container.find('.game13_hero_card_pic');
		var imgw = img[0].width; var imgh = img[0].height;
		var szbase = w / h;
		var imgbase = imgw / imgh;
		var marginTop = 0, marginLeft = 0, flscale = 0;
		if(imgbase > szbase){
			flscale = h / imgh;
			imgh = h;
			imgw = parseInt(imgw * flscale);
			marginLeft = -1 * parseInt((imgw - w) / 2);
		}else{
			flscale = w / imgw;
			imgw = w;
			imgh = parseInt(imgh * flscale);
			marginTop = -1 * parseInt((imgh  - h)/2);
		}
		img[0].width = imgw;
		img[0].height = imgh;
		img.css({'margin-left':marginLeft + 'px', 'margin-top':marginTop + 'px'});
		img.parent().css({'width':w + 'px', 'height':h + 'px', 'margin':margin + 'px'});
	}	
};
