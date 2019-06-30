
<!--详细页头部-->
	<header data-am-widget="header" class="am-header am-header-default  am-header-fixed" id="headerbar">
	<div class="am-header-left am-header-nav" >
        @if(isset($backToMain))
            <a href="/reshall"><i class="am-header-icon am-icon-arrow-left ym-icon-back" style='font-weight:100'></i></a>
        @elseif(isset($backUrl))
            <a href="{{$backUrl}}"><i class="am-header-icon am-icon-arrow-left ym-icon-back" style='font-weight:100'></i></a>
        @else
            <a href="javascript:void(0);" onclick="history.back();" ><i class="am-header-icon am-icon-arrow-left ym-icon-back" style='font-weight:100'></i></a>
        @endif
			</div>
	<h1 class="am-header-title">
		<img src="/imgs/logo.png" onclick="window.location='/reshall'" />
	</h1>
	<div class="am-header-right am-header-nav">
	@if(!isset($hideShare) || !$hideShare)
		<a href="#left-link" class="">
			<i class="am-header-icon am-icon-share-alt ym-icon-share"  style='font-weight:100'></i>
		</a>
	@else
		@if(isset($showSearch) && $showSearch)
		<a href="javascript:void(0)" class="" onclick="quickSearch()">
			<i class="am-header-icon am-icon-search ym-icon-search"  style='font-weight:100'></i>
		</a>
		@elseif(Auth::check()&&isset($showEdit) && isset($user_id) && Auth::user()->id == $user_id)
		<a href="javascript:void(0)" class="" onclick="location.href='{{$showEdit}}'">
			<i class="am-header-icon am-icon-edit ym-c-yellow"  style='font-weight:100'></i>
		</a>
		@endif
		@if(isset($menu) && is_array($menu))
		<div class="am-dropdown" data-am-dropdown>
			<i class="am-header-icon am-icon-wrench am-dropdown-toggle" data-am-dropdown-toggle style='font-weight:100'></i>
		  <ul class="am-dropdown-content">
		    @foreach($menu as $itemName => $itemAction)
			@if($itemName=='-')
		    <li class="am-divider"></li>
			@else
		    <li><a href="javascript:void(0)" onclick="{{$itemAction}}">{{$itemName}}</a></li>
			@endif
		    @endforeach
		  </ul>
		</div>
		@endif
	@endif
	</div>
	</header>
	@if(isset($showSearch) && $showSearch)
	<div class="am-modal am-modal-prompt" tabindex="-1" id="my-search">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">作品快速查询</div>
            <div class="am-modal-bd">
                <input type="text" class="am-modal-prompt-input">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>查询</span>
            </div>
        </div>
    </div>
		<script type="text/javascript">
	    function quickSearch(){
	        $('#my-search').modal({
	            onConfirm: function(e) {
	                window.location = '/search/list/'+e.data+"/0";
	            },
	            onCancel: function(e) {}}
            );
	    }
	    </script>
	@endif
