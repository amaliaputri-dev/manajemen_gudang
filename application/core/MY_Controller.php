<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $current_user = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url'));
        $this->load->library('session');
        $this->load->model('M_data');

        $this->current_user = array(
            'id' => $this->session->userdata('id'),
            'email' => $this->session->userdata('email'),
            'name' => $this->session->userdata('name'),
            'role_id' => (int) $this->session->userdata('role_id'),
        );
    }

    protected function require_login()
    {
        if (empty($this->current_user['email'])) {
            $this->session->set_flashdata('message', 'Silakan login terlebih dahulu.');
            redirect('auth_gudang');
        }
    }

    protected function require_role($roles = array())
    {
        $this->require_login();

        if ( ! in_array($this->current_user['role_id'], $roles, TRUE)) {
            $this->session->set_flashdata('message', 'Anda tidak memiliki akses ke halaman tersebut.');
            redirect($this->resolve_dashboard_by_role($this->current_user['role_id']));
        }
    }

    protected function resolve_dashboard_by_role($role_id)
    {
        $routes = array(
            1 => 'admin',
            2 => 'supervisor',
            3 => 'gudang',
            4 => 'kurir',
        );

        return isset($routes[$role_id]) ? $routes[$role_id] : 'auth_gudang';
    }

    protected function resolve_role_label($role_id)
    {
        $labels = array(
            1 => 'Admin',
            2 => 'Supervisor',
            3 => 'Gudang',
            4 => 'Kurir',
        );

        return isset($labels[$role_id]) ? $labels[$role_id] : 'Pengguna';
    }

    protected function resolve_role_slug($role_id)
    {
        $slugs = array(
            1 => 'admin',
            2 => 'supervisor',
            3 => 'gudang',
            4 => 'kurir',
        );

        return isset($slugs[$role_id]) ? $slugs[$role_id] : 'guest';
    }

    protected function build_dashboard_data($title, $subtitle, $options = array())
    {
        $query = trim((string) $this->input->get('q', TRUE));
        $stok_filter = trim((string) $this->input->get('stok', TRUE));
        $kurir_keyword = trim((string) $this->input->get('kurir', TRUE));

        return array_merge(
            array(
                'page_title' => $title,
                'page_subtitle' => $subtitle,
                'current_user' => $this->current_user,
                'role_label' => $this->resolve_role_label($this->current_user['role_id']),
                'role_slug' => $this->resolve_role_slug($this->current_user['role_id']),
                'stats' => $this->M_data->get_dashboard_stats(),
                'flow_summary' => $this->M_data->get_flow_summary(),
                'barang' => $this->M_data->get_barang(array(
                    'keyword' => $query,
                    'stok_filter' => $stok_filter,
                )),
                'product_options' => $this->M_data->get_product_options(),
                'kurir' => $this->M_data->get_kurir($kurir_keyword),
                'low_stock_items' => $this->M_data->get_low_stock_items(),
                'user_summary' => $this->M_data->get_user_summary(),
                'recent_inbound' => $this->M_data->get_recent_inbound(),
                'recent_outbound' => $this->M_data->get_recent_outbound(),
                'outbound_options' => $this->M_data->get_outbound_options(),
                'available_outbound_for_delivery' => $this->M_data->get_available_outbound_for_delivery(),
                'recent_deliveries' => $this->M_data->get_recent_deliveries(),
                'delivery_options' => $this->M_data->get_delivery_options(),
                'kurir_activity' => $this->M_data->get_kurir_activity(),
                'my_delivery_summary' => $this->M_data->get_my_delivery_summary($this->current_user['id']),
                'my_deliveries' => $this->M_data->get_recent_deliveries(8, $this->current_user['id']),
                'my_delivery_options' => $this->M_data->get_my_delivery_options($this->current_user['id']),
                'filters' => array(
                    'q' => $query,
                    'stok' => $stok_filter,
                    'kurir' => $kurir_keyword,
                ),
                'database_ready' => $this->M_data->is_database_ready(),
            ),
            $options
        );
    }
}
