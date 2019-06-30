@foreach($data as $val)
<div class="ym_cm_card ym_notice_normal_item ym_listitem" msgId="{{$val->id}}">
    <div class="ym_user">
        <img>
        <label></label>
        <span></span>
    </div> 
    <div class="ym_text">
        {{$val->msg}}
    </div> 
    <div class="ym_reference">
        @if(!is_null($val->reference['image']))        
            <img src="{{$val->reference['image']->getPath(1, '64h_64w_1e_1c')}}">
        @endif
        <label>
        {{$val->reference['name']}}
        </label>
    </div> 
</div>
@endforeach
