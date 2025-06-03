<?php
namespace App\Controllers\Admin;

date_default_timezone_set("Asia/Jakarta");

use App\Controllers\BaseController;
// use App\Models\TransaksiModel;

class Admin extends BaseController
{
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $session = \Config\Services::session();
        
        // $this->m_id = new TransaksiModel();

        $this->validation = \Config\Services::validation();
        helper("cookie");//remember password, password disimpan di cookie
        helper("global_fungsi_helper");//kirim email di bagian APP/Helper
        helper('url');
    }

    public function main_page()
    {
        $data = [];
        echo view("ui/v_header", $data);
        echo view("mainpage/v_main_page", $data);
        echo view("ui/v_footer", $data);
    }
}