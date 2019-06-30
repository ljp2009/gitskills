<div class="am-modal am-modal-confirm" tabindex="-1" id="ym_show_share_modal">
  <div class="am-modal-dialog">
    <div class="am-modal-bd">
        <div><b>您可以通过扫描下面二维码分享本页内容：</b></div>
        <div id="qrcodepar"></div>
        <div ><b>或者直接通过以下方式分享：</b></div>
        <ul class="am-avg-sm-3">
            <li onclick="_YMShowShare.share('qzone')"><i class="am-icon-star" style="color:#ffc028"></i> QQ空间</li>
            <li onclick="_YMShowShare.share('qqweibo')"><i class="am-icon-tencent-weibo" style="color:#23ccfe"></i>腾讯微博</li>
            <li onclick="_YMShowShare.share('sina')"><i class="am-icon-weibo" style="color:#ea1328"></i>新浪微博</li>
        </ul>
    </div>
 </div>
</div>
<script type="text/javascript">
function YMShowShare(){
    this._url = window.location.href;
    this._host = window.location.host;
    this._title = '欢迎来到有妹社区';
    this.show = function(url){
        if(typeof(url) != 'undefined'){
            this._url = this._host + url;
        }
        if($('#qrcodepar').html()==''){
            $('#qrcodepar').qrcode({text:this._url,height:150,width:150});
        }
        $('#ym_show_share_modal').modal('open');
    }
    this.share = function(target){
        var url = '';
        switch(target){
            case "sina":
                url = 'http://service.weibo.com/share/share.php?url=http://'+this._url+'&title='+this._title;
                break;
            case "wechat":
                url = '[qrcode]?url='+this._url+'&title='+this._title;
                var QRCode = $.AMUI.qrcode;
                $('qrcodepar').html(new QRCode({text: 'xxx'}));
                break;
            case "qzone":
                url = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+this._url+'&title='+this._title;
                break;
            case "qqweibo":
                url = 'http://v.t.qq.com/share/share.php?url='+this._url+'&title='+this._title;
                break;
        }
        window.open(url);
    }
}
var _YMShowShare = new YMShowShare();
</script>
