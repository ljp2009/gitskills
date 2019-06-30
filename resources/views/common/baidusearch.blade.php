@extends('layouts.publish')
@section('title',  '百度百科查询')
@section('formrange')
@include('partview.detailheader',array('hideShare'=>true))
<div class="am-container" style="padding:0 2rem">
<div class="am-g">
	<div class=" col-md-2"></div>
 	<div class=" col-md-8 col-sm-centered">
 		<?php 

            if (!isset($zpm)) {
                $zpm = '';
            }
            if (!isset($fmt)) {
                $fmt = '';
            }
            if (!isset($zz)) {
                $zz = '';
            }
            if (!isset($nrms)) {
                $nrms = '';
            }
            if (!isset($zj)) {
                $zj = '';
            }
            if (!isset($jbxx)) {
                $jbxx = '';
            }
            if (!isset($zjt)) {
                $zjt = '';
            }
            if (!isset($zjms)) {
                $zjms = '';
            }
            if (!isset($type)) {
                $type = '';
            }

            if (strlen($type) == 0) {
                $legend = '百度百科查询';
            } elseif ($type == 'author') {
                $legend = '查找基本信息';
            } elseif ($type == 'hero') {
                $legend = '查找主角';
            }
            echo '<legend>'.$legend.'</legend>';

            if (!isset($functype)) {
                echo '<p><label for="selectFunctype">选择类型</label>
 				';
                echo '<select onchange="verifyChange()" class="am-form-field ym-publish-field" id="selectFunctype">
 				';
                echo '<option value="cartoon" selected>动漫</option>';
                echo '<option value="story">小说</option>';
                echo '<option value="game">游戏</option>';
                echo '</select></p>';
                $functype = 'cartoon';
            }

        ?>
		<p> 
			<label for="searchInput">输入词条</label>
		<?php
            $value = '';
            if ($type == 'author') {
                $value = $zz;
            } elseif ($type == 'hero') {
                $value = $zj;
            }
            echo '<input type="text" id="searchInput" class="am-form-field am-radius" placeholder="输入词条..." value="'.$value.'"/>'
        ?>
		</p>
		<div>
			<button class="am-btn am-btn-primary" onclick="doSearch()">
			<i class="am-icon-search"></i>
			  搜索			  
			</button>
		</div>
 		<div id="options">
		</div>
 	</div>
 	<div class=" col-md-2"></div>
<?php 

    $oneform = Publish::form('/common/baiduimport/parse', '', false);
    $oneform->addComp(array('name' => 'url', 'type' => Publish::$TP_HIDDEN));
    $oneform->addComp(array('name' => 'option', 'type' => Publish::$TP_COMBO, 'label' => '选项',
        'selectables' => array('0', '1', '2'), 'selectlabels' => array('存在则什么都不作', '存在则从本地文件加载', '存在则重来'),
        'defaultValue' => '1', ));
    $oneform->addComp(array('name' => 'functype', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $functype));
    $oneform->addComp(array('name' => 'zpm', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $zpm));
    $oneform->addComp(array('name' => 'fmt', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $fmt));
    $oneform->addComp(array('name' => 'zz', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $zz));
    $oneform->addComp(array('name' => 'jbxx', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $jbxx));
    $oneform->addComp(array('name' => 'nrms', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $nrms));
    $oneform->addComp(array('name' => 'zj', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $zj));
    $oneform->addComp(array('name' => 'zjt', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $zjt));
    $oneform->addComp(array('name' => 'zjms', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $zjms));
    $oneform->addComp(array('name' => 'type', 'type' => Publish::$TP_HIDDEN, 'defaultValue' => $type));
    echo '<div class="am-g"><button class="am-btn am-btn-primary am-btn-block" onclick ="$YN_VALIDATOR.submitForm()"><i class="am-icon-magic"></i>&nbsp;解析</button></div>';
    $oneform->end(array());
?>
<p>&nbsp;</p>
 	<div class=" col-md-2"></div>
 	<div class=" col-md-8 col-sm-centered">
 		<p>
 			<label for="searchInput">贴入百度百科链接</label>
 			<input type="text" id="_baidubaikeInput" class="am-form-field am-radius" placeholder="贴入百度百科链接...">
 		</p>
 		<div>
			<button class="am-btn am-btn-primary" onclick="doSearch(1)">
			<i class="am-icon-search"></i>
			  解析该链接			  
			</button>
		</div>
 	</div>
 	<div class=" col-md-2"></div>
</div>
</div>
<script type="text/javascript">
	function verifyChange(){
		$('input[name=functype]').val($('#selectFunctype')[0].value);
	}

	function attachReturnResults(data){
		var str = "";
		var radioname = "optionvalue";
		var ct = 0;
		var defaultv = '';
		for(var key in data){
			str += "<input type='radio' name='" + radioname +"' ref='" + data[key].href +"' ";
			if(ct == 0){
				str += "checked";
				defaultv = data[key].href;
			}
			str += ">&nbsp;";
			str += "<a href='" + data[key].href + "' target='_blank'>" + data[key].content + "</a><br/>";
			ct ++;

		}
		$('#options').html(str);
		$('[name=url]').val(defaultv);
		$('[name=' + radioname +']').each(function(ind){
			var oneradio = $(this);
			oneradio.on('click', function(){
				$('[name=url]').val(oneradio.attr('ref'));
			});
		});
		$('form').css('display', 'block');
	}

	function doSearch(opt){
		if(typeof(opt) == 'undefined'){
			opt = 0;
		}
		if(opt == 0){
			var inputobj = $('#searchInput');
			if($YN_VALIDATOR.stringtrim(inputobj.val()).length == 0){
				$YN_VALIDATOR.handleErrorMessage('词条不能为空！');
				return;
			}else{
				$.post('/common/baiduimport/search', {'_token':$('meta[name="csrf-token"]').attr('content'), 
					'search':inputobj.val()}, 
					function(data){
						attachReturnResults(data);
					}, "json");	
			}			
		}else{
			var inputobj = $('#_baidubaikeInput');
			var v = inputobj.val();
			if($YN_VALIDATOR.stringtrim(v).length == 0){
				$YN_VALIDATOR.handleErrorMessage('输入链接不能为空!');
				return;
			}else if(v.indexOf('wapbaike.baidu.com')<0){
				$YN_VALIDATOR.handleErrorMessage('输入链接域名必须为"wapbaike.baidu.com"!');
				return;
			}		
			$('[name=url]').val(v);	
			$('form').submit();
		}
	}
</script>
@stop