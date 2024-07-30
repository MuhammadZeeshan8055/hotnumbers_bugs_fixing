<?php
$request = service('request');
echo view('admin/includes/header');
$masterModel = model('masterModel');
$get_count = $masterModel->query("SELECT COUNT(order_id) AS total FROM tbl_orders WHERE status='processing'",true,true);
$processing_order_count = $get_count['total'];

$unread_notifications = $masterModel->query("SELECT COUNT(notification_id) AS total FROM tbl_notifications WHERE is_read=0",true,true);
$unread_notifications = $unread_notifications['total'];

$req_count = $masterModel->getRow('tbl_request_acc',['status'=>'pending'],'','COUNT(req_id) AS count');
$wholesale_acc_count = $req_count['count'];

if(is_logged_in()) {
    $user = model('UserModel');

    $get_user = $user->get_user();
}
?>
<div class="row no-gutters">
    <div id="mySidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <button type="button" class="menu-toggle" onclick="menu_collapse_toggle()"><i class="lni lni-menu"></i></button>
        <div class="left_sidebar">
            <div class="logo_admin">
                <a href="<?php echo base_url('admin/dashboard') ?>"> <img src="<?php echo base_url('assets/images/favicon-1.png') ?>" width="100"></a>
            </div>
            <div class="navigation_bar">
                <ul class="profile_list">
                    <li><a class="<?php echo ($page == 'dashboard') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/dashboard"><i class="lni lni-dashboard"></i> Dashboard</a></li>

                    <li>
                        <a class=" <?php echo ($page == 'notifications') ? 'active' : '' ?>" href="<?php echo base_url(ADMIN); ?>/notifications"> <i class="lni lni-alarm"></i> Notifications <?php if(!empty($unread_notifications)) { ?><div class="number-count"><?php echo $unread_notifications ?></div> <?php } ?></a>
                    </li>


                    <li><hr></li>
                    <li><a class=" <?php echo ($page == 'page') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/pages"><i class="lni lni-book"></i> Pages</a>
                        <ul class="sub-menu">
                            <li>
                                <a class=" <?php echo ($page == 'page-add') ? 'active' : '' ?>"
                                   href="<?php echo base_url(ADMIN); ?>/pages/add">
                                    Add Page</a>
                            </li>
                            <li>
                                <a class="<?php echo ($page == 'page-categories') ? 'active' : '' ?>"
                                   href="<?php echo base_url(ADMIN); ?>/page-categories"> Page categories</a>
                            </li>
                        </ul>
                    </li>

                    <li><hr></li>

                    <li>
                        <a class="<?php echo ($page == 'product') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/products"> <i class="lni lni-bookmark"></i>  Products</a>
                        <ul class="sub-menu">
                            <li>
                                <a class="<?php echo ($page == 'product-add') ? 'active' : '' ?>"
                                   href="<?php echo base_url(ADMIN); ?>/products/add">Add Product</a>
                            </li>
                            <li>
                                <a class="<?php echo ($page == 'product-categories') ? 'active' : '' ?>"
                                   href="<?php echo base_url(ADMIN); ?>/product-categories"> Product Categories</a>
                            </li>
                        </ul>
                    </li>

                    <li><a class=" <?php echo ($page == 'orders') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/orders?status=<?php echo get_setting('default_order_listing_status') ?>">
                            <i class="lni lni-cash-app"></i>  Orders <?php if(!empty($processing_order_count)) { ?><div class="number-count"><?php echo $processing_order_count ?></div> <?php } ?></a>
                         <ul class="sub-menu">
                            <li>
                               <a class="<?php echo ($page == 'retail_orders') ? 'active' : '' ?>"
                                   href="<?php echo base_url(ADMIN); ?>/retail-orders?status=processing&sort-cols=10&sort-orders=desc&page=25">Retail</a>
                            </li>
                            <li>
                               <a class="<?php echo ($page == 'subscriptions_orders') ? 'active' : '' ?>"
                                   href="<?php echo base_url(ADMIN); ?>/subscriptions-orders?status=processing&sort-cols=10&sort-orders=desc&page=25">Subscriptions</a>
                            </li>
                            <li>
                               <a class="<?php echo ($page == 'whole_sale_orders') ? 'active' : '' ?>"
                                   href="<?php echo base_url(ADMIN); ?>/whole-sale-orders?status=processing&sort-cols=10&sort-orders=desc&page=25">Wholesale</a>
                            </li>
                            <li>
                               <a class="<?php echo ($page == 'internal_orders') ? 'active' : '' ?>"
                                   href="<?php echo base_url(ADMIN); ?>/internal-orders?status=processing&sort-cols=10&sort-orders=desc&page=25">Internal</a>
                            </li>
                        </ul>
                    </li>

                    <?php if(get_setting('subscription_enabled')) { ?>
                        <li><a class=" <?php echo ($page == 'subscriptions') ? 'active' : '' ?>"
                               href="<?php echo base_url(ADMIN); ?>/subscriptions?sort-cols=0&sort-orders=desc&page=0"><i class="lni lni-pulse"></i> Subscriptions</a>
                             <?php /*<ul class="sub-menu">
                                <li>
                                    <a class="<?php echo ($page == 'subscription-plans') ? 'active' : '' ?>" href="<?php echo base_url(ADMIN); ?>/subscription-plans">Subscription Plans</a>
                                </li>
                            </ul>*/ ?>
                        </li>
                    <?php } ?>

                    <li>
                        <a class="<?php echo ($page == 'show-hide-products') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/show-hide-products"><i class="lni lni-eye"></i> Product Visibility</a>
                    </li>

                    <li>
                        <a class="<?php echo ($page == 'coupons') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/coupons"><i class="lni lni-ticket"></i>  Coupons</a>
                    </li>

                    <li><hr></li>

                    <li><a class=" <?php echo ($page == 'blog') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/blog">
                            <i class="lni lni-postcard"></i> Blog Posts</a>
                    </li>

                    <li><a class="<?php echo ($page == 'add_post') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/blog/add">
                            <i class="lni lni-plus"></i> Add Post</a>
                    </li>

                    <li><a class="<?php echo ($page == 'post-categories') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/post-categories"> <i class="lni lni-list"></i> Post categories</a>
                    </li>



                    <?php
                    /* <li>
                        <a class="<?php echo ($page == 'gigs_events') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/gigs_events    ">
                            <i class="icon icon-heart"></i>Gigs & Events</a>
                    </li>*/
                    ?>

                    <li><hr></li>

                    <li>
                        <a class=" <?php echo ($page == 'emails') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/email-templates"><i class="lni lni-envelope"></i> Emails Templates</a>
                    </li>

                    <?php /*<li>
                        <a class=" <?php echo ($page == 'add-email-template') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/add-email-template"> Add Emails Templates</a>
                    </li>*/ ?>

                    <li>
                        <a class=" <?php echo ($page == 'email-logs') ? 'active' : '' ?>" href="<?php echo base_url(ADMIN); ?>/email-logs"> <i class="lni lni-timer"></i> Emails Logs</a>
                    </li>


                    <li><hr></li>


                    <li><a class=" <?php echo ($page == 'media-library') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/media-library">
                            <i class="lni lni-image"></i> Media Gallery</a>
                    </li>

                    <li><a class=" <?php echo ($page == 'users') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/users?sort-cols=0&sort-orders=desc&page=0"><i class="lni lni-users"></i> Users</a>
                        <ul class="sub-menu">
                            <li><a class=" <?php echo ($page == 'roles') ? 'active' : '' ?>"
                                   href="<?php echo base_url(ADMIN); ?>/user-roles">User roles</a></li>
                        </ul>
                    </li>

                    <li><a href="<?php echo admin_url() ?>wholesale-accounts?sort-cols=0&sort-orders=desc" class="<?php echo ($page == 'wholesale-accounts') ? 'active' : '' ?>"><i class="lni lni-ticket-alt"></i> Wholesale Registrations <?php if(!empty($wholesale_acc_count)) { ?><div class="number-count"><?php echo $wholesale_acc_count ?></div> <?php } ?></a>  </li>

                    <li><a class=" <?php echo ($page == 'profile') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/profile">
                            <i class="lni lni-user"></i> Profile</a></li>

                    <li><hr></li>

                    <li><a class=" <?php echo ($page == 'setting') ? 'active' : '' ?>"
                           href="<?php echo base_url(ADMIN); ?>/settings">
                            <i class="lni lni-cog"></i>  Settings</a>
                    </li>

            </div>

            <script>
                $(function() {
                    $('.left_sidebar .navigation_bar .profile_list').children().each(function() {
                        $(this).on('mouseover', function() {
                            const w = $(this).width();
                            const pos_x = $(this).offset().left;
                            const pos_y = $(this).position().top;
                            $(this).find('.sub-menu').css({left:w,top:pos_y});
                        });
                    });
                });

                const menu_collapse_toggle = ()=> {
                    $('body').toggleClass('sidebar-collapse');
                    if($('body').hasClass('sidebar-collapse')) {
                        localStorage.setItem('sidebar-collapse',true);
                    }else {
                        localStorage.setItem('sidebar-collapse',false);
                    }
                }
            </script>

            <style>
                .number-count {
                    aspect-ratio: 1 / 1;
                    margin-left: 1em;
                    background-color: var(--base-color);
                    font-size: 12px;
                    width: 26px;
                    max-width: initial;
                    height: 26px;
                    min-width: fit-content;
                    margin-bottom: -8px;
                    overflow: hidden;
                    margin-top: -8px;
                    text-indent: 0px;
                    text-align: center;
                    line-height: 26px;
                    border-radius: 100px;
                    box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
                }
            </style>

        </div>
    </div>


    <div class="admin_nav_bar flex_end">
        <button class="openbtn" onclick="openNav()">☰</button>

        <a href="<?php echo base_url()?>" target="_blank">Logged in as <?=$get_user->email?></a>

        <a href="<?php echo base_url()?>" target="_blank">Open Website</a>

        <a data-confirm="Are you sure to logout?" href="#" data-href="<?php echo base_url(ADMIN."/logout")?>">Logout</a>

    </div>



