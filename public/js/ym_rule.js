var _ym_rule = null;

function ruleClick(btn,jsonurl) {

    if (_ym_rule == null)
    	_ym_rule = new Rule();
    if(jsonurl){
    	_ym_rule.setting.defaultJsonUrl = jsonurl;
    	if(jsonurl == '/json/userskill'){
    		_ym_rule.setting.maxSkillLevel = '2';
    		_ym_rule.setting.defaultValidate = 'integer,numberLimit!1!2';
    		_ym_rule.setting.defaultErrormsg = '输入1到2之间的整数';
    	}
    }
    
    _ym_rule.click();
}

function Rule() {}

Rule.prototype.setting = {
    'tags': [],
    'modalid': 'select-rule-modal',
    'container': 'ruleContainer',
    'confirm':'my-confirm-rule',
    'tagsList':'rulesList',
    'defaultJsonUrl':'/json/taskRule',
    'defaultValue':'',
    'maxSkillLevel':'2',
    'defaultValidate':'integer,numberLimit!1!5',
    'defaultErrormsg':'输入1到5之间的整数',
    'skillShow':'together', //spread
    'skillLevelOption':['爱好','达人','专业','专家','大神']
};
Rule.prototype.bind = function(sets) {
    for (x in sets) this.setting[x] = sets[x];
};
Rule.prototype.addExistTag = function() {
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
                var type = tag.type;
                switch(type){
	                case "userSkill":
	                	var show = obj.setting.skillShow;
	                	if(show == 'together'){
		                	var html = '<div class="am-btn-group"><button class="am-btn am-btn-secondary">'+ tag.name +'</button>'
	            			+'<div class="am-dropdown am-dropdown-up" data-am-dropdown><button class="am-btn am-btn-secondary am-dropdown-toggle ym-skill-toggle" data-am-dropdown-toggle><span class="am-icon-caret-up"></span></button>'
	            			+' <ul class="am-dropdown-content" style="min-width:100px !important;padding: 0;overflow-y:auto;max-height:150px;">';
	//	                	contentDiv.append(html);
		                	//onclick="addSkill(this,'+j+',\''+tag.key[j]+'\')"
		                	for(var j in tag.key){
		                		html += '<li id="tag_' + j + '_addBtn" onclick="addSkill(this,'+j+',\''+tag.key[j]+'\',\''+type+'\')" class="am-btn am-btn-primary" style="width:100%;">'+ tag.key[j] +'</li>';
		                		
		                	}
		                	
		                	html += '</ul></div></div>';
		                	contentDiv.append(html);
		                	$('.ym-skill-toggle').on('click',function(e){
		                		if($(this).next('ul').css('display')=='none'){
		                			$(this).next('ul').css('display','block');
		                		}else{
		                			$(this).next('ul').css('display','none');
		                		}
		                		e.stopPropagation();//阻止冒泡
		                	});
		                	$(document).on('click',function(){
		             			$('.ym-skill-toggle').next('ul').css('display','none');
		             	    });
		                    continue;
	                	}else{
	                		for(var j in tag.key)
	                        {
	                             contentDiv.append('<label id ="tag_' + j +
	                                     '_addBtn" class="am-btn am-btn-warning am-radius" onclick="addSkill(this,'+j+',\''+tag.key[j]+'\',\''+type+'\')" style="margin:5px 5px;font-size: 1.2rem;padding: 0.5rem 0rem 0.5rem 0rem;width: 5.2rem;">' 
	                                     + tag.key[j] + '</label>');
	                        }
	                	}
                }
           }
            var arr = obj.setting.defaultValue.split(';');
