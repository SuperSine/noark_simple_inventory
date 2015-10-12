<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Noark Simple Inventory</title>
    <meta name="viewport" content="width=device-width">
    <script>
        var SITEURL = '<?php echo URL::to(); ?>';
        var CURRENT_URL = '<?php echo URL::to(Request::uri()); ?>';
        var BASEURL = '<?php echo URL::base(); ?>';
    </script>

    <?php echo Asset::styles(); ?>
    <link href='http://fonts.googleapis.com/css?family=Headland+One&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <?php echo Asset::scripts(); ?>

    <script>
    $(document).ready(function() {
        $('.main').on('click', '.delete',  function(e) {
            e.preventDefault();
            var settings={animation:700,buttons:{cancel:{action:function(){Apprise("close")},className:"gray",id:"cancel",text:"<?php echo __('site.cancel'); ?>"},confirm:{action:function(){window.location=$(e.target).attr("href")},className:"red",id:"delete",text:"<?php echo __('site.delete'); ?>"}},input:false,override:false}
            Apprise('<p class="center"><?php echo __('site.sure_delete'); ?></p>', settings);
        });
    });
    </script>
</head>
<body>