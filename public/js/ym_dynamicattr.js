var _ym_dynamicAttr = null;

function dynamicAttrbtnClick(btn) {
    if (_ym_dynamicAttr == null)
        _ym_dynamicAttr = new DynamicAttr();
    _ym_dynamicAttr.click();
}

function DynamicAttr() {}

DynamicAttr.prototype.setting = {
    'attrs': [{
        'key': '0001',
        'name': '作者',
        'type': 'label'
    }, {
        'key': '0002',
        'name': '开始时间',
        'type': 'label'
    }, {
        'key': '0003',
        'name': '结束时间',
        'type': 'label'
    }, {
        'key': '0004',
        'name': '字数',
        'type': 'number'
    }],
    'cartoonattrs' :[],
    'gameattrs' :[],
    'storyattrs' :[],
    'cartoon':[{
        'code':'10005',
        'value':'更新中'
    },{
        'code':'10005',
        'value':'已完结'
    }],
    'story':[{
        'code':'10005',
        'value':'连载中'
    },{
        'code':'10005',
        'value':'已完结'
    }],
    'modalid': 'select-attr-modal',
    'container': 'dynamicAttrContiner',
    'confirm':'my-confirm-attr',
    'attrsList':'attrsList'
};
DynamicAttr.prototype.bind = function(sets) {
    for (x in sets) this.setting[x] = sets[x];
};

