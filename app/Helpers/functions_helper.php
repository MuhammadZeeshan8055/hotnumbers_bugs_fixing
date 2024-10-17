<?php

if(get_setting('currency')) {
    $currency = get_currencies(get_setting('currency'));
    define('currency_symbol',$currency['symbol']);
}else {
    define('currency_symbol','£');
}

function has_subscription($uid) {

    // $sql="SELECT tbl_orders.customer_user,tbl_order_items.order_id,tbl_order_item_meta.item_id,tbl_order_item_meta.meta_key,tbl_order_item_meta.meta_value FROM `tbl_orders` join tbl_order_items ON tbl_orders.order_id=tbl_order_items.order_id join tbl_order_item_meta on tbl_order_items.order_item_id=tbl_order_item_meta.item_id where tbl_order_item_meta.meta_key='type' and tbl_order_item_meta.meta_value='club_subscription' and tbl_orders.customer_user='$uid'";
    $sql="SELECT tbl_orders.customer_user,tbl_order_items.order_id FROM `tbl_orders` join tbl_order_items ON tbl_orders.order_id=tbl_order_items.order_id where tbl_orders.order_type='shop_subscription' and tbl_orders.customer_user='$uid'";
    $master = model('MasterModel');
    $q = $master->query($sql, true, true);
    return $q;
}

// function get_subscription_item_total($uid,$transaction_id){
//     $sql="SELECT tbl_orders.order_id,tbl_orders.transaction_id,tbl_order_items.order_item_id,tbl_order_items.product_name,tbl_order_item_meta.meta_key,tbl_order_item_meta.meta_value FROM `tbl_orders` JOIN tbl_order_items on tbl_orders.order_id=tbl_order_items.order_id join tbl_order_item_meta on tbl_order_items.order_item_id=tbl_order_item_meta.item_id where tbl_orders.transaction_id='$transaction_id' and tbl_order_item_meta.meta_key='price' and tbl_order_items.product_name='The Coffee Club'";
//     $master = model('MasterModel');
//     $q = $master->query($sql, true, true);
//     return $q;
// }

function get_product_status_by_id($pid){
    $sql = "SELECT title,status,img FROM `tbl_products` WHERE status='publish' and id='$pid'";
    $master = model('MasterModel');
    $q = $master->query($sql, true, true);
    return $q;
}
function get_product_images_by_id($pid){
    $sql = "SELECT path FROM `tbl_files` WHERE id='$pid'";
    $master = model('MasterModel');
    $q = $master->query($sql, true, true);
    return $q;
}
function record_exists_in_notifications($content){
    $sql = "SELECT * FROM `tbl_notifications` WHERE content='$content'";
    $master = model('MasterModel');
    $q = $master->query($sql, true, true);
    return !empty($q); // Return true if the query result is not empty, false otherwise
}
function get_variation_starting_price($pid){
    $sql = "SELECT variation FROM `tbl_product_variations` WHERE product_id='$pid';";
    $master = model('MasterModel');
    $q = $master->query($sql, true, true);
    return $q; // Return true if the query result is not empty, false otherwise
}
function get_variation_product_price($pid){
    $sql = "SELECT variation FROM `tbl_product_variations` WHERE product_id='$pid';";
    $master = model('MasterModel');
    $q = $master->query($sql, true, true);
    return $q; // Return true if the query result is not empty, false otherwise
}

function already_has_subscription_in_cart($uid){
    $sql = "SELECT * FROM tbl_carts WHERE user_id = '$uid' AND JSON_CONTAINS(cart_data, '{\"type\": \"club_subscription\"}', '$.products') order by id desc LIMIT 1";
    $master = model('MasterModel');
    $q = $master->query($sql, true, true);
    return $q;
}

function global_wholesale_discount_value(){
    $sql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(value, '\$.\"4\".role_discount')) AS role_discount 
        FROM tbl_settings 
        WHERE title = 'user_discount'";

    $master = model('MasterModel');
    $q = $master->query($sql, true, true);
    return $q;
}


//manage stock for variations
function check_manage_stock_for_variations($pid){
    $sql = "SELECT variation FROM `tbl_product_variations` WHERE product_id='$pid'";
    $master = model('MasterModel');
    $q = $master->query($sql, true, true);
    return $q;
}

