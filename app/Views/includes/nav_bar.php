<!--- style ---->
<style>
.header1 .top_nav .top_menu .menu-top-menu-container ul li:last-of-type a {
    color: #ffffff !important;
}

.header1 .top_nav .top_menu .menu-top-menu-container ul li:last-of-type {
    background-color: #d62135;
    padding-right: 1em;
    margin-right: 8px;
}
</style>


<?php

 $masterModel = model('MasterModel');
$productModel = model('ProductsModel');

if(!is_logged_in()){
     
$categories = $masterModel->query("SELECT id, name, slug FROM tbl_categories WHERE show_in_menu=1 AND status=1 ORDER BY sort_order");
$role = current_user_role();
$user_role_id = $role['id'];

 
}else{
$role = current_user_role();
$user_role_id = $role['id'];
$categories = $masterModel->query("SELECT 
    tbl_categories.id, 
    tbl_categories.name, 
    tbl_categories.slug 
FROM 
    tbl_categories 
JOIN 
    tbl_user_role_meta 
    ON FIND_IN_SET(tbl_categories.id, tbl_user_role_meta.meta_value) > 0
WHERE 
   tbl_user_role_meta.role_id = '$user_role_id' 
ORDER BY 
    sort_order"); 
}


?>

<!--menu nav ---------------->
<nav id="nav">
    <div class="nav_container">
        <div class="menu-main-menu-container">
            <ul id="menu-main-menu" class="menu">
                <li class="menu-item">
                    <a href="<?php echo base_url() ?>" aria-current="page">HOME</a>
                </li>
                <li class="menu-item menu-item-has-children">
                    <a href="<?php echo base_url('shop') ?>">Shop</a>
                    <ul class="sub-menu">

                        <?php /*<li class="menu-item"><a
                                    href="<?php echo base_url('shop/category/coffee') ?>">Coffee</a>
                </li>
                <li class="menu-item"><a href="<?php echo base_url('shop/category/kandula-tea') ?>">Kandula Tea</a></li>
                <li class="menu-item"><a href="<?php echo base_url('shop/category/kandula-tea') ?>">Hot Chocolate</a>
                </li>
                <li class="menu-item">
                    <a href="<?php echo base_url('shop/category/barista-training') ?>">Barista Training</a>
                </li>*/ ?>

                <?php
                            foreach($categories as $category) {
                                $catID = $category->id;
                                $cat_rule = $productModel->user_role_categories($user_role_id, $catID);
                                if(!empty($cat_rule) && $cat_rule['role_category_mode'] == 'hide') {
                                    continue;
                                }
                                ?>
                <li class="menu-item"><a
                        href="<?php echo base_url('shop/category/'.$category->slug) ?>"><?php echo $category->name ?></a>
                </li>
                <?php
                            }
                        ?>


                <li class="menu-item mt-15" style="margin-top: 18px">
                    <a href="<?php echo base_url('coffee-club-subscription') ?>">Subscriptions</a>
                </li>
                <li class="menu-item" style="margin-bottom:;"><a
                        href="<?php echo base_url('shop/category/equipment') ?>">Equipment</a>
                </li>
            </ul>
            </li>
            <li class="separator"></li>
            <li class="menu-item"><span>Our locations</span></li>
            <li class="menu-item">
                <a href="<?php echo base_url('the-roastery') ?>">The Roastery</a>
            </li>
            <li class="menu-item">
                <a href="<?php echo base_url('gwydir-st') ?>">Gwydir St.</a>
            </li>
            <li class="menu-item">
                <a href="<?php echo base_url('trumpington-st') ?>">Trumpington St.</a>
            </li>
            <li class="separator"></li>
            <li class="gap_above"><a href="<?php echo base_url('hotnumbers-menu') ?>">Our Menu</a></li>
            <li class="menu-item">
                <a href="<?php echo base_url('about-us-cafe') ?>">About Hot Numbers</a>
            </li>
            <li class="menu-item">
                <a href="<?php echo base_url('gigs-events') ?>">Gigs &#038; Events</a>
            </li>

            <?php if (!empty($pages)) {
                    foreach ($pages as $page) {
                        if(!empty($page->slug))
                        {
                            ?>
            <li class="menu-item">
                <a href="<?php echo base_url('pages/'.$page->slug ) ?>"><?php echo $page->page_title ?></a>
            </li>
            <?php
                        }
                         }
                } ?>

            <li class="menu-item">
                <a href="<?php echo base_url('blog') ?>">Blog</a>
            </li>

            <li class="menu-item">
                <a href="<?php echo base_url('contact-us') ?>">Contact Us</a>
            </li>
            <li class="menu-item">
                <a href="<?php echo base_url('become-wholesale-customer') ?>">Wholesale</a>
            </li>
            <li class="menu-item"><a href="<?php echo base_url('work-with-us') ?>">Work with us!</a></li>
            </ul>
        </div>
    </div>
</nav>
<!--menu nav end ---------------->

<div class="body_wrap">

    <header id="header1" class="header1">
        <div class="logo">
            <a href="<?php echo base_url() ?>">
                <img class="logo_front" src="<?php echo base_url() ?>/assets/images/logo.png"
                    alt="Hot Number Coffee Roasters Logo" title="Logo">
                <img class="logo_back" src="<?php echo base_url() ?>/assets/images/logo_back.png"
                    alt="Hot Number Coffee Roasters Logo" title="Logo">
            </a>
        </div>

        <div class="hamburger">
            <a class="mobmenu menuicon"
                onclick="document.body.classList.toggle('menu_open'); this.classList.toggle('close'); ">
                <span></span><span></span><span></span>
            </a>
        </div>

        <div class="top_nav">
            <div class="top_menu">
                <div class="menu-top-menu-container">
                    <ul id="menu-top-menu" class="menu">
                        <li class="menu-item menu-account menu-shop">
                            <a href="<?php echo base_url('shop') ?>">Shop</a>
                        </li>

                        <?php
                        if(is_logged_in() || is_admin()) {
                            ?>

                        <li class="nmr-logged-in show-mob menu-item">
                            <a href="<?php echo base_url('account') ?>">My Account</a>
                        </li>
                        <?php if(is_admin()) {
                                ?>
                        <li class="nmr-logged-in show-mob menu-item">
                            <a href="<?php echo base_url('admin/dashboard') ?>" target="_blank">Admin Dashboard</a>
                        </li>
                        <?php
                            }?>
                        <li class="nmr-logged-in menu-account menu-item"><a class="logout-btn"
                                href="<?php echo base_url('account/logout') ?>"> Logout</a>
                        </li>
                        <?php
                        }else {
                            ?>
                        <li class="nmr-logged-out menu-account show-mob menu-item">
                            <a href="<?php echo base_url('account') ?>"> Login </a>
                        </li>
                        <?php
                        } ?>


                    </ul>

                </div>

                <div class="cart-icon" style="margin-top:-50px;  margin-left: 354px;">
                    <a href="<?php echo base_url('cart') ?>" class="icon">
                        <span class="cart-count-number" style="display:none;"></span>
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" xml:space="preserve">
                            <path class="st0"
                                d="M25.7,15.9v-5.5c0-3.1-2.6-5.7-5.7-5.7s-5.7,2.6-5.7,5.7v5.5H5.2l2.5,19.5h24.6l2.5-19.5H25.7z M15.5,10.4 c0-2.5,2-4.5,4.5-4.5s4.5,2,4.5,4.5v5.5h-9.1V10.4z M31.2,34.1H8.8L6.5,17h7.7v7.1h1.2V17h9.1v7.1h1.2V17h7.7L31.2,34.1z">
                            </path>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </header>