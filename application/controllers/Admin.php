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

    public function approve_outbound($id, $status)
    {
        $this->require_role(array(1, 2));

        $result = $this->M_data->approve_outbound($id, $status, $this->current_user['id']);

        if ($result['success']) {
            $this->session->set_flashdata('action_success', $result['message']);
        } else {
            $this->session->set_flashdata('action_error', $result['message']);
        }

        redirect($this->resolve_dashboard_by_role($this->current_user['role_id']));
    }

    public function tambah_user()
    {
        $this->require_role(array(1));

        if ( ! $this->input->post()) {
            redirect('admin');
        }

        $payload = array(
            'name' => $this->input->post('name', TRUE),
            'email' => $this->input->post('email', TRUE),
            'password' => $this->input->post('password', TRUE),
            'role_id' => $this->input->post('role_id', TRUE),
        );

        $result = $this->M_data->create_user($payload);

        if ($result['success']) {
            $this->session->set_flashdata('action_success', $result['message']);
        } else {
            $this->session->set_flashdata('action_error', $result['message']);
        }

        redirect('admin');
    }
}
