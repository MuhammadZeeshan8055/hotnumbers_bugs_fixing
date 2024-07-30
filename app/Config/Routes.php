<?php


namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override(function()
{
    helper('functions');
    echo view('404');
});
$routes->setAutoRoute(false);


/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes->get('/crone-subscriptions', 'Cron::subscriptions');

$routes->get( 'account', 'LoginController::index');
$routes->get( 'login', 'LoginController::index');

$routes->post('ajax/process_login', 'LoginController::process_login');
$routes->post('ajax/process_register', 'LoginController::register_customer');

$routes->get('account/user-verification/(:any)', 'LoginController::register_code_verification/$1');
$routes->match(['get', 'post'],'reset-password(:any)', 'LoginController::reset_password');
$routes->get('account/password-reset/(:any)', 'LoginController::register_password_reset/$1');
$routes->post('account/renew-password', 'LoginController::password_renew');


$routes->post('payment/paypal_ipn', 'Payment::paypal_ipn');
$routes->get('payment/success/(:any)', 'Payment::success_page/$1');

$routes->match(['post', 'get'],'payment/transaction', 'Payment::paypal_transaction');

$routes->group('account', ['filter' => 'AccountFilter'], function ($routes) {
//  $routes->add('login_action', 'Account::login_action');
    $routes->get('dashboard', 'Account::dashboard');
    $routes->get('orders', 'Account::orders');
    $routes->get('orders/pay/(:any)', 'Account::pay_order/$1');
    $routes->get('orders/order-again/(:num)', 'Account::order_remake/$1');
    $routes->get('orders/(:any)', 'Account::view_order/$1');
    $routes->get('edit-address', 'Account::edit_address');
    $routes->match(['get', 'post'], 'edit-address/billing', 'Account::edit_billing');
    $routes->match(['get', 'post'], 'edit-address/shipping', 'Account::edit_shipping');
    $routes->match(['get', 'post'], 'edit-account', 'Account::edit_account');
    $routes->get('payment-methods', 'Account::payment_methods');
    $routes->get('appointments', 'Account::get_appointments');
    $routes->get('appointments', 'Account::get_appointments');
    $routes->get('subscriptions', 'Account::get_subscriptions');
    $routes->get('subscription/pay/(:any)', 'Account::pay_subscription/$1');
    $routes->get('subscription/view/(:any)', 'Account::view_subscription/$1');
    $routes->get('logout', 'LoginController::logout_user');
    $routes->post('add-payment-method', 'Account::add_payment_method');
    $routes->post('enable-payment-invoice', 'Account::toggle_payby_invoice');
    $routes->get('disable-payment-method/(:any)', 'Account::disable_payment_method/$1');
});

$routes->get('shop', 'Shop::index');
$routes->get('shop/category/(:any)', 'Shop::shop_by_category/$1');
$routes->get('shop/product/getvariation', 'Shop::get_product_variation');
$routes->get('shop/product/(:any)', 'Shop::product_page/$1');
$routes->get('addressautocomplete/(:any)', 'Shop::getAddressAutoComplete/$1');
$routes->get('getaddressdata/(:any)', 'Shop::getAddressData/$1/$2');

$routes->get('coffee-club-subscription', 'Subscription::index');
$routes->match(['post', 'get'], 'coffee-club-subscription/checkout', 'Subscription::checkout');
$routes->post('coffee-club-subscription/submit', 'Subscription::submit');
$routes->post('coffee-club-subscription/coffee-variation-data', 'Subscription::coffee_variations_data');

$routes->get('barista-training', 'Barista_training::index');
$routes->get('group_experience_workshop', 'Group_experience_workshop::index');


$routes->get('terms_conditions_retail', 'Terms_conditions_retail::index');
$routes->get('privacy_policy', 'Privacy_policy::index');
$routes->get('refunds_cancellations_retail', 'Refunds_cancellations_retail::index');
$routes->get('ordering_faqs_wholesale', 'Ordering_faqs_wholesale::index');


$routes->get('shop_coffee', 'Shop_coffee::index');

$routes->get('cart', 'Cart::index');

