@extends('layouts.publish')
@section('title',  '喜欢的动漫')
@section('formrange')
<?php 
    $oneform = Publish::form('/user/adduserprefrence', '您喜欢的动漫');

    $oneform->addComp(array('name' => 'uid',
            'type' => Publish::$TP_HIDDEN,
            'defaultValue' => Auth::user()->id, ));
?>

<div id="ym-prefrence">
	 <ul class="am-avg-sm-3 am-thumbnails" style="">
	 	<div>
			<label style="font-size: 1.4rem;border-bottom: 0.1rem #e2e2e2 solid;width:100%;padding-bottom:0.5rem;margin-bottom:1rem;">您的喜欢的动漫</label>
		</div>
	 	@foreach($cartoon as $key=>$value)
	 	<li style="padding:0px 3px 0px 3px;position: relative;">
	 		<div style="position: relative;">
	 			<img src="{{ $value->imagePath->getPath(1,'250w_300h_1e|250x300-2rc')}}" class="am-thumbnail" style="margin-bottom:3px;padding:0rem;border-radius:0.2rem;" >
	 			<label style="position: absolute;width: 100%;height:100%;left:0;top:0;background:#fff;opacity:0.6;">
                </label>
	 		</div>
            <span style="display:block;text-align:center;font-size:1.3rem;">{{$value->name}}</span>
            <input type="checkbox" name="prefrence[]" value="{{$value->id}}" id="prefrence_{{$value->id}}" style="position:absolute;z-index:-1;visibility: hidden;" />
        </li>
        @endforeach
	 </ul>
	 <ul class="am-avg-sm-3 am-thumbnails" style="display:none;">
	 	<div>
			<label style="font-size: 1.4rem;border-bottom: 0.1rem #e2e2e2 solid;width:100%;padding-bottom:0.5rem;margin-bottom:1rem;">您的喜欢的作品</label>
		</div>
	 	@foreach($story as $key=>$value)
	 	<li style="padding:0px 3px 0px 3px;position: relative;">
	 		  <div style="position: relative;">
                <img src="{{ $value->imagePath->getPath(1,'250w_300h_1e|250x300-2rc')}}" class="am-thumbnail" style="margin-bottom:3px;padding:0rem;border-radius:0.2rem;" >
                <label style="position: absolute;width: 100%;height:100%;left:0;top:0;background:#fff;opacity:0.6;">
                </label>
	 		  </div>
              <span style="display:block;text-align:center;font-size:1.3rem;">{{$value->name}}</span>
              <input type="checkbox" name="prefrence[]" value="{{$value->id}}" id="prefrence_{{$value->id}}" style="position:absolute;z-index:-1;visibility: hidden;" />         
        </li>
        @endforeach
	 </ul>
	 <ul class="am-avg-sm-3 am-thumbnails" style="display:none;">
	 	<div>
			<label style="font-size: 1.4rem;border-bottom: 0.1rem #e2e2e2 solid;width:100%;padding-bottom:0.5rem;margin-bottom:1rem;">您的喜欢的游戏</label>
		</div>
	 	@foreach($game as $key=>$value)
	 	<li style="padding:0px 3px 0px 3px;position: relative;" >
              <div style="position: relative;">
              	<img src="{{ $value->imagePath->getPath(1,'250w_300h_1e|250x300-2rc')}}" class="am-thumbnail" style="margin-bottom:3px;padding:0rem;border-radius:0.2rem;" >
	              <label style="position: absolute;width: 100%;height:100%;left:0;top:0;background:#fff;opacity:0.6;">
	              </label>
              </div>
              <span style="display:block;text-align:center;font-size:1.3rem;">{{$value->name}}</span>
              <input type="checkbox" name="prefrence[]" value="{{$value->id}}" id="prefrence_{{$value->id}}" style="position:absolute;z-index:-1;visibility: hidden;" />        
        	  
        </li>
        @endforeach
	 </ul>