//            console.log(arr);
            for(var i in arr){
                if(arr[i].length > 0){
                	arr1 = arr[i].split('_');
                    $btn = $('#tag_' + arr1[1] + '_addBtn');
                    $btn.attr('disabled', 'disabled');
                    $btn.css('background-color', '#a5a5a5');
                    $btn.css('border-color', '#a5a5a5'); 
                    obj.addSkillTag(arr1[1],arr1[3],'userSkill',arr1[2]);
                }
            }
        });
       
    }
}
Rule.prototype.addSkill = function() {
	var obj = this;
    var modalctrl = $("#" + obj.setting.modalid);
    var contentDiv = $("#" + obj.setting.modalid + "-content");
    var jsonurl = obj.setting.defaultJsonUrl;
    modalctrl.modal();
    if (contentDiv.html() == "") {
        $.getJSON(jsonurl,function(data){
            obj.setting.tags = data;
            for(var i = 0;i<obj.setting.tags.length; i++)
           {
                var tag = obj.setting.tags[i];
                var type = tag.type;
                switch(type){
	                case "onlyFriend":
	                	contentDiv.append('<label id ="tag_' + tag.key +
	                            '_addBtn" class="am-btn am-btn-primary am-radius" style="margin:2px 3px">' 
	                            + tag.name + '</label>');
	                	var btn = $('#tag_' + tag.key + '_addBtn');
	                    btn.on('click', function() {
	                        var id = this.id.split('_')[1];
	                        obj.addNewTag(id);
	                        $(this).attr('disabled', 'disabled');
	                        modalctrl.modal('close');
	                    });
	                    continue;
	                case "userSkill":
	                	var show = obj.setting.skillShow;
	                	if(show == 'together'){
	                		var html = '<div class="am-btn-group"><button class="am-btn am-btn-secondary">'+ tag.name +'</button>'
	            			+'<div class="am-dropdown am-dropdown-up" data-am-dropdown><button class="am-btn am-btn-secondary am-dropdown-toggle ym-skill-toggle" onclick="toggleUp(this)" data-am-dropdown-toggle><span class="am-icon-caret-up"></span></button>'
	            			+' <ul class="am-dropdown-content" style="min-width:100px !important;padding: 0;overflow-y:auto;max-height:150px;">';
		                	for(var j in tag.key){
		                		html += '<li id="tag_' + j + '_addBtn" onclick="addSkill(this,'+j+',\''+tag.key[j]+'\')" class="am-btn am-btn-primary" style="width:100%;">'+ tag.key[j] +'</li>';
		                		var btn = $('#tag_' + j + '_addBtn');
			                    btn.on('click', function() {
			                        var id = this.id.split('_')[1];
			                        obj.addNewTag(id);
			                        $(this).attr('disabled', 'disabled');
			                        modalctrl.modal('close');
			                    });
		                	}
		                	
		                	html += '</ul></div></div>';
		                	contentDiv.append(html);
		                    continue;
	                	}else{
	                		
	                	}
                }
                
           }
        });
       
    }
}
Rule.prototype.click = function() {
    var obj = this;
    var modalctrl = $("#" + obj.setting.modalid);
    var contentDiv = $("#" + obj.setting.modalid + "-content");
    var jsonurl = obj.setting.defaultJsonUrl;
    modalctrl.modal();
    if (contentDiv.html() == "") {
        $.getJSON(jsonurl,function(data){
            obj.setting.tags = data;
            for(var i = 0;i<obj.setting.tags.length; i++)
           {
                var tag = obj.setting.tags[i];
                var type = tag.type;
                switch(type){
	                case "onlyFriend":
	                	contentDiv.append('<label id ="tag_' + tag.key +
	                            '_addBtn" class="am-btn am-btn-primary am-radius" style="margin:2px 3px">' 
	                            + tag.name + '</label>');
	                	var btn = $('#tag_' + tag.key + '_addBtn');
	                    btn.on('click', function() {
	                        var id = this.id.split('_')[1];
	                        obj.addNewTag(id);
	                        $(this).attr('disabled', 'disabled');
	                        modalctrl.modal('close');
	                    });
	                    continue;
	                case "userSkill":
	                	var show = obj.setting.skillShow;
	                	if(show == 'together'){
		                	var html = '<div class="am-btn-group"><button class="am-btn am-btn-secondary">'+ tag.name +'</button>'
	            			+'<div class="am-dropdown am-dropdown-up" data-am-dropdown><button class="am-btn am-btn-secondary am-dropdown-toggle ym-skill-toggle" data-am-dropdown-toggle><span class="am-icon-caret-up"></span></button>'
	            			+' <ul class="am-dropdown-content" style="min-width:100px !important;padding: 0;overflow-y:auto;max-height:150px;">';
	//	                	contentDiv.append(html);
		                	//onclick="addSkill(this,'+j+',\''+tag.key[j]+'\')"
		                	for(var j in tag.key){
		                		html += '<li id="tag_' + j + '_addBtn" onclick="addSkill(this,'+j+',\''+tag.key[j]+'\',\''+type+'\')" class="am-btn am-btn-primary" style="width:100%;">'+ tag.key[j] +'</li>';
		                		
		                	}
		                	
		                	html += '</ul></div></div>';
		                	contentDiv.append(html);
		                	$('.ym-skill-toggle').on('click',function(e){
		                		if($(this).next('ul').css('display')=='none'){
		                			$(this).next('ul').css('display','block');
		                		}else{
		                			$(this).next('ul').css('display','none');
		                		}
		                		e.stopPropagation();//阻止冒泡
		                	});
		                	$(document).on('click',function(){
		             			$('.ym-skill-toggle').next('ul').css('display','none');
		             	    });
		                    continue;
	                	}else{
	                		for(var j in tag.key)
	                        {
                                contentDiv.append('<label id ="tag_' + j +
                                     '_addBtn" class="am-btn am-btn-warning am-radius" onclick="addSkill(this,'+j+',\''+tag.key[j]+'\',\''+type+'\')" style="margin:5px 5px;font-size: 1.2rem;padding: 0.5rem 0rem 0.5rem 0rem;width: 5.2rem;">' 
                                     + tag.key[j] + '</label>');
  
	                        }
                            var arr = obj.setting.defaultValue.split(';');
                            for(var i in arr){
                                
                                if(arr[i].length > 0){
                                    arr1 = arr[i].split('_');
                                    $btn = $('#tag_' + arr1[1] + '_addBtn');
                                    $btn.css('background-color', '#a5a5a5');
                                    $btn.css('border-color', '#a5a5a5');
                                    $btn.attr('disabled', 'disabled').addClass('am-disabled');
                                    obj.addSkillTag(arr1[1],arr1[3],'userSkill',arr1[2]);
                                }
                            }

	                	}
                }
                
           }
        });
       
    }
}
function addSkill(o,id,name,type){
	_ym_rule.addSkillTag(id,name,type);
    $(o).css('background-color', '#a5a5a5');
    $(o).css('border-color', '#a5a5a5');   
    $(o).attr('disabled', 'disabled').addClass('am-disabled');
    $("#" + _ym_rule.setting.modalid).modal('close');
}

