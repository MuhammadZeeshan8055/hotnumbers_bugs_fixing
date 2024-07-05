

<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>


<div class="flickity-viewport"  style="height: 600px; touch-action: pan-y;">
    <div class="flickity-slider" style="left: 0px; transform: translateX(0%);">

        <div class="carousel-cell is-selected" style="position: absolute; left: 0%;">
            <img data-flickity-lazyload="<?php echo base_url('./assets/images/gwydir/20201022-IMG_0112-1024x828.jpg') ?>" alt="" />
        </div>

        <div class="carousel-cell" aria-hidden="true"  style="position: absolute; left: 64.85%;">
            <img data-flickity-lazyload="<?php echo base_url('./assets/images/gwydir/20210517-_DSC0023-2-1024x1024.jpg') ?>" alt="" />
        </div>

        <div class="carousel-cell" aria-hidden="true"  style="position: absolute; left: 117.62%;">
            <img data-flickity-lazyload="<?php echo base_url('./assets/images/gwydir/DSF3717-819x1024.jpg') ?>" alt="" />
        </div>

        <div class="carousel-cell" aria-hidden="true" style="position: absolute; left: 160.16%;">
            <img data-flickity-lazyload="<?php echo base_url('./assets/images/gwydir/DSF3736-819x1024.jpg') ?>" alt="" />
        </div>

        <div class="carousel-cell" aria-hidden="true" style="position: absolute; left: 200.16%;">
            <img data-flickity-lazyload="<?php echo base_url('./assets/images/gwydir/DSF3717-819x1024.jpg') ?>" alt="" />
        </div>

        <div class="carousel-cell" aria-hidden="true"  style="position: absolute; left: 242.7%;">
            <img  data-flickity-lazyload="<?php echo base_url('./assets/images/gwydir/DSF3723-819x1024.jpg') ?>" alt="" />
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

</script>


