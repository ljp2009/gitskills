function lasyLoad(control)
{
	this._obj = control;
	this._bindArr = {};
	this.bindControls= function(objs,attrName)
	{
		$(objs).scrollspy({
            animation: 'fade',
            delay: 0,
            repeat:false
          })
		.on('inview.scrollspy.amui', this.loadFunction(attrName));
	};
	this.bindManualControl = function(btn, targetAttr)
	{
		if(typeof(targetAttr) == "undefined") targetAttr = 'href';
		var obj = this;
		$(btn).on('click',function(){
			var target = $(this).attr(targetAttr);
			var isLoad = $(target).attr("isload");
			if(isLoad == "true") return;
			$.get($(target).attr('viewpath'),function(data){
	           $(target).html(data);
	           $(target).attr("isload", "true");
	        });
		});
		return $(btn);
	};

	this.loadFunction=function(attrName){
		return function(){
			var obj = $(this);
			var viewPath = obj.attr(attrName);
	        if(viewPath == "") return;

	        obj.attr(attrName,'');
	        $.get(viewPath,function(data){
	            obj.html(data);
	            if(typeof(obj.attr("fn"))!="undefined"){
	            	if(obj.attr("fn").length>0){
	            		eval(obj.attr("fn"));
	            	}
	            }
	        });
		}
	};
	this.load = function(){
		var obj = $(this._obj);
    if(obj.attr('viewpath').length == 0){
      return;
    }
		$.get(obj.attr('viewpath'),function(data){
           obj.html(data);
            if(typeof(obj.attr("fn"))!="undefined"){
            	if(obj.attr("fn").length>0){
            		eval(obj.attr("fn"));
            	}
            }
        });
	}
}
