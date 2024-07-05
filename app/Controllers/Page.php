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
        $admin_mail = get_setting('website.admin_email');

        if(!empty($this->request->getPost('submit'))) {
            $validation =  \Config\Services::validation();

            $data = $this->request->getPost();

            $validation->setRules([
                'name' => [
                    'rules'=>'required|alpha_space|min_length[3]',
                    'errors' => [
                        'alpha_space'=>'Please enter alphabetical characters only.'
                    ]
                ],
                'number' => [
                    'rules' => 'permit_empty|min_length[9]|max_length[12]',
                    'errors' => [
                        'min_length'=>'Please enter a valid phone number.',
                        'max_length'=>'Please enter a valid phone number.'
                    ]
                ],
                'email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'valid_email'=>'Please enter valid email address.'
                    ]
                ]
            ]);

            $validation->run($data);

            if (!empty($validation->getErrors())) {
                $errors = $validation->getErrors();
                session()->setFlashdata('form_errors', $errors);

                return redirect()->to(base_url('contact-us#contact'))->withInput();
            }else {
                $name = esc(strip_tags($data['name']));
                $company = esc(strip_tags($data['company-name']));
                $number = esc(strip_tags($data['number']));
                $email = esc(strip_tags($data['email']));
                $message = esc(strip_tags($data['message']));

                $mail = new MailModel();

                $mailbody = '
                <h3>Contact Form Message</h3>
                
                <div style="margin-top: 15px; padding-bottom: 20px">
                    <table width="100%" border="0" cellspacing="5" cellpadding="5" style="width: 550px">
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
                    </table>
                </div>';

                if($mail->send_email($admin_mail,$mailbody,['subject'=>'Contact Form - Hot Numbers'])) {
                    notice_success('Request sent successfully. Our representative will contact you shortly.','message');
                }else {
                    notice_success('Could not submit your request.','message');
                };

                return redirect()->to(base_url('contact-us#contact'));
            }
        }

        return view('pages/contact_us',[
            'form_error' => session()->get('form_errors')
        ]);
    }

    ///become wholesale customer
    public function become_wholesale_customer()
    {
        return view('pages/wholesale_customer', [
            'form_error' => session()->get('form_errors')
        ]);
    }

    public function workwithus()
    {
        $this->data['header_scripts'] = '<link href="'.asset('style/select2-front.css').'" rel="stylesheet">';
        $this->data['footer_scripts'] = '<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>';

        $this->data['form_error'] = session()->get('form_errors');

        return view('pages/workwithus',$this->data);
    }

    public function recruitment_post() {
        $post = $this->request->getPost();
        $mailModel = model('MailModel');

        if(!empty($post)) {
            $validation =  \Config\Services::validation();

            $validation->setRules([
                'your_name' => [
                    'rules' => 'required|alpha_space|min_length[3]',
                    'errors' => [
                        'required' => 'Your name is required',
                        'alpha_space'=>'Please enter alphabetical characters only.'
                    ]
                ],
                'your_email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Your email address is required',
                        'valid_email'=>'Please enter valid email address.'
                    ]
                ],
                'position' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'=>'Please select a position'
                    ]
                ],
                'location' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'=>'Please select your location'
                    ]
                ],
                'availability' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'=>'Please select your availability'
                    ]
                ],
                'your_message' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'=>'Your message is required'
                    ]
                ],
                'cv_document' => [
                    'rules' => 'uploaded[cv_document]|ext_in[cv_document,doc,docx,pdf]|mime_in[cv_document,application/msword,application/rtf,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document]|max_size[cv_document,5000]',
                    'errors' => [
                        'uploaded'=>'Your CV document is required',
                        'ext_in'=>'Please upload a valid file',
                        'mime_in'=>'Please upload a valid file',
                        'max_size'=>'File size is too large'
                    ]
                ]
            ]);

            $validation->run($post);

            if (!empty($validation->getErrors()) || !empty($form_errs)) {
                $errors = $validation->getErrors();
                session()->setFlashdata('form_errors', $errors);
                return redirect()->to(base_url('work-with-us#contact-form'))->withInput();
            }

            $mail_body = ' <div style="display:block;"><p>A new recruitment request has been received with following details</p>';

            $mail_body .= '
           
                <table border="0" style="text-align: left" cellspacing="5" cellpadding="5">
                        <tr>
                            <th style="text-align: left">Full name</th>
                            <td>'.$post['your_name'].'</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Email address</th>
                            <td>'.$post['your_email'].'</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Position applied for</th>
                            <td>'.$post['position'].'</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Location</th>
                            <td>'.$post['location'].'</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Availability</th>
                            <td>'.$post['availability'].'</td>
                        </tr>
                    </table>
                   
                    <br>

                <h4 style="margin-top: 0; margin-bottom: 0">Message:</h4>
                    <p>'.$post['your_message'].'</p>
                <br>
            </div>
            ';

            $recruitment_email = get_setting('website.recruitment_email');
            $mailModel->subject("Career form submission");
            $document = $this->request->getFile('cv_document');
            $ext = $document->getExtension();
            $filename = 'cv-'.$post['your_name'].'-'.rand().'.'.$ext;
            $document->move(WRITEPATH.'uploads/cv',$filename,true);
            $mailModel->attach(WRITEPATH.'uploads/cv/'.$filename);

            $send = $mailModel->send_email($recruitment_email, $mail_body);

            if($send) {
                notice_success('Your application has been submitted. Thank you for your interest in joining our team.','message');
            }
            else {
                notice_success('Something went wrong during submission. Please try again','message');
            }
            return redirect()->to('work-with-us#contact-form');
        }
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
