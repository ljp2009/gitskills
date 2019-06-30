@extends('layouts.publish')
@section('title',  $title)
@section('formrange')
<link rel="stylesheet" href="/assets/cropper/cropper.min.css" />
<link rel="stylesheet" href="/assets/uploadimg/scImageEditer.css" />
<link rel="stylesheet" href="/css/formpage.css" />
<?php
    $oneform = Publish::form('/pub/submit', $title);
?>
@if(isset($relateType))
    <div class="am-form-group"  id="ipcard-container">
    </div>
    <input name="relateType" type="hidden" value="{{$relateType}}" />
@endif
<div class="am-form-group" style="padding-top:1rem">
    @include('partview.publish.iptgroup',['name'=>'proTitle','type'=>'text',
        'placeholder'=>'请填写标题','defValue'=>$originData->name,
        'switch'=>['转载','原创'], 'defSwitch'=>$originData->is_original])
</div>
<?php
    $oneform->addComp(array(
        'name' => 'intro',
        'defaultValue' => $originData->intro,
        'type' => Publish::$TP_TEXTAREA,
        'validators' => array(Publish::$VAL_REQUIRED),
        'placeholder' => '请填写内容',
        'errorMessage' => '请填写内容', ));
    ?>
    <input name="image" type="hidden" placeholder="" maxct = "9" />
    <div class="ym_fp_row" style="padding:0;border:none;margin:10px 0;">
        <input name="image" type="hidden"
            placeholder=""
            maxct = "9" />
        <div class="imgValue" id="addImg">
            <img src="/imgs/imgbtn.jpg" />
        </div>
    </div>
<?php
   $oneform->addComp(array(
        'name' => 'linktext',
        'type' => Publish::$TP_TEXT,
        'defaultValue' => is_null($originData->link) ? '' : $originData->link['show'],
        'validators' => array('linktext', 'linkLength'),
        'placeholder' => '请填写链接名称...', ));
    $oneform->addComp(array(
        'name' => 'linkurl',
        'type' => Publish::$TP_TEXT,
        'defaultValue' => is_null($originData->link) ? '' : $originData->link['link'],
        'validators' => array('linkurl'),
        'placeholder' => '请填写链接地址...',
        'errorMessage' => '请填写链接地址', ));
   $oneform->addComp(array(
        'name' => 'id',
        'defaultValue' => $originData->id,
        'type' => Publish::$TP_HIDDEN, ));

    ?>
<input type="hidden" name="act_id" value="{{$act_id or -1}}">
@if(!isset($relateType))
    <div class="am-form-group" style="margin-bottom:5px">
        <div onclick="showSelectIp()" id="ipcard-container" style="position:relative"> </div>
    </div>
    <div class="am-form-group" id="iprelate"style="margin-bottom:5px;height:37px">
        @include('partview.publish.btngroup',['name'=>'relateType','label'=>'',
            'items'=>['coll'=>'同人作品','peri'=>'周边产品','disc'=>'长评论'],
            'default'=>is_null($originData->relate_type)?'coll':$originData->relate_type])
    </div>
@endif
<div class="am-modal am-modal-alert" tabindex="-1" id="_errorMessageDialog">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">提示</div>
    <div class="am-modal-bd">
        <span id="_errorMessage"></span>
    </div>
    <div class="am-modal-footer">
        <span class="am-modal-btn">确定</span></div>
  </div>
</div>
@if(!isset($ip))
<div class="am-modal am-modal-prompt" tabindex="-1" id="my-prompt">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">选择相关作品</div>
    <div class="am-modal-bd">
        <input id="keywd" type="text" class="am-modal-prompt-input"
        style="margin:0 0 5px 0;width:100%" placeholder="请输入作品名称" onchange="searchIp()"/>
        <div id="iplist" class="ym-select-iplist">
        </div>
    </div>
  </div>
</div>

@endif
<?php
    $oneform->end('发布');
?>
@stop
@section('scriptref')
<script src="/assets/cropper/cropper.min.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>
<script src="/js/imagefieldcontrol.js"></script>
@stop

