<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function __construct()
    {
        helper('url');
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        if(!session()->get('email_pengguna')) {//kalo tidak punya akun username maka akan langsung redirect to login
            session()->set('url_kendaraan', current_url());
            return redirect()->to('');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
