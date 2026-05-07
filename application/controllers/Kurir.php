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

    public function input_pengiriman()
    {
        $this->require_role(array(1, 2, 4));

        if ( ! $this->input->post()) {
            redirect($this->resolve_dashboard_by_role($this->current_user['role_id']));
        }

        if ((int) $this->current_user['role_id'] === 4) {
            $result = $this->M_data->update_my_delivery(
                (int) $this->input->post('delivery_id', TRUE),
                (int) $this->current_user['id'],
                (string) $this->input->post('status', TRUE),
                (string) $this->input->post('note', TRUE)
            );
        } else {
            $result = $this->M_data->update_delivery(
                (int) $this->input->post('delivery_id', TRUE),
                (string) $this->input->post('status', TRUE),
                (string) $this->input->post('note', TRUE)
            );
        }

        if ($result['success']) {
            $this->session->set_flashdata('action_success', $result['message']);
        } else {
            $this->session->set_flashdata('action_error', $result['message']);
        }

        redirect($this->resolve_dashboard_by_role($this->current_user['role_id']));
    }

    public function buat_pengiriman()
    {
        $this->require_role(array(1, 2, 4));

        if ( ! $this->input->post()) {
            redirect($this->resolve_dashboard_by_role($this->current_user['role_id']));
        }

        $outbound_id = (int) $this->input->post('outbound_id', TRUE);
        $kurir_id = (int) $this->current_user['role_id'] === 4
            ? (int) $this->current_user['id']
            : (int) $this->input->post('kurir_id', TRUE);
        $delivery_date = trim((string) $this->input->post('delivery_date', TRUE));

        if ($outbound_id <= 0 || $kurir_id <= 0 || $delivery_date === '') {
            $this->session->set_flashdata('action_error', 'Pilih outbound, kurir, dan isi tanggal kirim dengan benar.');
            redirect($this->resolve_dashboard_by_role($this->current_user['role_id']));
        }

        $result = $this->M_data->create_delivery(array(
            'outbound_id' => $outbound_id,
            'kurir_id' => $kurir_id,
            'delivery_date' => $delivery_date,
            'destination' => '',
            'note' => (string) $this->input->post('note', TRUE),
        ));

        if ($result['success']) {
            $this->session->set_flashdata('action_success', $result['message']);
        } else {
            $this->session->set_flashdata('action_error', $result['message']);
        }

        redirect($this->resolve_dashboard_by_role($this->current_user['role_id']));
    }
}
