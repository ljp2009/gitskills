<!--角色部分-->
@foreach ($roles as $role)
<div class="ym_ip_role" onclick="$.ymFunc.goTo('/roles/{{$role->id}}')">
    <img src="{{ $role->header->getPath(2,'100h_100w_1e|100x100-2rc') }}" />
    <label>{{$role->name}}</label>
</div>
@endforeach