function get_variable_product_stock($pid) {
        $sql = "SELECT SUM(stock) AS total_stock
        FROM (
        SELECT JSON_UNQUOTE(JSON_EXTRACT(variation, CONCAT('$[', idx, '].values.stock'))) AS stock
        FROM tbl_product_variations
        JOIN (
            SELECT 0 AS idx UNION ALL
            SELECT 1 UNION ALL
            SELECT 2 UNION ALL
            SELECT 3 UNION ALL
            SELECT 4 UNION ALL
            SELECT 5 UNION ALL
            SELECT 6 UNION ALL
            SELECT 7 UNION ALL
            SELECT 8 UNION ALL
            SELECT 9
        ) AS indices
            WHERE product_id = '$pid'
            AND idx < JSON_LENGTH(variation)
        ) AS stock_values
        ";
        
        $master = model('MasterModel');
        $q = $master->query($sql, true, true);
        return $q;
}
function get_variable_product_stock_zero($pid) {

    // $sql = "
    //     SELECT 
    //     SUM(stock) AS total_stock, 
    //     MAX(CASE 
    //         WHEN stock REGEXP '^[0-9]+$' THEN CASE 
    //             WHEN stock = 0 THEN 1 
    //             ELSE 0 
    //         END 
    //         ELSE NULL 
    //     END) AS has_zero_stock 
    //     FROM (
    //         SELECT 
    //             JSON_UNQUOTE(JSON_EXTRACT(variation, CONCAT('$[', idx, '].values.stock'))) AS stock 
    //         FROM 
    //             tbl_product_variations 
    //         JOIN (
    //             SELECT 0 AS idx UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 
    //             UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 
    //             UNION ALL SELECT 8 UNION ALL SELECT 9 
    //         ) AS indices 
    //         WHERE 
    //             product_id = '$pid' 
    //             AND idx < JSON_LENGTH(variation)
    //     ) AS stock_values;
    // ";

    $sql = "
      SELECT SUM(stock) AS total_stock, MAX(CASE WHEN stock = 0 THEN 1 ELSE 0 END) AS has_zero_stock FROM ( SELECT JSON_UNQUOTE(JSON_EXTRACT(variation, CONCAT('$[', idx, '].values.stock'))) AS stock FROM tbl_product_variations JOIN ( SELECT 0 AS idx UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS indices WHERE product_id = '$pid' AND idx < JSON_LENGTH(variation) ) AS stock_values
    ";
    $master = model('MasterModel');
    $q = $master->query($sql, true, true);
    return $q;
}


function php_errors() {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}


function set_session($key = '', $value = '')
{
    $session = session();
    $session->set($key, $value);
}

function get_session($key = '')
{
    $session = session();
    return session()->get($key);
}

function current_page() {
    $uri = service('uri');
    $segments = $uri->getSegments();
    return end($segments);
}

function toSlugUrl($text)
{
    $text = trim($text);
    $text = preg_replace('/[^A-Za-z0-9-]+/', '-', $text);
    $text = str_replace(["--"], '-', $text);
    $text = trim($text,'/[^A-Za-z0-9-]+/');

    return strtolower($text);
}

function curl_get($url='') {
    $cURLConnection = curl_init();
    curl_setopt($cURLConnection, CURLOPT_URL, $url);
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($cURLConnection);
    curl_close($cURLConnection);
    return $result;
}

function post_statuses() {
    return [
        'publish',
        'private',
        'draft',
        'Trash'
    ];
}

// function _order_number($number, $prefix='') {
//     $sql = "SELECT role.role FROM tbl_orders AS ord LEFT JOIN tbl_users AS user ON user.user_id=ord.customer_user LEFT JOIN tbl_user_roles AS role ON role.id=user.role WHERE ord.order_id='$number' LIMIT 1";
//     $master = model('MasterModel');
//     $q = $master->query($sql, true, true);

//     if(!empty($q['role']) && $q['role'] == "wholesale_customer") {
//         $prefix = 'HNW';
//     }elseif(!empty($q['role']) && $q['role'] == "internal") {
//         $prefix = 'HNI';
//     }
//     else {
//         $prefix = 'HNR';
//     }
//     $number = strlen($number) < 4 ? sprintf("%04d", $number) : $number;
//     if($prefix) {
//         $number = $prefix.$number;
//     }
//     return $number;
// }

function _order_number($number, $prefix='') {
    // Check if $number is an array, and if so, extract the first element (assuming it's the order ID)
    if (is_array($number)) {
        $number = $number['order_id'] ?? reset($number); // Fallback to first element if 'order_id' key is missing
    }

    // Ensure $number is now a string or an integer
    if (!is_string($number) && !is_int($number)) {
        return null; // Return null or handle error if $number is neither string nor integer
    }

    $sql = "SELECT role.role FROM tbl_orders AS ord 
            LEFT JOIN tbl_users AS user ON user.user_id=ord.customer_user 
            LEFT JOIN tbl_user_roles AS role ON role.id=user.role 
            WHERE ord.order_id='$number' LIMIT 1";

    $master = model('MasterModel');
    $q = $master->query($sql, true, true);

    // Determine the prefix based on user role
    if (!empty($q['role']) && $q['role'] == "wholesale_customer") {
        $prefix = 'HNW';
    } elseif (!empty($q['role']) && $q['role'] == "internal") {
        $prefix = 'HNI';
    } else {
        $prefix = 'HNR';
    }

    // Ensure $number is formatted correctly
    $number = strlen($number) < 4 ? sprintf("%04d", $number) : $number;

    if ($prefix) {
        $number = $prefix . $number;
    }

    return $number;
}


function _order_status($status='') {
    $status = str_replace("_"," ",$status);
    $statuses_map = order_statuses();
    $status = !empty($statuses_map[$status]) ? $statuses_map[$status] : $status;
    if($status === "on-hold") {
        $status = 'Paused';
    }
    return $status;
}

function order_editable($order) {
    $can_edit = false;
    $order = (array)$order;
    if(($order['status'] != "processing" && $order['status'] != "completed")) {
        $can_edit = true;
    }
    if($order['payment_method'] == 'invoice' && $order['status'] != "completed") {
        $can_edit = true;
    }
    return $can_edit;
}

function order_statuses() {
    return [
        'completed' => 'Completed',
        'processing' => 'Processing',
        'pending' => 'Pending Payment',
        'on-hold' => 'On Hold',
        //'ready_to_ship' => 'Ready to ship',
        'cancelled' => 'Cancelled',
        'refund' => 'Refunded',
        'failed' => 'Failed',
        'trashed' => 'Bin',
        'renew-failed' => 'Renew failed'
    ];
}

function order_form_statuses() {
    return [
        'completed' => 'Completed',
        'processing' => 'Processing',
        'cancelled' => 'Cancelled',
        'refund' => 'Refunded',
    ];
}


function subscription_statuses() {
//    return [
//        'processing' => 'Processing',
//        'active' => 'Active',
//        'inactive' => 'Inactive',
//        'on-hold' => 'Pause',
//        'expired' => 'Expired',
//        'cancelled' => 'Cancelled',
//        'renew-failed' => 'Renew failed'
//    ];
    return [
        'completed' => 'Completed',
        'processing' => 'Processing',
        //'pending' => 'Pending Payment',
        //'active' => 'Active',
        //'on-hold' => 'Pause',
        //'ready_to_ship' => 'Ready to ship',
        'cancelled' => 'Cancelled',
        'refund' => 'Refunded',
        //'failed' => 'Failed',
        //'trashed' => 'Bin',
        //'renew-failed' => 'Renew failed'
    ];
}

function empty_date($date='') {
    if(!empty($date)) {
        return $date == "0000-00-00 00:00:00";
    }
    return true;
}

function admin_url() {
    return site_url('admin/');
}

function _date($date='') {
    return !empty_date($date) ? date(env('date_format'),strtotime($date)) : '-';
}

function _time($date) {
    return !empty_date($date) ? date(env('time_format'),strtotime($date)) : '-';
}

function _date_full($date='') {
    return !empty_date($date) ? date(env('date_full_format'),strtotime($date)) : '-';
}

function _datetime($date='') {
    return !empty_date($date) ? date(env('datetime_db'),strtotime($date)) : '-';
}

function _datetime_full($date='') {
    return !empty_date($date) ? date(env('datetime_full_format'),strtotime($date)) : '-';
}

function _datetimeformat($date) {
    return !empty_date($date) ? date('d/m/Y h:i A',strtotime($date)) : '-';
}

function date_between($date,$from_date,$to_date): bool
{
    $date = date('Y-m-d',strtotime($date));
    $from_date = date('Y-m-d',strtotime($from_date));
    $to_date = date('Y-m-d',strtotime($to_date));

    return ($date >= $from_date) && ($date <= $to_date);
}

function coupon_status($coupon) {
    $output = 'inactive';
    if(!empty($coupon)) {
        $coupon = (object)$coupon;
        if ($coupon->status) {
            $output = 'active';
            if (!$coupon->is_unlimited && isset($coupon->use_limit) && $coupon->use_count >= $coupon->use_limit) {
                $output = 'Limit exceeded';
            }
            if ($coupon->has_expiration && date('Y-m-d H:i:s') > $coupon->valid_to) {
                $output = 'Date expired';
            }
        }
    }
    return $output;
}

function transaction_link($link_id='', $meta_data=[]) {
    $meta_data['payment_method_title'] = strtolower($meta_data['payment_method_title']);

    if($meta_data['payment_method_title'] === "stripe") {
        //Stripe
        return '<a target="_blank" class="color-base btn btn-sm btn-primary" href="https://dashboard.stripe.com/payments/'.$link_id.'">Stripe payment details</a>';
    }
    if($meta_data['payment_method_title'] === "squareup") {
        //Stripe
        return '<a target="_blank" class="color-base btn btn-sm btn-primary" href="https://squareup.com/receipt/preview/'.$link_id.'">Squareup payment details</a>';
    }
    if($meta_data['payment_method_title'] === "braintree") {
        //Braintree
        return '<a target="_blank" class="color-base btn btn-sm btn-primary" href="' .$link_id . '">Braintree payment details</a>';
    }
    if($meta_data['payment_method_title'] === "direct") {
        return '<span>Direct Check Out</span>';
    }
}

function _redirect($url) {
    header("Location: $url");
    exit;
}

function asset($url) {
    return site_url().'assets/'.$url;
}

function limit($value, $limit = 100, $end = '...')
{
    if (mb_strwidth($value, 'UTF-8') <= $limit) {
        return $value;
    }

    return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
}

function _render_page($view, $data = null, $returnhtml = false)
{
    $view_html = \Config\Services::renderer();
    $viewdata = $data;
    $view_html = $view_html->setData($viewdata)->render($view);
    echo $view_html;
}

function is_password_strong($password)
{
    //preg_match('#[0-9]#', $password) &&
    if ((preg_match('#[a-zA-Z]#', $password)) && strlen($password) >= 12) {
        return TRUE;
    }
    return FALSE;
}

function get_cate_name($id)
{
    if(!$id) {
        return '<strong style="color:red"> no parent</strong>';
    }
    $db = db_connect();
    $row = $db->query("select * from tbl_categories where id = " . $id);
    if($row) {
        $row = $row->getRow();
        return $row->name;
    }else {
        return '<strong style="color:red"> no parent</strong>';
    }
}

function get_child_category($id)
{
    $db = db_connect();

    $rows = $db->query("select * from tbl_categories where parent = " . $id)->getResult();
    //$rows = $db>getRows('categories', ['parent' => $id]);
    return $rows;
}

function remove_file_from_directry($path, $file_name)
{

    if (file_exists($path . $file_name)) {
        unlink($path . $file_name);
    }
    return;
    //unlink($path . '/thumb/' . $file_name);

}

function datetime($date, $formate = "Y-m-d")
{
    return date($formate, strtotime($date));
}

function timeAgo($time_ago)
{
    $time_ago = strtotime($time_ago);

    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );

    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "one minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}

function curr_time_diff($future_date) {
    $curr_date = new DateTime(curr_time);
    $diff = $future_date->diff($curr_date);
    return $diff;
}

function pr($ary, $exit = true)
{

    echo "<pre>";

    print_r($ary);

    echo "</pre>";

    if ($exit)

        exit;
}
function is_logged_in() {
    $session = session();
    $user = $session->get('user');
    $master = model('MasterModel');
    if(!empty($user['id'])) {
        $uid = $user['id'];
        $sql = "SELECT user_id FROM tbl_users WHERE user_id='$uid'";
        if(empty($user['force_login'])) {
            $sql .= " AND status=1";
        }
        $is_user = $master->query($sql,false,true);
        if(empty($is_user)) {
            return false;
        }
        return $uid;
    }
    return 0;
}

function is_admin() {
    $session = session();
    $user = $session->get('user');
    $is_admin = false;
    if(!empty($user['id'])) {
        $uid = $user['id'];
        $user = model('UserModel');
        $user_roles = $user->get_user_roles($uid);
        $user_roles = array_keys($user_roles);

        if(!empty($user_roles) && in_array('administrator',$user_roles)) {
            $is_admin = $uid;
        }
    }

    return $is_admin;
}

function is_customer() {
    $session = session();
    $user = $session->get('user');
    $is_user = false;
    if(!empty($user['id'])) {
        $uid = $user['id'];
        $userModel = model('UserModel');
        $user_roles = $userModel->get_user_roles($uid);
        if((empty($user_roles) || in_array('customer',array_keys($user_roles)))) {
            $is_user = $uid;
        }
    }
    return $is_user;
}

function is_guest() {
    $session = session();
    $user = $session->get('user');
    $is_user = false;
    if(!empty($user['id'])) {
        $uid = $user['id'];
        $userModel = model('UserModel');
        $user_roles = $userModel->get_user_roles($uid);
        if((empty($user_roles) || in_array('guest',array_keys($user_roles)))) {
            $is_user = $uid;
        }
    }
    return $is_user;
}
function is_internal() {
    $session = session();
    $user = $session->get('user');
    $is_user = false;
    if(!empty($user['id'])) {
        $uid = $user['id'];
        $userModel = model('UserModel');
        $user_roles = $userModel->get_user_roles($uid);
        if((empty($user_roles) || in_array('internal',array_keys($user_roles)))) {
            $is_user = $uid;
        }
    }
    return $is_user;
}

function is_wholesaler() {
    $session = session();
    $user = $session->get('user');
    $is_user = false;
    if(!empty($user['id'])) {
        $uid = $user['id'];
        $userModel = model('UserModel');
        $user_roles = $userModel->get_user_roles($uid);
        if((empty($user_roles) || in_array('wholesale_customer',array_keys($user_roles)))) {
            $is_user = $uid;
        }
    }
    return $is_user;
}

function is_staff() {
    $session = session();
    $user = $session->get('user');
    $output = false;
    if(!empty($user['id'])) {
        $uid = $user['id'];
        $master = model('MasterModel');
        $is_admin = $master->query("SELECT role FROM tbl_users WHERE user_id='$uid' AND role='3'",false,true);
        if(!empty($is_admin->role)) {
            $output = $user['id'];
        }
    }
    return $output;
}

function is_user($email='') {
    if($email) {
        $master = model('MasterModel');
        $is_user = $master->query("SELECT user_id FROM tbl_users WHERE email='$email'",false,true);
        if(!empty($is_user)) {
            return $is_user->user_id;
        }
    }
    return false;
}


function display_name($user) {
    $name = '';
    if(!empty($user->display_name)) {
        $name = $user->display_name;
    }
    else if(!empty($user->fname) || !empty($user->lname)) {
        $name = $user->fname.' '.$user->lname;
    }
    else if(!empty($user->username)) {
        $name = $user->username;
    }
    else {
        $name = strstr($user->email,'@',true);
    }
    return $name;
}

function is_frontend() {
    $curr_page = str_replace(site_url(),'',current_url());
    return strstr($curr_page,'admin/') ? false : true;
}

function current_user_role($include_meta=[]) {
    if(is_logged_in()) {
        $master = model('MasterModel');
        $uid = is_logged_in();
        $get_user = $master->query("SELECT role.role AS role_id, role.* FROM tbl_user_roles AS role JOIN tbl_users AS user ON user.role=role.id WHERE user.user_id='$uid'",true,true);
        return $get_user;
    }
    else {
        return [
            'id' => GUEST_ROLE_ID,
            'role' => 'guest',
            'role_id' => 'guest',
            'name' => 'Guest'
        ];
    }
}

function shop_roles($query='',$status=1) {
    if(empty($query)) {
        $query = 'WHERE 1=1 ';
    }
    if($status !== 'any') {
        $query .= ' AND status='.$status;
    }
    $master = model('MasterModel');
    $roles = $master->query("SELECT * FROM tbl_user_roles $query");
    if(!empty($roles)) {
        return $roles;
    }
}



function is_ajax() {
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    {
        return 1;
    }
    return 0;
}

function notice_success($message,$type='toast')
{
    set_message('sessionmessage', $message, $type);
}

function get_notice_message($key = 'sessionmessage',$once=true) {
    $session = session();
    if(!empty($session->get($key))) {
        $message = $session->get($key);
        $msg_type = $message['msg_type'];

        if ($message) {
            switch ($msg_type) {
                case 'success':
                    message_notice($message['message'],'success '.$message['type']);
                    break;
                case 'error':

                    message_notice($message['message'],'error '.$message['type']);
                    break;
            }

            if ($once) {
                $session->remove($key);
            }
        }
    }
}

function alert_message($message,$once=1) {
    notice_success($message,'notification_message');
    if($once) {
        $session = session();
        $session->remove('notification_message');
    }
}
function set_message($key = 'success', $message='',$type='toast', $extraClass='', $timer = 5000)
{
    $message = [
        'message' => $message,
        'timeout' => $timer,
        'type' => $key,
        'msg_type' =>$type,
        'extraClass'=>$extraClass
    ];

    session()->setFlashdata($key, $message);
}

function get_message($k='sessionmessage', $once = false)
{
    $session = session();
    $message = !empty($session->get($k)) ? $session->get($k) : '';

    if($once) {
        $session->remove($k);
    }

    if (!empty($message)) {

        if($message['msg_type'] == 'toast') {
            ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        html: '<div style="color: #fff"><?php echo is_array($message['message']) ? implode(', ',$message['message']) : $message['message'] ?></div>',
        toast: true,
        timer: <?php echo $message['timeout'] ?>,
        position: 'top',
        background: '#d8262f',
        showConfirmButton: false,
        showClass: {
            popup: 'animated windowIn'
        },
        hideClass: {
            popup: 'animated windowOut'
        },
    });
})
</script>
<style>
body.swal2-toast-shown .swal2-container.swal2-top .swal2-validation-message {
    display: none
}
</style>
<?php
            $session->remove($k);
        }
        else {
            return '<div class="notices-wrapper mb-20"><div class="notice-board notification_message '.(!empty($message['extraClass']) ? $message['extraClass'] : '').'">'.$message['message'].'</div></div>';
        }
    }
}

