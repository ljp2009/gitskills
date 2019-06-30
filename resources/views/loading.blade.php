@extends('layouts.block')
@section('content')
	@section('serverLoad')
    @include('partview.headerbar', ['left'=>'none', 'center'=>'logo', 'right'=>'none'])
    <div class="ym_pop_shade" style="z-index:500;background-color:#fff">
        <div style="
            width:100px;
            height:100px;
            line-height:30px;
            display:inline-block;
            vertical-align:middle;
            text-align:center">
        
        </div>
    </div>
	@show
	@parent
	@section('runScript')
<script type="text/javascript">
    var $shade = $('.ym_pop_shade');
    $shade.css('line-height', $shade.height()+'px');
    $shade.find('div').append('<img src="http://img.umeiii.com/scene/def-1492224536030-.gif@100h_100w_1e_1c.gif"/>');
    $shade.find('div').append('<label style="width:100px">加载中</label>');
    setTimeout(function(){
        $.post('/page/loading',{
            'funcs':'load'
        },function(data){
            console.log(data.res);
            console.log(data.info);
            console.log(data.url);
            console.log(data.debug);
            if(data.res){
                window.location = data.url;
            }else{
                history.go(0-data.info);
            }
        });
    },300);
</script>
	@stop
@stop
