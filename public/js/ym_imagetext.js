;dragula([image_text_area]);
function addText(){
	var len = $('#image_text_area').find('.text-box').length;
	var next = $('#image_text_area').find('.text-box').eq(len-1).next().length;
	if(len == 0 || ( len>=1&&next!=0) ){
	    var html = '<div class="text-box" placeholder="这里输入文本">'+
			    		'<p contenteditable="true" onfocus="editText(this);" onblur="resetText(this);" class="text-area">这里输入文本</p>'+
			    		'<span class="text-delete am-icon-trash" onclick="deleteText(this);"></span>'+
			    	'</div>';
	    $('#image_text_area').append(html);
	    // console.log(html.target);
	    $('#image_text_area').parent().removeClass('am-form-error');
	}
  }
  function editText(obj){
    var text = $(obj).text();
    if(text == '这里输入文本'){
      $(obj).text('');
    }
    $(obj).parent().find('.text-delete').css('display','none');
  }
  function resetText(obj){
    var text = $(obj).text();
    if(text == ''){
      $(obj).text('这里输入文本');
    }
    $(obj).parent().find('.text-delete').css('display','');
  }
//删除文本
  function deleteText(obj){
    var $this = $(obj);
    $('#my-deletetext-confirm').modal({
        relatedTarget: $this,
        onConfirm: function(options) {
          // var $link = $(this.relatedTarget).prev('a');
          // var msg = $link.length ? '你要删除的链接 ID 为 ' + $link.data('id') :
          //   '确定了，但不知道要整哪样';
          // alert(msg);
//        	console.log($this.parent().parent().parent());
          this.relatedTarget.parent().remove();
        },
        // closeOnConfirm: false,
        onCancel: function() {
        }
      });
  }
//删除链接
  function deleteLink(obj){
    var $this = $(obj);
    $('#my-deletelink-confirm').modal({
        relatedTarget: $this,
        onConfirm: function(options) {
          // var $link = $(this.relatedTarget).prev('a');
          // var msg = $link.length ? '你要删除的链接 ID 为 ' + $link.data('id') :
          //   '确定了，但不知道要整哪样';
          // alert(msg);
//        	console.log($this.parent().parent().parent());
          this.relatedTarget.parent().remove();
          $('.image-text-hand').find('#addlinkBox').attr('data-num',0);
        },
        // closeOnConfirm: false,
        onCancel: function() {
        }
      });
  }
//添加图片描述
  function addTitle(obj){
    $this = $(obj);
    var html = $this.parent().parent().parent().find('.img-desc').text();
    $('#my-adddesc-prompt').find('input[type="text"]').val(html);
    $('#my-adddesc-prompt').modal({
      relatedTarget: this,
      onConfirm: function(e) {
        // alert('你输入的是：' + e.data || '')
        html = '<span class="img-desc" onclick="editTitle(this);">'+e.data+'</span>';
        $this.parent().parent().parent().find('.img-desc').text(e.data);
      },
      onCancel: function(e) {
        // alert('不想说!');
      }
    });
  }
  
  function editTitle(obj){
	    $this = $(obj);
	    var html = $this.parent().find('.img-desc') ? $this.parent().find('.img-desc').text() : '';
	    $('#my-editdesc-prompt').find('input[type="text"]').val(html);
	    $('#my-editdesc-prompt').modal({
	      relatedTarget: this,
	      onConfirm: function(e) {
	        // alert('你输入的是：' + e.data || '')
	        $this.parent().find('.img-desc').text(e.data);
	      },
	      onCancel: function(e) {
	        // alert('不想说!');
	      }
	    });
	  }
  // 显示编辑区域
  function showEdit(obj){
    var $this = $(obj);
    if($this.hasClass('am-icon-plus')){
      $this.removeClass('am-icon-plus').addClass('am-icon-minus');
      $this.next().fadeIn();
    }else{
      $this.removeClass('am-icon-minus').addClass('am-icon-plus');
      $this.next().fadeOut();
    }
  }
  // 删除图片
  function deleteImg(obj){
    var $this = $(obj);
    $('#my-deleteimg-confirm').modal({
        relatedTarget: $this,
        onConfirm: function(options) {
          // var $link = $(this.relatedTarget).prev('a');
          // var msg = $link.length ? '你要删除的链接 ID 为 ' + $link.data('id') :
          //   '确定了，但不知道要整哪样';
          // alert(msg);
//        	console.log($this.parent().parent().parent());
          this.relatedTarget.parent().parent().parent().remove();
          var $count = $('#image-text-upload').attr('imgcount')*1;
          $('#image-text-upload').attr('imgcount',$count+1);
        },
        // closeOnConfirm: false,
        onCancel: function() {
        }
      });
  }
  function imageUpload(filectrl, flag){
	    var name = $("#objName").val();
	    var valuectrl = $("#"+name+"-text-upload");
	    var maxCount = parseInt(valuectrl.attr("maxCount"));
	    var imgCount = parseInt(valuectrl.attr("imgCount"));
	    if (imgCount >= maxCount) {
	        alert("上传图片数量超过限制。");
	        return;
	    }
	    //var maxSize = valuectrl.attr("maxSize");
	    //if(this.files[0].size > maxSize)
	    //{
	    //	alert("图片大小不能超过10MB");
	    //	return;
	    //}
	    var fileName = filectrl.files[0].name;
	    var ext = fileName.substr(fileName.lastIndexOf('.') + 1).toLowerCase();
	    var nameSeed = valuectrl.attr("nameSeed");
	    var nameIndex = valuectrl.attr("nameIndex");
	    var imageName = nameSeed + nameIndex + "." + ext;
	    valuectrl.attr("nameIndex", parseInt(nameIndex) + 1);
	    var formctrl = $("#_uploadImageForm");
	    formctrl.find("input[name=key]").val(imageName);
	    formctrl.find("input[name=content-Type]").val(getContentType(ext));
	    var redirectUrl = window.location.protocol + "//" + window.location.host + "/t2.php?name=" + name + "&imgname=" + imageName;
	    formctrl.find("input[name='success_action_redirect']").val(redirectUrl);

	    formctrl.submit();
  }
  function imageCallBack(name, imageName) {
	    var valuectrl = $("#" + name + "-text-upload");
	    //var btnctrl = $("#"+name+"_addbtn");
	    var imagectrl = $('#'+name+"_text_value");
	    var imgCount = valuectrl.attr('imgCount');
	    valuectrl.attr('imgCount', parseInt(imgCount) + 1);
	    if (parseInt(valuectrl.attr('imgCount')) >= parseInt(valuectrl.attr('maxCount'))) {
	        $("#" + name + "_addbtn").hide();
	    }
	    var v = imagectrl.val();
	    imagectrl.val(v + imageName + ";");
	    addImgBox(imageName);
	}
  // 添加图片区域
  function addImgBox(imageName){
	  var src = getImgBaseUrl(imageName);
    var html = '<div class="img-box">';
    html += '<div class="img-hole-box">';
    html += '<i class="am-icon-arrows-v ym-drag ym-drag-able"></i>';
    html += '<img src="'+src+'" data-src="'+imageName+'" class="" alt="" />';//图片
    html += '<span class="img-handel am-icon-plus" onclick="showEdit(this);"></span>';
    html += '<div class="img-handel-area">';
    html += '<span class="img-edit am-icon-pencil" onclick="addTitle(this);"></span>';
    html += '<span class="img-delete am-icon-trash" onclick="deleteImg(this);"></span>';
    html += '</div>';
    html += '</div><span class="img-desc" onclick="editTitle(this);"></span></div>';
    $('#image_text_area').append(html);
    $('#image_text_area').parent().removeClass('am-form-error');
 }
