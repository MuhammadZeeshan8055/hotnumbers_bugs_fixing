<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('functions_helper');
        $auth = get_session('admin_in');
        if(empty($auth['id'])) {
            return redirect()->to(site_url('admin/login'));
        }


    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

        //die("5");
        // Do something here
    }
}
