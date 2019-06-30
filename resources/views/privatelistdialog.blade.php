@extends('layouts.master')
@section('title', $title)
@section('content')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>$title])
    <link rel="stylesheet" href="/css/ym_private.css">
    <div class="chat-list-box" id='chat_box'>
        <div id="loading_item" class="loading-item">
            <label><i class="ymicon-eye" style="font-size:25px"></i></label>
            <span>点击或下拉加载更早的数据</span>
        </div>
        <div id="empty_item" class="info-item">这里很干净什么消息都木有!~~~</div>
    </div>
    @if($userId != 0)
    <div class="ym_commentbar" style="padding-right:45px">
        <div class="ym_commentbar_inputbox">
            <div id="chat_text" contentEditable="true" 
              class="input_div"     tabindex='1'
                placeholder="{{isset($placeholder)?$placeholder:'说点什么(200字)'}}" maxlength="200" ></div>
            <span class="ymicon-emoji emoji_icon"></span>
        </div>
        <div class="ym_commentbar_btnbox" >
            <span id="send_msg"><i class="ymicon-send"></i></span>
        </div>
    </div>
    @endif
    <script src="/js/emoji.js"></script>
    <script src="/js/ym_userchat.js"></script>
    <script>
    $('.emoji_icon').bindEmoji('#chat_text');
    $USERCHAT.bind({'targetUser':'{{$userId}}'});
    $USERCHAT.begin();
    </script>
@stop
