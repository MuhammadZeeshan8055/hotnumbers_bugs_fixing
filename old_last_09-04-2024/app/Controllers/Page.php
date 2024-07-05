<?php

namespace App\Controllers;
use App\Models\MailModel;
use App\Models\Req_wholesales_acc;
use CodeIgniter\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;


class Page extends MyController
{

    protected $uri;
    protected $wholesale_req;

    public function __construct()
    {
        parent::__construct();
        $this->uri = current_url(true);
    }

    public function index($slug='')
    {
        $segments = $this->uri->getSegments();
        $slug = end($segments);
        $getPage = $this->master->getRow('tbl_pages',['page_slug'=>$slug]);
        if($getPage) {
            $this->data['page'] = $getPage;
            $this->data['productModel'] = model('productsModel');
            $this->data['media'] = model('Media');
            return view('pages/index', $this->data);
        }
        else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function roastery()
    {
        return view('pages/the_roastery');
    }

    public function trumpington()
    {
        return view('pages/trumpington_st');
    }

    public function about_us_cafe()
    {
        return view('pages/about_us_cafe');
    }

    public function gwydir()
    {
        return view('pages/gwydir');
    }

    public function gigs_events()
    {
        return view('pages/gigs_events');
    }

    public function hotnumbers_menu()
    {
        return view('pages/hotnumbers-menu');
    }

    public function contact_us()
    {
        if(!empty($this->request->getPost('submit'))) {
            $data = $this->request->getPost();
            $name = $data['your-name'];
            $company = $data['company-name'];
            $number = $data['your-number'];
            $email = $data['your-email'];
            $message = $data['your-message'];

            $mail = new MailModel();

            $mailbody = '
                <h3>Hotnumbers Contact Request</h3>
                <hr>
                <table width="100%" border="0" cellspacing="5" cellpadding="5">
                    <tr>
                        <th style="text-align: left">Name</th>
                        <td style="text-align: left">'.$name.'</td>
                    </tr>
                    <tr>
                        <th style="text-align: left">Company</th>
                        <td style="text-align: left">'.$company.'</td>
                    </tr>
                    <tr>
                        <th style="text-align: left">Number</th>
                        <td style="text-align: left">'.$number.'</td>
                    </tr>
                    <tr>
                        <th style="text-align: left">Email</th>
                        <td style="text-align: left">'.$email.'</td>
                    </tr>
                    <tr>
                        <th style="text-align: left">Message</th>
                        <td style="text-align: left">'.$message.'</td>
                    </tr>
                </table>';

            $admin_mail = 'roastery@hotnumberscoffee.co.uk';
            $admin_mail = 'bilal.signumconcepts@gmail.com';

            if($mail->send_email($admin_mail,$mailbody)) {
                notice_success('Request sent successfully. Our representative will contact you shortly.');
            }else {
                notice_success('Could not submit your request.','error');
            };

            return redirect()->to(base_url('contact-us#message'));
        }
        notice_success('Request sent successfully. Our representative will contact you shortly.');
        return view('pages/contact_us');
    }

    ///become wholesale customer
    public function become_wholesale_customer()
    {
        return view('pages/wholesale_customer');
    }

    public function workwithus()
    {
        return view('pages/workwithus');
    }

    public function privacy_policy()
    {
        return view('pages/privacy_policy');
    }
    public function terms_conditions_retail()
    {
        return view('pages/terms_conditions_retail');
    }
    public function refunds_cancellations_retail()
    {
        return view('pages/refunds_cancellations_retail');
    }
    public function ordering_faqs_wholesale()
    {
        return view('pages/ordering_faqs_wholesale');
    }

}
