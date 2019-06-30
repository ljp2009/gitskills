<style type="text/css">
	.pagecover{
		background-color: #fff;
		background-repeat: no-repeat;
		background-size: cover;
		background-position: left;
        @if($model->background->checkSet())
		background-image:url({{$model->background->getPath()}});
        @endif
	}
</style>
<script type="text/javascript" src="/js/ym_animate.js"></script>
<script src="/assets/cropper/cropper.min.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>
<link rel="stylesheet" href="/assets/cropper/cropper.min.css" />
<link rel="stylesheet" href="/assets/uploadimg/scImageEditer.css" />
<link rel="stylesheet" href="/css/ym_publish.css" />
<link rel="stylesheet" href="/css/ym_home.css">

<div class="am-userInfo" id="home-userInfo" style="position:relvate">
        @if($isOwner)
        <i id="editBackground" class="ymicon-edit" style="font-size:1.3rem;color:#ffffff;position:absolute;right:20px;top:20px;z-index:1;">设置背景</i>
        @endif
        @if($model->background->checkSet())
        <div class="filter-bg pagecover" id="pagecover"></div>
        @endif
    <div class="am-user-box">
        @if($isOwner && $model->receiveGold > 0)
        <div class="coin-btn" onclick="getCoins(this)">
            <img alt="" src="/imgs/coin6.png">
        </div>
        @endif
        @if(strlen($model->label)> 0)
        <h5 class="ym-ft-12" style="display:none">
        {{$model->label}}
        </h5>
        @endif
        <div style="width:90px; display:inline-block">
        <div class="am-user-photo" style="width:90px">
            <img src="{{$model->avatar->getPath(2, '105h_105w_1e_1c')}}" alt="" class="am-img-responsive am-circle" />
        </div>
        <label style=" width: 90px; display:block; text-align: center; color: #fff;">
            信誉{{$model->getUserCredit()['label']}}
        </label>
        </div>
        <div class="am-u-mi-7 am-u-sm-7 am-user-info">
            <h3>
            {{$model->display_name}}
            </h3>
            <div class="am-info">
                <i class="@if($model->userInfo->sex=='男')ym-icon-female @else ym-icon-male @endif am-icon-sm"></i>
                <div class="am-person">
                    @if($model->userInfo->age >=0)
                    <span class="am-age">
                    {{$model->userInfo->age}}岁
                    </span>&nbsp;&nbsp;&nbsp;&nbsp;
                    @endif
                    <span class="am-marrage">
                    {{$model->userInfo->marriage}}
                    </span>
                </div>
            </div>
            <div class="am-user-tag">
                 <a href="/user/list/follow/0/{{$id}}">关注&nbsp;&nbsp;<span class="am-attention-num" >{{$model->followNum}}</span></a>
                &nbsp;&nbsp;|&nbsp;&nbsp;<a href="/user/list/fans/0/{{$id}}"> 粉丝&nbsp;&nbsp;
                <span class="am-fans-num">{{$model->fansNum}}</span></a>
            </div>
            <!-- 用户技能  等级 -->
            <div class="am-user-tag">
                <span>
                @if(count($model->getAttrSkill)>0)
                    @foreach ($model->getAttrSkill as $k => $attr)
                    <span class="am-badge am-badge-secondary am-radius icon" style="font-size:10px;padding: 3px 6px 3px 6px;background: #ef7c1e;font-weight: 100;">
                      {{$attr->skillName}}
                    </span>
                    @endforeach
                @endif
                </span>
            </div>
        </div>
    </div>
    <p class="am-sign" id="signature">
    @if($isOwner)
        <i class="ymicon-edit"></i>
        <label>{{ $model->signature == ''?'编辑签名':$model->signature}}</label>
    @else
        <label>{{$model->signature}}</label>
    @endif
    </p>
    @if(count($model->getUserBadge)>0)
    <div class="am-container">
        @foreach ($model->getUserBadge as $k => $attr)
        <a><img src="{{ $attr->getBadge->badge }}" alt="" class="am-img-responsive"></a>
        @endforeach
    </div>
    @endif
    <div class="am-handel">
        <span class="{{ $btnClass['follow'] }}" data-id="{{$id}}" data-action="addFollow"><i class="am-icon-eye"></i>{{$follow}}</span>
        <span class="{{ $btnClass['msg'] }}" id="sendPrivateMsg" data-id="{{$id}}"><i class="am-icon-envelope-o"></i></i>私信</span>
        <span class="{{ $btnClass['give'] }}" id="doc-prompt-toggle" data-action="addGive" data-id="{{$id}}"><i class="am-icon-thumbs-o-up"></i>打赏</span>
    </div>
    <div  class="am-modal am-modal-prompt" tabindex="-1" id="my-prompt">
         <div class="am-modal-dialog">
            <div class="am-modal-hd">打赏</div>
            <div class="am-modal-bd">
              来来来，打赏点吧
              <input id='giveMoney' type="number" class="am-modal-prompt-input" placeholder="输入打赏金币">
            </div>
            <div class="am-modal-footer">
              <span class="am-modal-btn" data-am-modal-cancel>取消</span>
              <span class="am-modal-btn" data-am-modal-confirm>提交</span>
            </div>
        </div>
    </div>
    <div class="am-modal am-modal-alert" tabindex="-1" id="my-alert" style="width:70%;left:15%;margin-left:0;">
      <div class="am-modal-dialog">
        <div class="am-modal-hd">提示</div>
        <div class="am-modal-bd">
        </div>
        <div class="am-modal-footer">
          <span class="am-modal-btn">确定</span>
        </div>
      </div>
    </div>