DynamicAttr.prototype.click = function() {
    var obj = this;
    var modalctrl = $("#" + obj.setting.modalid);
    var contentDiv = $("#" + obj.setting.modalid + "-content");
    var ipType = $('#formLogin').find('select[name="iptype"]').val();
    var attr;
    modalctrl.modal();
    if(ipType == 'cartoon'){
        attr = obj.setting.cartoonattrs;
    }else if(ipType == 'game'){
        attr = obj.setting.gameattrs;
    }else if(ipType == 'story'){
        attr = obj.setting.storyattrs;
    }else if(ipType == 'light'){
        attr = obj.setting.storyattrs;
    }
    if (attr.length == 0) {
        contentDiv.html('');
    	$.getJSON('/json/ipattrs',{'depend':(ipType=='light'?'story':ipType)},function(data){
    		obj.setting.attrs = data;
            if(ipType == 'cartoon'){
                obj.setting.cartoonattrs = data;
            }else if(ipType == 'game'){
                obj.setting.gameattrs = data;
            }else if(ipType == 'story'){
                obj.setting.storyattrs = data;
            }else if(ipType == 'light'){
                obj.setting.storyattrs = data;
            }
    		for (var i in data) {
	            var attrSet = data[i];
	            contentDiv.append('<button id ="attr_' + attrSet.key +
	                '_addBtn" class="am-btn am-btn-primary am-radius" attrType="' 
	                + attrSet.type + '" style="margin:2px 3px">' + attrSet.name + '</button>');
	            var btn = $('#attr_' + attrSet.key + '_addBtn');
	            btn.on('click', function() {
	                var id = this.id.split('_')[1];
	                obj.addNewAttr(id, $(this).attr('attrType'));
	                obj.addDelete(id);
	                $(this).attr('disabled', 'disabled');
	                modalctrl.modal('close');
	            });
        	}
    	});
    }else{
    	obj.setting.attrs = attr;
    	if(contentDiv.html() == ''){
	        contentDiv.html('');
	        for (var i in attr) {
	            var attrSet = attr[i];
	            contentDiv.append('<button id ="attr_' + attrSet.key +
	                '_addBtn" class="am-btn am-btn-primary am-radius" attrType="' 
	                + attrSet.type + '" style="margin:2px 3px">' + attrSet.name + '</button>');
	            var btn = $('#attr_' + attrSet.key + '_addBtn');
	            btn.on('click', function() {
	                var id = this.id.split('_')[1];
	                obj.addNewAttr(id, $(this).attr('attrType'));
	                obj.addDelete(id);
	                $(this).attr('disabled', 'disabled');
	                modalctrl.modal('close');
	            });
	        }
    	}
    }
}
DynamicAttr.prototype.addNewAttr = function(id, type) {
    switch (type) {
        case "label":
            this.addLabelAttr(id);
            break;
        case "number":
            this.addNumberAttr(id);
            break;
        case "date":
            this.addDateAttr(id);
            break;
        case "enum":
            this.addEnumAttr(id);
            break;
        default:
            this.addLabelAttr(id);
            break;
    }
    var v = $("#"+this.setting.attrsList).val();
    $("#"+this.setting.attrsList).val(v+";"+id);
}
DynamicAttr.prototype.addLabelAttr = function(id) {
    var set = this.findSet(id);
    var str = '<div class="am-form-group">' +
        '<label for="attr_' + id + '">' + set.name + '</label>' +
        '<input type="text" id="attr_' + id + '" name="_attr_' + id + '" class="am-form-field ym-publish-field" />' +
        '</div>';
    $("#"+this.setting.container).append(str);
}
DynamicAttr.prototype.addNumberAttr = function(id) {
    var set = this.findSet(id);
    var str = '<div class="am-form-group">' +
        '<label for="attr_' + id + '">' + set.name + '</label>' + '<input type="number" id="attr_' + id + '" name="_attr_' + id + '" class="am-form-field ym-publish-field" />' +
        '</div>';
    $("#"+this.setting.container).append(str);
}
DynamicAttr.prototype.addDateAttr = function(id) {
    var set = this.findSet(id);
    var str = '<div class="am-form-group">' +
        '<label for="attr_' + id + '">' + set.name + '</label>' + '<input type="date" id="attr_' + id + '" name="_attr_' + id + '" class="am-form-field ym-publish-field" />' +
        '</div>';
    $("#"+this.setting.container).append(str);
}
DynamicAttr.prototype.addEnumAttr = function(id) {
    var set = this.findSet(id);
    var ipType = $('#formLogin').find('select[name="iptype"]').val();
    var select = '<select class="am-form-field ym-publish-field" id="attr_' + id + '" name="_attr_' + id + '_status">';
    for(var i in this.setting[ipType]){
        select += '<option value="' + this.setting[ipType][i]["value"] + '">' + this.setting[ipType][i]["value"] + '</option>'
    }
    select += '</select>';
    var str = '<div class="am-form-group">' +
        '<label for="attr_' + id + '">' + set.name + '</label>' + select +
        '</div>';
    $("#"+this.setting.container).append(str);
}
DynamicAttr.prototype.findSet = function(id) {
    for (var i = 0; i < this.setting.attrs.length; i++) {
        if (this.setting.attrs[i].key == id)
            return this.setting.attrs[i];
    }
    return null;
}
DynamicAttr.prototype.addDelete = function(id) {
    var str = '<label id="attr_' + id + '_del" class="am-btn am-btn-danger am-round am-fr" style = "font-size:0.9rem;padding:0.2rem 0.5rem">移除</label>';
    var container = $("#attr_" + id).parent();
    container.prepend(str);
    var obj = this;
    $('#attr_' + id + "_del").on('click', obj.getDeleteConfirm(id));
}
DynamicAttr.prototype.getDeleteConfirm = function(id)
{
    var obj = this;
    return function(){
        $('#'+obj.setting.confirm+"-content").html('你确定要删除这个属性吗？');
        $('#'+obj.setting.confirm+"-content").attr('attrId',id);
        $('#'+obj.setting.confirm).modal({'onConfirm':function(c){
            var id = $('#'+obj.setting.confirm+"-content").attr('attrId');
            $('#attr_' + id + "_del").parent().remove();
            $("#attr_" + id + "_addBtn").removeAttr('disabled');
		    var v = $("#"+obj.setting.attrsList).val();
		    $("#"+obj.setting.attrsList).val(v.replace(";"+id, ""));
        }});
    };
}
