var ym_animate = function(){
  this.showAnimation = function(coinCount, callback){
    var tmpShade = $('<div class="ym_shade" style="z-index:1000"></div>');
    var coinBox = $('<div class="ym_coin_box" style="z-index:1000"></div>');
    $('body').append(tmpShade);
    $('body').append(coinBox);
    tmpShade.show();
    var labelTxt = '您收取了'+coinCount+'个金币';
    var rows = getRows(coinCount);
    var bottomMargin = 80;
    var iconWidth = 24;
    var iconHeight = 14;
    var dropSpeed = 100;
    var coinStr = '<img src="/imgs/coin7.png" class="ym_coin" />';
    var leftMargin = 0 - (iconWidth*rows)/2;
    var row = 0;
    var dropCount = 0;
    var iv = setInterval(function(){
        var bottom = bottomMargin + iconHeight*row;
        for(var i=0; i<(rows - row); i++){
            var left = leftMargin + (iconWidth / 2) * row + i * iconWidth;
            var coin = $(coinStr);
            coin.css('left', left + 'px');
            coin.css('bottom', rows*iconHeight*3+ 'px');
            $(coinBox).append(coin);
            coin.show().animate({'bottom' : bottom + 'px'}, dropSpeed * (i+1));
            dropCount++;
            if(dropCount == coinCount){
              break;
            }
        }
        row++;
       if(dropCount == coinCount || rows == row) {
          var label = $('<label class="ym_coin_label">'+labelTxt+'</label>');
          $(tmpShade).append(label);
          setTimeout(function(){
              tmpShade.remove();
              coinBox.remove();
              callback();
          }, 1500);
          clearInterval(iv);
       }
    }, 120);
  };
  function getRows(coins){
    var seed = 1;
    var rows = 1;
    while(seed < coins && rows < 9){
      rows++;
      seed += rows;
    }
    return rows;
  }
};

var $YM_ANIMATE = new ym_animate();
