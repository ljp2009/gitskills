(function($){
  $.extend($.fn, {
    bindEmoji:function(target){
      $(this).on('click', function(){
        var $container = $(this).parent().parent();
        var $emojiBar = $('.ym_emojibar');
        if($emojiBar.length > 0){
          $emojiBar.remove();
          $(target).prev().focus();
        }else{
          var emojis = [
            '(⌒▽⌒)', '（￣▽￣）', '(=・ω・=)', '(｀・ω・´)', 
            '(〜￣△￣)〜', '(･∀･)', '(°∀°)ﾉ', '(￣3￣)', 
            '╮(￣▽￣)╭', '( ´_ゝ｀)', '←_←', '→_→', 
            '(;¬_¬)', '("▔□▔)/', '(ﾟДﾟ≡ﾟдﾟ)!?', 'Σ(ﾟдﾟ;)',
            'Σ( ￣□￣||)', '(´；ω；`)', '（/TДT)/', '(^・ω・^ )',
            '(｡･ω･｡)', '(●￣(ｴ)￣●)', 'ε=ε=(ノ≧∇≦)ノ', '(´･_･`)',
            '(-_-#)', '（￣へ￣）', '(￣ε(#￣) Σ', 'ヽ(`Д´)ﾉ',
            '(╯°口°)╯(┴—┴', '（#-_-)┯━┯', '_(:3」∠)_', '(笑)',
            '(汗)', '(泣)', '(苦笑)'
          ];

          $emojiBar = $('<div class="ym_emojibar"></div>');
          $container.append($emojiBar);
          for(var i=0; i<emojis.length; i++){
            $emojiBar.append('<span>'+emojis[i]+'</span>');
          }
          var margin = 0 - parseInt($container.css('padding-right')); 
          $emojiBar.css('margin-right', margin + 'px');
          $emojiBar.find('span').on('click', function(){
            var text = $(target).text();
            $(target).text(text+$(this).text());
          });
        }
      });
    }
  });
})(jQuery);
