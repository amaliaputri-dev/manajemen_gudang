<?php
class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('M_data');
    }

    public function index(){
        $data['jumlah_barang'] = count($this->M_data->get_barang());
        $data['jumlah_kurir'] = count($this->M_data->get_kurir());

        $this->load->view('template/header');
        $this->load->view('dashboard', $data);
        $this->load->view('template/footer');
    }
}