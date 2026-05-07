<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kurir extends MY_Controller
{
    public function index()
    {
        $this->require_role(array(4));

        $data = $this->build_dashboard_data(
            'Dashboard Kurir',
            'Panel lapangan untuk melihat tugas pengiriman, status perjalanan, dan catatan delivery yang harus dituntaskan.'
        );

        $this->load->view('role_dashboard', $data);
    }
}
