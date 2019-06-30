<div class="am-modal am-modal-no-btn ym_addpanel" tabindex="-1" id="ym_add_panel">
  <div class="am-modal-dialog ym_addpanel">
    <div class="am-modal-bd" >
        <ul class="am-avg-sm-{{$cols or 2}}">
        @foreach($addFuncs as $name=>$func)
            <li><a href="{{$func}}">{{$name}}</a></li>
        @endforeach
        </ul>
    </div>
  </div>
</div>