</div>
<div  id="ym-prefrence-handel" class="ym-prefrence-handel">
	<div >
		<button class="am-btn am-btn-warning am-btn-block ym-next-step">下一步</button>
		<a class="am-btn am-btn-default am-btn-block ym-next-skip"style="width:100%;">跳过</a>
		<div class="am-cf"></div>
	</div>
	<div style="display:none;">
		<button class="am-btn am-btn-warning am-btn-block ym-next-step">下一步</button>
		<button class="am-btn am-btn-warning ym-prev-step">上一步</button>
	 	<a class="am-btn am-btn-default ym-next-skip">跳过</a>
	 	<div class="am-cf"></div>
	</div>
	<div style="display:none;">
		
		<button class="am-btn am-btn-warning am-btn-block am-ok">确定</button>
		<button class="am-btn am-btn-warning ym-prev-step">上一步</button>
		<a class="am-btn am-btn-default" style="width: 48%;float: right;margin-top: 1.5rem" href="/auth/createskill/{{Auth::user()->id}}">跳过</a>
	</div>
</div>
<script type="text/javascript" src="/js/ym_rule.js"></script>
@section('scriptrange')
	$(function(){
		$('#ym-prefrence li').on('click',function(){
			$this = $(this);
			if(!$this.hasClass('am-checked')){
				$this.addClass('am-checked');
				$this.find('input').prop('checked',true);
				$this.find('label').css('opacity',"0");
			}else{
				$this.removeClass('am-checked');
				$this.find('input').prop('checked',false);
				$this.find('label').css('opacity',"0.6");
			}
		});
		var arr = ['您喜欢的动漫','您喜欢的小说','您喜欢的游戏'];
		$('#ym-prefrence-handel .ym-next-step').on('click',function(){
			var index = $(this).parent().index();
			var num = $('#ym-prefrence ul:eq('+index+')').find('input:checked').length;
			if(num>0){
				$('.am-titlebar-title').text(arr[index+1]);
				$('#ym-prefrence ul').removeClass('active-prev').removeClass('active-next').removeClass('active-prev-step').removeClass('active-next-step').css('display','none');
				$('#ym-prefrence-handel>div').removeClass('active-prev').removeClass('active-next').removeClass('active-prev-step').removeClass('active-next-step').css('display','none');
				$(this).parent().addClass('active-prev');
				$(this).parent().next().addClass('active-next');
				$('#ym-prefrence ul:eq('+index+')').addClass('active-prev');
				$('#ym-prefrence ul:eq('+(index+1)+')').addClass('active-next');
				return false;
			}else{
				$YN_VALIDATOR.handleErrorMessage('请选择'+arr[index]);
				return false;
			}
		});
		$('#ym-prefrence-handel .ym-prev-step').on('click',function(){
			var index = $(this).parent().index();
			var num = $('#ym-prefrence ul:eq('+index+')').find('input:checked').length;
			$('#ym-prefrence ul').removeClass('active-prev').removeClass('active-next').removeClass('active-prev-step').removeClass('active-next-step').css('display','none');
			$('#ym-prefrence-handel>div').removeClass('active-prev').removeClass('active-next').removeClass('active-prev-step').removeClass('active-next-step').css('display','none');
			$('.am-titlebar-title').text(arr[index-1]);
			$(this).parent().addClass('active-prev-step');
			$(this).parent().prev().addClass('active-next-step');
			$('#ym-prefrence ul:eq('+index+')').addClass('active-prev-step');
			$('#ym-prefrence ul:eq('+(index-1)+')').addClass('active-next-step');
			return false;
		});
		$('#ym-prefrence-handel .ym-next-skip').on('click',function(){
			var index = $(this).parent().index();
			$('.am-titlebar-title').text(arr[index+1]);
			$('#ym-prefrence ul').removeClass('active-prev').removeClass('active-next').removeClass('active-prev-step').removeClass('active-next-step').css('display','none');
			$('#ym-prefrence-handel>div').removeClass('active-prev').removeClass('active-next').removeClass('active-prev-step').removeClass('active-next-step').css('display','none');
			$(this).parent().addClass('active-prev');
			$(this).parent().next().addClass('active-next');
			$('#ym-prefrence ul:eq('+index+')').addClass('active-prev');
			$('#ym-prefrence ul:eq('+(index+1)+')').addClass('active-next');
			return false;
		});
		$('.am-ok').on('click',function(){
			var index = $(this).parent().index();
			var num = $('#ym-prefrence ul:eq('+index+')').find('input:checked').length;
			if(num>0){
				$YN_VALIDATOR.submitForm();
			}else{
				$YN_VALIDATOR.handleErrorMessage('请选择'+arr[index]);
				return false;
			}
		});
	});
	
@stop
@stop
