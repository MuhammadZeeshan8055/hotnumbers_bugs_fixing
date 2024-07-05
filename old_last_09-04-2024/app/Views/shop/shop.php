<?php echo view( 'includes/header');?>

    <style>
        .row > * {
        flex-shrink: 0;
        width: 100%;
        max-width: 100%;
        padding-right: unset;
        padding-left: unset;
        margin-top: unset;
        }
        .header-caption {
            padding: 40px 0 22px;
            text-align: center;
            max-width: 90%;
            margin: auto;
            display: table;
            font-size: 1.2em;
        }
     </style>


<!---Main body ----->

<div class="underbanner" style="background-image:url(<?php echo base_url('./assets/images/shop/banner.jpg') ?>);" > </div>
<div id="shop" class="woocommerce wrapper content-area">
    <div class="container home-container" role="main">

        <nav class="woocommerce-breadcrumb"><a href="<?php echo base_url() ?>">Home</a>&nbsp;&#47;&nbsp;<?php echo @$title; ?></nav>
        <div class="shop_container">
            <div class="header">
                <br>
                <div class="container">
            <?php if($list_type == 'category') {
                ?>
                <!--header of shoping----->
                        <div class="pull-left">  <h1 style="text-transform: none;"><?php echo $title ?></h1></div>
                        <div class="pull-right"><h5 style="margin-top: 32px;">Please select an item</h5></div>
                        <div class="clear"></div>
                <?php
            }
            if(!empty($caption)) {
                ?>
               <div class="header-caption">
                   <p><?php echo $caption ?></p>
               </div>
                    <?php
            }
            ?>

                </div>
            </div>

        <div class="body woocommerce">
            <?php if(!empty($loop_data)){ ?>
            <div class="container">

                <ul class="products column-2">
                    <?php
                        foreach($loop_data as $row)
                        {
                    ?>
                    <!-- list pic ---->
                            <li class="">
                                <div>
                                    <a href="<?php echo site_url($row['url']) ?>">
                                        <img src="<?php echo $row['image'] ?> ">
                                       
                                        <span><?php echo $row['title']; ?></span>
                                    </a>
                                </div>
                            </li>
                    <?php        
                        }
                    ?>
                </ul>

            </div>
            <div class="bottom_arrow">
                <img src="<?php echo base_url('./assets/images/shop/icon-coffee-small.png') ?>" width="32"> 
            </div>
            <?php }else {
                ?>
            <div class="container">
                <h3 class="white_out">No <?php echo $list_type ?> found</h3>
            </div>
                <?php
            }?>
        </div>
        
    </div>


<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


