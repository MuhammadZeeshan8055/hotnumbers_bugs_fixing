<!DOCTYPE html>
<html lang="en">

<!---head ---->
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css" />
  
  
<style>
  .h-300px { height: 300px; }
  .h-400px { height: 360px; }
  .h-200px { height: 200px; }
  .h-500px { height: 546px; }

  .test-slider-1 { height: 546px; }
  .child-1 { background: ; }
  .child-2 { background: ; margin-left: 19px; }
  .child-3 { background: ; margin-left: 37px;}
  .child-4 { background: ; margin-left: 57px; }
  .child-5 { background: ; margin-left: 77px; }
  .child-6 { background: ; margin-left: 96px; }

  .slick-prev {
    left: 60px;
    z-index: 11;
  }

  .slick-next {
    right: 25px;
    z-index: 11;
  }

  .slick-dotted.slick-slider {
    margin-bottom: 50px;
    margin-top: 18px;
  }

  .slick-dots {
    display: block !important;
    bottom: 125px;
  }

  html body button:hover, html body button:focus {
    background-color: transparent;
}


  .slick-prev, .slick-next {
    font-size: 0;
    line-height: 0;
    position: absolute;
    top: 37%;
    display: block;
    width: 20px;
    height: 20px;
    padding: 0;
    -webkit-transform: translate(0, -50%);
    -ms-transform: translate(0, -50%);
    transform: translate(0, -50%);
    cursor: pointer;
    color: transparent;
    border: none;
    outline: none;
    background: transparent;
}

  .slick-slide {
  /*  margin-left: 19px;*/
  }

/*
@media (max-width: 860px){
    .child-1 {
    position: absolute;
    width: 50% !important;
    }   

    .child-2 {
    margin-left: 594px;
    position: absolute;
    width: 50% !important;
    }

    .child-3 {
    margin-left: 594px;
    position: absolute;
    width: 50% !important;
    }
  }*/
    
    



</style>
</head>
<!-- end head ---->


<!-- draggable slider --->
<div class='test-slider-1'>
    <div class="child-1 h-400px"> 
      <img src="./assets/images/blogs/lcf-300x299.jpg" style='height: 100%; width: 100%; object-fit: cover '> 
    </div>
    <div class="child-2 h-400px"> 
      <img src="./assets/images/blogs/IMG_0400-240x300.jpg" style='height: 100%; width: 100%; object-fit: cover '> 
    </div>
    <div class="child-3 h-400px" > 
      <img src="./assets/images/blogs/DSF1606-240x300.jpg" style='height: 100%; width: 100%; object-fit: cover'> 
    </div>
    <div class="child-4 h-400px"> 
      <img src="./assets/images/blogs/La-Marzocco-Logo-1024x1024_1024x1024-300x300.jpg" style='height: 100%; width: 100%; object-fit: cover'> 
    </div>

    <div class="child-1 h-400px"> 
      <img src="./assets/images/blogs/lcf-300x299.jpg" style='height: 100%; width: 100%; object-fit: cover '> 
    </div>
    <div class="child-2 h-400px"> 
      <img src="./assets/images/blogs/IMG_0400-240x300.jpg" style='height: 100%; width: 100%; object-fit: cover '> 
    </div>
    <div class="child-3 h-400px" > 
      <img src="./assets/images/blogs/DSF1606-240x300.jpg" style='height: 100%; width: 100%; object-fit: cover'> 
    </div>
    <div class="child-4 h-400px"> 
      <img src="./assets/images/blogs/La-Marzocco-Logo-1024x1024_1024x1024-300x300.jpg" style='height: 100%; width: 100%; object-fit: cover'> 
    </div>
</div>
<!-- draggable slider end --->


<!-- draggable slider script--->
  <script>
    $('.test-slider-1').slick({ 
      dots: true,
      arrows: true,
      slidesToShow: 3,
      slidesToScroll: 2,
      infinite: false
    })

  </script>


