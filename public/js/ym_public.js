var ym_common = function(){
	var CM = this;
	CM.TIME_THRESHOLD = [
		{
			threshold:60,
			lessFunction:function(realtimeGap){
				return '刚刚';
			}
		},
		{
			threshold:3600,
			lessFunction:function(realtimeGap){
				return parseInt(realtimeGap/60) + '分钟前';
			}
		},
		{
			threshold:3600*24,
			lessFunction:function(realtimeGap){
				return parseInt(realtimeGap/3600) + '小时前';
			}
		},
		{
			threshold:3600*24*7,
			lessFunction:function(realtimeGap){
				return parseInt(realtimeGap/24/3600) + '天前';
			}
		},
		{
			threshold:3600*24*30,
			lessFunction:function(realtimeGap){
				return parseInt(realtimeGap/24/3600/7) + '周前';
			}
		},
		{
			threshold:3600*24*365,
			lessFunction:function(realtimeGap){
				return parseInt(realtimeGap/24/3600/30) + '月前';
			},
			moreFunction:function(realtimeGap){
				return parseInt(realtimeGap/24/3600/365) + '年前';
			}
		}
	];

	CM.attachTabSwitchEvent = function(objName){
		var objs = $('[name='+objName+']');
		objs.each(function(index){
			var activeClass = 'am-active';
			var o = $(this);
			if(!o.hasClass(activeClass)){
				$('#' + o.attr('ref')).hide();
				o.click(function(){
					objs.each(function(idx){
						var o2 = $(this);
						if(o2.hasClass(activeClass)){
							o2.removeClass(activeClass);
							$('#' + o2.attr('ref')).hide();
						}
					});
					o.addClass(activeClass);
					var tabo = $('#' + o.attr('ref'));
					tabo.show();
					CM.attachTabSwitchEvent(objName);
				});
			}
		});

	};

	CM.evalTimeToNow = function(thetime){
		thetime = thetime.replace(/-/g,'/');
		var oldtime = Date.parse(new Date(thetime))/1000;
		var nowtime = new Date().getTime()/1000;
		var realtimeGap = nowtime - oldtime;
		var threshold = CM.TIME_THRESHOLD;
		for(var i=0; i<CM.TIME_THRESHOLD.length; i++){
			if(realtimeGap < CM.TIME_THRESHOLD[i].threshold){
				return CM.TIME_THRESHOLD[i].lessFunction(realtimeGap);
			}
		}
		return CM.TIME_THRESHOLD[CM.TIME_THRESHOLD.length-1].moreFunction(realtimeGap);

	};

	CM.applyTimeToObjects = function(){
		$('[name=_time]').each(function(){
			var thetime = $(this).attr('thetime');
			$(this).html(CM.evalTimeToNow(thetime));
		});
	};

	//deprecated - use listload.js instead
	CM.loadMoreContent = function(theurl, objId, fn){
		var obj = $('#' + objId);
		$.get(theurl,function(data){
	         obj.html(data);
	         if(typeof(fn)=='function'){
	         	fn();
	         }
	     });
	};

	CM.postLike = function(resourceName, resourceId, eleId){
		var className = "am-icon-heart ym-c-red ym-ft-15 am-fr";
		if($('#' + eleId).attr('class')==className){
			return;
		}
		$.post('/common/like', {'_token':$('meta[name="csrf-token"]').attr('content'), 
			'resource':resourceName, 'resourceId':resourceId}, 
			function(data){
				if(data=='true'){
					$('#' + eleId).attr('class', 'am-icon-heart ym-ft-15 am-fr ym-icon-liked');
				}
			});
	};
	CM.postLikeForList = function(resourceName, resourceId, eleId){
		var className = "ym-icon-liked";
		var ele = $('#' + eleId);
			if(ele.hasClass(className)){
				return;
			}
			$.post('/common/like', {'_token':$('meta[name="csrf-token"]').attr('content'),
				'resource':resourceName, 'resourceId':resourceId},
				function(data){
					if(data=='true'){
						ele.removeClass(className+'-o');
						ele.addClass(className);
					}
				});

	};
	CM.postUserScore = function(resourceName, resourceId, score, scoreId, eleName){
		if(!scoreId){
			scoreId = 0;
		}

		$.post('/common/score/user', {'_token':$('meta[name="csrf-token"]').attr('content'), 'resource':resourceName, 'resourceId':resourceId, 
					'score':score, 'scoreId':scoreId},
			function(data){
				if(data.code==1){
					$('i[name='+eleName+']').each(function(index){
						if(index < score){
							$(this).attr('class', 'am-icon-star ym-c-red ym-ft-15');
						}else{
							$(this).attr('class', 'am-icon-star ym-c-grey ym-ft-15');
						}
					});
					if(resourceName=='ip'){
						var html = '';
						for(var i=0; i<5; i++){
							if(i < data.parm['grade']){
								html += "<i class='am-icon-star ym-c-red ym-ft-15' style='margin-right: 4.5px;'></i>";
							}else{
								html += "<i class='am-icon-star ym-c-grey ym-ft-15' style='margin-right:4.5px;'></i>";
							}
						}
						$('#infoArea .ym-ip-score').html(html);
					}
				}
			}
		);
	};
	CM.displayDiscussionFullContent = function(discussionId){
		$('#_discussion_' + discussionId +"_action").css('display', 'none');
		$('#_discussion_' + discussionId +"_short").css('display', 'none');
		$('#_discussion_' + discussionId +"_full").css('display', 'block');
	};
	CM.displayDiscussionAllReplies = function(discussionId){
		$('#_discussion_reply_' + discussionId +"_mark").css('display', 'none');
		$('#_discussion_reply_' + discussionId ).css('display', 'block');
	};
	CM.displayForOneDiscuss = function(discussionId){

	};

	CM.initDiscussion = function(){
		if(typeof($hasDiscussion)=='undefined'){
			return;
		}
		if(!$hasDiscussion)
			return;

	};

	//Below defines the publish panel
	CM.publishStarted = false;
	CM.publishOptions = {
		button:'#publishButton',
		functionPane: '#_publishFunctionPane',
		radius: '10px'
	};
	CM.initPublish = function() {
		if(CM.publishStarted){
			return;
		}
		if(typeof($PUBLISH_FINE)=='undefined'){
			CM.publishStarted = true;
			return;
		}
		$(CM.publishOptions.functionPane).on(
			'open.modal.amui',
			function(){
				$(CM.publishOptions.button).hide();
			}
		);
		$(CM.publishOptions.functionPane).on(
			'close.modal.amui',
			function(){
				$(CM.publishOptions.button).show();
			}
		)
	};
	//我是否看过状态
	CM.postReading = function(resourceId, eleId){
		var className = "ym-btn-check-active";
		var read = $('#'+eleId).attr('data-read');
		$.post('/common/read', {'_token':$('meta[name="csrf-token"]').attr('content'),
			'resourceId':resourceId, 'read':read},
			function(data){
				if(data=='true'){
					$('#' + eleId).addClass(className).removeClass('ym-btn-check').siblings('.am-btn').removeClass('ym-btn-check-active').addClass('ym-btn-check');
				}
			});
	};
}

var $YM_COMMON = new ym_common();
$YM_COMMON.initPublish();
$YM_COMMON.initDiscussion();
