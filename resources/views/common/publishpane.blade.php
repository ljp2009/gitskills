<!-- Modal -->
<!--
    本页面可以使用
      include('common.publishpane', array('publishEles'=>array(...)))
    的形式引入
    其中publishEles必须声明为一个array().
    array中的值为string类型。
    每一个值必须用label:url的格式。
-->
<button type="button" id="publishButton" class="ym-floatbtn" data-am-modal="{target: '#_publishFunctionPane', width:'300px'}">
</button>
<?php $radius = '10px'; ?>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="_publishFunctionPane">
  <div class="am-modal-dialog" style="border: 1px solid #dedede;border-radius:{{$radius}}; -webkit-border-radius: {{$radius}}; -moz-border-radius: {{$radius}};">
    <div class="am-modal-hd">
          <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd" >
        <?php
          $count = 0;
          $size = sizeof($publishEles);
          foreach ($publishEles as $ele) {
              $eledata = explode(':', $ele);
              ++$count;
              if ($count % 2 == 0) {
                  echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
              }
              echo '<a href="'.$eledata[1].'">'.$eledata[0].'</a>';
              if ($count < $size && $count % 2 == 0) {
                  echo '<hr>';
              }
          }
        ?>
    </div>
  </div>
</div>

