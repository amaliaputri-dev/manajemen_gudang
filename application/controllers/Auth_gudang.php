<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_gudang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url'));
        $this->load->library('session');
        $this->load->model('Auth_model');
    }

    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('auth_gudang/pilihan_dashboard');
        }

        $this->load->view('login_view');
    }

    public function proses_login()
    {
        $email = trim((string) $this->input->post('email', TRUE));
        $password = (string) $this->input->post('password', TRUE);

        if ($email === '' || $password === '') {
            $this->session->set_flashdata('message', 'Email dan password wajib diisi.');
            redirect('auth_gudang');
        }

        $user = $this->Auth_model->login($email, $password);

        if ($user) {
            $this->session->set_userdata(array(
                'id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name'],
                'role_id' => $user['role_id'],
            ));

            $this->session->set_flashdata('success_msg', 'Berhasil Login!');
            redirect($this->resolve_dashboard_by_role((int) $user['role_id']));
        }

        $this->session->set_flashdata('message', 'Login gagal. Periksa kembali email dan password Anda.');
        redirect('auth_gudang');
    }

    public function pilihan_dashboard()
    {
        if ( ! $this->session->userdata('email')) {
            redirect('auth_gudang');
        }

        redirect($this->resolve_dashboard_by_role((int) $this->session->userdata('role_id')));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth_gudang');
    }

    private function resolve_dashboard_by_role($role_id)
    {
        $routes = array(
            1 => 'admin',
            2 => 'supervisor',
            3 => 'gudang',
            4 => 'kurir',
        );

        return isset($routes[$role_id]) ? $routes[$role_id] : 'auth_gudang';
    }
}
