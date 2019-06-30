var _ym_useFileName = false;
var _ym_type = 0;

function addImage(name) {
    var filectrl = $("#_uploadImageFile");
    $("#objName").val(name);
    filectrl.click();
}

function imageChange(filectrl, flag) {
    var name = $("#objName").val();
    var valuectrl = $("#" + name + "_value");
    var maxCount = parseInt(valuectrl.attr("maxCount"));
    var imgCount = parseInt(valuectrl.attr("imgCount"));
    if (imgCount >= maxCount) {
        alert("上传图片数量超过限制。");
        return;
    }
    if(filectrl.files.length == 0){return;}
    var fileName = filectrl.files[0].name;
    var ext = fileName.substr(fileName.lastIndexOf('.') + 1).toLowerCase();
    var nameSeed = valuectrl.attr("nameSeed");
    var nameIndex = valuectrl.attr("nameIndex");
    var imageName = nameSeed + nameIndex + "." + ext;
    valuectrl.attr("nameIndex", parseInt(nameIndex) + 1);
    if (_ym_useFileName) imageName = nameSeed + fileName;
    var formctrl = $("#_uploadImageForm");
    formctrl.find("input[name=key]").val(imageName);
    formctrl.find("input[name=content-Type]").val(getContentType(ext));
    var redirectUrl = window.location.protocol + "//" + window.location.host + "/t1.php?name=" + name + "&imgname=" + imageName;
    formctrl.find("input[name='success_action_redirect']").val(redirectUrl);

    formctrl.submit();
}

function uploadCallBack(name, imageName) {
  if( typeof customUploadCallback  === 'function' ){
    customUploadCallback(name,imageName);
  }else{
    defaultUploadCallBack(name,imageName);
  }
}
function defaultUploadCallBack(name,imageName){
    var valuectrl = $("#" + name + "_value");
    var listctrl = $("#" + name + "_thumbnaillist");
    //var btnctrl = $("#"+name+"_addbtn");

    var imgCount = valuectrl.attr('imgCount');
    valuectrl.attr('imgCount', parseInt(imgCount) + 1);
    if (parseInt(valuectrl.attr('imgCount')) >= parseInt(valuectrl.attr('maxCount'))) {
        $("#" + name + "_addbtn").hide();
    }
    var v = valuectrl.val();
    valuectrl.val(v + imageName + ";");
    if(_ym_type == 1){
        listctrl.prepend(generateCircleThumbnail(name, imageName));
    } else {
        listctrl.prepend(generateThumbnail(name, imageName));
    }
    
}
function generateThumbnail(name, imageName) {
    var str = '<li style="padding-bottom:0">' +
        ' <img id="' + name + '_' + imageName + '" imgName="' + imageName + '" class="am-thumbnail ym-flag-' + name + '" ' +
        ' style="margin-bottom:5px" src="' + getImgBaseUrl(imageName) + '@64h_64w_1e_1c"' +
        ' onclick="showImageDetail(\'' + name + '\',\'' + imageName + '\')" />' +
        '</li>';
    return str;

}
//圆形缩略图
function generateCircleThumbnail(name, imageName){
    var str = '<li style="padding-bottom:0">' +
        ' <img id="' + name + '_' + imageName + '" imgName="' + imageName + '" class="am-thumbnail ym-flag-' + name + '" ' +
        ' style="margin-bottom:0px" src="' + getImgBaseUrl(imageName) + '@90h_90w_1e_1c"' +
        ' onclick="showImageDetail(\'' + name + '\',\'' + imageName + '\')" />' +
        '</li>';
    return str;
}


function showImageDetail(name, imageName) {
    $("#showImg").attr('src', getImgBaseUrl(imageName) + "@400w_1e_1c");
    $("#showImg").attr('ctrlName', name + "_" + imageName);
    $('#your-modal').modal({});
}

