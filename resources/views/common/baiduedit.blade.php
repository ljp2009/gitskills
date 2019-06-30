@extends('layouts.master')
@section('title',  '百度百科数据编辑')
<link rel="stylesheet" type="text/css" href="/css/baidueditor.css">
@section('content')
<div class="ym-help-pane">帮助<a href="javascript:$('.ym-help-pane').css('display','none')" class="am-close am-close-spin" style="float:right">&times;</a></div>
<div class="ym-editcontainer">
<?php
    $index = 0;

    function echoBlockToSmallPieces($block, $ind)
    {
        $html = '';
        $str = '';
        for ($i = 0; $i < mb_strlen($block); ++$i) {
            $char = mb_substr($block, $i, 1);
            if (Utils::isSpecialChar($char)) {
                if (mb_strlen($str) > 0) {
                    $html .= $str.'</span>';
                }
                $html .= $char;
                $str = '';
            } else {
                if (strlen($str) == 0) {
                    ++$ind;
                    $html .= '<span ind="'.$ind.'">';
                }
                $str .= $char;
            }
        }
        if (mb_strlen($str) > 0) {
            $html .= $str.'</span>';
        }

        return array('content' => $html, 'index' => $ind);
    }

    $ROWALIGN = 3;
    if (is_array($result)) {
        $blocks = $result['contentblock'];
        $images = $result['images'];
        $imgct = sizeof($images);
        if ($imgct > 0) {
            echo '<div class="am-g">
			';
            $left = $imgct % $ROWALIGN;
            $vspace = 12 / $ROWALIGN;
            foreach ($images as $img) {
                ++$index;

                echo '<div class="am-u-sm-'.$vspace.'" >
					<img src="'.$img.'" class="am-img-responsive ym_img_pane" ind="'.$index.'">
					</div>';
            }
            if ($left > 0) {
                for ($i = 0; $i < $left; ++$i) {
                    echo '<div class="am-u-sm-'.$vspace.'" ></div>
					';
                }
            }
            echo '</div>
			';
        }
        foreach ($blocks as $block) {
            ++$index;
            $cont = echoBlockToSmallPieces($block, $index);
            echo '<div class="ym-editunit" ind="'.$index.'">'.$cont['content'].'</div>
			';
            $index = $cont['index'];
        }
    } else {
        echo '内容已经存在！';
    }
?>
</div>
<script type="text/javascript">
	var basicInfoData = {
        <?php
            if (is_array($sysAttr)) {
                $ct = 0;
                foreach ($sysAttr as $oneattr) {
                    echo '"'.$oneattr['code'].'":"'.$oneattr['name'].'"';
                    ++$ct;
                    if ($ct < sizeof($sysAttr)) {
                        echo ',';
                    }
                }
            }
        ?>
	};
</script>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="_baseEditPane">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      <div class="am-g">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 col-sm-centered">

        </div>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="_prev_zpm" value="{{$zpm}}">
<input type="hidden" id="_prev_fmt" value="{{$fmt}}">
<input type="hidden" id="_prev_zz" value="{{$zz}}">
<input type="hidden" id="_prev_jbxx" value="{{$jbxx}}">
<input type="hidden" id="_prev_nrms" value="{{$nrms}}">
<input type="hidden" id="_prev_zj" value="{{$zj}}">
<input type="hidden" id="_prev_zjt" value="{{$zjt}}">
<input type="hidden" id="_prev_zjms" value="{{$zjms}}">
<div class="ym-editpane"></div>
<form id="_submitform" action="/common/baiduimport/edit2" style="display:none" method="post">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="record" id="_record">
	<input type="hidden" name="md5v" id="_md5v" value="{{$result['md5v']}}">
	<input type="hidden" name="functype" value="{{$functype}}">
</form>
	<!--[if (gte IE 9)|!(IE)]><!-->
	<script src="/assets/js/jquery.min.js"></script>
	<!--<![endif]-->
	<!--[if lte IE 8 ]>
	<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
	<script src="assets/js/amazeui.ie8polyfill.min.js"></script>
	<![endif]-->
	<script src="/assets/js/amazeui.min.js"></script>
	<script src="/js/ym_public.js"></script>
	<script src="/js/ym_baidueditor.js"></script>
	<script type="text/javascript">
	$(function(){
		var $YM_BAIDUEDITOR = new ym_baidu_editor("{{$result['record']}}", "{{$result['md5v']}}", "{{$type}}", basicInfoData);
	});
	</script>
@stop