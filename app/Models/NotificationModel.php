<?php

namespace App\Models;
use CodeIgniter\Model;

class NotificationModel extends Model {
    public function fetch($where=[], $single = false) {
        $getdb = $this->db->table('tbl_notifications')->where($where)->get();
        if(!$single) {
            return $getdb->getResultArray();
        }else {
            return $getdb->getRowArray();
        }
    }

    public function create($content, $action_url='', $type='', $from_user='', $to_user=0) {
        if(!$to_user && !empty($_SESSION['user']['id'])) {
            $to_user = $_SESSION['user']['id'];
        }
        $db_data = [
            'content' => $content,
            'type' => $type,
            'action_url' => $action_url,
            'from_user' => $from_user,
            'to_user' => $to_user
        ];

        $this->db->table('tbl_notifications')->insert($db_data);

        return $this->db->insertID();
    }

    public function mark_read($notification_id) {
        $this->db->table('tbl_notifications')->where(['notification_id'=>$notification_id])->update(['is_read'=>1]);
    }
}