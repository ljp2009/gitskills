<link rel="stylesheet" href="/assets/lib/dragula/dragula.css" />
<link rel="stylesheet" href="/css/ym_imagetext.css" />
<link rel="stylesheet" href="/css/cropper.min.css" />
<link rel="stylesheet" href="/css/ym_publish.css" />

<!-- 图文混排 -->
<div class="am-form-group">
	<input type="hidden" name="image_text_intro" validate="predeal" class="ym-publish-field" value="">
	<label>图文混排</label>
  <!-- ym-drag-able 点击拖动的点 -->
  <div class="image-text-area" id="image_text_area" placeholder="图文编辑" validate="required">
  	@if(isset($imagetext)&&is_array($imagetext))
  	@foreach($imagetext as $k=>$val)
	  	@if($val['type']=='text')
	  	<div class="text-box" placeholder="这里输入文本">
	  		<p contenteditable="true" onfocus="editText(this);" onblur="resetText(this);" class="text-area">{{$val['text']}}</p>
	  		<span class="text-delete am-icon-trash" onclick="deleteText(this);"></span>
	  	</div>
	  	@elseif($val['type']=='img')
	  	<div class="img-box">
	  		<div class="img-hole-box">
	  			<i class="am-icon-arrows-v ym-drag ym-drag-able"></i>
	  			<img src="<?php echo Config::get('app.aliOSS')['showUrl']; ?>/{{$val['src']}}" class="" alt="" />
	  			<span class="img-handel am-icon-plus" onclick="showEdit(this);"></span>
	  			<div class="img-handel-area">
			        <span class="img-edit am-icon-pencil" onclick="addTitle(this);"></span>
			        <span class="img-delete am-icon-trash" onclick="deleteImg(this);"></span>
			    </div>
		    </div>
		    <span class="img-desc">{{$val['desc']}}</span>
		</div>
	  	@elseif($val['type']=='link')
	  	<div class="link-box">
	  		<a class="link-desc" data-href="{{$val['link']}}" onclick="editLink(this);">{{$val['name']}}</a>
	  		<span class="link-delete am-icon-trash" onclick="deleteLink(this);"></span>
	  	</div>
	  	@endif
  	@endforeach
  	@elseif(isset($imagetext)&&!is_array($imagetext))
  	<div class="text-box" contenteditable="true">{{$imagetext}}</div>
  	@endif
  </div>
  <!-- 图片区域 -->
</div>

<div class="am-form-group">
<input type="hidden" name="images_value" id="images_value" value=";" />
  <ul class="am-avg-sm-3 image-text-hand">
    <li class="box" onclick="addText()"><button class="am-btn am-btn-success am-radius" type="button">文 本</button></li>
    <li class="box" id="image-text-upload" onclick="selectImage()" nameIndex ="0" 
    									nameSeed="@if(isset($nameSeed)){{$nameSeed}}@endif" 
    									maxCount="@if(isset($imageLimit)){{$imageLimit}}@else 0 @endif" 
    									imgCount="@if(isset($imageNum)){{$imageNum}}@else 0 @endif">
    	<button class="am-btn am-btn-success am-radius" type="button">图 片</button>
    </li>
    <li class="box" id="addlinkBox" onclick="addLink(this)" data-num="0"><button class="am-btn am-btn-success am-radius" type="button">链 接</button></li>
  </ul>
</div>
<!-- 删除提示 -->
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-deleteimg-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">删除该图片</div>
    <div class="am-modal-bd">
      你，确定要删除该图片吗？
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span> </div>
  </div>
</div>
<!-- 删除提示 -->
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-deletetext-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">删除文本</div>
    <div class="am-modal-bd">
      你，确定要删除该文本吗？
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
<!-- 删除提示 -->
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-deletelink-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">删除链接</div>
    <div class="am-modal-bd">
      你，确定要删除该链接吗？
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
<!-- 添加图片描述 -->
<div class="am-modal am-modal-prompt" tabindex="-1" id="my-adddesc-prompt">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">图片描述</div>
    <div class="am-modal-bd">
      <input type="text" class="am-modal-prompt-input" style="width:100%;" maxlength="30" placeholder="图片描述，不能超过30个字">
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
<!-- 编辑图片描述 -->
<div class="am-modal am-modal-prompt" tabindex="-1" id="my-editdesc-prompt">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">图片描述</div>
    <div class="am-modal-bd">
      <input type="text" class="am-modal-prompt-input" style="width:100%;" maxlength="30" placeholder="图片描述，不能超过30个字">
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
<!-- 添加链接 -->
<div class="am-modal am-modal-prompt" tabindex="-1" id="my-addlink-prompt">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">添加链接</div>
    <div class="am-modal-bd">
      <input type="text" class="am-modal-prompt-input" name="linkname" style="width:100%;" maxlength="20" placeholder="链接描述，不能超过20个字">
      <input type="text" placeholder="请填写对应链接地址..." errormessage="请输入正确的url地址" style="width:100%;" name="linkurl" class="am-modal-prompt-input am-form-field ym-publish-field">
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-addlink" style="display:inline-block;width:100%;height:100%;">确定</span>
    </div>
  </div>
</div>
<!-- 编辑链接 -->
<div class="am-modal am-modal-prompt" tabindex="-1" id="my-editlink-prompt">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">编辑链接</div>
    <div class="am-modal-bd">
      <input type="text" class="am-modal-prompt-input" name="edit-linkname" style="width:100%;" maxlength="20" placeholder="链接描述，不能超过20个字">
      <input type="text" placeholder="请填写对应链接地址..." errormessage="请输入正确的url地址" style="width:100%;" name="edit-linkurl" class="am-modal-prompt-input am-form-field ym-publish-field">
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-editlink" data-am-modal-confirm  style="display:inline-block;width:100%;height:100%;">确定</span>
    </div>
  </div>
</div>
<script src="/assets/lib/dragula/dragula.js"></script>
<script src="/js/ym_imagetext.js"></script>
