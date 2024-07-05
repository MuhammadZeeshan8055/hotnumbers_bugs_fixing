<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AccountFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        helper('functions_helper');
        $user = model('UserModel');
        if (!is_logged_in()) {
            return redirect()->to(site_url('login'));
        }

        if(is_logged_in() && !is_customer() && !is_admin()) {
            $user->logout();
            return redirect()->to(site_url('account'))->with('message','Account inactive');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
