<td>
<div style="width:130px;text-align:center">
@if(is_null($item->verified_by)||$item->verified_by == 0)
    <label>未审核</label>
    <br />
    <button class='am-btn am-btn-success' style="padding:0.5rem 1rem" onclick='approveItem({{$item->id}})'>通过</button>
    <button class='am-btn am-btn-danger' style="padding:0.5rem 1rem" onclick='rejectItem({{$item->id}})'>拒绝</button>
@else
    @if($item->verified)
    <label>已通过</label>
    <br />
    <button class='am-btn am-btn-danger' style="padding:0.5rem 1rem" onclick='rejectItem({{$item->id}})'>拒绝</button>
    @else
    <label>已拒绝</label>
    <br />
    <button class='am-btn am-btn-success' style="padding:0.5rem 1rem" onclick='approveItem({{$item->id}})'>通过</button>
    @endif
@endif
</div>
</td>
