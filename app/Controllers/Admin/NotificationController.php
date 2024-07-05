<?php


namespace App\Controllers\Admin;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\MasterModel;

class NotificationController extends BaseController
{
    private $data;

    public function index() {

        $this->data['content'] = ADMIN . "/notifications";
        $this->data['page'] = 'notifications';

        $uid = is_logged_in();

        $notifications = model('NotificationModel');

        if($this->request->getGet('mark_read')) {
            $notification_id = $this->request->getGet('mark_read');

            $notifications->mark_read($notification_id);

            notice_success('Notification marked as read');

            return redirect()->back();
        }

        if($this->request->getGet('get_data')) {

            $userModel = model('UserModel');

            $rows = [
                'notification_id',
                'content',
                'from_user',
                'type',
                'action_url',
                'is_read',
                'date_created',
                'action_url'
            ];

            $sort_cols = [
                'notification_id',
                'content',
                'from_user',
                'type',
                'action_url',
                'is_read',
                'date_created',
            ];

            $where = "WHERE to_user='$uid'";

            $output = datatable_query("tbl_notifications",$rows,$sort_cols,"", $where);

            $records = $output['records'];

            unset($output['records']);

            foreach($records as $row) {

                $notifications->mark_read($row['notification_id']);

                $from_user = $userModel->get_user($row['from_user'],'fname,lname,display_name');
                $from_display_name = !empty($from_user->display_name) ? $from_user->display_name : $from_user->fname.' '.$from_user->lname;

                $actions = '<a href="'.admin_url().''.$row['action_url'].'" class="btn btn-sm btn-primary d-inline-block">View</a> ';

                if(!$row['is_read']) {
                    $actions .= ' <a href="?mark_read='.$row['notification_id'].'" class="btn btn-sm btn-primary d-inline-block">Mark as read</a>';
                }

                $output['data'][] = [
                    $row['notification_id'],
                    $row['content'],
                    $from_display_name,
                    '<div class="text-center">'.($row['is_read'] ? 'Yes':'No').'</div>',
                    '<div class="text-center">'._date($row['date_created']).'</div>',
                    '<div style="width: 170px">'.$actions.'</div>'
                ];
            }

            echo json_encode($output);

            exit;

        }

        _render_page('/' . ADMIN . '/index', $this->data);
    }
}