<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller
{
    public function index()
    {
        $this->require_role(array(1));

        $data = $this->build_dashboard_data(
            'Dashboard Admin',
            'Pusat kontrol untuk mengawasi user, approval, stok, dan kesehatan operasi gudang secara menyeluruh.'
        );

        $this->load->view('role_dashboard', $data);
    }
}
