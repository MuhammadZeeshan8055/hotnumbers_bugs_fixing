
<head>
<!-- Place somewhere in the <head> of your document -->
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/style/flexslider.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/javascript/jquery.flexslider.js"></script>


</head>


<!-- script for flex slider-->
<script type="text/javascript" charset="utf-8">
  $(window).load(function() {
    $('.flexslider').flexslider();
  });
</script>


<!-- Place somewhere in the <body> of your page -->
<div id="imgslider" class="flexslider">
  <ul class="slides">
    <li>
      <img src="<?php echo base_url('./assets/images/rwanda-bwenda-441/Fredy-Pineda-1024x683.jpg') ?>" />

    </li>

  </ul>
</div>

