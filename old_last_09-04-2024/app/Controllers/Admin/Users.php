<?php

namespace App\Controllers\Admin;

use App\Libraries\Pass_hash_lib;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\MasterModel;


class Users extends BaseController
{
    private $master;
    protected $uri;
    protected $table = "tbl_users";
    private $data;
    private $userModel;


    public function __construct()
    {
        $this->uri = service('uri');
        $this->master = new MasterModel();
        $this->data['page']="users";
        $this->userModel = model("UserModel");

    }
    public function index()
    {
        $this->data['userModel'] = $userModel = $this->userModel;

        $role = !empty($_GET['role']) ? $_GET['role'] : '';

        //$this->data['user_rows'] = $userModel->get_users('*','any',$role);

        if($this->request->getGet('get_data')) {

            $rows = [
                'u.user_id',
                'u.display_name',
                'u.username',
                'u.email',
                'u.last_active',
                ''=>['searchable'=>false]
            ];

            $sort_cols = [
                'u.user_id',
                'u.display_name',
                'u.username',
                'u.email',
                'u.last_active'
            ];

            $where = " ";

            if(!empty($_GET['role'])) {
                $where = " AND role.role IN ('".$_GET['role']."') ";
            }

            $output = datatable_query("tbl_users AS u JOIN tbl_user_roles AS role ON role.id=u.role",$rows,$sort_cols,"GROUP BY u.user_id",$where);


            $records = $output['records'];

            unset($output['records']);

            foreach($records as $i=>$row) {

                $user_roles = $userModel->get_user_roles($row['user_id']);

                $meta_last_active = !empty($row['last_active']) ? date('d/m/Y @ h:i a',$row['last_active']) : '';

                $actions = [
                  ' <a class="edit_row btn btn-primary btn-sm"
                        href="'.base_url(ADMIN . '/users/edit').'/'.$row['user_id'].'">View</a>',
                    '<a class="edit_row btn btn-primary btn-sm" data-confirm="Are you sure to login as this user?"
                        data-href="'.base_url(ADMIN . '/users/login').'/'.$row['user_id'].'">Login</a>'
                ];

                if(!in_array('administrator',array_keys($user_roles))) {
                    $actions [] = '<a class="edit_row btn btn-secondary btn-sm bg-black" data-confirm="Are you sure to delete this user?"
                        data-href="'.base_url(ADMIN . '/users/edit').'/'.$row['user_id'].'/?delete=1">Delete</a>';
                }

                $output['data'][] = [
                    '#'.$row['user_id'],
                    $row['username'],
                    $row['display_name'],
                    !empty($user_roles) ? implode(', ',$user_roles) : '',
                    $row['email'],
                    '<div style="width: 160px">'.$meta_last_active.'</div>',
                    '<div class="text-center"  style="width: max-content;">
                        '.implode(' &nbsp;',$actions).'
                    </div>'
                ];
            }

            echo json_encode($output);

            exit;

        }

        $this->data['content'] = ADMIN . "/users/index";

        $this->data['roles'] = $this->userModel->get_roles();

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function administrators()
    {
        $this->data['userModel'] = $userModel = $this->userModel;

        $this->data['user_rows'] = $userModel->get_users('*','any','administrator');

        $this->data['content'] = ADMIN . "/users/index";

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function guests() {
        $this->data['userModel'] = $userModel = $this->userModel;

        $this->data['user_rows'] = $userModel->get_users('*','any','guest');

        $this->data['content'] = ADMIN . "/users/index";

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function edit() {
        $user_id = $this->uri->getSegment(4);
        $this->data['user_row'] = $this->userModel->get_user($user_id);
        $this->data['user_meta'] = $this->userModel->get_user_meta($user_id);
        $this->data['user_roles'] = $this->userModel->get_user_roles($user_id);
        $this->data['roles'] = $this->userModel->get_roles();
        $this->data['content'] = ADMIN . "/users/add_user";

        if(!empty($_GET['delete'])) {
           $curr_user = session('user');

           if($curr_user['id'] == $user_id) {
               notice_success('Cannot delete current user');
               return redirect()->back();
           }

            $this->userModel->delete_user($user_id);

            notice_success('User deleted successfully');
            return redirect()->back();
        }

        $orderModel = model('OrderModel');
        $this->data['order_history'] = $orderModel->get_orders("AND o.customer_user='".$user_id."' GROUP BY o.order_id ORDER BY o.order_date DESC");

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function add()
    {
        if ($data = $this->request->getPost()) {

            $user_id = $this->uri->getSegment(4);

            $db_data = [
              'fname'=>'',
              'lname'=>'',
              'username'=>'',
              'display_name'=>'',
              'email'=>'',
              'img'=>'',
              'status'=>0,
              'role'=>''
            ];

            $user_meta = $data['meta'];

            if ($this->userModel->email_exists($data['email'])) {
                notice_success("A user with this email already exists", 'error');
                return redirect()->back();
            }

            if(!empty($_FILES['image']['name'])) {
                $avatar = $this->request->getFile('image');
                $newfileName = $avatar->getRandomName();
                $avatar->move(SITE_IMAGES . 'users', $newfileName);
                if(!empty($data['old_image'])){
                    remove_file_from_directry(SITE_IMAGES.'/users/',$data['old_image']);
                }
                $db_data['img'] = $newfileName;
            }else {
                unset($db_data['img']);
            }

            $db_data['fname'] = !empty($data['fname']) ? $data['fname'] : '';
            $db_data['lname'] = !empty($data['lname']) ? $data['lname'] : '';
            $db_data['email'] = !empty($data['email']) ? $data['email'] : '';
            $db_data['status'] = !empty($data['status']) ? $data['status'] : 0;

            $db_data['username'] = !empty($data['username']) ? $data['username'] : '';
            $db_data['role'] = !empty($data['roles']) ? implode(',',$data['roles']) : '';

            $db_data['display_name'] = $data['fname'].' '.$data['lname'];

            if(!empty($data['new_password'])) {
                $hash = new Pass_hash_lib();
                $new_password = $data['new_password'];
                $db_data['password'] = $hash->HashPassword($new_password);

                $mail = model('MailModel');

                $mailbody = $mail->get_parsed_content('password_reset_admin', [
                    'display_name' => $db_data['display_name'],
                    'username' => $db_data['username'],
                    'password' => $new_password,
                    'email_address' => $db_data['email']
                ]);

                $mail->send_email($db_data['email'],$mailbody);
            }

            if($user_id) {
                $msg = 'User updated successfully';
                $this->master->insertData($this->table,$db_data, 'user_id',  $user_id);
            }else {
                $msg = 'User added successfully';
                $this->master->insertData($this->table,$db_data);
                $user_id = $this->master->last_insert_id();
            }

            foreach($user_meta as $key=>$value) {
                $value = addslashes($value);
                $exists = $this->master->getRow('tbl_user_meta',['user_id'=>$user_id,'meta_key'=>$key]);
                if(empty($exists)) {
                    $this->master->insertData('tbl_user_meta',['user_id'=>$user_id,'meta_key'=>$key,'meta_value'=>$value]);
                }else {
                    $this->master->query("UPDATE tbl_user_meta SET meta_value='$value' WHERE user_id='$user_id' AND meta_key='$key'");
                }
            }

            notice_success($msg);

            return redirect()->back()->with('msg', $msg);

        }

        $this->data['roles'] = $this->userModel->get_roles();
        $this->data['user_roles'] = $this->userModel->get_user_roles(-1);

        //$this->data['page'] = "features";
        $this->data['content'] = ADMIN . "/users/add_user";
        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function force_user_login($user_id=0) {
        if($user_id) {
            $get_user =  $this->userModel->get_user($user_id);
            $sess_data = [
              'id' => $get_user->user_id,
              'username' => $get_user->username
            ];
            $_SESSION['user'] = $sess_data;

            notice_success('Logged in as '.$get_user->display_name);

            _redirect(site_url());
        }
    }

    public function user_roles() {
        $userModel = $this->userModel;
        $this->data['roles'] = shop_roles('','any');
        $this->data['content'] = ADMIN . "/users/roles";

        if(!empty($_GET['delete'])) {
            $role = $_GET['delete'];
            $this->master->delete_data('tbl_user_roles','role',$role);
            notice_success('User role deleted successfully');
            return redirect()->back();
        }

        if(!empty($_POST['save_roles'])) {
            //$this->master->query("TRUNCATE TABLE tbl_user_roles");

            $save_data = [];

            foreach($_POST['role']['value'] as $k=>$value) {
                $status = !empty($_POST['role']['status'][$k]) ? $_POST['role']['status'][$k] : 0;
                $role = $_POST['role']['id'][$k];
                $save_data[$role] = ['role'=>$role,'name'=>$value, 'status'=>$status];
            }

            foreach($save_data as $data) {
                $this->master->insertOrUpdate('tbl_user_roles', $data,'role',$data['role']);
            }

            notice_success('Roles updated successfully');

            return redirect()->back();
        }

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function wholesale_requests() {

        if($this->request->getGet('action')) {
            $action = $this->request->getGet('action');
            $id = $this->request->getGet('id');

            $userModel = $this->userModel;

            $mail = model("MailModel");

            $data = $this->master->getRow('tbl_request_acc',['req_id'=>$id]);

            if($action == 'accept') {
                //create customer
                $userModel->register_wholesaler($data);
                $userModel->wholesaler_update($id, ['status'=>1]);

                //Congratulate customer
                $mailbody = $mail->get_parsed_content('wholesale_account_request_approved', $data);

                $mail->send_email($data['email_address'],$mailbody);
            }
            if($action == 'reject') {
                $userModel->wholesaler_update($id, ['status'=>2]);
            }

            notice_success('Action completed successfully');

            return redirect()->back();
        }

        if($this->request->getGet('get_data')) {
            $rows = [
                'req_id',
                'account_name',
                'coffee_usage',
                'full_name',
                'company_name',
                'phone_number',
                'email_address',
                'message',
                'status',
            ];

            $sort_cols = [
                'account_name',
                'coffee_usage',
                'full_name',
                'company_name',
                'phone_number',
                'email_address',
                'message',
                'status',
            ];

            $output = datatable_query("tbl_request_acc",$rows,$sort_cols);

            $records = $output['records'];
            unset($output['records']);

            foreach($records as $i=>$row) {

                switch ($row['status']) {
                    case 1: $status = 'Accepted'; break;
                    default: $status = 'Pending'; break;
                    case 2: $status = 'Rejected'; break;
                }

                $actions = [];

                if($row['status'] == 0) {
                    $actions[] = '<a class="btn btn-primary btn-sm" href="?id='.$row['req_id'].'&action=accept">Accept</a>';
                    $actions[] = '<a class="btn btn-primary btn-sm" href="?id='.$row['req_id'].'&action=reject">Reject</a>';
                }

                if($row['status'] == 1) {
                    $actions[] = '<a class="btn btn-primary btn-sm" href="?id='.$row['req_id'].'&action=reject">Reject</a>';
                }

                if($row['status'] == 2) {
                    $actions[] = '<a class="btn btn-primary btn-sm" href="?id='.$row['req_id'].'&action=accept">Accept</a>';
                }

                $output['data'][] = [
                    $row['account_name'],
                    $row['coffee_usage'],
                    $row['full_name'],
                    $row['company_name'],
                    $row['phone_number'],
                    $row['email_address'],
                    $row['message'],
                    $status,
                    implode(' ',$actions)
                ];
            }

            echo json_encode($output);

            exit;
        }

        $this->data['content'] = ADMIN . "/users/wholesale_requests";
        _render_page('/' . ADMIN . '/index', $this->data);
    }


}