<?php

namespace App\Controllers;

class EmailController extends BaseController
{
    private $data;

    public function admin_list_email_templates() {
        $this->data['page'] = "emails";

        $mails = model('MailModel');
        $this->data['emails'] = $mails->get_mails("WHERE (keyname!='header' AND keyname!='footer') ORDER BY priority DESC")->getResultArray();

        if(!empty($_GET['delete'])) {
            pr($_GET['delete']);
        }

        $this->data['content'] = 'admin/emails/index';

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function admin_add_email_template() {
        $this->data['page'] = "add-email-template";
        $this->data['mode'] = "add";
        $mails = model('MailModel');
        $this->data['content'] = 'admin/emails/view';
        $this->data['shortcodes'] = $mails->get_mail_shortcodes();

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function admin_view_email_template($id) {
        $this->data['page'] = "emails";
        $this->data['mode'] = "edit";
        $mails = model('MailModel');

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

        return redirect()->back();
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

        $mails->logs_datatable();

        $this->data['content'] = 'admin/emails/logs';

        _render_page('/' . ADMIN . '/index', $this->data);
    }
}