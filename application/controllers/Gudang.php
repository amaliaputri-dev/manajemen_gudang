<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang extends MY_Controller
{
    public function index()
    {
        $this->require_role(array(3));

        $data = $this->build_dashboard_data(
            'Dashboard Staff Gudang',
            'Ruang kerja harian untuk mengecek stok, menangani barang masuk, dan menyiapkan permintaan keluar.'
        );

        $this->load->view('role_dashboard', $data);
    }

    public function input_stok()
    {
        $this->require_role(array(3));

        if ( ! $this->input->post()) {
            redirect('gudang');
        }

        $product_id = (int) $this->input->post('product_id', TRUE);
        $quantity = (int) $this->input->post('quantity', TRUE);

        if ($product_id <= 0 || $quantity <= 0) {
            $this->session->set_flashdata('action_error', 'Pilih barang dan isi jumlah stok dengan benar.');
            redirect('gudang');
        }

        $result = $this->M_data->add_stock($product_id, $quantity, $this->current_user['id']);

        if ($result['success']) {
            $this->session->set_flashdata('action_success', $result['message']);
        } else {
            $this->session->set_flashdata('action_error', $result['message']);
        }

        redirect('gudang');
    }

}
