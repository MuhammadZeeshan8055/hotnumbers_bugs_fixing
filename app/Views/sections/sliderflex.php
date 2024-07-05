

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




<!--style--->
<style>

    </style>



<!-- Place somewhere in the <body> of your page -->
<div id="imgslider" class="flexslider">
  <div class="flex-viewport" style="overflow: hidden; position: relative;">
  <ul class="slides" style="">
    <li class="clone" aria-hidden="true" style="width: 904px; margin-right: 0px; float: left; display: block;">
      <div class="article">
        <div class="featured">
          <img src="<?php echo base_url('./assets/images/the_roastery/farming-1024x597.jpg') ?>" />
        </div>
    </div>
    </li>

    <li class="clone" aria-hidden="true" style="width: 904px; margin-right: 0px; float: left; display: block;">
      <div class="article">
        <div class="featured">
          <img src="<?php echo base_url('./assets/images/the_roastery/carousel12-1024x597.jpg') ?>" />
        </div>
      </div>
    </li>

    <li class="clone" aria-hidden="true" style="width: 904px; margin-right: 0px; float: left; display: block;">
      <div class="article">
        <div class="featured">
          <img src="<?php echo base_url('./assets/images/the_roastery/roasted-1024x597.jpg') ?>" />
        </div>
      </div>
    </li>
  </ul>
</div>

</div>

