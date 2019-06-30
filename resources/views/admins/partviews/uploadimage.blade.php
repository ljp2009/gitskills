<div style="display:none">
    <iframe style="display:none" name='hiddenFrame'></iframe>
    <form style="display:none" id="_uploadImageForm" target="hiddenFrame"
          action="{{$st['postUrl']}}" method="post" enctype="multipart/form-data">
        <input name="OSSAccessKeyId"          type="hidden" value="{{$st['ossAccessKeyId']}}" />
        <input name="policy"                  type="hidden" value="{{$st['policy']}}" />
        <input name="signature"               type="hidden" value="{{$st['signature']}}" />
        <input name="seed"                    type="hidden" value="{{$st['nameSeed']}}" />
        <input name="success_action_status"   type="hidden" value="201" />
        <input name="content-Type"            type="hidden" value="" />
        <input name="key"                     type="hidden" value="" />
        <input name="success_action_redirect" type="hidden" value="" />
        <input name="file"                    type="file"   id="_uploadAttachFile"
               accept="aplication/zip" onchange="ImageController.fileChange()" />
    </form>
</div>
