/* 通用AliOss图片上传脚本, 图片上传的时候会压缩为jpg上传,
 * $(obj).fileUpload(uopts);
 * uopts:
 *    ---属性部分---
 *    name: string, 控件绑定名称，当页面需要一个一样图片追加控件时可以定义这个属性，用来避免控件冲突
 *    quality: int, (1~10), 数值越小图片质量越低，10的时候不压缩上传,仅转换为jpg
 *    fileType: 正则表达式，检查图片类型的正则表达式,默认/^(image\/jpeg|image\/png|image\/gif)$/i, 
 *    fileSize: int, >=0，文件大小，单位M 默认4M, 
 *    autoMaxWidth: int, >=0, 上传时候图片最大宽度，当图片的宽度大于此时，将按照比例缩放到此宽度，0时不限制,默认值:0
 *    autoMaxHeight: int, >=0, 上传时候图片最大高度，当图片的高度大于此时，将按照比例缩放到此高度，0时不限制, 默认值:1080
 *    uploadDrive: , 上传图片的引擎, local, alioss, customer,
 *                 alioss为默认值，当设置为customer的时候需要同时设置bindUploadDrive
 *    uploadParams: 上传引擎需要的参数, local, alioss，参考对应的js文件
 *    ---事件部分---
 * */
function aliOssUploadDrive(opts){
  
  var options = opts;
  options.url = '/json/getAliPolicy';
  this.uploadFile = function(file, fileName, flag){
  };
  
}
