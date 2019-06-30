<div class="ym_fp_row">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <input name="userName" type="text" value="{{$userName}}" readonly/>
</div>
<div class="ym_fp_row">
    <input name="displayName" type="text" placeholder="用户昵称" maxlength="16" value=""/>
</div>
<div class="ym_fp_row">
    <input name="password" type="password" placeholder="输入密码(最少8个字符)" maxlength="16" value=""/>
</div>
<div class="ym_fp_row">
    <input name="password2" type="password" placeholder="再次输入密码" maxlength="16" value=""/>
</div>
<div class="ym_fp_err">
    <span id="err"></span>
</div>
<button type="button" class="ym_fp_submit" onclick="register()">创建用户</button>
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
    var redirectCode = $('#redirectCode').val();
    $.post('/regist/register', {
        '_token'      : $('input[name=_token]').val(),
        'pwd'         : pwd,
        'redirectCode': redirectCode,
        'displayName' : displayName,
    }, function(data){
        if(!data.res){
            showInfo(data.info, !data.res);
        }else{
           $.ymFunc.goTo(data.url);
        }
    });
}
</script>
