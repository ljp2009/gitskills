var ym_baidu_editor = function(cacheRecord, md5v, type, basicinfoData){
	var ED = this;
	ED.paneclass = "ym-editpane";
	ED.editbtnClazz = "ym-editbtn";
	ED.imgpaneClass = "ym_img_pane";
	ED.unitClass = "ym-editunit";
	ED.unitSelectedClass = "ym-unitselected";
	ED.currentKey = '';
	ED.typeAttr = 'editType';
	ED.cacheRecord = cacheRecord;
	ED.recordHitArr = [];
	ED.md5v = md5v;
	ED.onscroll = false;
	ED.onpanemove = false;
	ED.pos = {left:0, top:0};
	ED.content = [];
	ED.paneheight = 0;
	ED.enlarged = false;
	ED.enlargeRate = 1.4;
	ED.onViewBasic = false;
	ED.typediv = {
		author:['基本信息'],
		hero:['基本信息', '主角', '主角图', '主角描述']
	};
	ED.basicinfoData = basicinfoData;
	ED.basicinfoBtns = '';
	ED.basicinfos = [];

	ED.fulldata = {
		'作品名':{color:'#000033', name:'zpm', join:true, smallunit:true},
		'封面图':{color:'#006600', pic:true, name:'fmt', join:true},
		//'作者/监督/导演':{color:'#990099', name:'zz', join:true, smallunit:true},
		'基本信息':{color:'#669900', name:'jbxx', func:function(){ED.toggleActions(true);}},
		'内容描述':{color:'#330000', name:'nrms'},
		'主角':{color:'#0000cc', name:'zj', join:true, smallunit:true},
		'主角图':{color:'#22ccaa', pic:true, name:'zjt', join:true},
		'主角描述':{color:'#764300', name:'zjms'}
	};

	ED.data = [];

	ED.initDataSets = function(type){
		var keys = ED.typediv[type];
		for(var key in keys){
			var k = keys[key];
			ED.data[k] = ED.fulldata[k];
		}
	};

	if(typeof(type)=='undefined'||type.length==0){
		ED.data = ED.fulldata;
	}else{
		ED.initDataSets(type);
	}

	ED.dataKeyInds = [];
	ED.dataKeys = [];
	var i = 0;
	for(var key in ED.fulldata){
		ED.dataKeyInds[key] = i;
		ED.dataKeys[i] = key;
		i ++ ;
	}

	ED.toggleActions = function(display){
		var w = 0, h = 0;;
		var clazz = (display?'.oriinfoClass':'.basicinfoClass');
		var oppclazz = (display?'.basicinfoClass':'.oriinfoClass');
		var stop = false;
		var children = $(clazz).children();
		children.each(function(){
			if(stop) return;
			var obj = $(this);
			if(obj[0].tagName == 'BUTTON'){
				w = obj[0].offsetWidth;
				h = obj[0].offsetHeight;
				stop = true;
			}
		});
		$(clazz).css('display', 'none');
		$(oppclazz).css('display', 'block');
		$(oppclazz).children().each(function(){
			var o = $(this);
			if(o[0].tagName == 'BUTTON'){
				o.css({'width':w + 'px', 'height':h + 'px'});
			}
		});
		
		ED.onViewBasic = display;
	};

	ED.isBasicInfo = function(key){
		//code || name
		return typeof(ED.basicinfoData[key])!='undefined'||typeof(ED.basicinfos[key])!='undefined';
	};

	ED.stringtrim = function(str){
		return str.replace(/(^\s*)|(\s*$)/g, '');
	};

	ED.getInputCompId = function(key){
		return '_input_' + ED.fulldata[key].name;
	};

	ED.getEditBtnId = function(key){
		return '_editbtn_' + ED.fulldata[key].name;
	};

	ED.asBtnClick = function(btn){
		var key = btn.text();
		if(ED.currentKey == btn.text()){
			btn.removeClass('selected');
			ED.currentKey = '';
			return;
		}
		ED.currentKey = btn.text();
		$('[name=basicinfo]').removeClass('selected');
		$('[name=editbtn]').removeClass('selected');
		btn.addClass('selected');
	};

	ED.getTypeAttrValue = function(obj){
		if(typeof(obj.attr(ED.typeAttr))!='undefined'){
			return obj.attr(ED.typeAttr);
		}
		return '';
	};

	ED.setTypeAttr = function(obj, isimg, key){
		if(typeof(isimg)=='undefined'){
			isimg = false;
		}
		var name = '', color = '';
		var dt = '';
		if(typeof(key)=='undefined'){
			key = ED.currentKey;
		}
		if(ED.isBasicInfo(key)){
			name = ED.basicinfos[key].attr('code');
			color =  ED.basicinfos[key].css('background');
		}else{
			dt = ED.data[key];
			color = dt.color;
			name = dt.name;
		}
		if(isimg){
			obj.parent().css('background', color);
			obj.parent().css('border', 'solid #aae 2px');
			obj.parent().css('border-radius', '0.5rem');
		}else{
			obj.css('background', color);
			obj.css('color', '#fff');

		}
		obj.attr(ED.typeAttr, name);	
		obj.addClass(ED.unitSelectedClass);	
	};

	ED.cleanTypeAttr = function(obj, isimg){
		if(typeof(isimg)=='undefined'){
			isimg = false;
		}
		if(isimg){
			obj.parent().css('background', 'none');
			obj.parent().css('border', 'none');
			obj.parent().css('border-radius', 'none');
		}else{
			obj.css('background', 'none');
			obj.css('color', '#111');			
		}

		obj.attr(ED.typeAttr, '');		
		obj.removeClass(ED.unitSelectedClass);	
	};

	ED.asSmallUnitClick = function(smallunitobj){
		if(ED.currentKey == ''){
			ED.cleanTypeAttr(smallunitobj);
		}else{
			var name = '';
			if(!ED.isBasicInfo(ED.currentKey)){
				var dt = ED.data[ED.currentKey];
				if(typeof(dt.pic)!='undefined'&&dt.pic){return;}
				else if(typeof(dt.smallunit)=='undefined'||!dt.smallunit){return;}		
				name = dt.name;		
			}else{
				name = ED.basicinfos[ED.currentKey].attr('code');
			}

			if(ED.getTypeAttrValue(smallunitobj)===name){
				ED.cleanTypeAttr(smallunitobj);
			}else{
				ED.setTypeAttr(smallunitobj);
			}
		}
	};

	ED.asImgClick = function(imgobj){
		if(ED.currentKey == ''){
			ED.cleanTypeAttr(imgobj, true);
		}else{
			var dt = ED.data[ED.currentKey];
			if(typeof(dt.pic)!='undefined'&&dt.pic){
				if(ED.getTypeAttrValue(imgobj)===dt.name){
					ED.cleanTypeAttr(imgobj, true);
				}else{
					ED.setTypeAttr(imgobj, true);
				}
			}
		}		
	};

	ED.asUnitClick = function(unitobj){
		if(ED.currentKey == ''){
			ED.cleanTypeAttr(unitobj);
		}else{
			var dt = ED.data[ED.currentKey];
			if(typeof(dt.pic)!='undefined'&&dt.pic){}
			else if(typeof(dt.smallunit)!='undefined'&&dt.smallunit){}
			else{
				if(ED.getTypeAttrValue(unitobj)===dt.name){
					ED.cleanTypeAttr(unitobj);
				}else{
					ED.setTypeAttr(unitobj);
				}
			}
		}
	};

	ED.refresh = function(){
		if(ED.currentKey!=''){
			$('#'+ ED.getEditBtnId(ED.currentKey)).removeClass('selected');
		}
		$('.' + ED.unitClass).each(function(index){
			ED.cleanTypeAttr($(this));
			$(this).find('span').each(function(idx){
				ED.cleanTypeAttr($(this));
			});
		});
	};

	ED.touchHandler = function(event){
	    var touches = event.changedTouches,
	        first = touches[0],
	        type = "";

	    switch(event.type){
	        case "touchstart": type = "mousedown"; break;
	        case "touchmove":  type="mousemove"; break;        
	        case "touchend":   type="mouseup"; break;
	        default: return;
	    }
	 
	    var simulatedEvent = document.createEvent("MouseEvent");

	    simulatedEvent.initMouseEvent(type, true, true, window, 1, 
	                              first.screenX, first.screenY, 
	                              first.clientX, first.clientY, false, 
	                              false, false, false, 0/*left*/, null);
	    first.target.dispatchEvent(simulatedEvent);
	    event.preventDefault();
	};

	ED.initDragUI = function(){
	    document.addEventListener("touchstart", ED.touchHandler, true);
	    document.addEventListener("touchmove", ED.touchHandler, true);
	    document.addEventListener("touchend", ED.touchHandler, true);
	    document.addEventListener("touchcancel", ED.touchHandler, true); 
	};

	ED.setRecordToCookie = function(record){
		var days = 1;
		var exp = new Date();
		exp.setTime(exp.getTime() + days * 24 * 60 * 60 * 1000);
		document.cookie = ED.md5v + "=" + escape(record) +";expires=" + exp.toGMTString();
	};

	ED.loadRecordFromCookie = function(defaultvalue){
		var arr = document.cookie.match(new RegExp("(^| )"+ED.md5v+"=([^;]*)(;|$)"));
		if(arr !=null){
			ED.cacheRecord = unescape(arr[2]);
		}else{
			ED.cacheRecord = defaultvalue;
		}
	};

	ED.initRecordHitArr = function(){
		var rcd = ED.cacheRecord;
		if(rcd.length == 0){
			return;
		}else if(rcd.indexOf("@")<0){
			return;
		}
		var sps = rcd.split("/");
		for(var key in sps){
			if(sps[key].indexOf("@")>0){
				var ss = sps[key].split("@");
				ED.recordHitArr[ss[0]] = ss[1];
			}
		}
	};

	ED.initMatchedObj = function(obj, isimg){
		var ind = obj.attr('ind');
		if(typeof(ED.recordHitArr[ind])!='undefined'){
			var keyind = ED.recordHitArr[ind];
			var key = keyind;
			if(!ED.isBasicInfo(keyind)){
				key = ED.dataKeys[parseInt(keyind)];
			}
			ED.setTypeAttr(obj, isimg, key);
		}
	};

	ED.pageScroll = function(){ 
		$('html, body').animate({scrollTop:0}, 'fast');
	};

	ED.initBasicInfos = function(){
		ED.basicinfoBtns = $('[name=basicinfo]');
		ED.basicinfoBtns.each(function(ind){
			var btn = $(this);
			btn.css('background', '#aacc00');
			ED.basicinfos[btn.text()] = btn;
			btn.on('click', function(){
				ED.asBtnClick(btn);
			});
		});
	};

	ED.rememberSizeForOne =function(obj, w, h){
		var robj = obj[0];
		if(typeof(w) == 'undefined'){
			w = robj.offsetWidth;
			h  =robj.offsetHeight;
		}
		obj.attr('oriWidth', w);
		obj.attr('oriHeight', h);
	};

	ED.rememberSize = function(){
		ED.rememberSizeForOne($('.' + ED.paneclass));
		$('.ym-submit-btn').each(function(){
			ED.rememberSizeForOne($(this));
		});

		ED.rememberSizeForOne($('.ym-pane-move-region'));
		var w = 0, h = 0;
		$('.oriinfoClass').children().each(function(){
			if($(this)[0].tagName == 'BUTTON'){
				ED.rememberSizeForOne($(this));
				w = $(this)[0].offsetWidth; h = $(this)[0].offsetHeight;
			}
		});

		$('.basicinfoClass').children().each(function(){
			if($(this)[0].tagName == 'BUTTON'){
				ED.rememberSizeForOne($(this), w, h);
			}
		});
	};

	ED.init = function(){
		ED.loadRecordFromCookie(ED.cacheRecord);
		ED.initRecordHitArr();

		var html = '<div class="ym-smallize-btn"><i class="am-icon-caret-down am-icon-sm"></i></div><div class="_action_pane"><div class="ym-pane-move-region enlargable">&nbsp;</div>';
		html += '<div style="margin-top:5px">';
		html += '<span><button class="ym-submit-btn lefttop enlargable" title="向上滚动" id="_upBtn"><i class="am-icon-chevron-circle-up"></i></button></span>';
		html += '<span><button class="ym-submit-btn righttop enlargable" title="回到顶部" id="_topBtn"><i class="am-icon-angle-double-up"></i></button></span>';
		html += '<span><button class="ym-submit-btn middle left enlargable" title="放大" id="_enlargeBtn"><i class="am-icon-plus-square"></i></button></span>';		
		html += '<span><button class="ym-submit-btn middle right enlargable" title="搜索别的资源" id="_searchBtn"><i class="am-icon-search-plus"></i></button></span>';		
		html += '<span><button class="ym-submit-btn middle large enlargable" title="下一步" id="_submitBtn"><i class="am-icon-play"></i></button></span>';
		html += '<span><button class="ym-submit-btn middle large enlargable" title="上一步" id="_backBtn"><i class="am-icon-chevron-circle-left"></i></button></span>';		
		html += '<span><button class="ym-submit-btn leftbottom enlargable" title="向下滚动" id="_downBtn"><i class="am-icon-chevron-circle-down"></i></button></span>';
		html += '<span><button class="ym-submit-btn rightbottom enlargable" title="刷新" id="_refreshBtn"><i class="am-icon-refresh"></i></button></span>';
		html += '</div><div style="margin-top:10px">';
		html += '<div class="oriinfoClass">';
		var ct = 0;
		var txthtml = $('#_submitform').html();
		for(var key in ED.data){
			html += '<button name="editbtn" class="' + ED.editbtnClazz +' enlargable heightfreeze" id="' + ED.getEditBtnId(key) +'" style="background:'+
						ED.data[key].color+'">'+key+'</button>';
			ct ++;
		}
		html += '</div><div class="basicinfoClass" style="display:none">';
		html += '<div><span id="_closebasic" class="am-close am-close-spin" style="float:right">&times;</span></div>';
		for(var key in ED.basicinfoData){
			html += '<button name="basicinfo" class="' + ED.editbtnClazz +' enlargable heightfreeze" code="'+key+'">'+ED.basicinfoData[key]+'</button>';			
		}
		html += '</div></div></div>';
		for(var key in ED.fulldata){
			ED.content[ED.fulldata[key].name]= $('#_prev_'+ED.fulldata[key].name).val();
			txthtml += '<textarea name="' + ED.fulldata[key].name + '" id="' +ED.getInputCompId(key)+ '"></textarea>';
		}

		$('.' + ED.paneclass).html(html);

		ED.initBasicInfos();

		$('[name=editbtn]').each(function(index){
			var btn = $(this);
			var dt = ED.fulldata[btn.text()];
			if(typeof(dt.func)=='function'){
				btn.on('click', dt.func);
			}else{
				btn.on('click', function(){
					ED.asBtnClick(btn);
				});				
			}

		});

		$('.' + ED.unitClass).each(function(index){
			var unitObj = $(this);
			ED.initMatchedObj(unitObj, false);

			unitObj.find('span').each(function(spanind){
				var smallobj = $(this);
				ED.initMatchedObj(smallobj, false);
				smallobj.on('click', function(){
					ED.asSmallUnitClick(smallobj);
				});
			});
			unitObj.on('click', function(){
				ED.asUnitClick(unitObj);
			});
		});

		$('.' + ED.imgpaneClass).each(function(index){
			var imgobj = $(this);
			ED.initMatchedObj(imgobj, true);
			imgobj.on('click', function(){
				ED.asImgClick(imgobj);
			});
		});

		$('#_closebasic').on('click', function(){
			ED.toggleActions(false);
		});
		$('#_submitform').html(txthtml);
		ED.attachMoveEvent($('.ym-pane-move-region'));
		ED.attachScrollEvent($('#_upBtn'), true);
		ED.attachScrollEvent($('#_downBtn'), false);

		$('#_backBtn').on('click', function(){
			ED.collectContent();
			history.go(-1);
		});
		$('#_topBtn').on('click', function(){
			ED.pageScroll();
		});			
		$('#_submitBtn').on('click', function(){
			ED.submitForm();
		});
		$('#_searchBtn').on('click', function(){
			ED.postSearch();
		});
		$('#_refreshBtn').on('click', function(){
			ED.refresh();
		});
		$('#_enlargeBtn').on('click', function(){
			ED.enlargeAction();
		});	
		ED.attachHelpEvent();
		ED.paneheight = $('.' + ED.paneclass)[0].offsetHeight;
		ED.togglePane();
		ED.rememberSize();
	};

	ED.scrollPage = function(up){
		var step = 500;
		if(ED.onscroll){
			var sctop = $(document).scrollTop();
			var nexttop = 0;
			if(up&&sctop>0){
				if(sctop > step){
					nexttop = sctop - step;
				}else{
					nexttop = 0;
				}
				
			}else if(!up){
				var totaltop = $(document).height();
				if(sctop < totaltop){
					var n = sctop + step;
					if(n > totaltop){
						nexttop = totaltop - sctop;
					}else{
						nexttop = n;
					}
				}
			}
			$('html, body').animate({scrollTop:nexttop}, 'fast');

			setTimeout(function(){
				ED.scrollPage(up);
			}, 300);
		}
	};

	ED.getEventPos = function(e, ispc){
		var x = 0, y = 0;
		if(ispc){
			x = e.clientX; 
			y = e.clientY;
		}else{
			e = e.originalEvent;
			if (e.targetTouches.length >= 1) {
        		var touch = e.targetTouches[0];
        		x = touch.pageX-50;
        		y = touch.pageY-50;
        	}			
		}
		return [x, y];
	};


	ED.attachMoveEvent = function(obj){
		var ispc= ED.isPC();
		var action = ['touchstart', 'touchmove', 'touchend'];
		if(ispc)
			action = ['mousedown', 'mousemove', 'mouseup'];

		obj.on(action[0], function(e){
			ED.onpanemove = true;
			$(this).addClass('touch');
			var ee = e || window.event;
			var initX = 0, initY = 0;
			var pos = ED.getEventPos(ee, ispc);
			initX = pos[0];
			initY = pos[1];
			var paneobj = $('.ym-editpane');

			ED.pos.left = initX - paneobj[0].offsetLeft;
			ED.pos.top = initY - paneobj[0].offsetTop;
			this.setCapture && this.setCapture(); 
			return false;
		});

		$(document).on(action[1], function(e){
			var ee = e || window.event; 
			if(ED.onpanemove){
				ee.preventDefault();
				var pos = ED.getEventPos(ee, ispc);
				var oX = pos[0] - ED.pos.left; 
				var oY = pos[1] - ED.pos.top; 
				var height = $('.ym-editpane')[0].offsetHeight;
				$('.ym-editpane').css({"left":oX + "px", "top":oY + "px", "height":height + "px"}); 				
				return false;
			}
		});

		obj.on(action[2], function(e){
			e.preventDefault();
			$(this).removeClass('touch');
			ED.onpanemove = false; 
			this.releaseCapture&&this.releaseCapture(); 
			e.cancelBubble = true; 
		});
	};

	ED.attachScrollEvent = function(obj, isup){
		var ispc= ED.isPC();
		var start = (ispc?'mousedown':'touchstart');
		var end = (ispc?'mouseup':'touchend');
		obj.on(start, function(){
			ED.onscroll = true;
			ED.scrollPage(isup);
		});

		obj.on(end, function(){
			ED.onscroll = false;
		});
	};

	ED.fillBasicContent = function(key, content){
		ED.fillContentGeneric('jbxx', key + ":"+content, true);
	};

	ED.fillContent = function(key, content, join){
		if(typeof(join)=='undefined')
			join = false;
		var thekey = ED.data[key].name;
		ED.fillContentGeneric(thekey, content, join);
	};

	ED.fillContentGeneric = function(thekey, content, join){
		var con = ED.content[thekey];
		if(con.length == 0){
			con = content;
		}else{
			con = con + (join?";":"\r\n") + content;
		}
		ED.content[thekey] = con;
	};

	ED.bindBasicValue = function(code, obj){
		return obj.attr('ind') + "@" + code + "/";
	};
	ED.bindRecordValue = function(dtkey, obj){
		return obj.attr('ind') + "@" + ED.dataKeyInds[dtkey] +"/";
	};

	ED.collectContent = function(){
		var recordValue = "";
		var ind = 0;
		for(var key in ED.basicinfos){
			$('span['+ED.typeAttr+'="'+ED.basicinfos[key].attr('code')+'"]').each(function(index){
				ED.fillBasicContent(key, $(this).text());
				recordValue += ED.bindBasicValue(key, $(this));
			});
		}

		for(var key in ED.data){
			var dt = ED.data[key];
			var mykey = key;
			if(typeof(dt.pic)!='undefined'&&dt.pic){
				$('img['+ED.typeAttr+'="'+dt.name+'"]').each(function(index){
					if(typeof(dt.join)!='undefined'&&dt.join)
						ED.fillContent(mykey, $(this).attr('src'), true);
					else
						ED.fillContent(mykey, $(this).attr('src'), false);
					recordValue += ED.bindRecordValue(mykey, $(this));
				});				
			}else if(typeof(dt.smallunit)!='undefined'&&dt.smallunit){
				$('span['+ED.typeAttr+'="'+dt.name+'"]').each(function(index){
					if(typeof(dt.join)!='undefined'&&dt.join)
						ED.fillContent(mykey, $(this).text(), true);
					else
						ED.fillContent(mykey, $(this).text(), false);
					recordValue += ED.bindRecordValue(mykey, $(this));
				});

			}else{
				$('div['+ED.typeAttr+'="'+dt.name+'"]').each(function(index){
					if(typeof(dt.join)!='undefined'&&dt.join)
						ED.fillContent(mykey, $(this).text(), true);
					else
						ED.fillContent(mykey, $(this).text(), false);
					recordValue += ED.bindRecordValue(mykey, $(this));
				});
			}
		}
		for(var key in ED.fulldata){
			var dt = ED.fulldata[key];
			$('#'+ED.getInputCompId(key)).val(ED.content[dt.name]);
		}

		$('#_record').val(recordValue);
		ED.setRecordToCookie(recordValue);

	};

	ED.isPC = function(){    
	     var userAgentInfo = navigator.userAgent;  
	     var agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");    
	     var flag = true;    
	     for (var v = 0; v < agents.length; v++) {    
	         if (userAgentInfo.indexOf(agents[v]) > 0) { flag = false; break; }    
	     }    
	     return flag;    
	};

	ED.enlargeAction = function(){
		var enlargeObj = $('#_enlargeBtn');
		if(!ED.enlarged){
			ED.enlargePane(ED.enlargeRate);
			ED.enlarged = true;
			enlargeObj.children().removeClass('am-icon-plus-square');
			enlargeObj.children().addClass('am-icon-minus-square');
			enlargeObj.attr('title', '恢复原始大小');
		}else{
			ED.recoverSize();
			ED.enlarged = false;
			enlargeObj.children().removeClass('am-icon-minus-square');
			enlargeObj.children().addClass('am-icon-plus-square');
			enlargeObj.attr('title', '放大');
		}
	};

	ED.enlargeOneObj = function(obj, rate){
		var clazz = obj.attr('class');
		if(clazz.indexOf('heightfreeze')<0)
			return [parseInt(obj[0].offsetWidth * rate), parseInt(obj[0].offsetHeight * rate)];
		else
			return [parseInt(obj[0].offsetWidth * rate), obj[0].offsetHeight];
	};

	ED.enlargePaneSub = function(rate){
		$('.enlargable').each(function(){
			var ele = $(this);
			var size = ED.enlargeOneObj(ele, rate);
			ele.css({'width':size[0] + 'px', 'height':size[1] + 'px'});
		});
	};

	ED.enlargePane = function(rate){
		var pane = $('.' + ED.paneclass);
		var size = ED.enlargeOneObj(pane, rate);
		pane.animate({width:size[0]}, 'fast');
		ED.enlargePaneSub( rate);
	};


	ED.recoverSize =function(){
		var pane = $('.' + ED.paneclass);
		$('.enlargable').each(function(){
			var ele = $(this);
			var w = parseInt(ele.attr('oriWidth'));
			var h = parseInt(ele.attr('oriHeight'));
			ele.css({'width':w + 'px', 'height':h + 'px'});
		});

		var panew = parseInt(pane.attr('oriWidth'));
		//var paneh = parseInt(pane.attr('oriHeight'));
		pane.animate({width:panew}, 'fast');

	};

	ED.submitForm = function(){
		ED.collectContent();
		$('#_submitform').submit();
	};

	ED.postSearch = function(){
		ED.collectContent();
		var form = $('#_submitform');
		form.attr('action', '/baidu');
		$('#_submitform').submit();
	};

	ED.attachHelpEvent = function(){
		$('.ym-help-pane').on('click', function(e){
			var pane = $(this);
			if(pane[0].offsetHeight > 30){
				$(this).animate({height: 30}, 'fast');
			}else{
				$(this).animate({height: 200}, 'fast');
			}
			
		});
	};

	ED.togglePane = function(){
		var h = 20;
		$('.ym-smallize-btn').on('click', function(){
			var pane = $('.' + ED.paneclass);
			if(pane[0].offsetHeight > h){
				ED.paneheight = pane[0].offsetHeight;
				pane.animate({height: h}, 'fast');
				$(this).children().removeClass('am-icon-caret-down');
				$(this).children().addClass('am-icon-caret-up');
				$('._action_pane').css('display', 'none');
				$('.' + ED.paneclass).css({'right':'5px', 'bottom':'5px'});
			}else{
				pane.animate({height: ED.paneheight}, 'fast');
				$(this).children().removeClass('am-icon-caret-up');
				$(this).children().addClass('am-icon-caret-down');
				$('._action_pane').css('display', 'block');
			}
		});
	};

	ED.init();
}

