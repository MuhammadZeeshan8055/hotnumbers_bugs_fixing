<!doctype html>
<html lang="en">

<!--head start----------------->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title><?php echo !empty($page['meta_title']) ? $page['meta_title'] : 'Hot Numbers Coffee Roasters' ?></title>
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo  base_url() ?>/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo  base_url() ?>/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo  base_url() ?>/assets/images/favicon-16x16.png">
    <link rel="manifest" href="<?php echo  base_url() ?>/assets/images/site.webmanifest">
    <link rel="mask-icon" href="<?php echo  base_url() ?>/assets/images/safari-pinned-tab.svg" color="#5bbad5">

    <?php if(!empty($page['meta_description'])) {?><meta name="description" content="<?php echo strip_tags($page['meta_description']) ?>"><?php } ?>
    <?php if(!empty($page['meta_keywords'])) {?><meta name="keywords" content="<?php echo strip_tags($page['meta_keywords']) ?>"><?php } ?>
    <link rel="canonical" href="" />

    <meta property="og:locale" content="en_GB" />
    <meta property="og:type" content="article" />
    <?php if(!empty($meta_title)) {?>
        <meta property="og:title" content="<?php echo $meta_title ?>" />
    <?php } ?>
    <?php if(!empty($meta_description)) {?>
        <meta property="og:description" content="<?php echo $meta_description ?>" />
    <?php } ?>
    <meta property="og:url" content="<?php echo current_url() ?>" />

    <meta property="og:site_name" content="Hot Numbers Coffee" />

    <meta property="article:publisher" content="https://www.facebook.com/HotNumbersCoffee" />

    <?php if(!empty($page['date_updated'])) { //2021-12-05T10:34:22+00:00 ?>
        <meta property="article:modified_time" content="<?php echo $page['date_updated'] ?>" />
    <?php } ?>

    <?php if(!empty($page['meta_image'])) {
        $image_url = $media->get_media_src($page['meta_image']);
        ?>
        <meta property="og:image" content="<?php echo $image_url ?>" />
    <?php }
    ?>
    <meta property="og:image:width" content="1920" />
    <meta property="og:image:height" content="1080" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@hotnumbers" />

    <link rel="stylesheet" data-href="https://use.typekit.net/xhp4uel.css">

    <link rel="stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/fonts/icons.css">

    <link rel="stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/style/custom.css">
    <link rel="stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/style/responsive.css">
    <link rel="stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/style/animations.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/style/flexslider.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/style/style_clogin.css" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flickity@3.0.0/dist/flickity.pkgd.min.js"></script>

    <script>
        window.site_url = '<?php echo site_url() ?>';
    </script>

    <?php
    if(!empty($header_scripts)) {
        echo $header_scripts;
    } ?>

    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url() ?>/assets/style/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>/assets/style/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url() ?>/assets/style/sweetalert2.min.css">

    <!--favicon and title-->

</head>
<!--head end ---------------------------------->



<!--- style ------------------------------------------------------------------>
<style type="text/css" id="wp-custom-css">


</style>

<!--- style end -------------------------------------------------------------->


<!-----------------BODY------------------->
<body class="data not_is_user">


<!--wrappper ---------------------->
<div class="wrapper home_wrapper" >


    <?php //get_message() ?>




<?php echo  view( 'includes/nav_bar.php');?>