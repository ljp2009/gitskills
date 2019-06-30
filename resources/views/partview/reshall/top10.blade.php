<div class="ym-hall-item-header">
    <div class="ym-hall-item-h-icon ym-hall-item-h-icon-fire"></div>
    <div class="ym-hall-item-h-title">排行TOP</div>
    <div class="ym-hall-item-h-btnicon" onclick="toMoreTop()"></div>
    <div class="ym-hall-item-h-btn" onclick="toMoreTop()">
          更多&nbsp;
    </div>
<script type="text/javascript">
    function toMoreTop(){
        window.location = '/reshall/list/top/0';
    }
</script>
</div>
<div class="am-container" style="padding:0 7px">
    <div class="am-g">
        <div class="am-u-sm-4" style="padding:0">
            <a href="{{$models[0]->ipPath}}">
            <div class="ym-hall-recommand-cover">
                <img src="{{$models[0]->cover}}@165w_192h_1e_1c" class="ym-hall-recommand-cover-img" alt="">
            </div>
            <div class="ym-hall-recommand-title">{{ $models[0]->name }}</div>
            <div class="ym-hall-recommand-like">{{Like::getLikeCount('ip', $models[0]->id)}}人喜欢过</div>
            </a>
        </div>
        <div class="am-u-sm-4" style="padding:0">
            <a href="{{$models[1]->ipPath}}">
            <div class="ym-hall-recommand-cover">
                <img src="{{$models[1]->cover}}@165w_192h_1e_1c" class="ym-hall-recommand-cover-img" alt="">
            </div>
            <div class="ym-hall-recommand-title">{{ $models[1]->name }}</div>
            <div class="ym-hall-recommand-like">{{Like::getLikeCount('ip', $models[1]->id)}}人喜欢过</div>
            </a>
        </div>
        <div class="am-u-sm-4" style="padding:0">
            <a href="{{$models[2]->ipPath}}">
            <div class="ym-hall-recommand-cover">
                <img src="{{$models[2]->cover}}@165w_192h_1e_1c" class="ym-hall-recommand-cover-img" alt="">
            </div>
            <div class="ym-hall-recommand-title">{{ $models[2]->name }}</div>
            <div class="ym-hall-recommand-like">{{Like::getLikeCount('ip', $models[2]->id)}}人喜欢过</div>
            </a>
        </div>
    </div>
</div>