</div>
<div class="post-comment" style="z-index:55;opacity:0;bottom:-42px;padding-left:3%;">
    <div class="comment-content" style="width:80%;">
      <input type="text" value="" name="privatemsg" placeholder="吐槽">
    </div>
    <button id="sendMsg">发送</button>
</div>
<script type="text/javascript">
function followSwitch(){
    $.post('/user/switch-follow',{
        '_token':$.ymFunc.getToken(),
        'id':'{{$model->id}}'
    }, function(data){
        if(data.res){
            if(data.info){
                
            }
            else{
            }
        }
    }).error(function(e){alert(e.responseText)});
}

@if($isOwner)
    function getCoins(obj){
        $.ajax({
            type     : 'POST',
            url      : '/user/receive',
            data     : {'_token' : $.ymFunc.getToken()},
            dataType : 'json',
            success  : function(data){
                $YM_ANIMATE.showAnimation({{$model->receiveGold}}, function(){
                    $(obj).remove();
                });
            }
        });
    }
$('#signature').ymEditField({
    'title'      : '编辑签名',
    'valueField' : '#signature>label',
    'maxLength'  : 16,
    'callback'   : function(newValue, ev){
        if(newValue == '编辑签名'){
            newValue == '';
        }
        $.post('/uset/signature',{
            '_token':$.ymFunc.getToken(),
            'signature': newValue,
        },function(data){
            if(data.res){
                $('#signature>label').text(newValue);
                ev.finish();
            }
            else{
                ev.error(data.info);
            }
        }).error(function(err){
            ev.error(err);
        });
    }
});
@else
  $('#doc-prompt-toggle').on('click', function() {
    var $action = $(this).attr('data-action');
    var $id = $(this).attr('data-id');
    var $this = $(this);
    $('#my-prompt').modal({
      relatedTarget: this,
      onConfirm: function(e) {
        var gold = $.trim(e.data);
        if(gold == '' || gold == 0){
          myAlert('请输入金币');
          return false;
        }
        $.ajax({
            type:'POST',
            url:'/user/relation',
            data:{id:$id,action:$action,gold:e.data,_token:"{{ csrf_token() }}"},
            dataType:'json',
            success:function(data){
                if(data.code == 1){
                    $('#giveMoney').val('');
                    $('#my-prompt').modal('close');
                }else if(data.code < 0){
                    myAlert(data.msg);
                }
            }
        });
      },
      onCancel: function(e) {
<!-- 	        alert('不想说!'); -->
      }
    });
  });
@endif

$('#editBackground').scUploadImageWork({
    name:'background',
    maxFileSize:10,
    allowAnimation:false,
    useImgEditer:false,
    uploadUrl:'/img/policy',
 })
 .bind('beforeUpload', function(uploadSet, params){
     uploadSet.uploadUrl = '/img/policy/'+uploadSet.fileName+'/background';
 }, null)
 .bind('afterUpload', function(imgInfo, res){
     if(res == null){
         alert('上传失败了。');
     }else{
         $.post('/uset/background', {
             '_token':$.ymFunc.getToken(),
             'fileName': 'background/'+imgInfo.name,
         }, function(data){
             if(data.res){
                 /*$('#pagecover').css('background-image',"url("+data.info+")");*/
                 location.reload();
             }
         }).error(function(e){});
     }
 }, null);
</script>
