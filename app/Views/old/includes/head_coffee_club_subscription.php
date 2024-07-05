<!doctype html>
<html lang="en">


<!-------------------HEAD----------------->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!--javascripting-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>/assets/javascript/javascript_fun.js"> </script>

    <link rel = "stylesheet"  href="https://use.typekit.net/xhp4uel.css">

    <!---- CSS ----->
    <!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">-->

    <!--Awsome Font 5-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!---css Style--->
    <link rel = "stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/style/style_header.css">
    <link rel = "stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/style/style_nav.css">
    <link rel = "stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/style/style_body.css">
    <link rel = "stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/style/style_footer.css">
    <link rel = "stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/style/workwithus.css">
    <!--link rel = "stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/style/style_shop.css">
    <link rel = "stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/style/Style_shop_coffee.css"-->
    <link rel = "stylesheet" type = "text/css" href ="<?php echo base_url(); ?>/assets/style/style_flex_nav.css">

    <!--favicon and title-->
    <title>Hot Numbers Coffee Roasters</title>
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo  base_url() ?>/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo  base_url() ?>/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo  base_url() ?>/assets/images/favicon-16x16.png">
    <link rel="manifest" href="<?php echo  base_url() ?>/assets/images/site.webmanifest">
    <link rel="mask-icon" href="<?php echo  base_url() ?>/assets/images/safari-pinned-tab.svg" color="#5bbad5">

    <script>
        window.site_url = '<?php echo site_url() ?>';
    </script>
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



<?php echo  view( 'includes/nav_bar.php');?>