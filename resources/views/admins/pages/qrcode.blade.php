@extends('admins.layouts.admin')
@section('detailcontent')
<link rel="stylesheet" href="/css/admin/admin.css">
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong> / <small>标签库</small></div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">操作</a></li>
        </ul>
    </div>
    <div class="am-tabs-bd">
        <div class="am-g am-margin-top">
          <div class="am-u-sm-12 am-u-md-12">
            <div class="inline_div">
                <label>链接模式</label>
                <select class="type">
                    <option value='0001'>微信自动登录</option>
                    <option value='0002'>QQ自动登录</option>
                    <option value='0000'>网页自动登录</option>
                    {{--<option value='G0000'>不需要登录</option>--}}
                 </select>
            </div>
            <div class="inline_div">
                <label>资源类型</label>
                <select class="resource">
                    <option value="0000">首页</option>
                    <option value="1000">IP</option>
                    <option value="2000">用户作品</option>
                    <option value="6000">专辑</option>
                    <option value="3000">次元</option>
                    <option value="3100">次元帖子</option>
                    <option value="5000">活动</option>
                    <option value="9000">其它</option>
                 </select>
            </div>
            <div class="inline_div">
                <label>资源ID</label>
                <input class="id" type="number" value="0"/>
            </div>
              <div class="inline_div">
                  <input class="check" type="checkbox">
                  <span>有无logo</span>
              </div>
            <div class="inline_div">

                <button type="button" class="am-btn am-btn-primary am-btn-xs createUrl" onclick="postReqeust()">
                    生成链接
                </button>
            </div>
            <hr />
            <div class="inline_div url">
                <div class="result" style="color: red;"></div>
                <label>URL:</label>
                <label class="qrUrl"></label>
            </div>
              <div class="inline_div qrCode">
                  <label>二维码:</label>
              </div>
              <div class="content">
                  <ul>
                      <li class="ym_firstLi ym_li">
                          <div id="bigCode">

                          </div>
                          <div class="firstTop">（300*300）</div>
                      </li>
                      <li class="ym_secondLi ym_li">
                          <div id="code">

                          </div>
                          <div class="secondTop">（150*150）</div>
                      </li>
                      <li class="ym_lastLi ym_li">
                          <div id="smallCode">

                          </div>
                          <div class="lastTop">（100*100）</div>
                      </li>
                  </ul>
              </div>


          </div>
        </div>
        <div class="inline_div ym_see" style="display: none;">
            <button type="button" class="am-btn am-btn-primary am-btn-xs">
                <a class="ym_seeArticle" target="_blank" style="color: white;">查看原文</a>
            </button>
        </div>
        <div class="am-g am-margin-top">
        </div>
    </div>
</div>

@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/jquery.qrcode.min.js"></script>
<script type="text/javascript" charset="utf-8">
function postReqeust(){
    var type     = $('.type').val();
    var resource = $('.resource').val();
    var id       = $('.id').val();;
    var check="";

    if($('.check').is(':checked')) {
        check="checked";
    }else{
        check="unchecked";
    }
//    获取路径
//    var _url="/admin/sp/MakeValue";
    $.post(
            '/admin/ctrl/make-value',
            {'resource':resource,'type':type,'id':id,  "_token":"{{ csrf_token() }}"},
            function(data){
                if(!data.res){
                    $(".result").html("找不到资源");
                    $(".content").hide();
                    return;
                }

                var url = data.url;
                var imgPath;
                if(check=="checked"){
                    imgPath = data.imgPath;
                }else{
                    imgPath ="";
                }
                $(".qrUrl").html(url);
                //连接的地址
                $(".result").html("");
                $(".firstTop").show();
                $(".lastTop").show();
                $(".secondTop").show();
                $(".qrCode").show();
                $(".content").show();
                $(".ym_see").show();
                $("ym_seeArticle").attr("target","_blank");
                $(".ym_seeArticle").attr("href",data.jumpPath);
                $("#smallCode").attr("style","border:2px solid #000;");
                $("#bigCode").attr("style","border:2px solid #000;");
                $("#code").attr("style","border:2px solid #000;");


                $('#code').qrcode({
                    render : "canvas",    //设置渲染方式，有table和canvas，使用canvas方式渲染性能相对来说比较好
                    text : url,    //扫描二维码后显示的内容,可以直接填一个网址，扫描二维码后自动跳向该链接
                    width : "200",               //二维码的宽度
                    height : "200",              //二维码的高度
                    background : "#ffffff",       //二维码的后景色
                    foreground : "#000000",        //二维码的前景色
                    src: imgPath             //二维码中间的图片
                });

                $('#smallCode').qrcode({
                    render : "canvas",    //设置渲染方式，有table和canvas，使用canvas方式渲染性能相对来说比较好
                    text : url,    //扫描二维码后显示的内容,可以直接填一个网址，扫描二维码后自动跳向该链接
                    width : "200",               //二维码的宽度
                    height : "200",              //二维码的高度
                    background : "#ffffff",       //二维码的后景色
                    foreground : "#000000",        //二维码的前景色
                    src: imgPath             //二维码中间的图片
                });

                $('#bigCode').qrcode({
                    render : "canvas",    //设置渲染方式，有table和canvas，使用canvas方式渲染性能相对来说比较好
                    text : url,    //扫描二维码后显示的内容,可以直接填一个网址，扫描二维码后自动跳向该链接
                    width : "200",               //二维码的宽度
                    height : "200",              //二维码的高度
                    background : "#ffffff",       //二维码的后景色
                    foreground : "#000000",        //二维码的前景色
                    src: imgPath             //二维码中间的图片
                });

                if($('#code').children().length==2){
                    $('#code').children().eq(0).remove();
                }
                if($('#smallCode').children().length==2){
                    $('#smallCode').children().eq(0).remove();
                }
                if($('#bigCode').children().length==2){
                    $('#bigCode').children().eq(0).remove();
                }
            })


    }
</script>
@stop
