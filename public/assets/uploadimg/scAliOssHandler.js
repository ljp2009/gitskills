function scAliOssHandler(opts){
  var $scalihandler = this;
  var options = opts;
  var bindFunc = {
      uploadSucessful: null,
      uploadFailed: null,
  };
  var func = {
    uploadData:function(data, name, contentType, callInfo, params){
      var fd = new FormData();
      fd.append('key', params.dir+name);
      fd.append('OSSAccessKeyId', params.accessid);
      fd.append('policy', params.policy);
      fd.append('signature', params.signature);
      fd.append('callback', params.callback);
      fd.append('success_action_status', '200');
      fd.append('content-Type', contentType);
      fd.append('file', data);
      var xhr = new XMLHttpRequest();
      xhr.addEventListener("load", function(a){
        var res = a.currentTarget.responseText;
        console.log(res);
        if(bindFunc.uploadSucessful != null){
          bindFunc.uploadSucessful(JSON.parse(res), callInfo);
        }
      }, false);
      xhr.addEventListener("error", function(a){
        console.log(a.currentTarget.responseText);
        if(bindFunc.uploadFailed != null){
          bindFunc.uploadFailed(a.currentTarget.responseText, callInfo);
        }
      }, false);
      xhr.open("POST",params.host);
      xhr.send(fd);
    },
  };
  this.uploadFile = function(file, name, contentType, callInfo){
    $.get(options.policyUrl, function(data){
      //var params =JSON.parse(data);
      var params = data;
      if(typeof(data) == 'string'){
        params =JSON.parse(data);
      }
      func.uploadData(file, name, contentType, callInfo, params);
    });
  }
  this.bind = function(name, callbackFunc){
    switch(name){
      case "uploadSucessful":
        bindFunc.uploadSucessful = callbackFunc;
        break;
      case "uploadFailed":
        bindFunc.uploadFailed = callbackFunc;
        break;
      default:
        alert('scAliOssHandler bind an undefined event.');
    }
    return $scalihandler;
  };
}