function error_message($message) {
    if(!empty($message)) {
        return '<div class="error_message">'.$message.'</div>';
    }
}

function get_setting($title='',$array=false) {
    $db = db_connect();
    $title_ = explode('.', $title);
    $title_name = !empty($title_[0]) ? $title_[0] : '';
    unset($title_[0]);
    $title_arr = array_values($title_);

    $row = $db->query("select value from tbl_settings where title='$title_name'");
    $row = $row->getRow();
    if(empty($title_arr)) {
        if(!empty($row->value)) {
            if(!$array) {
                return $row->value;
            }else {
                return json_decode($row->value,true);
            }
        }
    }else {
        if(!empty($row->value)) {
            $row_val = !empty($row->value) ? json_decode($row->value, true) : '';
            if(!empty($title_arr[0])) {
                return $row_val[$title_arr[0]];
            }
        }
    }
    return false;
}

function _text_input($text='') {
    $text = trim($text);
    $text = strip_tags($text);
    return $text;
}
function _email_input($email='') {
    $email = trim($email);
    $email = strip_tags($email);
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $email;
    }
    return '';
}

function _textarea_input($text='') {
    $text = esc(strip_tags($text));
    return $text;
}

function _number_input($number='') {
    $number = esc(strip_tags($number));
    return $number;
}

function help_text($text='') {
    return '<i class="lni lni-question-circle" data-tooltip title="'.$text.'"></i>';
}

function _price($price = 0) {
    if(strlen(intval($price)) <= 6) {
        $price = number_format((float)$price,2,'.','');
    }
    else {
      $price = number_format((float)$price,3,'.','');
    }

    return currency_symbol.$price;
}

function free_ship_amount($ship_cost, $product_count, $free_ship_count) {
    return ($ship_cost / $product_count) * ($product_count - $free_ship_count);
}

/**Calculate reduction in percentage*/
function percent_reduce($amount, $percentage, $reduced_amount=false) {
    if($amount && $percentage) {
        $percent = ($percentage/100)*$amount;

        if($reduced_amount) {
            return $percent;
        }
        return $amount - $percent;
    }
    return $reduced_amount ? 0 : $amount;
}

/**Calculate increase in percentage*/
function percent_increase($amount, $percentage, $increased_amount=false) {
    if($amount) {
        $percent = ($percentage/100)*$amount;
        if($increased_amount) {
            return $percent;
        }
        return $amount + $percent;
    }
    return $amount;
}

/**Calculate sales increase in percentage*/
function sale_percent_reduce($amount, $percentage, $reduced_amount=false) {
    if($amount) {
        $tax_rate = $percentage / 100;
        $reduced = $amount / (1 + $tax_rate);
        if($reduced_amount) {
            return round($amount - $reduced,2);
        }else {
            return round($reduced,2);
        }
    }
}

/**Calculate sales reduction in percentage*/

