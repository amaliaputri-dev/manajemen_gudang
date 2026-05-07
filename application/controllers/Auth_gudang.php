<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Nama class harus sama dengan nama file
class Auth_gudang extends CI_Controller { 
    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    public function index() {
        if($this->session->userdata('email')) {
            redirect('auth_gudang/pilihan_dashboard'); // Perubahan di sini
        }
        $this->load->view('login_view');
    }

   public function proses_login() {
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    
    $user = $this->Auth_model->login($email, $password);

    if ($user) {
        // PERHATIKAN PENULISAN ARRAY DI BAWAH INI:
        $data = [
            'id'      => $user['id'],
            'email'   => $user['email'],
            'name'    => $user['name'],
            'role_id' => $user['role_id']
        ];
        $this->session->set_userdata($data);

        $this->session->set_flashdata('success_msg', 'Berhasil Login!');

        // Lanjut ke pengecekan role_id...
        if ($user['role_id'] == 1) {
            redirect('admin');
        } elseif ($user['role_id'] == 2) {
            redirect('supervisor');
        } elseif ($user['role_id'] == 3) {
            redirect('gudang');
        } elseif ($user['role_id'] == 4) {
            redirect('kurir');
        }
    } else {
        $this->session->set_flashdata('message', 'Login Gagal!');
        redirect('auth_gudang');
    }
}

    public function pilihan_dashboard() {
        if(!$this->session->userdata('email')) { redirect('auth_gudang'); }
        $this->load->view('pilihan_view');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth_gudang'); // Perubahan di sini
    }
}