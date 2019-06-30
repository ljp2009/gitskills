<div class="am-input-group"  id="{{$name}}">
  @if(isset($switch))
  <input id="{{$name}}_switch" name="{{$name}}_switch"
     type="hidden" value="{{isset($defSwitch)?$defSwitch:'0'}}" />
  <span id="{{$name}}_switch_txt" class="am-input-group-label "
        style="background-color:#0e90d2;border-color:#0e90d2;color:#ffffff"
        onclick="switch{{$name}}()">{{$switch[isset($defSwitch)?$defSwitch:0]}}</span>
  <script type="text/javascript">
  function switch{{$name}}(){
    var v =[
    @for($i=0;$i<count($switch);$i++)
   {{$i==0?'':','}}"{{$switch[$i]}}"
    @endfor
    ];
    var vu =parseInt($('#{{$name}}_switch').val());
    vu += 1;
    if(vu == v.length) vu = 0;
    $('#{{$name}}_switch').val(vu);
    $('#{{$name}}_switch_txt').text(v[vu]);
  }
  </script>
  @endif
  @if(isset($ico))
  <span class="am-input-group-label"><i class="am-icon-{{$ico}}"></i></span>
  @endif
  @if(isset($prefix))
  <span class="am-input-group-label">{{$prefix}}</span>
  @endif
  <input type="{{isset($type)?$type:'text'}}" class="am-form-field" name='{{$name}}'
         placeholder="{{$placeholder}}" value="{{isset($defValue)?$defValue:''}}" />
  @if(isset($postfix))
  <span class="am-input-group-label">{{$postfix}}</span>
  @endif
</div>
