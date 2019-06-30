var ym_validator = function(){
	var VL = this;
	VL.errorMessageDialog = '_errorMessageDialog';
	VL.errorMessageCtrl = '_errorMessage';
	VL.errorMessageLabel = '_errorMessageLabel';

	VL.stringtrim = function(str){
		return str.replace(/(^\s*)|(\s*$)/g, '');
	};
	VL.validators = {
		required:[function(v){
			return v.length > 0;
		}, '内容不能为空！'],
		integer:[function(v){
			if(!VL.validators.required[0](v))
				return false;
			var reg = new RegExp("^-?\\d+$");
			return reg.test(v);
		}, '请输入整数！'],
		number:[function(v){
			if(!VL.validators.required[0](v))
				return false;
			if (isNaN(v)) {
				return false;
			}
			return true;
		}, '请输入数字！'],
		phone:[function(v){
			if(!VL.validators.required[0](v))
				return false;
			if(!VL.validators.integer[0](v))
				return false;
			if(VL.stringtrim(v).length!=11)
				return false;
			if (!/^0?1[3|4|5|7|8][0-9]\d{8}$/.test(v))
				return false;
			return true;
		}, '请输入正确的手机号码！'],
		email:[function(v){
			if(!VL.validators.required[0](v))
				return false;
			if (v.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
				return true;
			else
				return false;
		}, '请输入正确的邮件地址！'],
		url:[function(v){
			if(!VL.validators.required[0](v))
				return false;
			if (v.search(/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/g) != -1)
				return true;
			else
				return false;
		}, '请输入正确的url地址！'],
		limit:[function(v, args){
			if(typeof(args)=='undefined'||args.length==0){
				return true;
			}
			var min = 0, max = -1;
			if(args.length > 1){
				min = parseInt(args[0]);
				max = parseInt(args[1]);
			}else{
				min = parseInt(args[0]);
			}
			if(max < 0){
				return v.length >= min;
			}else{
				return (v.length >= min && v.length <= max);
			}
		}, '请输入正确的内容！'],
		numberLimit:[function(v, args){
			if(typeof(args)=='undefined'||args.length==0){
				return true;
			}
			var min = 0, max = 1;
			if(args.length > 1){
				min = parseInt(args[0]);
				max = parseInt(args[1]);
			}else{
				min = parseInt(args[0]);
			}
			if(max < 0){
				return v.length >= min;
			}else{
				return (v >= min && v <= max);
			}
		}, '请输入正确的内容！'],
		phoneOrMail:[function(v){
			if(!VL.validators.required[0](v))
				return false;
			if(VL.validators.integer[0](v)){
				return VL.validators.phone[0](v);
			}else{
				return VL.validators.email[0](v);
			}
		}, '请输入合法的邮件地址或手机号码！'],
		passwordEqual:[function(v){
			var pass = $.trim($('input[name="password"]').val());
			var passConfirm = $.trim($('input[name="password_confirmation"]').val());
			return pass === passConfirm;
		}, '两次输入的密码不一致'],
	};
	VL.extendValidators = [];

	VL.extendValidator = function(vName, fn, defaultErrorMessage){
		if(typeof(defaultErrorMessage)=='undefined'){
			defaultErrorMessage = '错误！';
		}
		VL.extendValdators[vName] = [fn, defaultErrorMessage];
	};

	VL.handleErrorMessage = function(errorMessage) {
		var errorDialog = $('#' + VL.errorMessageDialog);
		var errorMessageField = $('#' + VL.errorMessageCtrl);
		var errorMessageLabel = $('#' + VL.errorMessageLabel);
		//var errorField = $('#errorMessage');
			//errorField[0].innerText = errorMessage;
		//画面显示错误信息
		if (errorMessageLabel[0]!=undefined) {
			errorMessageLabel.html(errorMessage);
			errorMessageLabel.show();
		//画面弹出错误信息
		} else {
			errorMessageField.html(errorMessage);
			errorDialog.modal();
		};
		

	};

	VL.checkValidate = function(oneValidate, o, value, clazz){
		if(VL.stringtrim(oneValidate).length==0)
			return true;
		var args = [];
		if(oneValidate.indexOf('!')!=-1){
			var mos = oneValidate.split('!');
			for(var i=1; i<mos.length; i++){
				args[i-1] = mos[i];
			}
			oneValidate = mos[0];
		}
		var checkFnObj = false;
		if(typeof(VL.validators[oneValidate])!='undefined'){
			checkFn = VL.validators[oneValidate];
		}else{
			heckFn = VL.extendValdators[oneValidate];
		}
		if(typeof(checkFn) == 'undefined'){
					//Do nothing
		}else{
			if(!checkFn[0](value, args)){
				var msg = checkFn[1];
				if(typeof(o.attr('errorMessage'))!='undefined'){
					msg = o.attr('errorMessage');
				}
				o.parent().addClass(clazz);
				VL.handleErrorMessage(msg);
				return false;
			}
		}
		return true;
	};

	VL.submitForm = function(){
		var elements = $('.ym-publish-field');
		var formEle = $('form');
		var clazz = 'am-form-error';
		VL.error = 0;
		elements.each(function(index){
			if(VL.error>0)
				return;
			var o = $(this);
			var value = $.trim(o.val());
			if(typeof(o.attr('validate'))!='undefined'){
				var attr = o.attr('validate');
				var vals = false;

				if(attr.indexOf(',')!=-1){
					vals = attr.split(',');
				}else{
					vals = [attr];
				}
				for(var v in vals){
					if(!VL.checkValidate(vals[v], o, value, clazz)){
						VL.error = 1;
						break;
					}
				}
				if(VL.error == 0){
					o.parent().removeClass(clazz);
				}
			}
		});
		if(VL.error == 0){
			var isAjaxPost = $('input[name="isAjaxPost"]').val();
			if(isAjaxPost == undefined){
				formEle.submit();
			}else{
				var url = $('#formLogin').attr('action');
				var formData = $('#formLogin').serialize();
				$.ajax({
					type : 'post',
					url  : url,
					data : formData,
					dataType : 'json',
					success : function(data) {
            if(data.res){
                $.ymFunc.goTo(data.url)
            }else{
                $.ymNotice.show(data.info);
            }
            /*
						if(data.code < 0){
							VL.handleErrorMessage(data.msg);
						//提交成功后弹出结果框
						} else if(data.code == 100){
							$('#my-alert').modal();
						} else{
							if(data.parm.redirectUrl){
								location.href = data.parm.redirectUrl;
							}
						}
            */
					}
				});
			}
		}
	};
}

var $YN_VALIDATOR = new ym_validator();
