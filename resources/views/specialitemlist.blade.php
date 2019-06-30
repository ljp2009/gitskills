@extends('layouts.list')
@section('listcontent')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home', 'pageTitle'=>'专辑内容'])
    <style type="text/css">
    .ym_special_header{
        position:relative;
        width:100%;
    }
    .ym_special_header>img{
        width:100%;
    }
    .ym_special_header>label{
        position:absolute;
        bottom:0;
        left:0;
        width:100%;
        text-align:right;
        font-size:1.4rem;
        background-color:rgba(255,255,255,0.6);
        color:#383838;
        margin:0;
        padding:10px;
    }
    .ym_special_header>label>b{
        font-size:2rem;
        margin-bottom:10px;
    }
    </style>
    <div class="ym_special_header">
        <img src="{{$special->img->getPath(1, '430w_270w_1e_1c')}}" />
        <label>
            <b>{{$special->name}}</b>
            </br>
            {{$special->intro}}
        </label>
    </div>
	<div class="ym-line-list ym-line-list-group" id="deflist">
	</div>
@stop

@section('bindlist')
	list.bind({
		"container":"#deflist",
		"type":"special",
		"loadSize":10,
		"maxSize":100,
        "itemFeature":".ym-line-list-item",
		"listName":"detail-{{$special->id}}",
		"pageIndex":{{ $page }}
	});
@stop