function deleteName() {
    var ctrlName = $("#showImg").attr('ctrlName');
    document.getElementById(ctrlName).parentElement.remove();
    var arr = ctrlName.split('_');
    var valuectrl = $("#" + arr[0] + "_value");
    var tmpV = valuectrl.val();
    tmpV = tmpV.replace(arr[1] + ";", '');
    valuectrl.val(tmpV);

    valuectrl.attr('imgCount', parseInt(valuectrl.attr('imgCount') - 1));
    if (parseInt(valuectrl.attr('imgCount')) < parseInt(valuectrl.attr('maxCount'))) {
        $("#" + arr[0] + "_addbtn").show();
    }
    var file = $("#_uploadImageFile");
    file.after(file.clone().val(""));
    file.remove(); 

    $('#your-modal').modal('close');
}

function getContentType(ext) {
    var data = {
        "apk": "application/vnd.android.package-archive",
        "3gp": "video/3gpp",
        "ai": "application/postscript",
        "aif": "audio/x-aiff",
        "aifc": "audio/x-aiff",
        "aiff": "audio/x-aiff",
        "asc": "text/plain",
        "atom": "application/atom+xml",
        "au": "audio/basic",
        "avi": "video/x-msvideo",
        "bcpio": "application/x-bcpio",
        "bin": "application/octet-stream",
        "bmp": "image/bmp",
        "cdf": "application/x-netcdf",
        "cgm": "image/cgm",
        "class": "application/octet-stream",
        "cpio": "application/x-cpio",
        "cpt": "application/mac-compactpro",
        "csh": "application/x-csh",
        "css": "text/css",
        "dcr": "application/x-director",
        "dif": "video/x-dv",
        "dir": "application/x-director",
        "djv": "image/vnd.djvu",
        "djvu": "image/vnd.djvu",
        "dll": "application/octet-stream",
        "dmg": "application/octet-stream",
        "dms": "application/octet-stream",
        "doc": "application/msword",
        "dtd": "application/xml-dtd",
        "dv": "video/x-dv",
        "dvi": "application/x-dvi",
        "dxr": "application/x-director",
        "eps": "application/postscript",
        "etx": "text/x-setext",
        "exe": "application/octet-stream",
        "ez": "application/andrew-inset",
        "flv": "video/x-flv",
        "gif": "image/gif",
        "gram": "application/srgs",
        "grxml": "application/srgs+xml",
        "gtar": "application/x-gtar",
        "gz": "application/x-gzip",
        "hdf": "application/x-hdf",
        "hqx": "application/mac-binhex40",
        "htm": "text/html",
        "html": "text/html",
        "ice": "x-conference/x-cooltalk",
        "ico": "image/x-icon",
        "ics": "text/calendar",
        "ief": "image/ief",
        "ifb": "text/calendar",
        "iges": "model/iges",
        "igs": "model/iges",
        "jnlp": "application/x-java-jnlp-file",
        "jp2": "image/jp2",
        "jpe": "image/jpeg",
        "jpeg": "image/jpeg",
        "jpg": "image/jpeg",
        "js": "application/x-javascript",
        "kar": "audio/midi",
        "latex": "application/x-latex",
        "lha": "application/octet-stream",
        "lzh": "application/octet-stream",
        "m3u": "audio/x-mpegurl",
        "m4a": "audio/mp4a-latm",
        "m4p": "audio/mp4a-latm",
        "m4u": "video/vnd.mpegurl",
        "m4v": "video/x-m4v",
        "mac": "image/x-macpaint",
        "man": "application/x-troff-man",
        "mathml": "application/mathml+xml",
        "me": "application/x-troff-me",
        "mesh": "model/mesh",
        "mid": "audio/midi",
        "midi": "audio/midi",
        "mif": "application/vnd.mif",
        "mov": "video/quicktime",
        "movie": "video/x-sgi-movie",
        "mp2": "audio/mpeg",
        "mp3": "audio/mpeg",
        "mp4": "video/mp4",
        "mpe": "video/mpeg",
        "mpeg": "video/mpeg",
        "mpg": "video/mpeg",
        "mpga": "audio/mpeg",
        "ms": "application/x-troff-ms",
        "msh": "model/mesh",
        "mxu": "video/vnd.mpegurl",
        "nc": "application/x-netcdf",
        "oda": "application/oda",
        "ogg": "application/ogg",
        "ogv": "video/ogv",
        "pbm": "image/x-portable-bitmap",
        "pct": "image/pict",
        "pdb": "chemical/x-pdb",
        "pdf": "application/pdf",
        "pgm": "image/x-portable-graymap",
        "pgn": "application/x-chess-pgn",
        "pic": "image/pict",
        "pict": "image/pict",
        "png": "image/png",
        "pnm": "image/x-portable-anymap",
        "pnt": "image/x-macpaint",
        "pntg": "image/x-macpaint",
        "ppm": "image/x-portable-pixmap",
        "ppt": "application/vnd.ms-powerpoint",
        "ps": "application/postscript",
        "qt": "video/quicktime",
        "qti": "image/x-quicktime",
        "qtif": "image/x-quicktime",
        "ra": "audio/x-pn-realaudio",
        "ram": "audio/x-pn-realaudio",
        "ras": "image/x-cmu-raster",
        "rdf": "application/rdf+xml",
        "rgb": "image/x-rgb",
        "rm": "application/vnd.rn-realmedia",
        "roff": "application/x-troff",
        "rtf": "text/rtf",
        "rtx": "text/richtext",
        "sgm": "text/sgml",
        "sgml": "text/sgml",
        "sh": "application/x-sh",
        "shar": "application/x-shar",
        "silo": "model/mesh",
        "sit": "application/x-stuffit",
        "skd": "application/x-koan",
        "skm": "application/x-koan",
        "skp": "application/x-koan",
        "skt": "application/x-koan",
        "smi": "application/smil",
        "smil": "application/smil",
        "snd": "audio/basic",
        "so": "application/octet-stream",
        "spl": "application/x-futuresplash",
        "src": "application/x-wais-source",
        "sv4cpio": "application/x-sv4cpio",
        "sv4crc": "application/x-sv4crc",
        "svg": "image/svg+xml",
        "swf": "application/x-shockwave-flash",
        "t": "application/x-troff",
        "tar": "application/x-tar",
        "tcl": "application/x-tcl",
        "tex": "application/x-tex",
        "texi": "application/x-texinfo",
        "texinfo": "application/x-texinfo",
        "tif": "image/tiff",
        "tiff": "image/tiff",
        "tr": "application/x-troff",
        "tsv": "text/tab-separated-values",
        "txt": "text/plain",
        "ustar": "application/x-ustar",
        "vcd": "application/x-cdlink",
        "vrml": "model/vrml",
        "vxml": "application/voicexml+xml",
        "wav": "audio/x-wav",
        "wbmp": "image/vnd.wap.wbmp",
        "wbxml": "application/vnd.wap.wbxml",
        "webm": "video/webm",
        "wml": "text/vnd.wap.wml",
        "wmlc": "application/vnd.wap.wmlc",
        "wmls": "text/vnd.wap.wmlscript",
        "wmlsc": "application/vnd.wap.wmlscriptc",
        "wmv": "video/x-ms-wmv",
        "wrl": "model/vrml",
        "xbm": "image/x-xbitmap",
        "xht": "application/xhtml+xml",
        "xhtml": "application/xhtml+xml",
        "xls": "application/vnd.ms-excel",
        "xml": "application/xml",
        "xpm": "image/x-xpixmap",
        "xsl": "application/xml",
        "xslt": "application/xslt+xml",
        "xul": "application/vnd.mozilla.xul+xml",
        "xwd": "image/x-xwindowdump",
        "xyz": "chemical/x-xyz",
        "zip": "application/zip"
    };
    return data[ext];
}
