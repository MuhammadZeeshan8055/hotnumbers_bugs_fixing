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
    private $from, $cc, $bcc, $attachment, $smtp_id;

    public function __construct(?ConnectionInterface &$db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        helper('functions');

        $config = get_setting('website', true);

        $admin_email = $config['online_admin_email'];
        $title = $config['title'];

        $this->headers .= 'MIME-Version: 1.0' . "\r\n";
        $this->headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $this->headers .= 'From: '.$title.' <'.$admin_email.'>' . "\r\n";

        $this->view = \Config\Services::renderer();
        $this->smtp_id = 0;
    }

    public function get_mail_header() {
        //return view("email_templates/email_header");
        $header = $this->get_mails("WHERE keyname='header'")->getRowArray();

        $tags = email_default_shortcodes();
        $content = '';
        if(!empty($header)) {
            $content = base64_decode($header['content']);
            foreach($tags as $tag=>$value) {
                $content = str_replace("{{{$tag}}}", $value, $content);
            }
        }

        return $content;
    }

    public function get_mail_footer($tags=[]) {
        //return view("email_templates/email_footer");
        $footer = $this->get_mails("WHERE keyname='footer'")->getRowArray();

        $tags = email_default_shortcodes();
        $content = '';
        if(!empty($footer)) {
            $content = base64_decode($footer['content']);
            foreach($tags as $tag=>$value) {
                $content = str_replace("{{{$tag}}}", $value, $content);
            }
        }

        return $content;
    }

    public function subject($subject_text='') {

        $this->subject = $subject_text;
    }

    public function set_from($from) {
        $this->from = $from;
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

    public function attach($file) {
        $this->attachment = $file;
    }

    public function send_email($to,$mailbody='',$attrs=[]) {

        if(empty($mailbody)) {
            return '';
        }

        $masterModel = model('MasterModel');

        $smtp_id = !empty($attrs['smtp_id']) ? $attrs['smtp_id'] : 0;

        if(!empty($this->smtp_id)) {
            $smtp_id = $this->smtp_id;
        }

        unset($attrs['smtp_id']);

        $content = '<div style="width: 620px; margin: auto; font-family: Arial">';
        $content .= $this->get_mail_header();
        $content .= $mailbody;
        $content .= $this->get_mail_footer();
        $content .= '<style>table {font-size: 16px}</style>';
        $content .= "</div>";

        //pr($content);

        $config = get_setting('website', true);

        $admin_email = $config['online_admin_email'];

        $subject = !empty($this->subject) ? $this->subject : '';

        $mail_from = !empty($this->from) ? $this->from : $admin_email;

        $mail_headers = $this->headers;

        if(env('email.protocol') === "smtp") {
            if($smtp_id) {
                $smtp_config = $masterModel->getRow('tbl_smtp_settings',['id'=>$smtp_id]);

                if(!empty($smtp_config)) {
                    $host = $smtp_config['host'];
                    $port = $smtp_config['port'];
                    $from_addr = $smtp_config['mail_from'];
                    $username = $smtp_config['username'];
                    $password = $smtp_config['password'];

                    $mailer = new PHPMailer();

                    $mailer->isSMTP();
                    $mailer->Host = $host;
                    $mailer->SMTPAuth = true;
                    $mailer->Username = $username;
                    $mailer->Port = $port;
                    $mailer->Password = $password;

                    $mail_from = $username;

                    $mailer->setFrom($mail_from, $from_addr);
                    $mailer->Subject = $subject;
                    $mailer->Body = $content;
                    $mailer->addAddress($to);

                    $mailer->CharSet = "UTF-8";

                    if(!empty($mail_headers)) {
                        $headers = explode("\r\n",$mail_headers);
                        foreach($headers as $header) {
                            $mailer->addCustomHeader($header);
                        }
                    }
                    $mailer->isHTML(1);
                    if($this->attachment) {
                        $mailer->addAttachment($this->attachment);
                    }
                    $email = $mailer->send();
                }
            }else {
                $email = \Config\Services::email();
                $email->setFrom($mail_from);
                $email->setTo($to);
                $email->setSubject($subject);
                $email->setMessage($content);
                $email->setMailType('html');
                if(!empty($mail_headers)) {
                    $headers = explode("\r\n",$mail_headers);
                    foreach($headers as $header) {
                        $email->addCustomHeader($header);
                    }
                }
                if($this->attachment) {
                    $email->attach($this->attachment);
                }
                $email->send();
            }
        }
        else {
            $mailer = new PHPMailer();
            $mailer->setFrom($mail_from);
            $mailer->Subject = $subject;
            $mailer->Body = $content;
            $mailer->addAddress($to);

            $mailer->CharSet = "UTF-8";

            if(!empty($mail_headers)) {
                $headers = explode("\r\n",$mail_headers);
                foreach($headers as $header) {
                    $mailer->addCustomHeader($header);
                }
            }
            $mailer->isHTML(1);
            if($this->attachment) {
                $mailer->addAttachment($this->attachment);
            }
            $email = $mailer->send();
        }

        $log_arr = [
            'to' => $to,
            'from' => $mail_from,
            'subject' => $subject,
            'content' => $content,
            'status' => !empty($email) ? 1 : 0
        ];
        if(!empty($attrs['mail_type'])) {
            $log_arr['type'] = $attrs['mail_type'];
        }
        if(!empty($attrs['post_id'])) {
            $log_arr['post_id'] = $attrs['post_id'];
        }

        $this->record_log($log_arr);

        return $email;
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

        if(empty($getMail)) {
            return false;
        }

        if($getMail['smtp_id']) {
            $this->smtp_id = $getMail['smtp_id'];
        }

        $content = '';
        if(!empty($getMail) && !empty($getMail['content'])) {
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

        return '<div style="padding-bottom: 0em; padding-top: 0em;">'.$content.'</div>';
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
            'smtp_id' => @$data['smtp_id'],
            'cc' => $data['cc'],
            'bcc' => $data['bcc'],
            'content' => $content,
            'status'=>empty($data['status']) ? 0 : 1,
            'is_default' => '0'
        ];

        return $this->db->table('tbl_mail_templates')->insert($db_data);
    }

    public function remove_mail($mail_id=0) {
        return $this->db->table('tbl_mail_templates')->delete(['id'=>$mail_id]);
    }

    public function toggleLogs() {
        $is_enabled = get_setting('record_email_logs');
        $master = model('MasterModel');
        if($is_enabled) {
            $master->insertOrUpdate('tbl_settings',['title'=>'record_email_logs', 'value'=>0],'title','record_email_logs');
            return 0;
        }else {
            $master->insertOrUpdate('tbl_settings',['title'=>'record_email_logs', 'value'=>1],'title','record_email_logs');
            return 1;
        }
    }

    public function update_mail($id,$data=[]) {

        $content = base64_encode($data['mail_content']);
        $db_data = [
            'name' => @$data['name'],
            'subject' => @$data['subject'],
            'smtp_id' => @$data['smtp_id'],
            'cc' => @$data['cc'],
            'bcc' => @$data['bcc'],
            'content' => $content,
            'status'=>empty($data['status']) ? 0 : 1
        ];

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
                    <a id="mail_content_<?php echo $record['mail_id'] ?>" href="#" class="edit_row btn btn-secondary btn-sm red">View</a>
                </div>
                <script>
                    document.querySelector('#mail_content_<?php echo $record['mail_id'] ?>').addEventListener('click',(e)=>{
                        e.preventDefault();
                        Swal.fire({
                            title: 'Email Preview',
                            html: `<div style="text-align:left"><?php echo $record['mail_content'] ?></div>`,
                            showConfirmButton: false,
                            showCancelButton: false,
                            showCloseButton: true,
                            showClass: {
                                popup: 'animated windowIn'
                            },
                            hideClass: {
                                popup: 'animated windowOut'
                            },
                        });
                    })
                </script>
                <?php
                $action = ob_get_clean();

                $output['data'][] = [
                    $record['mail_id'],
                    $record['mail_to'],
                    $record['mail_cc'],
                    $record['mail_bcc'],
                    $record['mail_from'],
                    $record['mail_subject'],
                    $status,
                    '<div class="text-center">'._date($record['send_date']).'</div>',
                    $action
                ];
            }

            echo json_encode($output);
            exit;
        }
    }

    public function record_log($data=[]) {
        if(get_setting('record_email_logs')) {
            $data['from'] = !empty($this->from) ? $this->from : $data['from'];
            $data['cc'] = $this->cc;
            $data['bcc'] = $this->bcc;

            $db_data = [
                'mail_to' => $data['to'],
                'mail_from' => $data['from'],
                'mail_cc' => $data['cc'],
                'mail_bcc' => $data['bcc'],
                'mail_subject' => $data['subject'],
                'mail_content' => $data['content'],
                'status' => $data['status'],
                'type' => !empty($data['type']) ? $data['type'] : '',
                'post_id' => !empty($data['post_id']) ? $data['post_id'] : '',
            ];

            $this->db->table('tbl_mail_logs')->insert($db_data);
        }
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