function get_tax_rate($data=[],$ignore_permission=0) {
    $allowed = get_setting('enable_tax_rates');

    if($allowed && !$ignore_permission) {
        $db_tax_rate = tax_rates();

        array_walk($data, function(&$value)
        {
            $value = strtolower($value);
        });

        $_data = array_merge([
            'country' => '',
            'postcode' => '',
            'state' => '',
            'city' => '',
        ], $data);

        $_data = array_filter($_data);

        if(!empty($_data['tax_class'])) {
            $db_tax_rate = isset($db_tax_rate[$_data['tax_class']]) ? [$_data['tax_class']=>$db_tax_rate[$_data['tax_class']]] : [];
        }

        $tax_rate = [];
        if (!empty($db_tax_rate)) {
            foreach ($db_tax_rate as $rate) {
                foreach($rate['values'] as $value) {
                    array_walk($value, function(&$value)
                    {
                        $value = strtolower($value);
                    });
                    $match = array_intersect([
                        'country'=>($value['country']),
                        'postcode'=>$value['postcode'],
                        'state'=>($value['state']),
                        'city'=>($value['city'])
                    ],$_data);
                    if(!empty($match) && $match === $_data) {
                        $tax_rate = $rate;
                        break;
                    }
                }
            }

            if(empty($tax_rate)) {
                foreach ($db_tax_rate as $rates) {
                    if(!empty($rates['values'])) {
                        foreach($rates['values'] as $value) {
                            if(@$value['country'] == "" && @$value['postcode'] == "" && @$value['state'] == "" && @$value['city'] == "") {
                                $tax_rate = $value;
                            }
                        }
                    }
                }
            }
        }

        return $tax_rate;
    }
}

function get_address_tax($tax_rates=[], $address=[]) {
    $allowed = get_setting('enable_tax_rates');
    $tax_rate = [];
    if($allowed) {
        $_address = array_merge([
            'country' => '',
            'postcode' => '',
            'state' => '',
            'city' => '',
        ], $address);

        $_address = array_filter($_address);

        if(!empty($tax_rates['values'])) {
            foreach($tax_rates['values'] as $rate) {
                if(empty($rate['tax_name'])) {
                    $rate['tax_name'] = 'VAT';
                }
                $addr_data = [
                    'country'=>($rate['country']),
                    'postcode'=>$rate['postcode'],
                    'state'=>($rate['state']),
                    'city'=>($rate['city'])
                ];

                $rate['label'] = $tax_rates['label'];
                $addr_data = array_filter($addr_data);
                if(!empty($addr_data)) {
                    $match = array_intersect($addr_data,$_address);
                    if(!empty($match) && $match === $_address) {
                        $tax_rate = $rate;
                        break;
                    }
                }else {
                    $tax_rate = $rate;
                    break;
                }
            }
        }

    }
    return $tax_rate;
}

function selling_countries() {
    $selling_location = get_setting('selling_location');
    $countries = get_countries();
    if($selling_location === 'except') {
        $exceptions = get_setting('selling_countries',true);
        foreach($exceptions as $exception) {
            unset($countries[$exception]);
        }
    }
    if($selling_location === 'specific') {
        $allows = get_setting('selling_countries',true);
        $allowed_countries = [];
        foreach($allows as $code) {
            $allowed_countries[] = [
                'name' => $countries[$code],
                'code' => $code
            ];
        }
        $countries = $allowed_countries;
    }
    return $countries;
}

function default_selling_country() {
    $countries = selling_countries();
    $default_country = [];
    if(!empty($countries[0])) {
        $default_country = $countries[0];
    }
    return $default_country;
}

function tax_rates() {
    $master = model('MasterModel');
    $get_classes = $master->query("SELECT * FROM `tbl_settings` WHERE `title` LIKE '%_tax_rate%'");
    $output = [];

    if($get_classes) {
        foreach($get_classes as $tax_class) {
            if(json_decode($tax_class->value)) {
                $tax_arr = json_decode($tax_class->value,true);

                $class_name = $tax_class->title;
                $tax_class_name = str_replace('_',' ',$class_name);
                $tax_class_name = ucfirst($tax_class_name);
                $tax_class_name = str_replace(' tax rate','',$tax_class_name);

                $row_data = [];

                if(!empty($tax_arr['country'])) {
                    foreach($tax_arr['country'] as $i=>$tax_country) {
                        $state = $tax_arr['state'][$i];
                        $country = $tax_arr['country'][$i];
                        $postcode = $tax_arr['postcode'][$i];
                        $city = $tax_arr['city'][$i];
                        $amount = $tax_arr['amount'][$i];
                        $type = $tax_arr['type'][$i];
                        $tax_name = $tax_arr['tax_name'][$i];
                        $tax_shipping = !empty($tax_arr['tax_shipping'][$i]) ? $tax_arr['tax_shipping'][$i] : 0;

                        $row_data[] = [
                            'country' => $country,
                            'state' => $state,
                            'postcode' => $postcode,
                            'city' => $city,
                            'amount' => $amount,
                            'type' => $type,
                            'tax_name' => $tax_name,
                            'tax_shipping' => $tax_shipping
                        ];
                    }
                }

                $tax_row = ['label'=>$tax_class_name,'values'=>$row_data];


                $output[$tax_class->title] = $tax_row;
            }
        }
    }
    unset($output['enable_tax_rates']);
    return $output;
}

function get_tax_price($tax_rate_arr=[], $tax_class='') {

    $tax_rate_arr = array_merge([
        'country' => '',
        'postcode' => '',
        'state' => '',
        'city' => ''
    ], $tax_rate_arr);

        if(is_logged_in()) {
            $UserModel = model('UserModel');

            $user_meta = $UserModel->get_user_meta();

            $tax_based_on = get_setting('tax_based_on');

            if(empty($tax_based_on) || $tax_based_on === "billing") {
                $tax_rate_arr = [
                    'country' => @$user_meta['billing_country'],
                    'postcode' => @$user_meta['billing_postcode'],
                    'state' =>  @$user_meta['billing_state'],
                    'city' => @$user_meta['billing_city'],
                ];
            }else {
                $tax_rate_arr = [
                    'country' => @$user_meta['shipping_country'],
                    'postcode' => @$user_meta['shipping_postcode'],
                    'state' =>  @$user_meta['shipping_state'],
                    'city' => @$user_meta['shipping_city'],
                ];
            }
        }

        if($tax_class) {
            $tax_rate_arr['tax_class'] = $tax_class;
        }

    return get_tax_rate($tax_rate_arr);
}

function getRandomString($n=14) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function datatable_query($table,$rows,$sort_cols,$group='',$extraQuery='',$customJoin='') {

    $query = "SELECT ";
    $start = $_GET['start'];
    $length = $_GET['length'];
    $search = $_GET['search']['value'];

    $query_count = "SELECT ".$rows[0]." FROM $table ";

    foreach($rows as $k=>$row) {
        if(is_array($row)) {
            if(empty($k)) {
                continue;
            }
            $row = $k;
        }
        $query .= $row.",";
    }


    $query = trim($query,',');

    $query .= " FROM ".$table;

    $query .= $customJoin;

    if(empty($extraQuery)) {
        $query .= " WHERE 1=1 ";
        $query_count .= " WHERE 1=1 ";
    }

    $query .= " ".$extraQuery." ";

    $query_count .= " ".$extraQuery." ";

    if(!empty($search)) {
        $search = ltrim($search,0);
        $search = trim($search);
        $search_query = " AND (";

        foreach($rows as $row) {
            if(is_array($row)) {
                continue;
            }
            if(strstr($row,'(SELECT')) {
                $row = explode(') AS', $row);
                $row = $row[0].')';
            }

            $search_query .= "(".$row." LIKE '%$search%') OR ";
        }
        $search_query = trim($search_query,'OR ');
        $search_query .= ")";

        $query .= $search_query;
        $query_count .= $search_query;
    }



    $query .= " ".$group." ";
    $query_count .= " ".$group." ";

    if(!empty($_GET['order'])) {
        $order_col = '';
        foreach($_GET['order'] as $order) {
            if(!empty($sort_cols[$order['column']]) && !empty($order['dir'])) {
                $col = $sort_cols[$order['column']];
                $dir = $order['dir'];
                $order_col .= $col." ".$dir;
            }
        }
        if(!empty($order_col)) {
            $query .= " ORDER BY ".$order_col;
            $query_count .= " ORDER BY ".$order_col;
        }
    }

    $db = db_connect();


    $total_records = $db->query($query_count)->getNumRows();

    if($length > -1) {
        $query .= " LIMIT $start,$length";
    }

    $records = $db->query($query)->getResultArray();

    $output = [
        'draw'=>$_GET['draw'],
        'recordsTotal'=>$total_records,
        'recordsFiltered'=>$total_records,
        'records' => $records
    ];

    return $output;
}


