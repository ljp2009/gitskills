@extends('layouts.formpage')
@section('title', $title)
<style type="text/css">
.selected{
	background: #eeeeee;
	border-bottom: solid 3px #ffffff;
}
.select{
	border-bottom: solid 3px #eeeeee;
}
</style>

@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'post', 'pageTitle'=>$title])
<div class="ym_fp_container ym_active" style="height: initial;">
	<div class="am-g">
		<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" style="margin:0 -1rem">
	    <h2 class="am-titlebar-title ">{{$title}}</h2>
	</div>
	  <div class="am-u-lg-6 " style="margin-top:1.5rem; width:100%">
	    <div class="am-input-group am-input-group-primary">
	    	<input type="text" class="am-form-field" id="search-name" placeholder = '请输入用户名称'>
	      <span class="am-input-group-btn">
	        <button class="am-btn am-btn-primary" type="button" id="btn-search"><span class="am-icon-search"></span></button>
	      </span>
	      <input type="hidden" name="sysUserSkill" id="sysUserSkill" value="{{$sysSkillsArray}}">
	      <input type="hidden" name="resourceId" id="resourceId" value="{{$resourceId}}">
	      <input type="hidden" name="resourceType" id="resourceType" value="{{$resourceType}}">
	      <input type="hidden" name="inviteUserId" id="inviteUserId" value="{{$inviteUserId}}">
	    </div>
	  </div>
	</div>
	<div style="padding-top:1rem; margin-bottom:5rem" id="listContent">
		
	</div>

</div>

<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
  <div class="am-modal-dialog">
    <div class="am-modal-bd">
      邀请成功！
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn">确定</span>
    </div>
  </div>
</div>
@stop
@section('scriptrange')
//<script >

function postForm(){
	invite();
}


//检索
$('#btn-search').click(function(){

	var sysSkill = $("#sysUserSkill").val();
	var nameSearch = $.trim($("#search-name").val());
	var $YN_VALIDATOR = new ym_validator();
	if(nameSearch == ''){
		$YN_VALIDATOR.handleErrorMessage("请输入用户名称");

	} else if (nameSearch.length < 2){
		$YN_VALIDATOR.handleErrorMessage("用户名称的字数必须是两个或两个以上");
	} else {
		$.ajax({
			url:"/invite/list",
			type:"POST",
			dataType:'json',
			data:{sysSkillsArray:sysSkill, name:nameSearch, _token:"{{ csrf_token() }}"},
			success:function(data){

				if (data.length == 0) {
					$YN_VALIDATOR.handleErrorMessage("没有检索到该用户");
				} else {
					var ht ='';
					for(var j in data){
						var user = data[j];
						ht +='<div class="am-container select" id="'
							+'inviteUser'+j+'" style="padding-top:1rem; padding-bottom:1rem;" onclick="selectUser('+j+')">'
							+'<input name="checkbox" style="display:none"  id="cb'
							+j+ '" value="'+ user.id +'" type="checkbox">'
							+'<div class="am-fl">'
								+'<img class="am-circle" src="'+user.homeAvatar+'" width="80" height="80"/>'
							+'</div>'
							+'<div class="am-fl am-text-middle" style="margin-left:1rem;padding-top:1rem">'
								+'<span style="margin-left:0.5rem">'+ user.name +'</span>'
								+'<br>';
						for(var i in user.skill){
							ht +='<span class="am-badge am-badge-secondary am-round" style="margin-left:0.5rem">'+ user.skill[i] +'</span>';
						}
									
						ht +='</div></div>';
					}
					
					$("#listContent").html(ht);
				}
				
			}
		}).error(function(e){
			$('body').html(e.responseText);
		});
	}
	
});


//选择邀请用户
function selectUser(j){

	var ch_id =  "#cb"+j;
	var div_id = "#inviteUser"+j;

	if($(ch_id).is(':checked')){

		$(ch_id).prop("checked", false);
		$(div_id).removeClass("selected");
		$(div_id).addClass("select");
	} else {
		$(ch_id).prop("checked", true);
		$(div_id).addClass("selected");
		$(div_id).removeClass("select");
	}

}

//发送邀请
function invite(){
	var idSelect ='';
	//遍历选中的用户id
	$("#listContent input[type=checkbox]").each(function(){
		if(this.checked){
			if(idSelect != ''){
				idSelect+=$(this).val()+",";
			} else {
				idSelect=$(this).val()+",";
			}
			
		}
	});
	//邀请源id
	var resourceId = $("#resourceId").val();
	//邀请源种类
	var resourceType = $("#resourceType").val();
	//发送邀请者id
	var inviteUserId = $("#inviteUserId").val();

	var $YN_VALIDATOR = new ym_validator();
	//没有选择用户
	if(idSelect == ''){
		
		$YN_VALIDATOR.handleErrorMessage("请选择用户");	
	} else {

		$.ajax({
			url     : "/invite/publishDesignated",
			type    : "POST",
			dataType: "json",
			data    : {resourceId:resourceId, resourceType:resourceType, inviteUserId:inviteUserId,
						idSelect:idSelect, _token:"{{ csrf_token() }}"},
			success : function(data){
				//邀请成功
				if(data.code == 1){
					$('#my-alert').modal();
				//邀请失败
				} else {
					$YN_VALIDATOR.handleErrorMessage(data.msg);	
				}
				
			}

		}).error(function(e){
			$('body').html(e.responseText);
		});
	}

}



@stop


