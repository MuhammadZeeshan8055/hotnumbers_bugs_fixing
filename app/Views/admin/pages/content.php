<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title></title>
    <!-- Bootstrap core CSS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="<?php echo site_url() ?>assets/admin/builder/editor.css">
    <style>
        html, body
        {
            width:100%;
            height:100%;
        }
    </style>

    <style id="vvvebjs-styles"></style>
    <script>
        AOS.init();
    </script>
</head>
<body>
<!-- Page Content -->
<div>
    <?php
    if(!empty($pageData)) {
        echo !empty($pageData->content) ? base64_decode($pageData->content) : '';
    } ?>

</div>
</body>
</html>