Rule.prototype.addNewTag = function(id) {
    var set = this.findSet(id,id);
    var str = '<label  class="am-btn am-btn-warning am-round" style = "font-size:1.1rem;padding:0.2rem 0.5rem; margin:0.5rem 0.2rem">' + set.name + 
    '<input type="hidden" id="rule_' + id + '" name="rule_' + id + '" value = "'+set.key+'" />' +
    '</label>';
    var obj = this;
    $("#"+this.setting.container).append(str);
    $("#rule_"+id).parent().on('click',  obj.getDeleteConfirm(id,id));

    var v = $("#"+this.setting.tagsList).val();
    $("#"+this.setting.tagsList).val(v+";"+set.type+'_'+id);
}

Rule.prototype.addSkillTag = function(id,name,type,existLevel) {
	var set = this.findSet(id,type);
	if(!existLevel){
		existLevel = 1;
	}
	var max = this.setting.maxSkillLevel*1;
	var skillLevelOption = this.setting.skillLevelOption;
	var select = '',option = '';
	for(i=1;i<=max;i++){
		if(existLevel == i){
			select = "selected";
		}else{
			select = '';
		}
		option +='<option value="'+i+'" '+select+'>'+skillLevelOption[i-1]+'</option>';
	}
    var str = '<label style="display:inline-block;width:100%;"><label  class="am-btn" style = "font-size:1.2rem;width: 6rem; margin-right: 1.5rem;color:#fff;background-color: #383838;border-radius: 2px;">' + name + 
    
    '<input type="hidden"  name="_skill_' + id + '" value = "'+id+'" id="_skill_' + id + '" />' +
    '</label>' + '<select class=" am-form-field ym-publish-field" style="display:inline-block;width:45%;padding:0 0.8rem;height:30px;font-size:1.2rem;color:#383838;border:#e2e2e2 0.1rem solid;border-radius: 2px;" id="rule_' + id + '" name="rule_' + id + '">'
    + option
    +'</select><label id="skill_'+id+'_del" class="am-btn am-btn-warning am-fr" style="font-size:1.2rem;padding-left: 1.5rem;padding-right: 1.5rem;border-radius: 0.2rem;"><span>移除</span></label></label>';
    var obj = this;
    $("#"+this.setting.container).append(str);
    $("#skill_"+id+"_del span").parent().on('click',  obj.getDeleteConfirm(id,type));

    var v = $("#"+this.setting.tagsList).val();
    $("#"+this.setting.tagsList).val(v+";"+type+'_'+id);
}

Rule.prototype.findSet = function(id) {
    for (var i = 0; i < this.setting.tags.length; i++) {
        if (this.setting.tags[i].type == id)
            return this.setting.tags[i];
    }
    return null;
}
Rule.prototype.getDeleteConfirm = function(id,type)
{
	var set = this.findSet(type);
    var obj = this;
    return function(){
        $('#'+obj.setting.confirm+"-content").html('你确定要删除这个标签吗？');
        $('#'+obj.setting.confirm+"-content").attr('tagId',id);
        $('#'+obj.setting.confirm).modal({'onConfirm':function(c){
            var id = $('#'+obj.setting.confirm+"-content").attr('tagId');
            $("#rule_"+id).parent().remove();
            $("#tag_" + id + "_addBtn").removeAttr('disabled');
            $("#tag_" + id + "_addBtn").removeClass('am-disabled');
            $("#tag_" + id + "_addBtn").css('background-color', '#f37b1d');
            $("#tag_" + id + "_addBtn").css('border-color', '#f37b1d');
            var v = $("#"+obj.setting.tagsList).val();
            $("#"+obj.setting.tagsList).val(v.replace(";"+type+'_'+id, ""));
        }});
    };
}