@section('scriptrange')
//<script >
function postForm(){
    $YN_VALIDATOR.submitForm();
}
function submitForm(){
    $("#formLogin").submit();
}
function makeIpCard(name, ipInfo){
    var mainDiv = $('<div id="'+name+'_'+ipInfo.id+'" class="ym-pub-ipcard"><div>');
    mainDiv.append('<img src="'+ipInfo.cover+'" class="ym-pub-ipcard-img" />');
    var infoDiv =$('<div class="ym-pub-ipcard-info"></div>');
    infoDiv.append('<span class="ym-pub-ipcard-info-title">'+ipInfo.name+'</span>');
    infoDiv.append('<span class="ym-pub-ipcard-info-text">'+ipInfo.cardInfo+'</span>');
    infoDiv.append('<input type="hidden" name="'+name+'" value="'+ipInfo.id+'">');
    mainDiv.append(infoDiv);
    return mainDiv;
}
function loadIpCard(ipInfo){
    var ipcard = makeIpCard('ipcard',ipInfo);
    var container = $('#ipcard-container');
    container.html('');
    container.append(ipcard);
    return container;
}
function sellSwitch(){
    var switchControl = $('input[name="issell"]:checked');
    var checkValue = switchControl.length>0 ? switchControl.val():'0';//默认不售卖
    if(checkValue == '0'){
        $('input[name="price"]').parent().hide();
        $('textarea[name="sellintro"]').parent().hide();
    }
    else{
        $('input[name="price"]').parent().show();
        $('textarea[name="sellintro"]').parent().show();
    }
}
@if(!isset($relateType))
    function selectIp(ipInfo){
        var container = loadIpCard(ipInfo);
        container.append('<div class="ym-pub-ipcard-flag" onclick="clearIp();event.stopPropagation();">清除</div>');
        $('#iprelate').show();
        $('#my-prompt').modal('close');
    }
    function clearIp(){
        loadIpCard({ 'id':0, 'cover':'http://umeiii.img-cn-hangzhou.aliyuncs.com/default.jpg@64w_64h_1e_1c', 'name':'点击选择相关作品',
                 'cardInfo':'您的作品会显示在相关组品的同人或者周边中。' });
        $('#iprelate').hide();
    }
    function showSelectIp(){
        $('#my-prompt').modal('open');
    }
    function searchIp(){
        var kw = $('#keywd').val();
        if(kw == '') return;
        $.post('/pub/quick-search',{
            '_token':$('meta[name="csrf-token"]').attr('content'),
            'keywd':kw
            },function(ips){
            var container = $('#iplist');
            container.html('');
            for(var i=0; i< ips.length;i++){
                var ipItem= $('<div class="ym-select-ipitem"></div>');
                ipItem.append(makeIpCard('iplistItem',ips[i]));
                container.append(ipItem);
                ipItem.on('click',makeSelectIpClick(ips[i]));
            }
        }).error(function(a){
            alert(a);
        });
    }
    function makeSelectIpClick(ipInfo){
        return function (){
                selectIp(ipInfo);
        };
    }
    @if(!isset($ip))
        clearIp();
    @else
        selectIp({ 'id':{{$ip->id}}, 'cover':'{{$ip->cover->getPath(1,'220h_186w_1e_1c')}}', 'name':'{{$ip->name}}', 'cardInfo':'{{$ip->cardInfo}}' });
    @endif
@else
    loadIpCard({ 'id':{{$ip->id}}, 'cover':'{{$ip->cover->getPath(1,'220h_186w_1e_1c')}}', 'name':'{{$ip->name}}', 'cardInfo':'{{$ip->cardInfo}}' });
@endif
$YN_VALIDATOR.validators['titleLength']=
    [function(v){
        return v.length <=30;
    },'标题长度不要多于30个字。'];
$YN_VALIDATOR.validators['linkLength']=
    [function(v){
        return v.length <=20;
    },'链接名称不要多于20个字。'];
$YN_VALIDATOR.validators['linktext']=
    [function(v){
        if(v.length == 0) {
           var url =  $('input[name="linkurl"]').val();
           if(url.length > 0){ return false; }
        }
        return true;
    },'请填写链接名称。'];
$YN_VALIDATOR.validators['linkurl']=
    [function(v){
        if(v.length == 0) {
           var text =  $('input[name="linktext"]').val();
           if(text.length > 0){ return false; }
        }
        return true;
    },'请填写链接地址。'];
$YN_VALIDATOR.validators['titleLength']=
    [function(v){
        return v.length <=30;
    },'标题请不要多余30个字。'];
$YN_VALIDATOR.validators['sellprice']=
    [function(v){
        var switchControl = $('input[name="issell"]:checked');
        var checkValue = switchControl.length>0 ? switchControl.val():'0';//默认不售卖
        if(checkValue == '1'){
          return  parseInt(v) > 0;
        }
        return true;
    },'售卖金额需要大于0。'];
$YN_VALIDATOR.validators['sellintro']=
    [function(v){
        var switchControl = $('input[name="issell"]:checked');
        var checkValue = switchControl.length>0 ? switchControl.val():'0';//默认不售卖
        if(checkValue == '1'){
          return  v.length > 0;
        }
        return true;
    },'请填写售卖说明。'];
$('input[name="issell"]').on('change',sellSwitch);
$('input[name="proTitle"]').addClass('ym-publish-field');
$('input[name="proTitle"]').attr('validate','required,titleLength');
$('input[name="price"]').addClass('ym-publish-field');
$('input[name="price"]').attr('validate','sellprice');
bindImageField('production');
@foreach($originData->image as $img)
    addImgValue('{{$img->originName}}','{{$img->getPath(1,"64w_64h_1e_1c")}}')
@endforeach
$('textarea[name="intro"]').attr('rows',7);
@stop
