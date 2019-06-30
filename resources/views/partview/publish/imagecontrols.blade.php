<div class="am-modal am-modal-prompt" tabindex="-1" id="ym-select-img">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">选择图片</div>
    <div class="am-modal-bd">
        <div id='ym-selectDiv'>
            <div style='height:350px'>
                <div class="ym-img-history-label"><span>上传过的图片：</span></div>
                <div id='ym-img-history' class="ym-img-history"></div>
                <div id='ym-desc-label' class="ym-img-history-label"></div>
                <div id='ym-editimg-div' style='height:285px'></div>
            </div>
        </div>
    </div>
    <div class="am-modal-footer">
<span class="am-modal-btn" data-am-modal-cancel>取消</span>
<span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
<iframe style="display:none" name="hiddenFrame"></iframe>
<form style="display:none" id="ym_upload_form" target ="hiddenFrame"
    action= "{{$postUrl}}" method="post" enctype="multipart/form-data" >
    <input type="hidden" id="objName" />
    <input type="hidden" id="ym_upload_name_seed" ct="0" value="{{$nameSeed}}" />
    <input type="hidden" id="ym_upload_token" ct="0" value="{{$token}}" />
    <input type="hidden" value="testfileA.jpg" name="key" />
    <input type="hidden" value="image/jpeg" name="content-Type" />
    <input type="hidden" value="{{$accessId}}" name="OSSAccessKeyId" />
    <input type="hidden" value="{{$policy}}" name="policy" />
    <input type="hidden" value="{{$signature}}" name="signature" />
    <input type="file" id="ym_upload_image_file" onchange="$.ymImgField.uploadToAli()"
         class="oss-upload-file" name="file" accept="image/*" />
    <input type="hidden" value="" name="success_action_redirect" />
    <input type="hidden" value="201" name="success_action_status">
</form>
<script type='text/javascript'>
function uploadImgCallBack(fieldName,imgName){ $.ymImgField.selectedImage(fieldName,imgName); }
</script>
