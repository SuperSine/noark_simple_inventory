<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<head>
    <meta charset="utf-8">
    <style type="text/css">
        html,body,input{
            font: normal 16px/19px 'Ubuntu', 'Helvetica', Arial, sans-serif
        }

        span{
            float: left;
            width: 70px;
        }
    </style>
    <script type="text/javascript" src="<?php echo URL::base(); ?>/app/assets/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">

        function params(data){
            var keys = [];
            for(var key in data)keys.push(key);
                
            var url = keys.map(function(k) {
                return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]);
            }).join('&');

            return url;
        }

    </script>
</head>
<body>