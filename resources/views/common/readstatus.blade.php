@if(Auth::check())
	<button type="button" id="{{$readObj['objName']}}-readed" class="am-btn am-btn-default am-radius @if($model->getIpUserStatus->status == 'readed')ym-btn-check-active @else ym-btn-check @endif" data-read="readed"  onclick='$YM_COMMON.postReading({{$readObj["resourceId"]}}, "{{$readObj['objName']}}-readed")'>
		我看过
	</button>
	<button type="button" id="{{$readObj['objName']}}-reading" class="am-btn am-btn-default am-radius @if($model->getIpUserStatus->status == 'reading')ym-btn-check-active @else ym-btn-check @endif" data-read="reading" onclick='$YM_COMMON.postReading({{$readObj['resourceId']}}, "{{$readObj['objName']}}-reading")'>
		正在看
	</button>
@else
	<button type="button" class="am-btn am-btn-default am-radius ym-btn-check" data-read="readed">
		我看过
	</button>
	<button type="button" class="am-btn am-btn-default am-radius ym-btn-check" data-read="reading">
		正在看
	</button>
@endif