function getImgBaseUrl(imgName)
{
	return "http://img.umeiii.com/"+imgName;
}
  //添加链接
  function addLink(obj){
	  var num = $(obj).attr('data-num');
	  $('.am-modal-addlink').off('click');
//	  return false;
	  if(num == 0){
	    $('#my-addlink-prompt').modal({
	      relatedTarget: this,
	      onCancel: function(e) {
//		    	  $('#my-addlink-prompt').find('input[type="text"]').val('');
	      }
	    });
	    $('.am-modal-addlink').on('click',function(){
	    	console.log(1);
	    	var linkName = $.trim($('#my-addlink-prompt').find('input[name="linkname"]').val());
    		var linkUrl = $.trim($('#my-addlink-prompt').find('input[name="linkurl"]').val());
    		if(linkName && linkUrl){
    			if (linkUrl.search(/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/g) == -1){
        			$YN_VALIDATOR.handleErrorMessage('请输入正确的url地址');
        			$('#my-addlink-prompt').find('input[name="linkurl"]').parent().addClass('am-form-error');
        			return false;
        		}
    		    html = '<div class="link-box"><a class="link-desc" data-href="'+linkUrl+'" onclick="editLink(this);">'+linkName+'</a><span class="link-delete am-icon-trash" onclick="deleteLink(this);"></span></div>';
    	        $('#image_text_area').append(html);
    	        $(obj).attr('data-num','1');
    	        $('#my-addlink-prompt').modal('close');
    		}
    		return false;
	    });
	  }
	  $('#image_text_area').parent().removeClass('am-form-error');
  }
  
  function editLink(obj){
	  $this = $(obj);
	  var linkName = $.trim($this.text());
	  var linkUrl = $.trim($this.attr('data-href'));
	  $('#my-editlink-prompt').find('input[name="edit-linkname"]').val(linkName);
	  $('#my-editlink-prompt').find('input[name="edit-linkurl"]').val(linkUrl);
	  $('#my-editlink-prompt').modal({
		  relatedTarget: this,
	      onCancel: function(e) {
	    	 
	      }
	  });
	  $('.am-modal-editlink').on('click',function(){
	    	var linkName = $.trim($('#my-editlink-prompt').find('input[name="edit-linkname"]').val());
	  		var linkUrl = $.trim($('#my-editlink-prompt').find('input[name="edit-linkurl"]').val());
	  		if(linkName && linkUrl){
	  			if (linkUrl.search(/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/g) == -1){
	      			$YN_VALIDATOR.handleErrorMessage('请输入正确的url地址');
	      			$('#my-editlink-prompt').find('input[name="linkurl"]').parent().addClass('am-form-error');
	      			return false;
	      		}
	  			$this.attr('data-href',linkUrl).text(linkName);
	  	        $('#my-editlink-prompt').modal('close');
	  		}
		
	  });
  }
 
