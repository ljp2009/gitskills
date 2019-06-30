var ym_game_thirteen = function(){
	var G13 = this;

	G13.mode = 'GUI';//GUI or MUD
	G13.margin = 10;
	G13.offset = 8;
	G13.prepareTimeout = 30; //seconds
	G13.dazhaoImgs = ['dazhao_1','dazhao_2','dazhao_3','dazhao_4','dazhao_5','dazhao_6'];

	G13.stages = {load:'load', showcard:'start', prepare:'prepare', waitop:'waitop',
		compete:'compete', showdazhao:'showdazhao', close:'close'};

	G13.params = {
		roomid:0,
		statusid:0,
		left:0,
		top:0,
		width:$(document).width(),
		height:$(document).height(),
		ctx:false,
		backImg:false,
		actionDiv:false,
		cardWidth:0,
		cardHeight:0,
		cardImgs:[],
		curCardObjs:[],
		isPC:false,
		selectedCards:[],
		mycards:[],
		dazhaos:[],
		stage:false,
		pickCardPaneImgs:[],
		allHtmlCardImgs:[],
		strategyFetched:false,
		curStrategyData:false,
		loadCardsForDisplay:false,
		interrupted:false
	};

	G13.allowTouch = false;
	G13.curData = false;
	G13.loaded = false;
	G13.canvas = $('#cardsPlatform');

	G13.colors = ['spade', 'heart', 'diamond', 'club', 'diamond'];

	G13.loadingCount = 0;
	G13.pickCardPane = $('#pickCardPane');
	G13.dazhaoPane = $('#dazhaoPane');

	G13.controlPane = $('#controlPane');

	G13.getRandomNum = function(Min,Max){   
		var Range = Max - Min;   
		var Rand = Math.random();   
		return(Min + Math.round(Rand * Range));   
	};

	G13.hideDazhaoAndRestart = function(){
		G13.dazhaoPane.css('display', 'none');
		G13.hideAllCardImgs();
		G13.restart();
	};

	G13._loadDazhaoWhenReady = function(img){
		var imgtarget = img[0];
		var w = imgtarget.width;
		var h = imgtarget.height;

		var showw = G13.params.width;
		var showh = parseInt(h * (showw / w));
		var showtop = 0;
		var showleft = 0;

		if(showh > G13.params.height){
			showw = parseInt(showw * G13.params.height/showh);
			showh = G13.params.height;
			showleft = parseInt((G13.params.width - showw) / 2);
		}else{
			showtop = parseInt((G13.params.height - showh) / 2);
		}

		img.css({'left':showleft + "px", 'top':showtop + "px", 'width':showw + "px", 'height':showh + "px"});
		img.attr('width', showw);
		img.attr('height', showh);

		G13.dazhaoPane.css('display', 'block');

		setTimeout(function(){G13.hideDazhaoAndRestart();}, 10000);
	};

	G13.showDazhao = function(dazhao){
		if(typeof(dazhao) == 'undefined'){
			var idx = G13.getRandomNum(0, G13.dazhaoImgs.length - 1);
			dazhao = G13.dazhaoImgs[idx];
		}
		var src = "/pic/local/cards/" +dazhao +".gif";
		G13.dazhaoPane.css({'display':'none', 
			'width':G13.params.width + "px", 'height':G13.params.height + "px"});
		G13.dazhaoPane.html("<img id='_dazhao_tmp' class='dazhao'>");
		var img = $('#_dazhao_tmp');
		img[0].onload = function(){
			G13._loadDazhaoWhenReady(img);
		};
		img.attr('src', src);
	};

	G13.clearCanvas = function(){
		var context = G13.params.ctx;
		context.clearRect(0, 0, G13.params.width, G13.params.height);
	};

	G13.refreshCanvas = function(){
		G13.clearCanvas();
		for(var i=0; i<G13.params.curCardObjs.length; i++){
			var one = G13.params.curCardObjs[i];
			G13.drawCardObj(one, false);
		}
	};

	G13.countForTimeout = function(curtime, func){
		var context = G13.params.ctx;
		context.font="30px Georgia";
		context.fillStyle="#0000ff";
		var left = G13.params.width - 50;
		var top = 50;
		if(G13.params.interrupted)
			return;
		context.clearRect(left - 50, top - 50, 150, 150);

		if(curtime > 0){
			context.fillText(curtime,left, top);
			curtime --;
			setTimeout(function(){G13.countForTimeout(curtime, func);}, 1000);			
		}else{
			if(typeof(func)==='function'){
				func();
			}
		}
	};

	G13.startCountTime = function(func, timeout){
		G13.params.interrupted = false;
		if(typeof(timeout)=='undefined')
			timeout = G13.prepareTimeout;
		G13.countForTimeout(timeout, func);
	};

	G13.swapTwoCardObjs = function(imgid1, imgid2){
		var obj1 = $('#' + imgid1);
		var obj2 = $('#' + imgid2);

		for(var i=0; i<G13.params.pickCardPaneImgs.length; i++){
			if(G13.params.pickCardPaneImgs[i] == imgid1){
				G13.params.pickCardPaneImgs[i] = imgid2;
			}
			if(G13.params.pickCardPaneImgs[i] == imgid2){
				G13.params.pickCardPaneImgs[i] = imgid1;
			}			
		}

		var x0 = obj1.attr('myleft'), x1 = obj2.attr('myleft');
		var y0 = obj1.attr('mytop'), y1 = obj2.attr('mytop');
		var h0 = obj1.attr('height'), h1 = obj2.attr('height');
		var w0 = obj1.attr('width'), w1 = obj2.attr('width');

		obj1.attr('myleft', x1);
		obj1.attr('mytop', y1);
		obj1.attr('mystatus', 'normal');
		obj1.css('left', x1 + "px");
		obj1.css('top', y1 + "px");
		obj1.attr('width', w1);
		obj1.attr('height', h1);


		obj2.attr('myleft', x0);
		obj2.attr('mytop', y0);
		obj2.attr('mystatus', 'normal');	
		obj2.css('left', x0 + "px");
		obj2.css('top', y0 + "px");
		obj2.attr('width', w0);
		obj2.attr('height', h0);
		
	}

	G13.attachTouchEvent = function(imgid){
		var obj = $('#' + imgid);
		obj.on('click', function(){
			if(!G13.allowTouch)
				return;
			var top = parseInt(obj.attr('mytop'));
			var status = obj.attr('mystatus');
			var mykey = obj.attr('mykey');
			if(G13.params.selectedCards.length==1){
				if(G13.params.selectedCards[0] == imgid){
					obj.attr('mystatus', 'normal');
					obj.css('top', top + "px");					
				}else{
					G13.swapTwoCardObjs(imgid, G13.params.selectedCards[0]);
				}
				G13.params.selectedCards = [];
			}else{
				G13.params.selectedCards[0] = imgid;
				obj.attr('mystatus', 'selected');
				top = top - G13.offset;
				obj.css('top', top + "px");				
			} 
		});
	};

	G13.isPC = function(){    
	     var userAgentInfo = navigator.userAgent;  
	     var agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");    
	     var flag = true;    
	     for (var v = 0; v < agents.length; v++) {    
	         if (userAgentInfo.indexOf(agents[v]) > 0) { flag = false; break; }    
	     }    
	     return flag;    
	};
	
	G13.getEventPos = function(e){
		var ispc = G13.params.isPC;
		var x = 0, y = 0;
			x = e.clientX; 
			y = e.clientY;

		return [x, y];
	};

	G13.getCardKey = function(cardIdx){
		var cardNo = cardIdx % 13 + 1;
		if(cardNo == 1)
			cardNo = 14;
		var cardColorIdx = parseInt(cardIdx / 13);
		return G13.colors[cardColorIdx] +"_"+cardNo;
	};

	G13.countSuitableSizeOfImg = function(imgw, imgh){
		var leftMargin = G13.margin, rightMargin = G13.margin, bottomMargin = G13.margin;
		var canvasW = G13.canvas[0].width;
		var canvasH = G13.canvas[0].height;
		var posw = parseInt((canvasW - leftMargin - rightMargin)/17*6); 
		var posh = imgh * posw / imgw;
		var maxH = canvasH/4;
		if(posh > maxH){
			posw = posw * maxH / posh
			posh = maxH;
		}
		var posl = leftMargin;
		var post = canvasH - bottomMargin - posh;
		return [posl, post, posw, posh];
	};

	G13.resetActionDivPos = function(pos){
		if(typeof(pos)=='undefined'){
			pos = 'middle';
		}
		var div = G13.params.actionDiv;
		var w = div[0].offsetWidth;
		var h = div[0].offsetHeight;
		var x = (G13.params.width - w) / 2;
		var y = 0;
		if(pos == 'middle')
			y = (G13.params.height - h) / 2;
		else if(pos == 'top')
			y = G13.margin;
		else if(pos == 'bottom')
			y = G13.params.height - G13.margin - h;

		div.css('left', x + 'px');
		div.css('top', y + 'px');
	};

	G13.attachAction = function(name, func, hideAfterClick, pos){
		if(typeof(hideAfterClick)=='undefined'){
			hideAfterClick = true;
		}
		var div = G13.params.actionDiv;
		div.css('display', 'block');
		div.html('<button>'+name +'</button>');
		G13.resetActionDivPos(pos);
		div.find('button').on('click', function(e){
			if(hideAfterClick){
				div.css('display', 'none');
			}
			func(e);
	
		});
	};

	G13.restart = function(){
		G13.clearCanvas();
		G13.attachAction('Start', function(e){
			G13.displayCards([]);
		});
	};

	G13.initAfterLoaded = function(){
		G13.loaded = true;
		G13.attachAction('Start', function(e){
			G13.displayCards([]);
		});
		// G13.loadDazhao();

		//G13.startCountTime();
	};

	G13.createCardImgId = function(cardKey, second){
		if(typeof(second)=='undefined')
			second = false;
		var id = 'img_' + cardKey;
		if(second)
			id = id + "_2";
		return id;
	};

	G13.initCardImgs = function(){
		G13.params.actionDiv.html('<span>游戏正在加载...</span>');
		G13.params.actionDiv.css('display', 'block');
		G13.resetActionDivPos();
		var html = "";
		for(var i=0; i<65; i++){
			var cardKey = G13.getCardKey(i);
			var imgid = G13.createCardImgId(cardKey, i>=52);
			html +="<img id='"+imgid+"' class='fixobj' mystatus='normal' style='display:none' mykey='"+ cardKey +"' isvisible='false'>";
			G13.params.allHtmlCardImgs[G13.params.allHtmlCardImgs.length] = imgid;				
		}
		G13.pickCardPane.html(html);

		for(var i=0; i<65; i++){
			var cardKey = G13.getCardKey(i);
			var imgid = G13.createCardImgId(cardKey, i>=52);	
			var imgsrc = "/pic/local/cards/" + cardKey + ".png";
			var imgobj =  $('#' + imgid)[0];
			imgobj.onload = function(){
				G13.loadingCount++;
				if(G13.loadingCount == 65){
					//G13.params.actionDiv.css('display', 'none');
					G13.initAfterLoaded();
				}
			};
			G13.attachTouchEvent(imgid);
			imgobj.src = imgsrc;
			if(i<52)
				G13.params.cardImgs[cardKey] = imgobj;
		}
	};

	G13.init = function(){
		G13.params.isPC = G13.isPC();
		G13.params.actionDiv = $('#actionDiv');
		G13.canvas.css("height", G13.params.height + 'px');
		G13.canvas.css("width", G13.params.width + 'px');
		G13.params.ctx = G13.canvas[0].getContext("2d");
		G13.canvas[0].width = G13.params.width;
		G13.canvas[0].height = G13.params.height;
		G13.params.backImg = new Image();
		G13.params.backImg.src = "/pic/local/cards/card_back.jpg";

		G13.initCardImgs();
	};

	G13.updateStatus = function(){
		$.get('/game/thirteen/updateStatus', function(data){
			G13.params.statusid = data;
		});		
	};

	G13.setReady = function(){
		$.post('/game/thirteen/setReady', {'_token':$('meta[name="csrf-token"]').attr('content')}, function(data){
			data = eval(data);
			G13.params.roomid = data[1];
			if(data[0]!='true'){
				G13.checkReady();
			}else{
				G13.getMyCards();
			}
		});
	};

	G13.checkReady = function(){
		$.get('/game/thirteen/checkReady', function(data){

		});
	};


	G13.getMyCards = function(){
		$.get('/game/thirteen/getMyCards/' + G13.params.statusid, function(data){
			G13.displayCards(data);
		});		
	};

	G13.addToCurObjs = function(imgidx, x, y, w, h, isoff){
		var arr = G13.params.curCardObjs;
		var obj = {imgidx:imgidx, x:x, y:y, w:w, h:h, off:isoff};
		arr[arr.length] = obj;
	};

	G13.drawCardObj = function(obj, isnormal){
		G13.drawCard(obj.imgidx, obj.x, obj.y, obj.w, obj.h, obj.off, isnormal, obj);
	};

	G13.setRadius = function(context, cornerX, cornerY, width, height, cornerRadius) {
	   context.beginPath();
	   if (width> 0) 
	   		context.moveTo(cornerX + cornerRadius, cornerY);
	   else  
	   		context.moveTo(cornerX - cornerRadius, cornerY);
	   context.arcTo(cornerX+width,cornerY,cornerX + width,cornerY+height,cornerRadius);
	   context.arcTo(cornerX+width,cornerY + height,cornerX,cornerY+height,cornerRadius);
	   context.arcTo(cornerX,cornerY+height,cornerX,cornerY,cornerRadius);
	   if(width> 0) {
	      context.arcTo(cornerX,cornerY,cornerX+cornerRadius,cornerY,cornerRadius);
	   }
	   else{
	     context.arcTo(cornerX,cornerY,cornerX-cornerRadius,cornerY,cornerRadius);
	   }
	   context.closePath();
	};

	G13.drawCard = function(imgidx, x, y, w, h, isoff, isnormal, obj){
		var offset = G13.offset;
		var context= G13.params.ctx;
		if(typeof(w)=='undefined')
			w = G13.params.cardWidth;
		if(typeof(h)=='undefined')
			h = G13.params.cardHeight;
		context.clearRect(x, y, w, h);
		if(typeof(isoff)=="undefined")
			isoff = false;
		if(typeof(isnormal)=="undefined")
			isnormal = true;
		if(typeof(obj) == "undefined")
			obj = false;
		// if(isnormal)
		// 	y = (!isoff)?(y-offset):y;
		// else
		// 	y = isoff?(y+offset):y;
		var img = false;
		if(imgidx===false){
			img = G13.params.backImg;
		}else{
			img = G13.params.cardImgs[G13.getCardKey(imgidx)];
		}
		// G13.setRadius(context, x, y, w, h, 15);
		context.drawImage(img,0, 0,img.width,img.height,x, y, w, h);
		
		if(obj === false)
			G13.addToCurObjs(imgidx, x, y, w, h, isoff);
		else
			obj.y = y;
	};

	G13.displayOneCard = function(idx, size){
		var left = size[0] + size[2]/6 * idx;
		var right = G13.params.width - 10 - size[2]/6 * idx - size[2];

		G13.drawCard(false, left, size[1], size[2], size[3]);
		G13.drawCard(false, right, 10, size[2], size[3]);

		if(idx < 15){
			setTimeout(function(){idx++; G13.displayOneCard(idx, size);}, 20);
		}else{
			G13.params.loadCardsForDisplay = true;
			if(G13.params.strategyFetched){
				G13.displayStrategy();
			}
			//G13.displayChosenCardsWithStragety([0, 1, 1, 3, 4], [5, 6, 7, 8, 9], [10, 11, 12], [13, 14, 15]);
			//G13.displayCardsDuringCompetetion(G13._tmp_getTmpCards(), G13._tmp_getTmpCards(), 3);
		}
	};

	G13.displayStrategy = function(){
		var data = G13.params.curStrategyData;
		var carddata = data.strategies[0];
		G13.displayChosenCardsWithStragety(carddata['bottom'], carddata['middle'], carddata['top'], carddata['rest'], 
			[carddata['bottomLevelName'], carddata['middleLevelName'], carddata['topLevelName']]);
		G13.startCountTime(function(){
			G13._tmp_showCardCompetetion();
		});
		G13.attachAction("Ready", function(){
			G13.params.interrupted = true;
			G13._tmp_showCardCompetetion();
		}, true, 'bottom');
	};

	G13._tmp_showCardCompetetion = function(mycards, opcards, turn){
		G13.params.actionDiv.css('display', 'none');
		if(typeof(mycards)=='undefined'){
			mycards = G13._tmp_getTmpCards();
			opcards = mycards;
			turn = 0;
			
		}
		G13.displayCardsDuringCompetetion(mycards, opcards, turn);
		if(turn < 3){
			turn ++;
			setTimeout(function(){
				G13._tmp_showCardCompetetion(mycards, opcards, turn);
			}, 2000);
		}else{
			G13.showDazhao();
		}
	};

	G13._tmp_getTmpCards = function(){
		return {
			"bottom":[0, 1, 2, 3, 4],
			"middle":[5, 6, 7, 8, 9],
			"top":[10, 11, 12],
			"extra":[13, 14, 15]
		};
	};

	G13.displayCards = function(cards){
		G13.params.curCardObjs = [];
		var img = G13.params.backImg;
		if(img.complete){
			//context.drawImage(img,'0px','0px',img.width,img.height,50,50,img.width*0.5,img.height*0.5);
			var size = G13.countSuitableSizeOfImg(img.width, img.height);
			G13.params.cardWidth = parseInt(size[2]);
			G13.params.cardHeight = parseInt(size[3]);
			G13.params.loadCardsForDisplay = false;
			G13.getStrategy();
			G13.displayOneCard(0, size);
		}else{
			img.onload = function(){
				G13.displayCards(cards);
			};
		}

	};

	G13.hideAllCardImgs = function(){
		for(var k in G13.params.allHtmlCardImgs){
			G13.showCardImg(G13.params.allHtmlCardImgs[k], false);
		}
	};

	G13.showCardImg = function(imgid, display, x, y, w, h){
		var imgobj = $('#'+ imgid);
		imgobj.attr('isvisible', display?'true':'false');
		imgobj.css('display', display?'block':'none');
		if(display){
			imgobj.attr('width', w);
			imgobj.attr('height', h);
			imgobj.attr('mytop', y);
			imgobj.attr('myleft', x);
			imgobj.css({'left':x + 'px', 'top':y + 'px'});
		}else{
			imgobj.attr('mystatus', 'normal');
		}
	};

	G13.displayCardInALine = function(inpane, cards, startx, starttop, hgap, isBg, opdir, w, h){
		if(typeof(isBg)=='undefined')
			isBg = false;
		if(typeof(opdir)=='undefined')
			opdir = false;
		if(typeof(w)=='undefined'){
			w = G13.params.cardWidth;
		}
		if(typeof(h)=='undefined'){
			h = G13.params.cardHeight;
		}
		starttop = parseInt(starttop);
		var newObjs = [];

		for(var i=0; i<cards.length; i++){			
			var x = parseInt(opdir?(startx - i * (w + hgap)):(startx + i * (w + hgap)));
			var imgidx = (isBg?false:cards[i]);
			if(imgidx===false){
				inpane = false;
			}else{
				inpane = true;
			}
			if(inpane){
				//G13.drawCard(false, x, starttop, w, h);
				var cardKey = G13.getCardKey(imgidx);
				var imgid = G13.createCardImgId(cardKey);
				var imgobj = $('#'+ imgid);
				if(imgobj.attr('isvisible')==='false'){
					G13.showCardImg(imgid, true, x, starttop, w, h);
				}else{
					imgid = G13.createCardImgId(cardKey, true);
					if($('#' + imgid).length>0){
						G13.showCardImg(imgid, true, x, starttop, w, h);
					}else{
						var img = G13.params.cardImgs[cardKey];
						G13.params.allHtmlCardImgs[G13.params.allHtmlCardImgs.length] = imgid;
						G13.pickCardPane.append("<img id='"+imgid+"' src='"+img.src+"' height='" + h + "' width='" +
									w +"' class='fixobj' style='left:" + x +"px; top:" + starttop 
									+"px' mytop='"+ starttop +"' myleft='"+ x +"' mystatus='normal' mykey='"+ cardKey +"'>");
						newObjs[newObjs.length] = imgid;
					}
				}
	
				var len = G13.params.pickCardPaneImgs.length;
				G13.params.pickCardPaneImgs[len] = imgid;

			}else{
				G13.drawCard(imgidx, x, starttop, w, h);
			}

		}
		if(newObjs.length > 0)
			return newObjs;
		return false;
	};

	G13.evalSizeOfCardsForChosen = function(){
		var cardW = G13.params.cardWidth;
		var cardH = G13.params.cardHeight;
		var margin = G13.margin;
		var hgap = margin/2;
		var tgap = G13.offset * 2;
		var sW = G13.params.width;
		var sH = G13.params.height;

		var baseW = parseInt((sW - margin * 2 - hgap * 4) / 5);
		var baseH = parseInt((sH - tgap * 5 - margin)/(3 + 2* 3/4));
		
		var w = baseW;
		var h = parseInt(cardH * baseW / cardW);

		if(h > baseH){
			w = parseInt(baseW * baseH / h);
			h = baseH;
		}

		G13.params.cardWidth = w;
		G13.params.cardHeight = h;

	};

	G13.displayChosenCardsWithStragety = function(bottom, middle, top, extra, levels){
		G13.params.curCardObjs = [];
		G13.params.pickCardPaneImgs = [];
		G13.evalSizeOfCardsForChosen();

		var context = G13.params.ctx;
		context.clearRect(0, 0, G13.params.width, G13.params.height);
		var margin = G13.margin;
		var hgap = margin/2;
		var tgap = G13.offset * 2;

		var shortWidth = parseInt(G13.params.cardWidth * 3 / 4);
		var shortHeight = parseInt(G13.params.cardHeight * 3 / 4);
		var starttop = G13.params.height - margin - shortHeight;
		var startx = margin;

        context.strokeStyle="#00cc00";
        context.lineWidth=5;
   		G13.setRadius(context, startx, starttop, shortWidth, shortHeight, 5);
   		context.stroke();
		// context.rect(startx, starttop, shortWidth, shortHeight);
		context.fillStyle="white";
		context.fill();	
		context.font="10px Georgia";
		context.fillStyle="#0000ff";
		var txtleft = margin * 2 + (shortWidth - 60)/2;
		for(var i=0; i<levels.length; i++){
			var txt = levels[i];
			context.fillText(txt, txtleft, starttop + shortHeight/3*(3-i) - margin);
		}

		starttop = starttop - tgap - G13.params.cardHeight;
		
		var newobjs = [];

		newobjs[newobjs.length] = G13.displayCardInALine(true, bottom, startx, starttop, hgap);

		starttop = starttop - G13.params.cardHeight - tgap;
		newobjs[newobjs.length] = G13.displayCardInALine(true, middle, startx, starttop, hgap);

		starttop = starttop - G13.params.cardHeight - tgap;
		newobjs[newobjs.length] = G13.displayCardInALine(true, top, startx, starttop, hgap);

		starttop = starttop - shortHeight - tgap;
		newobjs[newobjs.length] = G13.displayCardInALine(true, extra, startx, starttop, hgap, false, false, shortWidth, shortHeight);

		G13.allowTouch = true;

		for(var k in newobjs){
			if(newobjs[k] !== false){
				for(var id in newobjs[k]){
					G13.attachTouchEvent(id);
				}
			}
		}



	};

	G13.evalSizeOfCardsForCompetetion = function(){
		var cardW = G13.params.cardWidth;
		var cardH = G13.params.cardHeight;
		var margin = G13.margin;
		var hgap = margin/2;
		var tgap = G13.offset * 2;
		var sW = G13.params.width;
		var sH = G13.params.height;

		var baseW = parseInt((sW - margin * 2 - hgap * 4) / 5);
		var baseH = parseInt((sH - margin * 2 - tgap) / 5);
		
		var w = baseW;
		var h = parseInt(cardH * baseW / cardW);

		if(h > baseH){
			w = parseInt(baseW * baseH / h);
			h = baseH;
		}

		G13.params.cardWidth = w;
		G13.params.cardHeight = h;
	};

	G13.displayCardsDuringCompetetion = function(mine, oposite, turn){
		G13.allowTouch = false;
		G13.hideAllCardImgs();
		if(turn == 0)
			G13.evalSizeOfCardsForCompetetion();

		if(typeof(turn) == 'undefined')
			turn = 0;
		var context = G13.params.ctx;
		context.clearRect(0, 0, G13.params.width, G13.params.height);
		var margin = G13.margin;
		var hgap = -1 * G13.params.cardWidth/2;
		var tgap = -1 * G13.params.cardHeight/4;

		G13.allowTouch = false;
		//draw mine		
		var starttop_me_btm = G13.params.height - margin - G13.params.cardHeight;
		var startx_me = margin;
		var starttop_me_mid = starttop_me_btm - G13.params.cardHeight - tgap;
		var starttop_me_top = starttop_me_mid - G13.params.cardHeight - tgap;

		var starttop_op_btm = margin;
		var startx_op = G13.params.width - margin - G13.params.cardWidth;
		var starttop_op_mid = starttop_op_btm + G13.params.cardHeight + tgap;
		var starttop_op_top = starttop_op_mid + G13.params.cardHeight + tgap;		

		if(turn < 3){
			G13.displayCardInALine(false, mine['bottom'], startx_me, starttop_me_btm, hgap, true);
			G13.displayCardInALine(false, oposite['bottom'], startx_op, starttop_op_btm, hgap, true, true);
			if(turn == 2){
				G13.displayCardInALine(false, mine['top'], startx_me, starttop_me_top, hgap);
				G13.displayCardInALine(false, mine['middle'], startx_me, starttop_me_mid, hgap);

				G13.displayCardInALine(false, oposite['top'], startx_op, starttop_op_top, hgap, false, true);
				G13.displayCardInALine(false, oposite['middle'], startx_op, starttop_op_mid, hgap, false, true);
			}else{
				G13.displayCardInALine(false, mine['middle'], startx_me, starttop_me_mid, hgap, true);
				G13.displayCardInALine(false, mine['top'], startx_me, starttop_me_top, hgap, turn<1);

				G13.displayCardInALine(false, oposite['middle'], startx_op, starttop_op_mid, hgap, true, true);
				G13.displayCardInALine(false, oposite['top'], startx_op, starttop_op_top, hgap, turn<1, true);
			}
		}else{
			G13.displayCardInALine(false, mine['top'], startx_me, starttop_me_top, hgap);
			G13.displayCardInALine(false, mine['middle'], startx_me, starttop_me_mid, hgap);
			G13.displayCardInALine(false, mine['bottom'], startx_me, starttop_me_btm, hgap);

			G13.displayCardInALine(false, oposite['top'], startx_op, starttop_op_top, hgap, false, true);
			G13.displayCardInALine(false, oposite['middle'], startx_op, starttop_op_mid, hgap, false, true);
			G13.displayCardInALine(false, oposite['bottom'], startx_op, starttop_op_btm, hgap, false, true);
		}

		//draw extra
		hgap = parseInt(-1 * G13.params.cardWidth * 5/6);
		startx_me = G13.params.width - margin - parseInt(G13.params.cardWidth * 4 / 3);
		startx_op = margin;
		var starttop_me = starttop_me_btm;
		var starttop_op = starttop_op_btm;

		G13.displayCardInALine(false, mine['extra'], startx_me, starttop_me, hgap, true);
		G13.displayCardInALine(false, oposite['extra'], startx_op, starttop_op, hgap, true);

	};

	G13.getStrategy = function(){
		G13.params.strategyFetched = false;
		G13.params.curStrategyData = false;
		$.get('/game/thirteen/getStrategy', function(data){
			G13.params.curStrategyData = eval("("+data+")");
			G13.params.strategyFetched = true;
			if(G13.params.loadCardsForDisplay){
				G13.displayStrategy();
			}
		});		
	};


	G13.optimizeStrategy = function(strategies){

	};

	G13.submitCards = function(){
		$.post('/game/thirteen/submitCards', {'_token':$('meta[name="csrf-token"]').attr('content')}, function(data){

		});
	};

	G13.checkAllPrepared = function(){
		$.get('/game/thirteen/checkAllPrepared/' + G13.params.roomid, function(data){
			data = eval(data);
			if(data[1] == 'true'){
				G13.compareCard();
			}
		});
	};

	G13.compareCard = function(){
		$.get('/game/thirteen/getGameData/' + G13.params.roomid, function(data){
			G13.loadCompareCard(data);
		});
	};


	G13.loadCompareCard = function(data){

	};

	G13.readyForNextTurn = function(){
		$.post('/game/thirteen/readyForNextTurn', {'_token':$('meta[name="csrf-token"]').attr('content'),
			'statusid':G13.params.statusid}, function(data){

		});
	};

	G13.checkNextTurn = function(){
		$.get('/game/thirteen/checkNextTurn/' + G13.params.roomid, function(data){

		});
	};

	G13.leaveRoom = function(){
		$.get('/game/thirteen/leaveRoom/' + G13.params.roomid, function(data){
			if(data=='false'){
				var info = '游戏已经开始，您不能离开房间！';
			}
		});
	};

	G13.printStatus = function(win, collectedData){
		if(win){
			
		}
	};

	G13.init();
};

var YM_GAME_13 = false;

$(function(){
	YM_GAME_13 = new ym_game_thirteen();
});

