@extends('layouts.formpage')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'post', 'pageTitle'=>$title])
<link rel="stylesheet" href="/assets/cropper/cropper.min.css" />
<link rel="stylesheet" href="/assets/uploadimg/scImageEditer.css" />
<link rel="stylesheet" href="/css/scContentEditor.css" />
<div style="padding:10px;width:100%;min-height:100%;">
    <div class="ym_fp_pub_row">
        <input id="act_id" type="hidden"
             value="{{$act_id or 0}}" />
        <input id="p_id" type="hidden"
             value="{{isset($originData)?$originData->id:0}}" />
        <input id="p_name" type="text"
            maxlength="50"
            placeholder="作品的标题"
            value="{{isset($originData)?$originData->name:''}}" />
        <button id="is_origin_btn" class='ym_fp_change_btn' origin='1'>原创<i class="ymicon-order"></i></button>
    </div>
    <div class="ym_fp_pub_row" >
        <input id="relate_ip" type="text"
            maxlength="50"
            placeholder="选择相关IP（可选）"
            value="" />
        <button id="relate_type_btn" class='ym_fp_change_btn' origin='disc'>长评<i class="ymicon-order"></i></button>
    </div>
    <label class="sc_ce_title" >作品内容</label>
    <div id="content" style= "width:100%" ></div>
</div>
<script src="/assets/cropper/cropper.min.js"></script>
<script src="/assets/exif/exif.js"></script>
<script src="/assets/uploadimg/scUploadImage.0.2.js"></script>
<script src="/assets/uploadimg/scAliOssHandler.js"></script>
<script src="/js/scContentEditor.js"> </script>
<script src="/js/publish_production.js"> </script>
<script type="text/javascript">
var $contentEditor = $('#content').scContentEditor({});
$contentEditor.bindEvent('save', function(par){});
$contentEditor.bindEvent('remove', function(par){});

var $isOriginBtn = $('#is_origin_btn').switchBtn({
    items : [ {key:'1', text:'原创'}, {key:'0', text:'转载'} ],
    value : '1',
});
var $relateTypeBtn = $('#relate_type_btn').switchBtn({
    items : [ {key:'disc', text:'长评'}, {key:'coll', text:'同人'}, {key:'peri', text:'周边'}],
    value : "{{isset($relateType)?$relateType:'disc'}}",
});
var $searchCombo = $('#relate_ip').searchCombo({});
@if(isset($ip))
$searchCombo.setValue({
    id    :  {{ $ip['id']    }},
    cover : "{{ $ip['cover'] }}",
    name  : "{{ $ip['name']  }}"
});
@endif
@if(isset($originData))
$contentEditor.loadValue([
    @foreach($originData->contents as $content)
    {
        id     : {{$content->id}},
        order  : {{$content->order}},
        status : {{$content->status}},
        type   : "{{$content->type}}",
        text   : "{!!$content->textForEdit!!}",
        url    : "{!!$content->url!!}"
    },
    @endforeach
]);
@endif
function postForm(){
    var name = $('#p_name').val();
    $('#p_name').parent().removeClass('error');
    $('#p_name').parent().parent().find('.ym_fp_pub_err_row').remove();
    if(name == ''){
        $('#p_name').parent().addClass('error');
        $('#p_name').parent().after('<div class="ym_fp_pub_err_row">请填写作品标题。</div>');
    }
    var isOrigin    = $isOriginBtn.getValue();
    var ip          = $searchCombo.getValue();
    var relatedType = $relateTypeBtn.getValue();
    var contents     = $contentEditor.getValue();
    $('.sc_ce_title').removeClass('error');
    if(contents.length ==0){
        $('.sc_ce_title').addClass('error');
    }
    $.post('{{$post}}', {
        'id':$('#p_id').val(),
        'act_id':$('#act_id').val(),
        'name':name, 
        'is_origin':isOrigin,
        'ip':ip,
        'related_type':relatedType,
        'contents':contents,
        '_token':$.ymFunc.getToken(),
    }, function(data){
        if(data.res){
            $.ymFunc.goTo(data.url)
        }else{
            $.ymNotice.show(data.info);
        }
    }).error(function(err){
        alert(err.responseText);
    });
}
</script>
@stop
