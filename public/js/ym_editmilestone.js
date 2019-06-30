 function getTaskId(){
     return  $('input[name="task_id"]').val();
 }
 function reloadMilestone(){
      $.get('/milestone/list/'+getTaskId(),function(data){
          $("#m_container").html('');
          for (var i = data.desc.length - 1; i >= 0; i--) {
            $("#m_container").append(addMilestone(data.desc[i]));
          };
      },'json');
 }
 function attachMilestone_btn(){
      $("#m_text").val("");
      $('#my-prompt').modal({
          onConfirm: function(e) {
              if(e.data[1] == ''){
                  alert('请填写描述。');
                  return;
              }
              var o = {'_token':$('meta[name="csrf-token"]').attr('content'), 'date':e.data[0], 'text':e.data[1]};
              $.post('/milestone/add/'+getTaskId(),o,
                  function(data){
                      reloadMilestone();
                  },'json').error(function(a,b,c){
                      alert(a+b+c);
                  });
          },
          onCancel: function(e) {
          }
      });
 }
 function addMilestone(obj)
 {
      var html = '\
      <div class="am-g ym_milestone_item" id="m_'+obj.id+'"\
      date="'+obj.date+'"\
      style="margin-bottom:0.5rem; padding-bottom:0.5rem; border-bottom:solid 1px #cccccc">\
      <div class="am-u-sm-6">\
          <label class="am-badge am-round am-badge-success " style="background-color:silver;" >\
               <span class="am-icon-tag"></span>&nbsp; &nbsp;'+obj.date+'&nbsp; &nbsp;\
          </label>\
      </div>\
      <div class="am-u-sm-6" style="text-align:right">\
          <label class="am-badge am-round am-badge-success "\
              style="background-color:red;" onclick="deleteMilestone('+obj.id+')">\
              &nbsp;删除 &nbsp;<span class="am-icon-remove"></span>\
          </label>\
      </div>\
      <div class="am-u-sm-12" style="margin-top:-0.2rem;margin-bottom:0rem">\
          '+obj.text+'\
      </div>\
  </div>';
  return html;
 }
 function deleteMilestone(id){
  $('#my-confirm').modal({
      relatedTarget:id,
          onConfirm: function(e) {
              $.post('/milestone/remove/'+getTaskId(),{
                  '_token':$('meta[name="csrf-token"]').attr('content'),
                   'milestoneId':id
              },
              function(data){
                  reloadMilestone();
              }).error(function(a,b,c){
                  alert(a+b+c);
              });
          },
          onCancel: function(e) {
          }
      });
 }