$routes->post('cart/remove_item', 'Cart::remove_item');

$routes->post('cart/checkout', 'Cart::checkout_post');
$routes->get('cart/checkout', 'Cart::checkout_form');
$routes->post('cart/create-order', 'Cart::create_order');
$routes->get('cart/order-complete', 'Cart::order_complete');
$routes->post('cart/applycode', 'Cart::applyCouponCode');
$routes->get('cart/destroy', 'Cart::cart_destroy');
$routes->post('cart/cart-process', 'Cart::cart_process');
$routes->post('cart/create-paypal-order', 'Cart::create_paypal_order');
$routes->post('cart/capture-paypal-order', 'Cart::capture_paypal_order');

//Ajax
$routes->post('ajax/updatequantity', 'Ajax::update_item_quantity');
$routes->post('ajax/add_cart', 'Ajax::add_cart');
$routes->get('ajax/get_cart', 'Ajax::get_cart');
$routes->get('ajax/sub_plan_expire_options', 'Ajax::sub_plan_expire_options');
$routes->get('ajax/shop_subscription_form', 'Ajax::shop_subscription_form');
$routes->post('ajax/check-email-exists', 'Ajax::check_email_exists');
$routes->post('ajax/switch_shipping_method', 'Cart::switch_shipping_method');

//// edit accounts

$routes->get('blog', 'Blog::index');
$routes->get('blog/(:any)', 'Blog::details/$1');
$routes->get('event/(:any)', 'Blog::details/$1');

$routes->post('blog/loadmore', 'Blog::ajaxlist');

//$routes->get('blog', 'Blog::index');
//$routes->get('blog(:any)', 'Blog::details');

$routes->get('the-roastery', 'Page::roastery');
$routes->get('gwydir-st', 'Page::gwydir');
$routes->get('about-us-cafe', 'Page::about_us_cafe');
$routes->get('gigs-events', 'Page::gigs_events');
$routes->get('trumpington-st', 'Page::trumpington');
$routes->get('hotnumbers-menu', 'Page::hotnumbers_menu');
$routes->match(['get','post'],'contact-us', 'Page::contact_us');
$routes->get('become-wholesale-customer', 'Page::become_wholesale_customer');
$routes->post('wholesale-request', 'Account::wholesale_request');

$routes->get('privacy-policy', 'Page::privacy_policy');
$routes->get('terms-conditions-retail', 'Page::terms_conditions_retail');
$routes->get('refunds-cancellations-retail', 'Page::refunds_cancellations_retail');
$routes->get('ordering-faqs-wholesale', 'Page::ordering_faqs_wholesale');

$routes->get('work-with-us', 'Page::workwithus');
$routes->post('recruitment-post', 'Page::recruitment_post');


//pages
$routes->get('pages/(:any)', 'Page::index');



//////////////////gigs////////////////////////////
//$routes->get('gigs-events', 'Events::index');
//$routes->match(['get', 'post'], 'events/ajaxlist', 'Events::ajaxlist');


//$routes->get('admin', 'AdminController::index');

$routes->get('captain', 'Admin/AdminController::login');
$routes->get('admin/login', 'Admin/AdminController::login');
$routes->post('admin/authentication', 'Admin/AdminController::authentication');


