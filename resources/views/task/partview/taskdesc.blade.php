<div class="info_text">
{!!$task->formatIntro!!}
@foreach ($task->image as $img)
<img style="width:100%" src="{{$img->getPath()}}" />
@endforeach
</div>
