<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <script src="/assets/js/jquery.min.js"></script>
</head>
<body>
<input style="display:none" id='ur' type="text" value=''/>
<script type="text/javascript">
    var url = "";
    var backStep = 2;
<?php
    echo 'url='.'"'.$_GET['url'].'";';
    echo 'backStep='.'"'.$_GET['bs'].'";';
?>
    var st = setTimeout(function(){
        var ip = document.getElementById('ur');
        if(ip.value == ''){
            ip.value = url;
            window.location = url;
        }else{
            if(backStep > 0){
                history.go(0-backStep);
            }else{
                window.location = url;
            }
        }
    },100);
</script>
</body>
</html>
