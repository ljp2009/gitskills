@for($i=0; $i<5; $i++)
	@if($scoreObj['isSys'])
		@if($i < $scoreObj['score'])
			<i class='am-icon-star ym-c-red ym-ft-15'></i>
		@else
			<i class='am-icon-star ym-c-grey ym-ft-15'></i>
		@endif
	@else
		@if($scoreObj['isLogin'])
			@if($i < $scoreObj['score'])
				<i name='{{$scoreObj['objName']}}' class='am-icon-star ym-c-red ym-ft-15' onclick='$YM_COMMON.postUserScore("{{$scoreObj['resourceName']}}", {{$scoreObj['resourceId']}}, {{$i + 1}}, {{$scoreObj['scoreId']}}, "{{$scoreObj['objName']}}")'></i>
			@else
				<i name='{{$scoreObj['objName']}}' class='am-icon-star ym-c-grey ym-ft-15' onclick='$YM_COMMON.postUserScore("{{$scoreObj['resourceName']}}", {{$scoreObj['resourceId']}}, {{$i + 1}}, {{$scoreObj['scoreId']}}, "{{$scoreObj['objName']}}")'></i>
			@endif			
		@else

			@if($i < $scoreObj['score'])
				<i name='{{$scoreObj['objName']}}' class='am-icon-star ym-c-red ym-ft-15'></i>
			@else
				<i name='{{$scoreObj['objName']}}' class='am-icon-star ym-c-grey ym-ft-15'></i>
			@endif
		@endif
	@endif
@endfor