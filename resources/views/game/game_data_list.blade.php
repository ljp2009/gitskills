@extends('layouts.block')
<style type="text/css">
	.tableColumn{
		float:left;
		border:solid 1px #eee;
		text-align: right;
	}
	.tableHeader{
		float:left;
		border:solid 1px #eee;
		background: #ccc;
		font-weight: bold;
		text-align: center;
	}
	.tableRow{
		margin-left:10px;
	}
	#tableHeaders{
		margin-left:10px;
	}
	#theEditor{
		position:fixed;
		z-index: 9;
		border: solid 1px #22a;
		text-align:  right;
	}
</style>
@section('content')
<div>
<label for='selectableDataList'>选择数据名：</label>
<select id='selectableDataList' onchange='showInEditPane()'>
	<option value='0' selected>===选择数据名===</option>
	<?php
        $name = '';
        $jsdatas = array();
        foreach ($list as $oneresult) {
            $curname = $oneresult['name'];
            if ($name !== $curname) {
                echo "<option value='".$curname."'>".$oneresult['name_description'].'</option>';
                if (strlen($name) > 0) {
                    $jsdatas[$name] = $jsdatas[$name].']';
                }
                $jsdatas[$curname] = '[';

                $name = $curname;
            } else {
                $jsdatas[$name] = $jsdatas[$name].', ';
            }
            $len = sizeof($oneresult);
            $count = 0;
            $str = '';
            $jsdatas[$curname] = $jsdatas[$curname].json_encode($oneresult, JSON_UNESCAPED_UNICODE);
        }
        $jsdatas[$name] = $jsdatas[$name].']';
    ?>
</select>
<div>
<input type="text" id="theEditor" style="display:none" preid=""/> 
<div id="editPane">
<div>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/amazeui.min.js"></script>
<script type="text/javascript">
	var jsdatas = {};
	<?php
        foreach ($jsdatas as $k => $v) {
            echo 'jsdatas.'.$k.'='.$v.';';
        }
    ?>

	var resetValue = function(objid, dataidx){
		if(typeof(dataidx)=='undefined')
			dataidx = $('#selectableDataList')[0].value;
		var theEditor = $('#theEditor');
		if(typeof(objid)=='undefined'){
			objid = theEditor.attr('preid');
		}
		if(typeof(objid)=='undefined'||objid == '')
			return;
		var dataset = jsdatas[dataidx];
		var targetobj = $('#' + objid);
		var value = theEditor.val();
		targetobj.text(value);
		var dataidx = parseInt(targetobj.attr('datasetIdx'));
		var valueName = targetobj.attr('valueName');
		var orivalue = dataset[dataidx][valueName];
		dataset[dataidx][valueName] = value;
		if(orivalue != value){
			dataset[dataidx].changed = true;
		}

	};

	var showInEditPane = function(dataidx){
		 $('#theEditor').css('display', 'none');
		if(typeof(dataidx)=='undefined')
			dataidx = $('#selectableDataList')[0].value;
		var editpane = $('#editPane');
		if(dataidx == '0'){
			editpane.html('');
			return;
		}
		var dataset = jsdatas[dataidx];
		var width = $(document).width();
		var displaycols = {
			'diff':'diff_description',
			'data':'data_description',
			'extra_data1':'extra_data1_description',
			'extra_data2':'extra_data2_description',
			'extra_data3':'extra_data3_description'
		};
		var basedata = dataset[0];
		var sz = 0;
		var editable0 = true;
		var tablehtml = '<h2>' + basedata['name_description'] + '</h2>';
		var colhtml = '<div id="tableHeaders">';
		for(var key in displaycols){
			if(basedata[key] == 'null'||basedata[key] == null){
				break;
			}else{
				sz ++;
				var headername = basedata[displaycols[key]];
				if(headername == 'null'||headername == null||headername==' '){
					headername = '序号';
					editable0 = false;
				}
					
				colhtml = colhtml + '<div class="tableHeader">' + headername + '</div>';
			}
		}
		colhtml = colhtml + '</div>';
		tablehtml = tablehtml + colhtml;

		var avgsz = parseInt(width / sz) - 30;

		for(var i=0; i<dataset.length; i++){
			var onedata = dataset[i];
			var szct = 0;
			var rowhtml = '<div class="tableRow">';
			for(var key in displaycols){
				szct ++;
				if(szct <= sz){
					var value = onedata[key];
					var myid = 'col_' + i + '_' + szct;
					rowhtml = rowhtml + '<div class="tableColumn" datasetIdx="' + i + '" valueName="' + key+'" id="'+ myid +'" orivalue="'+value+'">' + value + '</div>';
				}else{
					break;
				}
			}
			rowhtml = rowhtml + '</div>';
			tablehtml = tablehtml + rowhtml;
		}
		editpane.html(tablehtml);
		$('.tableHeader').css('width', avgsz + 'px');
		$('.tableColumn').css('width', avgsz + 'px');
		$('.tableColumn').on('click', function(){
			var id = $(this).attr('id');
			resetValue();
			var theEditor = $('#theEditor');
			theEditor.css('display', 'block');
			theEditor.val($(this).text());
			theEditor.focus();
			theEditor.select();
			theEditor.attr('preid', id);
			var pos = $(this).position();
			theEditor.css('left', (pos.left + 1) + "px");
			theEditor.css('top', (pos.top + 1 - document.body.scrollTop) + "px");
			theEditor.css('width', avgsz + "px");
		});
	};
</script>
@stop
