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

if (!is_logged_in()) {
    // $categories = $masterModel->query("SELECT 
    //     tbl_categories.id, 
    //     tbl_categories.name, 
    //     tbl_categories.slug 
    // FROM 
    //     tbl_categories 
    // JOIN 
    //     tbl_user_role_meta 
    //     ON FIND_IN_SET(tbl_categories.id, tbl_user_role_meta.meta_value) > 0
    // WHERE 
    //    tbl_user_role_meta.role_id = '8' 
    // ORDER BY 
    //     sort_order"); 



    $categories = $masterModel->query("
    SELECT 
    tbl_categories.id, 
    tbl_categories.name, 
    tbl_categories.slug 
FROM 
    tbl_categories 
WHERE 
    (
        EXISTS (
            SELECT 1 
            FROM tbl_user_role_meta 
            WHERE role_id = '8' 
            AND meta_value = 'hide'
        )
        AND tbl_categories.id NOT IN (
            SELECT DISTINCT
                SUBSTRING_INDEX(SUBSTRING_INDEX(meta_value, ',', numbers.n), ',', -1) AS category_id
            FROM 
                tbl_user_role_meta
            JOIN
                (SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10) numbers
                ON LENGTH(meta_value) - LENGTH(REPLACE(meta_value, ',', '')) >= numbers.n - 1
            WHERE
                role_id = '8'
                AND meta_key = 'user_role_categories'
                AND meta_value <> ''
        )
    )
    OR
    (
        NOT EXISTS (
            SELECT 1 
            FROM tbl_user_role_meta 
            WHERE role_id = '8' 
            AND meta_value = 'hide'
        )
        AND tbl_categories.id IN (
            SELECT DISTINCT
                SUBSTRING_INDEX(SUBSTRING_INDEX(meta_value, ',', numbers.n), ',', -1) AS category_id
            FROM 
                tbl_user_role_meta
            JOIN
                (SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10) numbers
                ON LENGTH(meta_value) - LENGTH(REPLACE(meta_value, ',', '')) >= numbers.n - 1
            WHERE
                role_id = '8'
                AND meta_key = 'user_role_categories'
                AND meta_value <> ''
        )
    )
    AND tbl_categories.status = 1

ORDER BY 
    tbl_categories.id

");
} else {
    $role = current_user_role();
    $user_role_id = $role['id'];

    $user_category_visibility = 'user_cat_visibility_' . is_logged_in();
    $category_visibility_by_user = $masterModel->query("SELECT * FROM `tbl_settings` WHERE title = '$user_category_visibility'");

    $categories = $masterModel->query("
SELECT 
    tbl_categories.id, 
    tbl_categories.name, 
    tbl_categories.slug 
FROM 
    tbl_categories 
WHERE 
    (
        EXISTS (
            SELECT 1 
            FROM tbl_user_role_meta 
            WHERE role_id = '$user_role_id' 
            AND meta_value = 'hide'
        )
        AND tbl_categories.id NOT IN (
            SELECT DISTINCT
                TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(meta_value, ',', numbers.n), ',', -1)) AS category_id
            FROM 
                tbl_user_role_meta
            JOIN
                (
                    SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
                    UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 
                    UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 
                    UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 UNION ALL SELECT 16
                    UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20
                ) numbers
                ON LENGTH(meta_value) - LENGTH(REPLACE(meta_value, ',', '')) >= numbers.n - 1
            WHERE
                role_id = '$user_role_id'
                AND meta_key = 'user_role_categories'
                AND meta_value <> ''
        )
    )
    OR
    (
        NOT EXISTS (
            SELECT 1 
            FROM tbl_user_role_meta 
            WHERE role_id = '$user_role_id' 
            AND meta_value = 'hide'
        )
        AND tbl_categories.id IN (
            SELECT DISTINCT
                TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(meta_value, ',', numbers.n), ',', -1)) AS category_id
            FROM 
                tbl_user_role_meta
            JOIN
                (
                    SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
                    UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 
                    UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 
                    UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 UNION ALL SELECT 16
                    UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20
                ) numbers
                ON LENGTH(meta_value) - LENGTH(REPLACE(meta_value, ',', '')) >= numbers.n - 1
            WHERE
                role_id = '$user_role_id'
                AND meta_key = 'user_role_categories'
                AND meta_value <> ''
        )
    )
    AND tbl_categories.status = 1

ORDER BY 
    tbl_categories.id;

");
}


?>

<!--menu nav ---------------->
<nav id="nav">
    <div class="nav_container" id="scrollable-div">
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
                        // Fetch the first record
                        $record = $category_visibility_by_user[0];

                        // Decode the JSON value
                        $value_data = json_decode($record->value, true);

                        // Extract user permission and category IDs
                        $user_id = $value_data['user_id'];
                        $category_ids = explode(',', $value_data['category_id']);
                        $permission = $value_data['permission'];

                        if ($permission == 'allow') {
                            foreach ($category_ids as $category_id) {
                                $category_details = get_cate_name($category_id);
                        ?>
                                <li class="menu-item">
                                    <a href="<?php echo base_url('shop/category/' . $category_details['slug']) ?>">
                                        <?php echo $category_details['name'] ?>
                                    </a>
                                </li>
                            <?php
                            }
                        } elseif ($permission == 'disallow') {
                            // Skip categories in $category_ids and show only others
                            foreach ($categories as $category) {
                                if (in_array($category->id, $category_ids)) {
                                    continue; // Skip disallowed category IDs
                                }
                                $catID = $category->id;
                                $cat_rule = $productModel->user_role_categories($user_role_id, $catID);
                                if (!empty($cat_rule) && $cat_rule['role_category_mode'] == 'hide') {
                                    continue; // Skip hidden categories
                                }
                            ?>
                                <li class="menu-item">
                                    <a href="<?php echo base_url('shop/category/' . $category->slug) ?>">
                                        <?php echo $category->name ?>
                                    </a>
                                </li>
                            <?php
                            }
                        } else {
                            // Default behavior if permission is neither "allow" nor "disallowed"
                            foreach ($categories as $category) {
                                $catID = $category->id;
                                $cat_rule = $productModel->user_role_categories($user_role_id, $catID);
                                if (!empty($cat_rule) && $cat_rule['role_category_mode'] == 'hide') {
                                    continue; // Skip hidden categories
                                }
                            ?>
                                <li class="menu-item">
                                    <a href="<?php echo base_url('shop/category/' . $category->slug) ?>">
                                        <?php echo $category->name ?>
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>



                        <li class="menu-item mt-15" style="margin-top: 18px">
                            <a href="<?php echo base_url('coffee-club-subscription') ?>">Subscriptions</a>
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
                        if (!empty($page->slug)) {
                ?>
                            <li class="menu-item">
                                <a href="<?php echo base_url('pages/' . $page->slug) ?>"><?php echo $page->page_title ?></a>
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
                        if (is_logged_in() || is_admin()) {
                        ?>

                            <li class="nmr-logged-in show-mob menu-item">
                                <a href="<?php echo base_url('account') ?>">My Account</a>
                            </li>
                            <?php if (is_admin()) {
                            ?>
                                <li class="nmr-logged-in show-mob menu-item">
                                    <a href="<?php echo base_url('admin/dashboard') ?>" target="_blank">Admin Dashboard</a>
                                </li>
                            <?php
                            } ?>
                            <li class="nmr-logged-in menu-account menu-item"><a class="logout-btn"
                                    href="<?php echo base_url('account/logout') ?>"> Logout</a>
                            </li>
                        <?php
                        } else {
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