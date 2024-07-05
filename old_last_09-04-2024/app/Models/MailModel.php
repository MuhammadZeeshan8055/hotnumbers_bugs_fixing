<?php

namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

use CodeIgniter\Validation\ValidationInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailModel extends Model {
    private $headers;
    private $view;
    private $subject;
    private $from, $cc, $bcc;

    public function __construct(?ConnectionInterface &$db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        $this->headers .= 'MIME-Version: 1.0' . "\r\n";
        $this->headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

        $this->view = \Config\Services::renderer();
    }

    public function get_mail_header() {
        //return view("email_templates/email_header");
        $header = $this->get_mails("WHERE keyname='header'")->getRowArray();
        return base64_decode($header['content']);
    }

    public function get_mail_footer() {
        //return view("email_templates/email_footer");
        $footer = $this->get_mails("WHERE keyname='footer'")->getRowArray();
        return base64_decode($footer['content']);
    }

    public function subject($subject_text='') {
        $this->subject = $subject_text;
    }

    public function get_mails($query="") {
        $sql = "SELECT * FROM tbl_mail_templates";
        $sql .= " ".$query;
        return $this->db->query($sql);
    }

    public function getMailbyID($id,$fields='*') {
        $sql = "SELECT $fields FROM tbl_mail_templates WHERE id='$id'";
        return $this->db->query($sql)->getFirstRow();
    }

    public function getMailbyKey($key,$fields='*') {
        $sql = "SELECT $fields FROM tbl_mail_templates WHERE keyname='$key'";
        return $this->db->query($sql)->getFirstRow();
    }

    public function send_email($to,$mailbody) {
        $content = $this->get_mail_header();
        $content .= $mailbody;
        $content .= $this->get_mail_footer();

        $subject = !empty($this->subject) ? $this->subject : '';

        $send = mail($to,$subject,$content,$this->headers);

        $this->record_log([
            'to' => $to,
            'subject' => $subject,
            'content' => $content,
            'status' => !empty($send) ? 1 : 0
        ]);

        return $send;
    }

    public function get_parsed_content($key,$tags=[],$status=1) {
        $where = "WHERE keyname='$key'";

        if($status !== 'any') {
            $where .= " AND status=$status";
        }

        if(empty($tags['profile_link'])) {
            $tags['profile_link'] = '<a href="'.site_url('account').'">'.site_url('account').'</a>';
        }

        $tags = array_merge($tags, email_default_shortcodes());
        $tags = array_filter($tags);

        $getMail = $this->get_mails($where)->getRowArray();

        $content = '';
        if(!empty($getMail)) {
            $content = base64_decode($getMail['content']);
            foreach($tags as $tag=>$value) {
                $content = str_replace("{{{$tag}}}", $value, $content);
            }
        }

        if(!empty($getMail['mail_from'])) {
            $this->headers .= 'From: '.$getMail['mail_from']. "\r\n";
            $this->from = $getMail['mail_from'];
        }else {
            $setting = get_setting('website');
            $setting = json_decode($setting,true);
            $this->headers .= 'From: '.$setting['online_admin_email']. "\r\n";
            $this->from = $setting['online_admin_email'];
        }

        if(!empty($getMail['cc'])) {
            $this->headers .= 'Cc: '.$getMail['cc']. "\r\n";
            $this->cc = $getMail['cc'];
        }

        if(!empty($getMail['bcc'])) {
            $this->headers .= 'Bcc: '.$getMail['bcc']. "\r\n";
            $this->bcc = $getMail['bcc'];
        }

        $mail_subject = $this->subject;

        if(empty($this->subject) && !empty($getMail)) {
            $mail_subject = $getMail['subject'];
        }

        if($mail_subject) {
            foreach($tags as $tag=>$value) {
                $mail_subject = str_replace("{{{$tag}}}", $value,$mail_subject);
            }
        }

        $this->subject = $mail_subject;

        return $content;
    }

    public function view($keyname='') {
        $tags = email_default_shortcodes();
        $body = $this->get_mail_header();
        $body .= $this->get_parsed_content($keyname);
        $body .= $this->get_mail_footer();
        if(!empty($tags)) {
            foreach($tags as $tag=>$value) {
                $body = str_replace("{{{$tag}}}", $value, $body);
            }
        }
        return $body;
    }

    public function add_mail($data=[]) {
        $keyname = strtolower($data['name']);
        $keyname = str_replace(' ','_',$keyname);
        $content = base64_encode($data['mail_content']);
        $db_data = [
            'name' => $data['name'],
            'keyname' => $keyname,
            'subject' => $data['subject'],
            'mail_from' => $data['from'],
            'cc' => $data['cc'],
            'bcc' => $data['bcc'],
            'content' => $content,
            'status'=>empty($data['status']) ? 0 : 1,
            'is_default' => 0
        ];
        $db_data = array_filter($db_data);
        return $this->db->table('tbl_mail_templates')->insert($db_data);
    }

    public function update_mail($id,$data=[]) {

        $content = base64_encode($data['mail_content']);
        $db_data = [
            'name' => @$data['name'],
            'subject' => @$data['subject'],
            'mail_from' => @$data['from'],
            'cc' => @$data['cc'],
            'bcc' => @$data['bcc'],
            'content' => $content,
            'status'=>empty($data['status']) ? 0 : 1
        ];
        $db_data = array_filter($db_data);

        notice_success('Mail template is updated');
        return $this->db->table('tbl_mail_templates')->where(['id'=>$id])->update($db_data);
    }

    public function get_logs($where=[]) {
        return $this->db->table('tbl_mail_logs')->where($where)->get()->getResultArray();
    }

    public function logs_datatable() {
        if(!empty($_GET['draw'])) {
            $rows = [
                'mail_id',
                'mail_content',
                'mail_to',
                'mail_from',
                'mail_subject',
                'mail_cc',
                'mail_bcc',
                'status',
                'send_date'
            ];
            $sort_cols = [
                'mail_id',
                'mail_to',
                'mail_from',
                'mail_subject',
                'mail_cc',
                'mail_bcc',
                'status',
                'send_date'
            ];

            $output = datatable_query('tbl_mail_logs',$rows,$sort_cols);

            $records = $output['records'];



            foreach($records as $record) {
                $status = $record['status'] ? '✅ Sent':'⚠️ Failed';

                ob_start();
                ?>
                <div class="text-center" style="padding: 5px">
                    <a id="mail_content_<?php echo $record['mail_id'] ?>" href="#" class="edit_row btn btn-primary btn-sm red">View Content</a>
                </div>
                <script>
                    document.querySelector('#mail_content_<?php echo $record['mail_id'] ?>').addEventListener('click',(e)=>{
                        e.preventDefault();
                        Swal.fire({
                            html: `<div style="text-align:left"><?php echo $record['mail_content'] ?></div>`,
                            showConfirmButton: false,
                            showCancelButton: false,
                            showCloseButton: true
                        });
                    })
                </script>
                <?php
                $action = ob_get_clean();

                $output['data'][] = [
                    $record['mail_id'],
                    $record['mail_to'],
                    $record['mail_from'],
                    $record['mail_subject'],
                    $record['mail_cc'],
                    $record['mail_bcc'],
                    $action,
                    $status,
                    $record['send_date']
                ];
            }

            echo json_encode($output);
            exit;
        }
    }

    public function record_log($data=[]) {
        $data['from'] = $this->from;
        $data['cc'] = $this->cc;
        $data['bcc'] = $this->bcc;

        $db_data = [
            'mail_to' => $data['to'],
            'mail_from' => $data['from'],
            'mail_cc' => $data['cc'],
            'mail_bcc' => $data['bcc'],
            'mail_subject' => $data['subject'],
            'mail_content' => $data['content'],
            'status' => $data['status']
        ];

        $this->db->table('tbl_mail_logs')->insert($db_data);
    }

    public function get_mail_shortcodes($mail_id=0) {
        $tags = array_keys(email_default_shortcodes());
        if($mail_id) {
            $mail = $this->getMailbyID($mail_id,'shortcodes');
            $mail_shortcodes = !empty($mail) && $mail->shortcodes ? explode(',',$mail->shortcodes) : [];
            $tags = array_merge($tags, $mail_shortcodes);
        }
        return $tags;
    }
}