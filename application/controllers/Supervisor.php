<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supervisor extends MY_Controller
{
    public function index()
    {
        $this->require_role(array(2));

        $data = $this->build_dashboard_data(
            'Dashboard Supervisor',
            'Area pengawasan approval, ritme pengiriman, dan titik rawan stok sebelum mengganggu operasional.'
        );

        $this->load->view('role_dashboard', $data);
    }
}
