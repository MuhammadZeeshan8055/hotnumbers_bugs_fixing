<?php

namespace App\Controllers;

class EmailController extends BaseController
{
    private $data;

    public function admin_list_email_templates() {

        $this->data['page'] = "emails";
        $mails = model('MailModel');
        $master = model('MasterModel');

        if(!empty($_GET['delete'])) {
            $mails->remove_mail($_GET['delete']);
            notice_success('Template deleted successfully');
        }

        $this->data['emails'] = $mails->get_mails("WHERE (keyname!='header' AND keyname!='footer') AND is_default=1 ORDER BY sort_order")->getResultArray();
        $this->data['custom_emails'] = $mails->get_mails("WHERE (keyname!='header' AND keyname!='footer') AND is_default=0 ORDER BY sort_order")->getResultArray();

        $smtp_configs = $master->getRows('tbl_smtp_settings',['status'=>1]);
        $this->data['smtp_configs'] = [];
        if(!empty($smtp_configs)) {
            foreach($smtp_configs as $config) {
                $this->data['smtp_configs'][$config->id] = $config;
            }
        }

        $this->data['content'] = 'admin/emails/index';
        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function admin_add_email_template() {
        $this->data['page'] = "add-email-template";
        $this->data['mode'] = "add";
        $mails = model('MailModel');
        $master = model('MasterModel');
        $this->data['content'] = 'admin/emails/view';
        $this->data['smtp_settings'] = $master->getRows('tbl_smtp_settings',['status'=>1]);
        $this->data['shortcodes'] = $mails->get_mail_shortcodes();

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function admin_view_email_template($id) {
        $this->data['page'] = "emails";
        $this->data['mode'] = "edit";
        $mails = model('MailModel');
        $master = model('MasterModel');

        $this->data['smtp_settings'] = $master->getRows('tbl_smtp_settings',['status'=>1]);

        $this->data['shortcodes'] = $mails->get_mail_shortcodes($id);
        if($id === "header") {
            $this->data['email'] = $mails->get_mails("WHERE keyname='header'")->getRowArray();
        }elseif($id === "footer") {
            $this->data['email'] = $mails->get_mails("WHERE keyname='footer'")->getRowArray();
        }else {
            $this->data['email'] = $mails->get_mails("WHERE id='$id'")->getRowArray();
        }

        $this->data['content'] = 'admin/emails/view';

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function admin_insert_email_template() {

        $postData = $this->request->getPost();

        $mails = model('MailModel');

        $mails->add_mail($postData);

        notice_success('Mail added successfully');

        return redirect()->to(admin_url().'email-templates');
    }

    public function admin_update_email_template() {
        $postData = $this->request->getPost();
        $template_id = $postData['template_id'];
        $mails = model('MailModel');

        $mails->update_mail($template_id,$postData);

        notice_success('Mail updated successfully');

        return redirect()->back();
    }

    public function admin_email_logs() {
        $this->data['page'] = "email-logs";

        $mails = model('MailModel');

        if(isset($_GET['toggle_logs'])) {
            $toggle = $mails->toggleLogs();
            if($toggle) {
                $msg = "Email log recording is started";
            }else {
                $msg = "Email log recording is stopped";
            }
            notice_success($msg);
            return redirect()->back();
        }

        $mails->logs_datatable();

        $this->data['content'] = 'admin/emails/logs';

        _render_page('/' . ADMIN . '/index', $this->data);
    }
}