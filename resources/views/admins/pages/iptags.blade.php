@extends('admins.layouts.admin')
@section('detailcontent')
<link rel="stylesheet" href="/css/admin/admin.css">
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['数据管理', '作品管理','作品标签'], 'searchControl'=>'/admin/ip/list'])
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">操作</a></li>
        </ul>
    </div>
    <div class="am-tabs-bd">
        <div class="am-g am-margin-top">
          <div class="am-u-sm-4 am-u-md-2 am-text-right">操作</div>
          <div class="am-u-sm-8 am-u-md-10">
            设置作品<b>《{{$ip->name}}》</b>的标签。
            <br />
            当前的标签是：<label id="tag_label">{{implode('&nbsp;&nbsp;&nbsp;&nbsp;',$ip->tags)}}</label>
          </div>
        </div>
        <div class="am-g am-margin-top">
        </div>
        <div class="am-g am-margin-top">
          <div class="am-u-sm-4 am-u-md-2 am-text-right">{{$ip->type}}</div>
          <div class="am-u-sm-8 am-u-md-10">
            @foreach($tags as $tag)
            <div class="div_tag">
                <button class="div_tag_text" value="{{ $tag->code }}" onclick="switchTag(this)">{{ $tag->name }}</button>
                @if(in_array($tag->name, $ip->tags))
                <button class="div_tag_btn selected" value="{{ $tag->name }}" >O</button>
                @else
                <button class="div_tag_btn" value="{{ $tag->name }}" onclick="">&nbsp;</button>
                @endif
            </div>
            @endforeach
          </div>
        </div>
    </div>
</div>
@include('admins.partviews.modalcontrols')
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/admin/iprelate.js"></script>
<script type="text/javascript" charset="utf-8">
function switchTag(obj){
    var $this = $(obj);
    var $ct = $this.parent().find('.div_tag_btn');
    if($ct.hasClass('selected')){
        $ct.removeClass('selected');
        $ct.html('');
    }else{
        $ct.addClass('selected');
        $ct.html('O');
    }
    var $btns = $('button.div_tag_btn.selected');
    var value = ''; 
    $btns.each(function(){
        value += ($(this).attr('value')+';');
    });
    $.post('/admin/ip/tag-update',{
            '_token':'{{csrf_token()}}',
            'id':'{{$ip_id}}',
            'tags':value
        },
        function(data){
            if(data.res){
                $('#tag_label').html(data.tagStr);
            }
        });
    
}
</script>
@stop
