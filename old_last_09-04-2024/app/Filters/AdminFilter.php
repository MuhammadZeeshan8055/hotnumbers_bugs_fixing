<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        helper('functions_helper');

        if(!is_admin()) {
            return redirect()->to(site_url('admin/login'));
        }

    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

        //die("5");
        // Do something here
    }
}