function upload_media_box($config=[],$include_scripts=true) {
    if(!empty($config['return'])) {
        ob_start();
    }
    $media = model('Media');
    ?>
<div class="input_file upload_media_images <?php echo !empty($config['multiple']) ? 'multiple':'' ?>">
    <?php if(empty($config['textarea'])) { ?>
    <div class="gallery-images" data-inputname="<?php echo $config['input_name'] ?>">
        <?php if(!empty($config['images'])) {
                    $config['images'] = array_filter($config['images']);
                    foreach($config['images'] as $img) {
                        $src = $media->get_media_src($img);
                        ?>
        <div class="media-slide">
            <div onclick="remove_product_media(this)" class="lni lni-cross-circle del-image"
                data-id="<?php echo $img ?>"></div>
            <div><img class="media_image" src="<?php echo $src ?>"></div>
            <?php
                            if(!empty($config['input_name'])) {
                                ?>
            <input type="hidden" name="<?php echo $config['input_name'] ?>[]" class="media_input"
                value="<?php echo $img ?>">
            <?php
                            }?>
        </div>
        <?php
                    }
                } ?>
    </div>
    <?php }?>

    <div>


        <?php
            $atts = '';
            if(!empty($config['textarea'])) {
                $atts .= 'data-textarea="'.$config['textarea'].'"';
            }
            if(!empty($config['multiple'])) {
                $atts .= ' data-multiple="true"';
            }
            if(!empty($config['replacemedia'])) {
                $atts .= ' data-replace="true"';
            }
            if(!empty($config['editor'])) {
                $atts .= ' data-editor="'.$config['editor'].'"';
            }
            ?>

        <a href="#" <?php echo $atts ?>
            class="btn btn-sm btn-secondary back browse_media"><?php echo empty($config['buttonText']) ? '<i class="icon-up-circled2"></i> Upload image':$config['buttonText'] ?></a>

    </div>
</div>
<?php

    if(!empty($config['return'])) {
        return ob_get_clean();
    }
}

function message_notice($message,$extraClass='') {
    ?>
<div class="alert woocommerce-message mb-50 <?php echo $extraClass ?>">
    <span class=" closebtn" onclick="this.parentElement.style.display='none';"><i
            class="lni lni-cross-circle"></i></span>
    <?php echo $message ?>
</div>
<?php
}

function get_html_tag($html='',$tagname='',$wrap='') {
    $parts = explode('<'.$tagname,$html);
    $output = '';
    $endtag = '</'.$tagname.'>';
    if($tagname == 'img') {
        $endtag = '';
    }
    foreach($parts as $part) {
        $part = trim($part);
        if(strstr($part, '/>')) {
            if($wrap) {
                $wr = explode('><',$wrap);
                $output .= $wr[0].">";
            }
            $output .= '<'.$tagname.' ' . strstr($part, '/>', true) . '>';
            if($endtag) {
                $output .= $endtag;
            }
            if($wrap) {
                $output .= "<".$wr[1];
            }
            $output .= "\n";
        }
    }
    return $output;
}

function shortcodes_decode($html='') {
    $codes = ['post-grids','post-grids2'];
    $scodeList = [];
    preg_match_all("/\[[^\]]*\]/", $html, $matches);
    foreach($matches[0] as $match) {
        foreach($codes as $code) {
            $mk = explode(' ',$match)[0];
            if(explode(' ',$match)[0] !== '['.$code) {
                continue;
            }
            $matchKeys = str_replace("[$code ",'',$match);
            $matchKeys = trim($matchKeys,']');
            $parts = explode('" ',$matchKeys);
            preg_match_all('/"([^"]+)"/', $matchKeys, $atts);
            $atts_list = [];
            foreach($atts[1] as $k=>$m) {
                $part = $parts[$k];
                $part = strstr($part,'="',true);
                $part = trim($part,'[]');
                $atts_list[$part] = $m;
            }

            if(!empty($atts_list)) {
                $atts_list['shortcode'] = $match;
                $scodeList[trim($mk,'[]')][] = $atts_list;
            }
        }
    }

    $output = [];

    if(!empty($scodeList)) {
        foreach($scodeList as $k=>$atts) {
            if($k == 'post-grids') {
                foreach($atts as $att) {
                    $output = postgrids_html($att);
                }
            }
        }
    }
    return $output;
}

function admin_page_title($label='') {
    ?>
<div class="back_btn">
    <a class="btn back" href="#" onclick="history.back()" title="Go Back" data-tooltip><i
            class="lni lni-arrow-left-circle"></i></a>
    <a class="btn back" href="#" onclick="location.reload()" title="Reload Page" data-tooltip><i
            class="lni lni-reload"></i></a>

    <?php if($label) { ?>
    <h3 class="label"><?php echo $label; ?></h3>
    <?php } ?>
</div>
<?php
}

function postgrids_html($atts=[]) {
    if(!empty($atts['ids'])) {
        $ids = explode(',',$atts['ids']);

    }
}

