

<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>


<div class="flickity-viewport"  style="height: 600px; touch-action: pan-y;">
    <div class="flickity-slider" style="left: 0px; transform: translateX(0%);">
        <div class="carousel-cell is-selected" style="position: absolute; left: 0%;">
            <img data-flickity-lazyload="<?php echo base_url('./assets/images/become-a-wholesale-customer/20200520-_DSC3640.jpg') ?>" alt="" />
        </div>

        <div class="carousel-cell" aria-hidden="true"  style="position: absolute; left: 64.85%;">
            <img data-flickity-lazyload="<?php echo base_url('./assets/images/become-a-wholesale-customer/20200528-_DSC4178.jpg') ?>" alt="" />
        </div>

        <div class="carousel-cell" aria-hidden="true"  style="position: absolute; left: 117.62%;">
            <img data-flickity-lazyload="<?php echo base_url('./assets/images/become-a-wholesale-customer/20200423-_DSC0551-1024x683.jpg') ?>" alt="" />
        </div>

        <div class="carousel-cell" aria-hidden="true" style="position: absolute; left: 160.16%;">
            <img data-flickity-lazyload="<?php echo base_url('./assets/images/become-a-wholesale-customer/Espresso-HN-1024x1024.jpg') ?>" alt="" />
        </div>

        <div class="carousel-cell" aria-hidden="true" style="position: absolute; left: 200.16%;">
            <img data-flickity-lazyload="<?php echo base_url('./assets/images/become-a-wholesale-customer/20200615-_DSC4335-1024x1024.jpg') ?>" alt="" />
        </div>

        <div class="carousel-cell" aria-hidden="true"  style="position: absolute; left: 242.7%;">
            <img  data-flickity-lazyload="<?php echo base_url('./assets/images/become-a-wholesale-customer/HotNumbers_CoffeeandMilk-1024x1024.jpg') ?>" alt="" />
        </div>
    </div>
</div>


<!-- draggable slider script--->
<script>
    $('.flickity-slider').flickity({
        lazyLoad: true,
        autoPlay: false,
        prevNextButtons: true,
        pageDots: false,
        groupCells: '80%',
        cellAlign: 'left',
        selectedAttraction: 0.01,
        friction: 0.15,
        contain: true,
        accessibility: true
    })

</script


