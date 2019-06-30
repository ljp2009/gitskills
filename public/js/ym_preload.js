var ym_preload = function(){
	var loaded = false;
	var duringload = false;
	var PRE = this;

	PRE.preloadId = "__preload";
	PRE.progressDivId = "__progress";
	PRE.progressImgId = "__progressImg";
	PRE.progressBarId = "__progressBar";
	PRE.progressBarInnerId = "__progressBarInner";

	PRE.__progressImg = '';
	PRE.curProgress = 0;

	PRE.preProgress = 0.0;

	PRE.resources = {};

	PRE.isUIReady = false;

	PRE.pendingFunc = false;

	PRE.loadedObj = {};

	PRE.__initProgressField_sub = function(width, progressDiv){

			progressDiv.css('width', width + "px");

			var progressBar = $('#' + PRE.progressBarId);

			progressBar.css('width', width + "px");

			progressDiv.css('display', 'block');

			var scrwidth = $(document.body).width();
			var scrheight = $(document.body).height();

			var left = parseInt((scrwidth - width)/2);
			var top = parseInt((scrheight - progressDiv.height())/2);

			progressDiv.css({'left':left + 'px', 'top':top + 'px'});

			progressDiv.css('display', 'block');

			PRE.isUIReady = true;

			if(PRE.pendingFunc!==false){
				PRE.pendingFunc();
			}
	};

	PRE.__initProgressField = function(){
		var progressDiv = $('#' + PRE.progressDivId);
		progressDiv.css('display', 'none');

		var inhtml = '';
		if(PRE.__progressImg === ''){
			inhtml = "<div id='" + PRE.progressBarId +
				"' class='ym_preload_progressbar'><div id='"+ PRE.progressBarInnerId+"' class='ym_preload_progressbar_inner'></div></div>";
			progressDiv.append(inhtml);

			PRE.__initProgressField_sub(100, progressDiv);
		}else{
			inhtml = "<img id='" + PRE.progressImgId+"' src=''/><div id='" + PRE.progressBarId +
				"' class='ym_preload_progressbar'><div id='"+ PRE.progressBarInnerId+"' class='ym_preload_progressbar_inner'></div></div>";
			progressDiv.append(inhtml);

			var theProcessImg = $('#' + PRE.progressImgId)[0];
			theProcessImg.onload = function(){
				var imgwidth = this.width;
				PRE.__initProgressField_sub(imgwidth, progressDiv);

			};
			theProcessImg.src = PRE.__progressImg;
		}
	};

	PRE.setPreProgress = function(prog){
		if(!PRE.isUIReady){
			PRE.pendingFunc = function(){
				PRE.setPreProgress(prog);
			};
			return;
		}
		if(prog <= PRE.preProgress){
			return;
		}
		PRE.preProgress = prog;

		var progperc = parseInt(prog * 100);
		if(prog < 1){
			$('#' + PRE.progressBarInnerId).animate({width:progperc + '%'}, 10);
		}else{
			$('#' + PRE.progressBarInnerId).animate({width:progperc + '%'}, 10, function(){
				PRE.__completeLoad();
			});
		}
		
	};

	PRE.__setProgress = function(prog){
		if(prog <= PRE.curProgress)
			return;
		PRE.curProgress = prog;
		var progperc = (prog===1?100:parseInt((prog * (1 - PRE.preProgress) + PRE.preProgress) * 100 ));
		// $('#' + PRE.progressBarInnerId).css({width:progperc + '%'});
		$('#' + PRE.progressBarInnerId).animate({width:progperc + '%'}, 10);
	};

	PRE.setResources = function(res){
		var html = "";
		PRE.res = res;
		PRE.count = 0;
		var ct = 0;
		for(var key in res){
			ct ++;
			res[key].name = key;
			html += PRE.__evalHTMLForOneRes(res[key]);
		}

		PRE.totalResources = ct;

		var func = function(){
			$('#' + PRE.preloadId).html(html);

			for(var key in res){
				var obj = PRE.getResourceObj(key);
				PRE.resources[key] = obj;
				(obj.children())[0].onload = function(){
					PRE.__onloadone();
				};
			}

			for(var key in res){
				(PRE.resources[key].children())[0].src = res[key].path; 
			}			
		};

		if(PRE.isUIReady){
			func();
		}else{
			PRE.pendingFunc = func;
		}

	};
	/**
	  * Resource format : name, type (audio | video | img), path 
	  */
	PRE.startLoad = function(data, afterfunc) {

		if(typeof(data.res)!=='undefined'){
			for(var k in data.res){
				PRE.setResources(data.res);
				break;
			}
		}

		if( typeof(data.img)!=='undefined' ){
			PRE.__progressImg = data.img;
		}

		PRE.afterfunc = afterfunc;

		if(loaded){
			PRE.__completeLoad();
			return;
		}
		if(duringload){
			return;
		}
		duringload = true;

		$(document.body).append('<div id="'+PRE.preloadId+'"></div><div id="'+PRE.progressDivId+'" style="text-align:center; position:fixed; z-index:99"></div>');

		PRE.__initProgressField();

	};

	PRE.__evalHTMLForOneRes = function(res){
		var html = "";
		switch(res.type){
			case 'audio':
				html = "<div id='" +res.name+ "' style='position:fixed; z-index:100; display:none'><audio src='' preload='auto' /></div>"; break;
			case 'video':
				html = "<div id='" +res.name+ "' style='position:fixed; z-index:100; display:none'><video src='' preload='meta' /></div>"; break;
			case 'img':
				html = "<div id='" +res.name+ "' style='position:fixed; z-index:100; display:none'><img src='' width=100% height=100%  /></div>"; break;
		}
		return html;
	};

	PRE.__onloadone = function(){
		PRE.count ++;
		
		if(PRE.count == PRE.totalResources){
			PRE.__setProgress(1);
			setTimeout(function(){PRE.__completeLoad();}, 150);
		}else{
			PRE.__setProgress(PRE.count/PRE.totalResources);
		}
	};

	PRE.__completeLoad = function(){		
		$('#' + PRE.progressDivId).css('display', 'none');

		if(typeof(PRE.count)!='undefined'&&PRE.count > 0){
			for(var k in PRE.res){
				PRE.res[k].obj = PRE.getResourceObj(k);	
			}
		}

		if(typeof(PRE.afterfunc)==='function'){
			PRE.afterfunc();
		}
	};

	PRE.getResourceObj = function(name){
		return $('#' + name);
	};

	PRE.getResources = function(){
		return PRE.res;
	};
};