function filesize_text($bytes=0) {

    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function app_log($type, $exception) {
    if(ENVIRONMENT === 'development') {
        echo '<div class="error-'.$type.'">
        '.$exception->getMessage().'<br>File: '.$exception->getFile().'<br>Line '.$exception->getLine();
        echo '<h4>Trace:</h4>';
        ob_start();
        echo '<pre>';
        print_r($exception);
        echo '</pre>';
        echo ob_get_clean();
        echo '</div>';
    }
    //log_message($type,$exception->getMessage());
}

function payment_method_map($text='') {
    $maps = [
        'cod'=>'Cash on delivery',
        'credit_card'=>'Credit Card',
        'paypal'=>'Paypal',
        'stripe'=>'Stripe',
        'braintree'=>'Braintree',
        'squareup' => 'Squareup',
        'direct'=>'Direct Checkout',
        'Credit/Debit Card'=>'Credit/Debit Card',
        'invoice' => 'Invoice Checkout',
        'Zero Charge' => 'Zero Charge'
    ];
    return !empty($maps[$text]) ? $maps[$text] : '';
}

function get_billing_fields() {
    $data = [
        'billing_address_1',
        'billing_address_2',
        'billing_first_name',
        'billing_last_name',
        'billing_city',
        'billing_state',
        'billing_postcode',
        'billing_country',
        'billing_company',
        'billing_email',
        'billing_phone'
    ];
    return $data;
}

function get_shipping_fields() {
    $data = [
        'shipping_address_1',
        'shipping_address_2',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_city',
        'shipping_state',
        'shipping_postcode',
        'shipping_country',
        'shipping_company',
        'shipping_email',
        'shipping_phone'
    ];
    return $data;
}

function get_countries($country_code='') {
    $countries = array(
        "AF" => "Afghanistan",
        "AL" => "Albania",
        "DZ" => "Algeria",
        "AS" => "American Samoa",
        "AD" => "Andorra",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AQ" => "Antarctica",
        "AG" => "Antigua and Barbuda",
        "AR" => "Argentina",
        "AM" => "Armenia",
        "AW" => "Aruba",
        "AU" => "Australia",
        "AT" => "Austria",
        "AZ" => "Azerbaijan",
        "BS" => "Bahamas",
        "BH" => "Bahrain",
        "BD" => "Bangladesh",
        "BB" => "Barbados",
        "BY" => "Belarus",
        "BE" => "Belgium",
        "BZ" => "Belize",
        "BJ" => "Benin",
        "BM" => "Bermuda",
        "BT" => "Bhutan",
        "BO" => "Bolivia",
        "BA" => "Bosnia and Herzegovina",
        "BW" => "Botswana",
        "BV" => "Bouvet Island",
        "BR" => "Brazil",
        "BQ" => "British Antarctic Territory",
        "IO" => "British Indian Ocean Territory",
        "VG" => "British Virgin Islands",
        "BN" => "Brunei",
        "BG" => "Bulgaria",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "KH" => "Cambodia",
        "CM" => "Cameroon",
        "CA" => "Canada",
        "CT" => "Canton and Enderbury Islands",
        "CV" => "Cape Verde",
        "KY" => "Cayman Islands",
        "CF" => "Central African Republic",
        "TD" => "Chad",
        "CL" => "Chile",
        "CN" => "China",
        "CX" => "Christmas Island",
        "CC" => "Cocos [Keeling] Islands",
        "CO" => "Colombia",
        "KM" => "Comoros",
        "CG" => "Congo - Brazzaville",
        "CD" => "Congo - Kinshasa",
        "CK" => "Cook Islands",
        "CR" => "Costa Rica",
        "HR" => "Croatia",
        "CU" => "Cuba",
        "CY" => "Cyprus",
        "CZ" => "Czech Republic",
        "CI" => "Côte d’Ivoire",
        "DK" => "Denmark",
        "DJ" => "Djibouti",
        "DM" => "Dominica",
        "DO" => "Dominican Republic",
        "NQ" => "Dronning Maud Land",
        "DD" => "East Germany",
        "EC" => "Ecuador",
        "EG" => "Egypt",
        "SV" => "El Salvador",
        "GQ" => "Equatorial Guinea",
        "ER" => "Eritrea",
        "EE" => "Estonia",
        "ET" => "Ethiopia",
        "FK" => "Falkland Islands",
        "FO" => "Faroe Islands",
        "FJ" => "Fiji",
        "FI" => "Finland",
        "FR" => "France",
        "GF" => "French Guiana",
        "PF" => "French Polynesia",
        "TF" => "French Southern Territories",
        "FQ" => "French Southern and Antarctic Territories",
        "GA" => "Gabon",
        "GM" => "Gambia",
        "GE" => "Georgia",
        "DE" => "Germany",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GR" => "Greece",
        "GL" => "Greenland",
        "GD" => "Grenada",
        "GP" => "Guadeloupe",
        "GU" => "Guam",
        "GT" => "Guatemala",
        "GG" => "Guernsey",
        "GN" => "Guinea",
        "GW" => "Guinea-Bissau",
        "GY" => "Guyana",
        "HT" => "Haiti",
        "HM" => "Heard Island and McDonald Islands",
        "HN" => "Honduras",
        "HK" => "Hong Kong SAR China",
        "HU" => "Hungary",
        "IS" => "Iceland",
        "IN" => "India",
        "ID" => "Indonesia",
        "IR" => "Iran",
        "IQ" => "Iraq",
        "IE" => "Ireland",
        "IM" => "Isle of Man",
        "IL" => "Israel",
        "IT" => "Italy",
        "JM" => "Jamaica",
        "JP" => "Japan",
        "JE" => "Jersey",
        "JT" => "Johnston Island",
        "JO" => "Jordan",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KI" => "Kiribati",
        "KW" => "Kuwait",
        "KG" => "Kyrgyzstan",
        "LA" => "Laos",
        "LV" => "Latvia",
        "LB" => "Lebanon",
        "LS" => "Lesotho",
        "LR" => "Liberia",
        "LY" => "Libya",
        "LI" => "Liechtenstein",
        "LT" => "Lithuania",
        "LU" => "Luxembourg",
        "MO" => "Macau SAR China",
        "MK" => "Macedonia",
        "MG" => "Madagascar",
        "MW" => "Malawi",
        "MY" => "Malaysia",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malta",
        "MH" => "Marshall Islands",
        "MQ" => "Martinique",
        "MR" => "Mauritania",
        "MU" => "Mauritius",
        "YT" => "Mayotte",
        "FX" => "Metropolitan France",
        "MX" => "Mexico",
        "FM" => "Micronesia",
        "MI" => "Midway Islands",
        "MD" => "Moldova",
        "MC" => "Monaco",
        "MN" => "Mongolia",
        "ME" => "Montenegro",
        "MS" => "Montserrat",
        "MA" => "Morocco",
        "MZ" => "Mozambique",
        "MM" => "Myanmar [Burma]",
        "NA" => "Namibia",
        "NR" => "Nauru",
        "NP" => "Nepal",
        "NL" => "Netherlands",
        "AN" => "Netherlands Antilles",
        "NT" => "Neutral Zone",
        "NC" => "New Caledonia",
        "NZ" => "New Zealand",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigeria",
        "NU" => "Niue",
        "NF" => "Norfolk Island",
        "KP" => "North Korea",
        "VD" => "North Vietnam",
        "MP" => "Northern Mariana Islands",
        "NO" => "Norway",
        "OM" => "Oman",
        "PC" => "Pacific Islands Trust Territory",
        "PK" => "Pakistan",
        "PW" => "Palau",
        "PS" => "Palestinian Territories",
        "PA" => "Panama",
        "PZ" => "Panama Canal Zone",
        "PG" => "Papua New Guinea",
        "PY" => "Paraguay",
        "YD" => "People's Democratic Republic of Yemen",
        "PE" => "Peru",
        "PH" => "Philippines",
        "PN" => "Pitcairn Islands",
        "PL" => "Poland",
        "PT" => "Portugal",
        "PR" => "Puerto Rico",
        "QA" => "Qatar",
        "RO" => "Romania",
        "RU" => "Russia",
        "RW" => "Rwanda",
        "RE" => "Réunion",
        "BL" => "Saint Barthélemy",
        "SH" => "Saint Helena",
        "KN" => "Saint Kitts and Nevis",
        "LC" => "Saint Lucia",
        "MF" => "Saint Martin",
        "PM" => "Saint Pierre and Miquelon",
        "VC" => "Saint Vincent and the Grenadines",
        "WS" => "Samoa",
        "SM" => "San Marino",
        "SA" => "Saudi Arabia",
        "SN" => "Senegal",
        "RS" => "Serbia",
        "CS" => "Serbia and Montenegro",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapore",
        "SK" => "Slovakia",
        "SI" => "Slovenia",
        "SB" => "Solomon Islands",
        "SO" => "Somalia",
        "ZA" => "South Africa",
        "GS" => "South Georgia and the South Sandwich Islands",
        "KR" => "South Korea",
        "ES" => "Spain",
        "LK" => "Sri Lanka",
        "SD" => "Sudan",
        "SR" => "Suriname",
        "SJ" => "Svalbard and Jan Mayen",
        "SZ" => "Swaziland",
        "SE" => "Sweden",
        "CH" => "Switzerland",
        "SY" => "Syria",
        "ST" => "São Tomé and Príncipe",
        "TW" => "Taiwan",
        "TJ" => "Tajikistan",
        "TZ" => "Tanzania",
        "TH" => "Thailand",
        "TL" => "Timor-Leste",
        "TG" => "Togo",
        "TK" => "Tokelau",
        "TO" => "Tonga",
        "TT" => "Trinidad and Tobago",
        "TN" => "Tunisia",
        "TR" => "Turkey",
        "TM" => "Turkmenistan",
        "TC" => "Turks and Caicos Islands",
        "TV" => "Tuvalu",
        "UM" => "U.S. Minor Outlying Islands",
        "PU" => "U.S. Miscellaneous Pacific Islands",
        "VI" => "U.S. Virgin Islands",
        "UG" => "Uganda",
        "UA" => "Ukraine",
        "SU" => "Union of Soviet Socialist Republics",
        "AE" => "United Arab Emirates",
        "GB" => "United Kingdom",
        "US" => "United States",
        "ZZ" => "Unknown or Invalid Region",
        "UY" => "Uruguay",
        "UZ" => "Uzbekistan",
        "VU" => "Vanuatu",
        "VA" => "Vatican City",
        "VE" => "Venezuela",
        "VN" => "Vietnam",
        "WK" => "Wake Island",
        "WF" => "Wallis and Futuna",
        "EH" => "Western Sahara",
        "YE" => "Yemen",
        "ZM" => "Zambia",
        "ZW" => "Zimbabwe",
        "AX" => "Åland Islands"
    );
    return empty($country_code) ? $countries : $countries[$country_code];
}

function get_currencies($currency='') {
    $currencies = array(
        "AFA" => array("name" => "Afghan Afghani", "symbol" => "؋"),
        "ALL" => array("name" => "Albanian Lek", "symbol" => "Lek"),
        "DZD" => array("name" => "Algerian Dinar", "symbol" => "دج"),
        "AOA" => array("name" => "Angolan Kwanza", "symbol" => "Kz"),
        "ARS" => array("name" => "Argentine Peso", "symbol" => "$"),
        "AMD" => array("name" => "Armenian Dram", "symbol" => "֏"),
        "AWG" => array("name" => "Aruban Florin", "symbol" => "ƒ"),
        "AUD" => array("name" => "Australian Dollar", "symbol" => "$"),
        "AZN" => array("name" => "Azerbaijani Manat", "symbol" => "m"),
        "BSD" => array("name" => "Bahamian Dollar", "symbol" => "B$"),
        "BHD" => array("name" => "Bahraini Dinar", "symbol" => ".د.ب"),
        "BDT" => array("name" => "Bangladeshi Taka", "symbol" => "৳"),
        "BBD" => array("name" => "Barbadian Dollar", "symbol" => "Bds$"),
        "BYR" => array("name" => "Belarusian Ruble", "symbol" => "Br"),
        "BEF" => array("name" => "Belgian Franc", "symbol" => "fr"),
        "BZD" => array("name" => "Belize Dollar", "symbol" => "$"),
        "BMD" => array("name" => "Bermudan Dollar", "symbol" => "$"),
        "BTN" => array("name" => "Bhutanese Ngultrum", "symbol" => "Nu."),
        "BTC" => array("name" => "Bitcoin", "symbol" => "฿"),
        "BOB" => array("name" => "Bolivian Boliviano", "symbol" => "Bs."),
        "BAM" => array("name" => "Bosnia-Herzegovina Convertible Mark", "symbol" => "KM"),
        "BWP" => array("name" => "Botswanan Pula", "symbol" => "P"),
        "BRL" => array("name" => "Brazilian Real", "symbol" => "R$"),
        "GBP" => array("name" => "British Pound Sterling", "symbol" => "£"),
        "BND" => array("name" => "Brunei Dollar", "symbol" => "B$"),
        "BGN" => array("name" => "Bulgarian Lev", "symbol" => "Лв."),
        "BIF" => array("name" => "Burundian Franc", "symbol" => "FBu"),
        "KHR" => array("name" => "Cambodian Riel", "symbol" => "KHR"),
        "CAD" => array("name" => "Canadian Dollar", "symbol" => "$"),
        "CVE" => array("name" => "Cape Verdean Escudo", "symbol" => "$"),
        "KYD" => array("name" => "Cayman Islands Dollar", "symbol" => "$"),
        "XOF" => array("name" => "CFA Franc BCEAO", "symbol" => "CFA"),
        "XAF" => array("name" => "CFA Franc BEAC", "symbol" => "FCFA"),
        "XPF" => array("name" => "CFP Franc", "symbol" => "₣"),
        "CLP" => array("name" => "Chilean Peso", "symbol" => "$"),
        "CLF" => array("name" => "Chilean Unit of Account", "symbol" => "CLF"),
        "CNY" => array("name" => "Chinese Yuan", "symbol" => "¥"),
        "COP" => array("name" => "Colombian Peso", "symbol" => "$"),
        "KMF" => array("name" => "Comorian Franc", "symbol" => "CF"),
        "CDF" => array("name" => "Congolese Franc", "symbol" => "FC"),
        "CRC" => array("name" => "Costa Rican Colón", "symbol" => "₡"),
        "HRK" => array("name" => "Croatian Kuna", "symbol" => "kn"),
        "CUC" => array("name" => "Cuban Convertible Peso", "symbol" => "$, CUC"),
        "CZK" => array("name" => "Czech Republic Koruna", "symbol" => "Kč"),
        "DKK" => array("name" => "Danish Krone", "symbol" => "Kr."),
        "DJF" => array("name" => "Djiboutian Franc", "symbol" => "Fdj"),
        "DOP" => array("name" => "Dominican Peso", "symbol" => "$"),
        "XCD" => array("name" => "East Caribbean Dollar", "symbol" => "$"),
        "EGP" => array("name" => "Egyptian Pound", "symbol" => "ج.م"),
        "ERN" => array("name" => "Eritrean Nakfa", "symbol" => "Nfk"),
        "EEK" => array("name" => "Estonian Kroon", "symbol" => "kr"),
        "ETB" => array("name" => "Ethiopian Birr", "symbol" => "Nkf"),
        "EUR" => array("name" => "Euro", "symbol" => "€"),
        "FKP" => array("name" => "Falkland Islands Pound", "symbol" => "£"),
        "FJD" => array("name" => "Fijian Dollar", "symbol" => "FJ$"),
        "GMD" => array("name" => "Gambian Dalasi", "symbol" => "D"),
        "GEL" => array("name" => "Georgian Lari", "symbol" => "ლ"),
        "DEM" => array("name" => "German Mark", "symbol" => "DM"),
        "GHS" => array("name" => "Ghanaian Cedi", "symbol" => "GH₵"),
        "GIP" => array("name" => "Gibraltar Pound", "symbol" => "£"),
        "GRD" => array("name" => "Greek Drachma", "symbol" => "₯, Δρχ, Δρ"),
        "GTQ" => array("name" => "Guatemalan Quetzal", "symbol" => "Q"),
        "GNF" => array("name" => "Guinean Franc", "symbol" => "FG"),
        "GYD" => array("name" => "Guyanaese Dollar", "symbol" => "$"),
        "HTG" => array("name" => "Haitian Gourde", "symbol" => "G"),
        "HNL" => array("name" => "Honduran Lempira", "symbol" => "L"),
        "HKD" => array("name" => "Hong Kong Dollar", "symbol" => "$"),
        "HUF" => array("name" => "Hungarian Forint", "symbol" => "Ft"),
        "ISK" => array("name" => "Icelandic Króna", "symbol" => "kr"),
        "INR" => array("name" => "Indian Rupee", "symbol" => "₹"),
        "IDR" => array("name" => "Indonesian Rupiah", "symbol" => "Rp"),
        "IRR" => array("name" => "Iranian Rial", "symbol" => "﷼"),
        "IQD" => array("name" => "Iraqi Dinar", "symbol" => "د.ع"),
        "ILS" => array("name" => "Israeli New Sheqel", "symbol" => "₪"),
        "ITL" => array("name" => "Italian Lira", "symbol" => "L,£"),
        "JMD" => array("name" => "Jamaican Dollar", "symbol" => "J$"),
        "JPY" => array("name" => "Japanese Yen", "symbol" => "¥"),
        "JOD" => array("name" => "Jordanian Dinar", "symbol" => "ا.د"),
        "KZT" => array("name" => "Kazakhstani Tenge", "symbol" => "лв"),
        "KES" => array("name" => "Kenyan Shilling", "symbol" => "KSh"),
        "KWD" => array("name" => "Kuwaiti Dinar", "symbol" => "ك.د"),
        "KGS" => array("name" => "Kyrgystani Som", "symbol" => "лв"),
        "LAK" => array("name" => "Laotian Kip", "symbol" => "₭"),
        "LVL" => array("name" => "Latvian Lats", "symbol" => "Ls"),
        "LBP" => array("name" => "Lebanese Pound", "symbol" => "£"),
        "LSL" => array("name" => "Lesotho Loti", "symbol" => "L"),
        "LRD" => array("name" => "Liberian Dollar", "symbol" => "$"),
        "LYD" => array("name" => "Libyan Dinar", "symbol" => "د.ل"),
        "LTC" => array("name" => "Litecoin", "symbol" => "Ł"),
        "LTL" => array("name" => "Lithuanian Litas", "symbol" => "Lt"),
        "MOP" => array("name" => "Macanese Pataca", "symbol" => "$"),
        "MKD" => array("name" => "Macedonian Denar", "symbol" => "ден"),
        "MGA" => array("name" => "Malagasy Ariary", "symbol" => "Ar"),
        "MWK" => array("name" => "Malawian Kwacha", "symbol" => "MK"),
        "MYR" => array("name" => "Malaysian Ringgit", "symbol" => "RM"),
        "MVR" => array("name" => "Maldivian Rufiyaa", "symbol" => "Rf"),
        "MRO" => array("name" => "Mauritanian Ouguiya", "symbol" => "MRU"),
        "MUR" => array("name" => "Mauritian Rupee", "symbol" => "₨"),
        "MXN" => array("name" => "Mexican Peso", "symbol" => "$"),
        "MDL" => array("name" => "Moldovan Leu", "symbol" => "L"),
        "MNT" => array("name" => "Mongolian Tugrik", "symbol" => "₮"),
        "MAD" => array("name" => "Moroccan Dirham", "symbol" => "MAD"),
        "MZM" => array("name" => "Mozambican Metical", "symbol" => "MT"),
        "MMK" => array("name" => "Myanmar Kyat", "symbol" => "K"),
        "NAD" => array("name" => "Namibian Dollar", "symbol" => "$"),
        "NPR" => array("name" => "Nepalese Rupee", "symbol" => "₨"),
        "ANG" => array("name" => "Netherlands Antillean Guilder", "symbol" => "ƒ"),
        "TWD" => array("name" => "New Taiwan Dollar", "symbol" => "$"),
        "NZD" => array("name" => "New Zealand Dollar", "symbol" => "$"),
        "NIO" => array("name" => "Nicaraguan Córdoba", "symbol" => "C$"),
        "NGN" => array("name" => "Nigerian Naira", "symbol" => "₦"),
        "KPW" => array("name" => "North Korean Won", "symbol" => "₩"),
        "NOK" => array("name" => "Norwegian Krone", "symbol" => "kr"),
        "OMR" => array("name" => "Omani Rial", "symbol" => ".ع.ر"),
        "PKR" => array("name" => "Pakistani Rupee", "symbol" => "₨"),
        "PAB" => array("name" => "Panamanian Balboa", "symbol" => "B/."),
        "PGK" => array("name" => "Papua New Guinean Kina", "symbol" => "K"),
        "PYG" => array("name" => "Paraguayan Guarani", "symbol" => "₲"),
        "PEN" => array("name" => "Peruvian Nuevo Sol", "symbol" => "S/."),
        "PHP" => array("name" => "Philippine Peso", "symbol" => "₱"),
        "PLN" => array("name" => "Polish Zloty", "symbol" => "zł"),
        "QAR" => array("name" => "Qatari Rial", "symbol" => "ق.ر"),
        "RON" => array("name" => "Romanian Leu", "symbol" => "lei"),
        "RUB" => array("name" => "Russian Ruble", "symbol" => "₽"),
        "RWF" => array("name" => "Rwandan Franc", "symbol" => "FRw"),
        "SVC" => array("name" => "Salvadoran Colón", "symbol" => "₡"),
        "WST" => array("name" => "Samoan Tala", "symbol" => "SAT"),
        "STD" => array("name" => "São Tomé and Príncipe Dobra", "symbol" => "Db"),
        "SAR" => array("name" => "Saudi Riyal", "symbol" => "﷼"),
        "RSD" => array("name" => "Serbian Dinar", "symbol" => "din"),
        "SCR" => array("name" => "Seychellois Rupee", "symbol" => "SRe"),
        "SLL" => array("name" => "Sierra Leonean Leone", "symbol" => "Le"),
        "SGD" => array("name" => "Singapore Dollar", "symbol" => "$"),
        "SKK" => array("name" => "Slovak Koruna", "symbol" => "Sk"),
        "SBD" => array("name" => "Solomon Islands Dollar", "symbol" => "Si$"),
        "SOS" => array("name" => "Somali Shilling", "symbol" => "Sh.so."),
        "ZAR" => array("name" => "South African Rand", "symbol" => "R"),
        "KRW" => array("name" => "South Korean Won", "symbol" => "₩"),
        "SSP" => array("name" => "South Sudanese Pound", "symbol" => "£"),
        "XDR" => array("name" => "Special Drawing Rights", "symbol" => "SDR"),
        "LKR" => array("name" => "Sri Lankan Rupee", "symbol" => "Rs"),
        "SHP" => array("name" => "St. Helena Pound", "symbol" => "£"),
        "SDG" => array("name" => "Sudanese Pound", "symbol" => ".س.ج"),
        "SRD" => array("name" => "Surinamese Dollar", "symbol" => "$"),
        "SZL" => array("name" => "Swazi Lilangeni", "symbol" => "E"),
        "SEK" => array("name" => "Swedish Krona", "symbol" => "kr"),
        "CHF" => array("name" => "Swiss Franc", "symbol" => "CHf"),
        "SYP" => array("name" => "Syrian Pound", "symbol" => "LS"),
        "TJS" => array("name" => "Tajikistani Somoni", "symbol" => "SM"),
        "TZS" => array("name" => "Tanzanian Shilling", "symbol" => "TSh"),
        "THB" => array("name" => "Thai Baht", "symbol" => "฿"),
        "TOP" => array("name" => "Tongan Pa'anga", "symbol" => "$"),
        "TTD" => array("name" => "Trinidad & Tobago Dollar", "symbol" => "$"),
        "TND" => array("name" => "Tunisian Dinar", "symbol" => "ت.د"),
        "TRY" => array("name" => "Turkish Lira", "symbol" => "₺"),
        "TMT" => array("name" => "Turkmenistani Manat", "symbol" => "T"),
        "UGX" => array("name" => "Ugandan Shilling", "symbol" => "USh"),
        "UAH" => array("name" => "Ukrainian Hryvnia", "symbol" => "₴"),
        "AED" => array("name" => "United Arab Emirates Dirham", "symbol" => "إ.د"),
        "UYU" => array("name" => "Uruguayan Peso", "symbol" => "$"),
        "USD" => array("name" => "US Dollar", "symbol" => "$"),
        "UZS" => array("name" => "Uzbekistan Som", "symbol" => "лв"),
        "VUV" => array("name" => "Vanuatu Vatu", "symbol" => "VT"),
        "VEF" => array("name" => "Venezuelan BolÃvar", "symbol" => "Bs"),
        "VND" => array("name" => "Vietnamese Dong", "symbol" => "₫"),
        "YER" => array("name" => "Yemeni Rial", "symbol" => "﷼"),
        "ZMK" => array("name" => "Zambian Kwacha", "symbol" => "ZK"),
        "ZWL" => array("name" => "Zimbabwean dollar", "symbol" => "$")
    );
    if(!empty($currency)) {
        return $currencies[$currency];
    }else {
        return $currencies;
    }
}

function product_types() {
    return [
        'simple' => 'Simple',
        'variable' => 'Variable',
        'external' => 'External/Affiliate Product'
    ];
}

function in_stock($product=[]) {
    if(!empty($product) && $product->stock_managed=='yes') {
        $stock_status = $product->stock_status;
        $stock_value = $product->stock;
        if($stock_status === "outofstock" || $stock_value == 0) {
            return false;
        }
    }
    return true;
}

function product_tax_statuses() {
    return [
        'taxable' => 'Taxable',
        //'shipping_only' => 'Shipping Only',
        'none' => 'none'
    ];
}

function email_default_shortcodes() {
    $site_settings = get_setting('website',true);
    $get_tags = [
        'current_date' => date(env('date_format')),
        'current_time' => date(env('time_format')),
        'current_year' => date('Y'),
        'site_name' => @$site_settings['title'],
        'site_url' => trim(site_url(),'/'),
        'contact_email' => @$site_settings['contact_email'],
        'contact_number' => @$site_settings['contact_email'],
        'site_address_1' => @$site_settings['site_address_1'],
        'site_address_2' => @$site_settings['site_address_2'],
        'vat_number' => @$site_settings['vat_number'],
    ];
    return $get_tags;
}

function get_selling_countries() {
    $get_countries = get_countries();
    $selling_countries = get_setting('selling_countries',true);
    $selling_location = get_setting('selling_location');

    if(!empty($selling_countries)) {

        if(!empty($selling_countries)) {
            if($selling_location === "specific") {
                $countries = [];
                foreach($selling_countries as $code) {
                    $countries[$code] = $get_countries[$code];
                }
                return $countries;
            }
            if($selling_location === "except") {
                $countries = [];
                foreach($get_countries as $code=>$country) {
                    if(!in_array($code,$selling_countries)) {
                        $countries[$code] = $country;
                    }
                }
                return $countries;
            }
            if($selling_location === "all") {
                return $get_countries;
            }
        }

    }else {
        return $get_countries;
    }
}

function number_position($number='') {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}

function init_subscription_form_script() {
    ?>
<script type="text/javascript">
function plan_expire_options(element) {
    const parent = element.closest('.shop_subscription_form');
    const interval = $(parent).find('.subscription-interval').val();
    const period = $(parent).find('.subscription-period').val();

    const plan_expire = $(parent).find('.subscription-plan-expire');
    const plan_price = $(parent).find('.subscription_plan_price');

    const url = `<?php echo site_url() ?>ajax/sub_plan_expire_options?period=${period}&interval=${interval}`;
    plan_expire.find('select').html('');

    if (interval && period) {
        $('.add_to_cart_form').addClass('loading');
        plan_price.text('');

        fetch(url).then(res => res.json()).then((res) => {
            if (Object.keys(res).length) {
                for (let k in res) {
                    let option = document.createElement('option');
                    option.value = k;
                    option.text = res[k];
                    //document.querySelector('#subscription-plan-expire > select').appendChild(option);
                    plan_expire.find('select').append(`<option value="${res[k]}">${res[k]}</option>`);
                }
                if (plan_expire.find('select').attr('value')) {
                    plan_expire.find('select').val(plan_expire.find('select').attr('value'));
                }
            }
            plan_expire.find('select').trigger('change');
            $('.add_to_cart_form').removeClass('loading');
        });
    }
}

function set_price_display(expire) {
    const parent = expire.closest('.subscription_box_fields');
    const interval_input = parent.querySelector('.subscription-interval');
    const interval_text = $(interval_input).find('option:selected').text().toLowerCase();

    const period_input = parent.querySelector('.subscription-period');
    const period_text = $(period_input).find('option:selected').text().toLowerCase();
    const expire_text = $(expire).find('option:selected').text().toLowerCase();

    const prod_price = $(parent).data('price');

    $(parent).find('.subscription_plan_price').text(`${prod_price} ${interval_text} ${period_text} for ${expire_text}`);

    $(parent).find('.plan_price_value').val(prod_price);
}

function subscription_switch(check) {
    const parent = $(check).closest('.shop_subscription_form');
    if (check.checked) {
        parent.find('.subscription_box_fields').show();
        parent.find('.subscription_box_fields').find('input,select').prop('disabled', false);
    } else {
        parent.find('.subscription_box_fields').hide();
        parent.find('.subscription_box_fields').find('input,select').prop('disabled', true);
    }
}
</script>
<?php
}

function squareup_script_tag() {
    if(env('squareup.enable')) {
        $payment_methods = get_setting('payment_method',true);
        if(!empty($payment_methods['squareup']['mode']) && $payment_methods['squareup']['mode'] === "sandbox") {
            ?>
<script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
<?php
        }else {
            ?>
<script type="text/javascript" src="https://js.squareup.com"></script>
<?php
        }
    }
}