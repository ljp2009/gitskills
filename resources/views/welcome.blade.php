<html> 
    <head> 
        <script src="http://img.jb51.net/jslib/jquery/jquery-1.3.2.min.js"></script> 
        <script src="http://img.jb51.net/jslib/jquery/jquery.jcrop.min.js"></script> 
<style type="text/css"> 
        body{ 
            margin:100px auto; 
            text-align:center; 
        } 
        .jcrop-holder { 
            text-align: left; 
        } 
        .jcrop-vline, .jcrop-hline{ 
            font-size: 0; 
            position: absolute; 
            background: white url('http://img.jb51.net/jslib/images/Jcrop.gif') top left repeat; 
        } 
        .jcrop-vline { 
            height: 100%; 
            width: 1px !important; 
        } 
        .jcrop-hline { 
            width: 100%; 
            height: 1px !important; 
        } 
        .jcrop-handle { 
            font-size: 1px; 
            width: 7px !important; 
            height: 7px !important; 
            border: 1px #eee solid; 
            background-color: #333; 
            *width: 9px; 
            *height: 9px; 
        } 
         
        .jcrop-tracker { 
            width: 100%; 
            height: 100%; 
        } 
         
        .custom .jcrop-vline,.custom .jcrop-hline{ 
            background: yellow; 
        } 
        .custom .jcrop-handle{ 
            border-color: black; 
            background-color: #C7BB00; 
            -moz-border-radius: 3px; 
            -webkit-border-radius: 3px; 
        } 
        </style> 
        <script language="Javascript"> 
        jQuery(function(){ 
            jQuery('#cropbox').Jcrop(); 
        }); 
        </script> 
    </head> 
    <body> 
            <img src="http://img.jb51.net/jslib/images/flowers.jpg" id="cropbox" />
 
<a href="http://www.jb51.net/">脚本之家</a> 
    </body> 
</html> 