$routes->group('admin', ['filter' => 'AdminFilter'], static function ($routes) {

    $routes->get('products', 'Admin/Products::index');
    $routes->get('trash-products/(:any)', 'Admin\Products::trash_products/$1');
    $routes->get('products/trash/(:any)', 'Admin\Products::trash_product/$1');
    $routes->match(['get', 'post'], 'products/add(:any)', 'Admin\Products::add/$1');
    $routes->get('products/delete/(:any)', 'Admin\Products::delete/$1');
    $routes->post('products/update_variations/(:any)', 'Admin\Products::update_variations/$1');
    $routes->match(['get', 'post'],'show-hide-products', 'Admin/Products::show_hide_products');
    $routes->post('product-sortorder', 'Admin/Products::product_sortorder');

    $routes->get('coupons', 'Admin/Products::coupon_list');
    $routes->post('coupons', 'Admin/Products::coupon_add');
    $routes->post('coupons/toggle', 'Admin/Products::coupon_toggle');


    $routes->get('product-categories', 'Admin/Categories::product_categories');

    $routes->post('product-category-sortorder', 'Admin/Categories::product_category_sortorder');
    $routes->get('page-categories', 'Admin/Categories::page_categories');
    $routes->match(['get', 'post'], 'product-categories/add(:any)', 'Admin/Categories::add');
    $routes->get('categories/delete/(:any)', 'Admin\Categories::delete/$1');

    $routes->match(['get', 'post'], 'profile(:any)', 'Admin/AdminController::profile');
    $routes->match(['get', 'post'], 'settings(:any)', 'Admin/AdminController::settings');

    $routes->get('users', 'Admin/Users::index');
    $routes->get('users/login/(:any)', 'Admin\Users::force_user_login/$1');
    $routes->get('administrators', 'Admin/Users::administrators');
    $routes->get('guests', 'Admin/Users::guests');
    $routes->match(['get', 'post'], 'users/add(:any)', 'Admin/Users::add');
    $routes->get('users/edit(:any)', 'Admin/Users::edit');
    $routes->match(['get', 'post'], 'user-roles', 'Admin/Users::user_roles');
    $routes->get('wholesale-accounts', 'Admin/Users::wholesale_requests');
    $routes->post('wholesale-account-note-add', 'Admin/Users::wholesale_customer_note_add');

    $routes->get('logout', 'Admin/AdminController::logout');
    $routes->get('dashboard', 'Admin/Dashboard::index');

    $routes->get('email-templates', 'EmailController::admin_list_email_templates');
    $routes->get('add-email-template', 'EmailController::admin_add_email_template');
    $routes->post('email-templates/add', 'EmailController::admin_insert_email_template');
    $routes->get('email-templates/view/(:any)', 'EmailController::admin_view_email_template/$1');
    $routes->post('email-templates/update', 'EmailController::admin_update_email_template/$1');
    $routes->get('email-logs', 'EmailController::admin_email_logs');

    $routes->get('notifications', 'Admin/NotificationController::index');

    $routes->get('blog', 'Admin/Blog::index');
    $routes->get('blog/add(:any)', 'Admin/Blog::add');
    $routes->post('blog/add_post', 'Admin/Blog::add_post');
    $routes->get('post-categories', 'Admin/Categories::post_categories');


    //wholesale orders
    $routes->match(['get', 'post'], 'whole-sale-orders', 'Admin/Orders::whole_sale_orders');
    //internal orders
    $routes->match(['get', 'post'], 'internal-orders', 'Admin/Orders::internal_orders');
    //internal orders
    $routes->match(['get', 'post'], 'retail-orders', 'Admin/Orders::retail_orders');
    //Subscriptions orders
    $routes->match(['get', 'post'], 'subscriptions-orders', 'Admin/Orders::subscriptions_orders');

    //Orders
    $routes->match(['get', 'post'], 'orders', 'Admin/Orders::index');
    $routes->match(['get', 'post'], 'orders/add', 'Admin/Orders::add_order');
    $routes->match(['get', 'post'], 'orders/view/(:any)', 'Admin\Orders::view_order/$1');
    $routes->match(['get', 'post'], 'orders/edit/(:any)', 'Admin\Orders::add_order/$1');
    $routes->match(['get', 'post'], 'shipping-methods', 'Admin/Orders::shipping_methods');
    $routes->get('generate-pdf-slip/(:any)', 'Admin\Orders::generate_pdf_slip/$1');
    $routes->get('generate-invoice-slip/(:any)', 'Admin\Orders::generate_invoice_slip/$1');
    $routes->get('generate-order-csv/(:any)', 'Admin\Orders::generate_order_csv/$1');
    $routes->get('delete-orders/(:any)', 'Admin\Orders::delete_orders/$1');
    $routes->get('change-order-status/(:any)', 'Admin\Orders::change_order_status/$1');
    $routes->post('order/force_refund_status','Shop::set_refund_status');

    $routes->match(['get', 'post'], 'orders/send_status_update(:any)', 'Admin/Orders::send_status_update');
    //$routes->get('/settings', 'Admin/AdminController::settings');

    //Subscriptions
    $routes->match(['get', 'post'],'subscription-settings', 'Admin/Orders::subscriptions_settings');
    $routes->match(['get', 'post'], 'subscription/(:num)', 'Admin\Orders::view_order/$1');
    $routes->get('subscriptions', 'Admin\Orders::subscriptions_index');
    $routes->match(['get', 'post'],'subscription-plans', 'Admin\Orders::subscription_plans');

    $routes->get('shop-settings', 'Admin/Products::shopping_settings');

    $routes->match(['get', 'post'], 'shipping-methods', 'Admin/Orders::shipping_methods');


    $routes->get('orders/generate(:any)', 'Admin/Orders::HTMLPdfController');
    $routes->get('orders/PdfDownload(:any)', 'Admin/Orders::PdfDownload');

    $routes->get('gigs_events', 'Admin/Blog::gig_event_listing');
    $routes->match(['get', 'post'], 'gigs_events/add(:any)', 'Admin/Gigsevents::add');


    $routes->get('pages', 'Admin/Pages::index');
    $routes->match(['get', 'post'],'pages/add(:any)', 'Admin/Pages::add');
    $routes->post('save_page', 'Admin/Pages::save_page');
    $routes->get('pages/delete/(:num)', 'Admin\Pages::deletePage/$1');
    $routes->get('pages/getwidgetview/(:any)', 'Admin\Pages::getWidget/$1');

    $routes->get('media-library', 'Admin/MediaController::index');
    $routes->get('media-library-frame', 'Admin/MediaController::media_gallery_frame');
    $routes->post('media-library/upload-media', 'Admin/MediaController::media_upload');
    $routes->get('media-library/delete-media/(:num)', 'Admin\MediaController::delete_media/$1');
    $routes->get('media-library/media_path_by_id/(:num)', 'Admin\MediaController::media_path_by_id/$1');


    //Ajax

    $routes->get('ajax/get_product_info_ajax/(:num)', 'Admin\AjaxController::admin_product_info_json/$1');
    $routes->get('ajax/product_list_json', 'Admin\AjaxController::product_list_json');
    $routes->get('ajax/product_categories_json', 'Admin/AjaxController::product_categories_json');
    $routes->get('ajax/user_list_json', 'Admin/AjaxController::user_list_json');
    $routes->get('ajax/user_roles_json', 'Admin/AjaxController::user_roles_json');
    $routes->get('ajax/coupon-info-json/(:num)', 'Admin\AjaxController::coupon_info_json/$1');
    $routes->post('ajax/address-tax-ajax', 'Admin/AjaxController::address_tax_json');
    $routes->get('ajax/get-order-preview/(:any)', 'Admin\AjaxController::get_order_preview/$1');
    $routes->get('ajax/get-email-preview/(:num)', 'Admin\AjaxController::get_email_preview/$1');
    $routes->get('ajax/edit-product-form-data', 'Admin\AjaxController::edit_product_form_table');
    $routes->post('ajax/emailtemplate-sortorder', 'Admin\AjaxController::email_template_sort_order');
    $routes->get('ajax/wholesale-customer-notes/(:num)', 'Admin\AjaxController::wholesale_customer_notes/$i');
    $routes->post('ajax/add-shipping-rule', 'Admin\AjaxController::add_shipping_rule');
    $routes->post('ajax/remove-shipping-rule', 'Admin\AjaxController::remove_shipping_rule');
    $routes->get('ajax/add_product_attribute', 'Admin\AjaxController::add_product_attribute');
    $routes->get('ajax/add_product_variation', 'Admin\AjaxController::add_product_variation');
    $routes->get('ajax/check-product-slug', 'Admin\AjaxController::check_product_slug');

//    $routes->get('ckfinder', 'Admin/AdminController::ckfinder');
//    $routes->match(['get','post'],'ckfinder/connector', 'Admin/AdminController::ckfinder_connector');

});

$routes->get('sql-sync','Admin/SqlSync::index');

$routes->get('/', 'Home::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
