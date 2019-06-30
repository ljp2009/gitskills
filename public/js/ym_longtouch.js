/**
 * 手机端长按事件
 * @returns
 */

function longTouch(){
	
	this._attrs = {
		"parentContainer":"#listDataDiv",
		"container":".am-icon-trash-o",
		"deleteContainer":".ym_listitem",
		"deleteInfo":"您确定要删除该作品吗？",
		"deleteDom":'<button class="am-btn am-btn-danger delete-obj" type="button" style="position:absolute;left:50%;top:50%;margin-left:-33px;margin-top:-18.5px;">删除</button>',
		"deleteRoute":"",
		"token":"",
		
	};
	this.bind =  function (attrArr)
	{
		for(var key in attrArr) 
		{
			this._attrs[key] = attrArr[key];
		}
//		$('body').append('<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm-rule"><div class="am-modal-dialog"><div class="am-modal-hd">确认删除</div>'+
//			    '<div class="am-modal-bd" id="my-confirm-rule-content">你确定要删除吗？</div>'+
//			    '<div class="am-modal-footer"><span class="am-modal-btn" data-am-modal-cancel>取消</span><span class="am-modal-btn" data-am-modal-confirm>确定</span></div>'+
//			  '</div></div>');
	};
	var obj = this;
	
	this.delete_obj = function(){
		var container = this._attrs['container'];
		var parentContainer = this._attrs['parentContainer'];
		var timeout;
		$(parentContainer).on('click',container,function(e) {
			var id = $(this).attr('data-id')*1;
			var $this = $(this);
//		    timeout = setTimeout(function() { 
//		    	$this.css({'position':'relative','border':'1px solid red'});
//		    	$this.append(obj._attrs['deleteDom']);
//		    }, 2000); 
			obj.getDeleteConfirm(id,obj._attrs['deleteInfo']);
		});
		  
//		$(parentContainer).on('mouseup',container,function() { 
//		    clearTimeout(timeout); 
//		    $(this).css({'position':'','border':''});
//	    	$(this).remove($(this).find('.delete-obj')); 
//		}); 
		 
        
	};
	/**
	 * 
	 */
//	this.delete_obj = function(){
//		var container = this._attrs['container'];
//		var obj = $(container);
//		obj.css({'position':'relative','border':'1px solid red'});
//		obj.append(this._attrs['deleteDom']);
//		
//	};
	this.getDeleteConfirm = function(id,msg)
	{
		var container = obj._attrs['container'];
		var $this = $(this);
        $('#my-confirm-delete-content').html(msg);
        $('#my-confirm-delete').modal({'onConfirm':function(c){
        	$.ajax({
    			type:'POST',
    			url:obj._attrs['deleteRoute'],
    			data:{id:id,_token:obj._attrs['token']},
    			dataType:'json',
    			success:function(data){
    				if(data.code == 1){
    					location.reload();
    				}
    			}
    		});
        }});
	};
}