<div class="am-cf am-padding">
    <div class="am-fl am-cf">
    @for($i=0;$i<count($titles);$i++)
        @if($i==0)
        <strong class="am-text-primary am-text-lg">{{$titles[$i]}}</strong>
        @endif
        @if($i==1)
        /<strong>{{$titles[$i]}}</strong>
        @endif
        @if($i==2)
        /<small>{{$titles[$i]}}</small>
        @endif
    @endfor
    </div>
    @if(isset($addBtn))
    <div class="am-fr">
        <button onclick="{{$addBtn}}" class="am-btn am-btn-success">
        {{isset($addText)?$addText:'添加'}}
        </button>
    </div>
    @endif
    @if(isset($backTo))
    <div class="am-fr">
        <a href="{{$backTo}}" class="am-btn am-btn-success">
        {{isset($backText)?$addText:'返回'}}
        </a>
    </div>
    @endif
    @if(isset($searchControl))
    <div class="am-fr">
        <form action="{{$searchControl}}" method="post">
            <input type="text" name="search" value="{{isset($search)?$search:''}}" />
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="submit" class="am-btn am-btn-default" value="查询"/>
        </form>
    </div>
    @endif
</div>
