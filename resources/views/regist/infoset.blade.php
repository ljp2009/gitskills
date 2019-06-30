<div class="ym_fp_row">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <input name="userName" type="text" value="{{$userName}}" readonly/>
</div>
<div class="ym_fp_row">
    <div class="ym_fp_avatar">
        <input value="" name="avatar" type="hidden"/>
        <img src= "http://img.umeiii.com/test/default.jpg" id="modifyAvatar"/>
    </div>
    <span style="text-align:center">用户形象</span>
</div>
<button type="button" class="ym_fp_submit" onclick="register()">更新信息</button>
<script type="text/javascript">
function register(){
    var userName    = $('input[name=userName]').val();
    var avatar      = $('input[name=avatar]').val();
    var displayName = $('input[name=displayName]').val();
    var pwd         = $('input[name=password]').val();
    var pwd2        = $('input[name=password2]').val();

    if(displayName == ''){
        showInfo('请填写用户昵称', true);
        return;
    }
    if(pwd == ''){
        showInfo('请输入密码', true);
        return;
    }
    if(pwd.length < 8){
        showInfo('密码至少需要需要大于8字符', true);
        return;
    }
    if(pwd != pwd2){
        showInfo('两次输入的密码不一致', true);
        return;
    }
    $.post('/regist/register', {
        '_token'      : $('input[name=_token]').val(),
        'pwd'         : pwd,
        'displayName' : displayName,
        'avatar'      : avatar,
    }, function(data){
        if(!data.res){
            showInfo(data.info, !data.res);
        }else{
           $.ymFunc.goTo('/reshall');
        }
    });
}
$('#modifyAvatar').scUploadImageWork({
    name           : 'avatar',
    maxFileSize    : 10,
    allowAnimation : false,
    uploadUrl      : '/img/policy',
 })
 .bind('beforeUpload', function(uploadSet, params){
     uploadSet.uploadUrl = '/img/policy/'+uploadSet.fileName+'/avatar';
 }, null)
 .bind('afterUpload', function(imgInfo, res){
     if(res == null){
         alert('上传失败了，稍后再试。');
     }else{
         $('input[name=avatar]').val('avatar/'+imgInfo.name);
         $('#modifyAvatar').attr('src', 'http://img.umeiii.com/avatar/'+imgInfo.name);
     }
 }, null);
</script>
