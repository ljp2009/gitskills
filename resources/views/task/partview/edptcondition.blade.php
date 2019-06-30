<div class="ym_backheader">
    <ul class="am-avg-sm-3">
        <li style="text-align:left" onclick="back()">
            <i class="am-icon-angle-left"></i>
            <span class="ym_backheader_btn">&nbsp;&nbsp;返回</span>
        </li>
        <li style="text-align:center"><span class="ym_backheader_title">编辑交付条件</span></li>
        <li style="text-align:right"
            onclick="saveCondition($('#cdId').val(),$('#cdType').val(), $('#ym_param_label').val(), $('#ym_param_value').val(), $('#ym_param_text').val())">
            <span class="ym_backheader_btn">保存&nbsp;&nbsp;</span>
            <i class="am-icon-save"></i>
        </li>
    </ul>
<input type="hidden" id="cdId" value="{{$condition->id}}" />
<input type="hidden" id="cdType" value="{{$condition->type}}" />
</div>
<div class="ym_taskmg_desc">
    <i class="am-icon-info-circle"></i>&nbsp;&nbsp;您正在{{$condition->id == 0?'添加':'修改'}}“{{$condition->typeName}}”。
</div>
<div class="ym_taskmg_item_top">
    <input type="text" class="ym_taskmg_info_header_input"
    placeholder="标签" id="ym_param_label" value="{{$condition->label}}" />
</div>
@if($condition->type == 1)
<div class="ym_taskmg_item">
    日期
    <span class="ym_taskmg_item_value" id="ym_param_skill_type_show">
        <input type="date" class="ym_taskmg_info_header_input" style="text-align:right"
         id="ym_param_value" value="{{$condition->value}}" />
    </span>
</div>
@elseif($condition->type == 2)
<div class="ym_taskmg_item">
    数量
    <span class="ym_taskmg_item_value" id="ym_param_skill_type_show">
        <input type="number" class="ym_taskmg_info_header_input" style="text-align:right"
         id="ym_param_value" value="{{$condition->value}}" />
    </span>
</div>
@elseif($condition->type == 5)
<div class="ym_taskmg_item">
    上传附件<span id="select_status" style="color:#f00"></span>
    <span class="ym_taskmg_item_value" id="ym_param_skill_type_show">
    <button onclick="submitAttachment()"><i class="am-icon-file"></i></button>
        <input type="hidden" class="ym_taskmg_info_header_input" style="text-align:right"
         id="ym_param_value" value="{{$condition->attachment}}" />
    </span>
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
    <input type="file" id="ym_upload_file" onchange="fileChange(this)"
         class="oss-upload-file" name="file" accept="*/*" />
    <input type="hidden" value="" name="success_action_redirect" />
    <input type="hidden" value="201" name="success_action_status">
</form>
<script type='text/javascript'>
function uploadAttachmentCallBack(fieldName,fileName){
    $('#ym_param_value').val(fileName);
    $('#select_status').html('(上传完成)');
}
function submitAttachment(){
    $('#ym_upload_file').click();
}
function fileChange(fileControl){
    var aliForm = $('#ym_upload_form');
    var filectrl = $('#ym_upload_file');
    var $nameSeed = $('#ym_upload_name_seed');
    var token = $('#ym_upload_token').val();
    var seedCount = parseInt($nameSeed.attr('ct'))+1;
    var fileName = $nameSeed.val()+seedCount;
    var realFileName = filectrl[0].files[0].name;
    var ext = realFileName.substr(realFileName.lastIndexOf('.') + 1).toLowerCase();
    fileName = fileName + '.' + ext;
    var redirectUrl = window.location.protocol + "//" + window.location.host
            + "/img/file-callback/" + "attachment" + "/" + fileName + "/"+token;
    aliForm.find("input[name=key]").val(fileName);
    aliForm.find("input[name=content-Type]").val(getContentType(ext));
    aliForm.find("input[name='success_action_redirect']").val(redirectUrl);
    aliForm.submit();
}
function getContentType(ext){
    var data = { "bmp": "image/bmp", "gif": "image/gif", "jpe": "image/jpeg",
      "jpeg": "image/jpeg", "jpg": "image/jpeg", "png": "image/png","zip": "application/zip" };
    return data[ext];
}

</script>
@endif
<div class="ym_taskmg_split"></div>
<div class="ym_taskmg_item">
    <textarea class="ym_taskmg_textarea" style="margin-top:15px;margin-bottom:15px;"
    id="ym_param_text" rows=8 placeholder="描述">{{$condition->text}}</textarea>
</div>
<div class="ym_taskmg_error"></div>
<div class="ym_taskmg_desc">&nbsp;</div>
