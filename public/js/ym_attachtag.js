var _ym_attachTag = null;

function attachTagClick(btn,jsonurl,defaultValue) {
    if (_ym_attachTag == null)
        _ym_attachTag = new AttachTag();
    if(jsonurl){
    	_ym_attachTag.setting.defaultJsonUrl = jsonurl;
    }
    _ym_attachTag.click();
}

function AttachTag() {}

AttachTag.prototype.setting = {
    'tags': [],
    'cartoonattrs' :[],
    'gameattrs' :[],
    'storyattrs' :[],
    'cartoon':[],
    'story':[],
    'modalid': 'select-tag-modal',
    'container': 'tagContainer',
    'confirm':'my-confirm-tag',
    'tagsList':'tagsList',
    'defaultJsonUrl':'/json/iptags',
    'defaultValue':''
};
AttachTag.prototype.bind = function(sets) {
    for (x in sets) this.setting[x] = sets[x];
};
AttachTag.prototype.addExistTag = function() {
    var obj = this;
    var modalctrl = $("#" + obj.setting.modalid);
    var contentDiv = $("#" + obj.setting.modalid + "-content");
    var jsonurl = obj.setting.defaultJsonUrl;
    if (contentDiv.html() == "") {
        $.getJSON(jsonurl,function(data){
            obj.setting.tags = data;
            for(var i = 0;i<obj.setting.tags.length; i++)
           {
                var tag = obj.setting.tags[i];
                contentDiv.append('<label id ="tag_' + tag.key +
                    '_addBtn" class="am-btn am-btn-primary am-radius" style="margin:2px 3px">'
                    + tag.name + '</label>');
                var btn = $('#tag_' + tag.key + '_addBtn');
                btn.on('click', function() {
                    var id = this.id.split('_')[1];
                    obj.addNewTag(id);
                    var length = $('#tagContainer>label').length;
                	if(length == 5){
                		obj.alertInfo('最多选择5个标签');
                		modalctrl.modal('close');
                		return false;
                	}
                    $(this).attr('disabled', 'disabled');
                    modalctrl.modal('close');
                });
           }
            var arr = obj.setting.defaultValue.split(';');
            for(var i in arr){
                if(arr[i].length > 0){
                    $btn = $('#tag_' + arr[i] + '_addBtn');
                    $btn.attr('disabled', 'disabled');
                    obj.addNewTag(arr[i]);
                }
            }
        });
    }
}
AttachTag.prototype.click = function() {
    var obj = this;
    var modalctrl = $("#" + obj.setting.modalid);
    var contentDiv = $("#" + obj.setting.modalid + "-content");
    var jsonurl = obj.setting.defaultJsonUrl;
    var ipType = $('#formLogin').find('select[name="iptype"]').val();
    modalctrl.modal();
    var attr = [];
    if(ipType == 'cartoon'){
        attr = obj.setting.cartoonattrs;
    }else if(ipType == 'game'){
        attr = obj.setting.gameattrs;
    }else if(ipType == 'story'){
        attr = obj.setting.storyattrs;
    }else{
        attr = obj.setting.tags;
    }
    $('body').css('overflow','hidden');
    if (attr.length == 0) {
    	contentDiv.html('');
        $.getJSON(jsonurl,{depend:ipType},function(data){
            obj.setting.tags = data;
            if(ipType == 'cartoon'){
                obj.setting.cartoonattrs = data;
            }else if(ipType == 'game'){
                obj.setting.gameattrs = data;
            }else if(ipType == 'story'){
                obj.setting.storyattrs = data;
            }
            for(var i = 0;i<obj.setting.tags.length; i++)
           {
                var tag = obj.setting.tags[i];
                contentDiv.append('<label id ="tag_' + tag.key +
                        '_addBtn" class="am-btn am-btn-primary am-radius" style="margin:2px 3px">'
                        + tag.name + '</label>');
                var btn = $('#tag_' + tag.key + '_addBtn');
                btn.on('click', function() {
                	var length = $('#tagContainer>label').length;
                	if(length == 5){
                		modalctrl.modal('close');
                		obj.alertInfo('最多选择5个标签');
                		return false;
                	}
                    var id = this.id.split('_')[1];
                    obj.addNewTag(id);
                    $(this).attr('disabled', 'disabled');
                    modalctrl.modal('close');
                });
           }
        });
        $('body').css('overflow','');
    }else{
    	obj.setting.tags = attr;
    	if(contentDiv.html() == ''){
    		contentDiv.html('');
            for (var i in attr) {
            	var tag = attr[i];
                contentDiv.append('<label id ="tag_' + tag.key +
                        '_addBtn" class="am-btn am-btn-primary am-radius" style="margin:2px 3px">'
                        + tag.name + '</label>');
                var btn = $('#tag_' + tag.key + '_addBtn');
                btn.on('click', function() {
                    var id = this.id.split('_')[1];
                    var length = $('#tagContainer>label').length;
                	if(length == 5){
                		obj.alertInfo('最多选择5个标签');
                		modalctrl.modal('close');
                		return false;
                	}
                    obj.addNewTag(id);
                    $(this).attr('disabled', 'disabled');
                    modalctrl.modal('close');
                });
            }
    	}
    	$('body').css('overflow','');
    }
}
AttachTag.prototype.addNewTag = function(id) {
    var set = this.findSet(id);
    var str = '<label  class="am-btn am-btn-warning am-round" style = "font-size:1.1rem;padding:0.2rem 0.5rem; margin:0.5rem 0.2rem">' + set.name +
    '<input type="hidden" id="tag_' + id + '" name="_tag_' + id + '" value = "'+set.name+'" />' +
    '</label>';
    var obj = this;
    $("#"+this.setting.container).append(str);
    $("#tag_"+id).parent().on('click',  obj.getDeleteConfirm(id));

    var v = $("#"+this.setting.tagsList).val();
    $("#"+this.setting.tagsList).val(v+";"+id);
}

AttachTag.prototype.findSet = function(id) {
    for (var i = 0; i < this.setting.tags.length; i++) {
        if (this.setting.tags[i].key == id)
            return this.setting.tags[i];
    }
    return null;
}
AttachTag.prototype.getDeleteConfirm = function(id)
{
    var obj = this;
    return function(){
        $('#'+obj.setting.confirm+"-content").html('你确定要删除这个标签吗？');
        $('#'+obj.setting.confirm+"-content").attr('tagId',id);
        $('#'+obj.setting.confirm).modal({'onConfirm':function(c){
            var id = $('#'+obj.setting.confirm+"-content").attr('tagId');
            $("#tag_"+id).parent().remove();
            $("#tag_" + id + "_addBtn").removeAttr('disabled');
            var v = $("#"+obj.setting.tagsList).val();
            $("#"+obj.setting.tagsList).val(v.replace(";"+id, ""));
        }});
    };
}
AttachTag.prototype.alertInfo = function($msg){
	var $modal = $('#my-alert');
    var $target = $modal;
    if (($target).hasClass('js-modal-open')) {
      $modal.modal();
    } else if (($target).hasClass('js-modal-close')) {
      $modal.modal('close');
    } else {
      $modal.modal('toggle');
    }
	  $('#my-alert .am-modal-bd').text($msg);
}
