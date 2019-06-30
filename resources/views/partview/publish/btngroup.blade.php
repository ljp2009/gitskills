@if(isset($label))
<label for="{{$name}}" class="am-btn" style="padding-left:0">{{$label}}</label>
@endif
<div id="{{$name}}" class="am-btn-group am-fr"  data-am-button>
@foreach($items as $key=>$value)
  <label class="am-btn am-btn-primary am-radius {{$key==$default?'am-active':''}}">
    <input type="radio" name="{{$name}}" value="{{$key}}" id="{{$name.'_'.$key}}" {{$key==$default?'checked=checked':''}}>{{$value}}
  </label>
@endforeach
</div>
