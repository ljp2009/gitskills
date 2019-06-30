function listLoad() {

	this._attrs = {
		"container":"#listDataDiv",//列表容器
		"controlBtn":"#listControlBtn", //加载按钮
		"loadingBtn":"#listLoadingBtn",//加载loading
		"noneItem":"#noneItem",//空数据时显示的控件
		"type":null,//列表类别
		"parentId":null,//父id
		"loadSize":4,//每次追加的列表数据量
		"maxSize":16,//每页最大追加数据量
		"listName":"default",//列表名称
		"itemFeature":".ym_listitem",//单条数据标记，根据此判断载入了多少数据
		"pageIndex":0,//页码
		"orderField":null,//排序字段（废弃）
		"searchField":null,//查询字段（废弃）
		"extraFns":[]//每次载入后执行的操作
	};
	this._status = {
		"url" :"",
		"firstIndex":0,
		"loadedCount":0,
		"lastIndex":4
	};
	this.cancel = false;
	//自动加载能动性
	this.autoLoadEnable = true;
  this.ctrlLock = false;
	var obj = this;

	this.bind =  function (attrArr) {
		for(var key in attrArr) {
			obj._attrs[key] = attrArr[key];
		}
		obj._status["url"] = "/"+obj._attrs["type"]+"/list/"+obj._attrs["listName"]+"/{from}-{to}/";
		if(obj._attrs["parentId"] != null && obj._attrs["parentId"] != "") {
			this._status["url"] += this._attrs["parentId"];
		}
		if(obj._attrs["orderField"] != null && obj._attrs["orderField"] != "") {
			this._status["url"] += this._attrs["orderField"];
		}
		if(obj._attrs["searchField"] != null && obj._attrs["searchField"] != "") {
			this._status["url"] += "/" + this._attrs["searchField"];
		}
		obj._status["firstIndex"] = obj._attrs["pageIndex"] * obj._attrs["maxSize"];
		obj._controlBtn = $(this._attrs["controlBtn"]);
		obj._loadingBtn = $(this._attrs["loadingBtn"]);
		obj._container = $(this._attrs["container"]);
		if(obj._controlBtn != null){
      obj._controlBtn.on('click',function (){
          obj.loadEvent(false);
        }
      );
    }
	}

	//响应加载数据事件
	this.loadEvent = function(isBegin) {
    if(obj.ctrlLock) return;
    obj.ctrlLock = true;
		var from = obj._status["firstIndex"] + obj._status["loadedCount"];
		if(obj._status["loadedCount"] >= obj._attrs["maxSize"]) {
			window.location = obj._status["url"].replace("{from}-{to}",obj._attrs["pageIndex"]+1).replace("listdata","listpage");
		}
		else {
			obj._loadingBtn.show();
			obj._controlBtn.text("加载中");
			obj.loadData(from, isBegin);
		}
	}

	this.loadData = function(from, isBegin) {
		var url = obj._status['url']
					.replace('{from}',from)
					.replace('{to}', from + obj._attrs["loadSize"]-1);
    url += ('/?'+Date());
		 $.get(url, function(data){
              //处理有无数据展示区域
              if(typeof(isBegin) != 'undefined' && isBegin){
                if($.trim(data) == '') {
                    $(obj._attrs['noneItem']).show();
                }else{
                    $(obj._attrs['noneItem']).hide();
                }
              }

	            $(obj._attrs['container']).append(data);
	            //此处更新nowIndex 总的记录数
	            var totalCount =$(obj._attrs["itemFeature"]).length;
	            if(totalCount < obj._status["loadedCount"] + obj._attrs["loadSize"]) {
                obj._status["loadedCount"] = totalCount;
	            	//所有数据都已经加载完成了
	            	// obj._controlBtn.html("没有更多记录");
                	obj._controlBtn.hide();
                	obj._loadingBtn.hide();
	            	//禁止自动加载
	            	obj.autoLoadEnable = false;
	            }
	            else {
	            	obj._status["loadedCount"] = totalCount;
	            	//加载到了页面的最大值
	            	if(obj._status["loadedCount"] >= obj._attrs["maxSize"]) {
		         		obj._controlBtn.text("下一页");
		         		obj._loadingBtn.hide();
		         	  	//禁止自动加载
	            		obj.autoLoadEnable = false;
	                }
	                else {
	                	obj._loadingBtn.hide();
			            obj._controlBtn.text("加载更多");
			            //允许自动加载
		            	obj.autoLoadEnable = true;
			        }
              obj.ctrlLock = false;

		        }
	            if(obj._attrs['extraFns'].length>0){
	            	for(var key in obj._attrs['extraFns']){
	            		var fn = obj._attrs['extraFns'][key];
	            		if(typeof(fn) == 'function'){
	            			fn.call();
	            		}
	            	}
	            }
	        });
	}
	this.begin =function() {
		if(this.cancel) return;
    obj.loadEvent(true);
	}
  $(window).scroll(function () {
    //禁止自动加载
    if(!obj.autoLoadEnable) return;
    //对象最顶端和窗口中可见内容的最顶端之间的距离
    var scrollTop = $(this).scrollTop();
    //浏览器当前窗口文档的高度 
    var scrollHeight = $(document).height();
    //浏览器当前窗口可视区域高度
    var windowHeight = $(this).height();
    if (scrollTop + windowHeight >= scrollHeight) {
      //自动加载数据
      obj.loadEvent(false);
    }
  });

}

//列表方法
jQuery.ymListItem = {};
jQuery.ymListItem._listBind = {'deleteItem':function(){}, 'editItem':function(){}};
jQuery.ymListItem.getItem = function(id){
    return $('#ym_detail_list_item_'+id);
};
jQuery.ymListItem.alert = function(text){
  var $modal = $('#my-alert');
  $modal.find('div.am-modal-bd').html(text);
  $modal.modal('open');
};
jQuery.ymListItem.editListItem = function(id){
  $.ymListItem._listBind.editItem(id);
};
jQuery.ymListItem.deleteListItem = function(objid){
  var $modal = $('#my-confirm-delete');
  $modal.modal({relatedTarget:objid, onConfirm:function(){
    $.ymListItem._listBind.deleteItem(this.relatedTarget, function(res,id){
      if(res){
        $.ymListItem.getItem(id).remove();
      }else{
        $.ymListItem.alert('无法删除这条记录。');
      }
    });
  }});
};
jQuery.ymListItem.likeListItem = function(resource , id, btn){
  $.ymFunc.switchLike(resource, id, function(res,resid,isLike){
    var $icon = $(btn).find('i');
    var $label = $(btn).find('span');
    if(isLike){
      $icon.removeClass('ymicon-heart-o');
      $icon.addClass('ymicon-heart');
      $label.text(parseInt($label.text())+1);
    }else{
      $icon.removeClass('ymicon-heart');
      $icon.addClass('ymicon-heart-o');
      $label.text(parseInt($label.text())-1);
    }
  });
};
jQuery.ymListItem.shareListItem = function(id, btn){
};
jQuery.ymListItem.alertListItem = function(id, btn){
};
jQuery.ymListItem.commentListItem = function(id, btn){
};
//列表方法的绑定操作
jQuery.ymListItem.bindListEdit = function(func){
  $.ymListItem._listBind.editItem = func;
};
jQuery.ymListItem.bindListDelete = function(func){
  $.ymListItem._listBind.deleteItem = func;
};

