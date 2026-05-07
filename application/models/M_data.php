<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_data extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->boot_database();
    }

    public function is_database_ready()
    {
        return $this->db !== NULL && ! empty($this->db->database);
    }

    private function table_ready($table)
    {
        return $this->is_database_ready() && $this->db->table_exists($table);
    }

    private function tables_ready($tables)
    {
        foreach ($tables as $table) {
            if ( ! $this->table_ready($table)) {
                return FALSE;
            }
        }

        return TRUE;
    }

    private function boot_database()
    {
        require APPPATH . 'config/database.php';

        if (empty($db[$active_group]['database'])) {
            return NULL;
        }

        return $this->load->database($active_group, TRUE);
    }

    public function get_barang($filters = array())
    {
        if ( ! $this->table_ready('products')) {
            return array();
        }

        $keyword = isset($filters['keyword']) ? trim($filters['keyword']) : '';
        $stok_filter = isset($filters['stok_filter']) ? trim($filters['stok_filter']) : '';

        if ($keyword !== '') {
            $this->db->group_start()
                ->like('name', $keyword)
                ->or_like('sku', $keyword)
                ->group_end();
        }

        if ($stok_filter === 'menipis') {
            $this->db->where('stock <=', 10);
        } elseif ($stok_filter === 'aman') {
            $this->db->where('stock >', 10);
        }

        return $this->db
            ->select('id, name AS nama_barang, sku, stock AS stok, unit')
            ->order_by('stock', 'ASC')
            ->order_by('name', 'ASC')
            ->get('products')
            ->result();
    }

    public function get_product_options()
    {
        if ( ! $this->table_ready('products')) {
            return array();
        }

        return $this->db
            ->select('id, name, sku, stock, unit')
            ->order_by('name', 'ASC')
            ->get('products')
            ->result();
    }

    public function get_kurir($keyword = '')
    {
        if ( ! $this->table_ready('users')) {
            return array();
        }

        $this->db->where('role_id', 4);

        if ($keyword !== '') {
            $this->db->group_start()
                ->like('name', $keyword)
                ->or_like('email', $keyword)
                ->group_end();
        }

        return $this->db
            ->select('id, name AS nama_kurir, email AS kontak')
            ->order_by('name', 'ASC')
            ->get('users')
            ->result();
    }

    public function get_low_stock_items($limit = 5)
    {
        if ( ! $this->table_ready('products')) {
            return array();
        }

        return $this->db
            ->select('id, name AS nama_barang, sku, stock AS stok, unit')
            ->where('stock <=', 10)
            ->order_by('stock', 'ASC')
            ->limit($limit)
            ->get('products')
            ->result();
    }

    public function get_dashboard_stats()
    {
        $stats = array(
            'total_barang' => 0,
            'total_stok' => 0,
            'barang_menipis' => 0,
            'total_kurir' => 0,
        );

        if ($this->table_ready('products')) {
            $stats['total_barang'] = (int) $this->db->count_all('products');

            $stok = $this->db->select_sum('stock')->get('products')->row();
            $stats['total_stok'] = isset($stok->stock) ? (int) $stok->stock : 0;

            $stats['barang_menipis'] = (int) $this->db
                ->where('stock <=', 10)
                ->count_all_results('products');
        }

        if ($this->table_ready('users')) {
            $stats['total_kurir'] = (int) $this->db
                ->where('role_id', 4)
                ->count_all_results('users');
        }

        return $stats;
    }

    public function get_user_summary()
    {
        $summary = array(
            'total_users' => 0,
            'admin' => 0,
            'supervisor' => 0,
            'gudang' => 0,
            'kurir' => 0,
        );

        if ( ! $this->table_ready('users')) {
            return $summary;
        }

        $summary['total_users'] = (int) $this->db->count_all('users');

        $rows = $this->db
            ->select('role_id, COUNT(*) AS total', FALSE)
            ->group_by('role_id')
            ->get('users')
            ->result();

        foreach ($rows as $row) {
            if ((int) $row->role_id === 1) {
                $summary['admin'] = (int) $row->total;
            } elseif ((int) $row->role_id === 2) {
                $summary['supervisor'] = (int) $row->total;
            } elseif ((int) $row->role_id === 3) {
                $summary['gudang'] = (int) $row->total;
            } elseif ((int) $row->role_id === 4) {
                $summary['kurir'] = (int) $row->total;
            }
        }

        return $summary;
    }

    public function get_flow_summary()
    {
        $summary = array(
            'inbound_pending' => 0,
            'inbound_approved' => 0,
            'outbound_pending' => 0,
            'outbound_approved' => 0,
            'deliveries_ready' => 0,
            'deliveries_on_road' => 0,
            'deliveries_done' => 0,
        );

        if ($this->table_ready('inbound')) {
            $rows = $this->db
                ->select('status, COUNT(*) AS total', FALSE)
                ->group_by('status')
                ->get('inbound')
                ->result();

            foreach ($rows as $row) {
                if ($row->status === 'pending') {
                    $summary['inbound_pending'] = (int) $row->total;
                } elseif ($row->status === 'approved') {
                    $summary['inbound_approved'] = (int) $row->total;
                }
            }
        }

        if ($this->table_ready('outbound')) {
            $rows = $this->db
                ->select('status, COUNT(*) AS total', FALSE)
                ->group_by('status')
                ->get('outbound')
                ->result();

            foreach ($rows as $row) {
                if ($row->status === 'pending') {
                    $summary['outbound_pending'] = (int) $row->total;
                } elseif ($row->status === 'approved') {
                    $summary['outbound_approved'] = (int) $row->total;
                }
            }
        }

        if ($this->table_ready('deliveries')) {
            $rows = $this->db
                ->select('status, COUNT(*) AS total', FALSE)
                ->group_by('status')
                ->get('deliveries')
                ->result();

            foreach ($rows as $row) {
                if ($row->status === 'disiapkan') {
                    $summary['deliveries_ready'] = (int) $row->total;
                } elseif ($row->status === 'dalam_pengiriman') {
                    $summary['deliveries_on_road'] = (int) $row->total;
                } elseif ($row->status === 'terkirim') {
                    $summary['deliveries_done'] = (int) $row->total;
                }
            }
        }

        return $summary;
    }

    public function get_recent_inbound($limit = 6)
    {
        if ( ! $this->tables_ready(array('inbound', 'products', 'users'))) {
            return array();
        }

        return $this->db
            ->select('inbound.id, products.name AS product_name, inbound.quantity, inbound.status, users.name AS creator_name, inbound.created_at')
            ->from('inbound')
            ->join('products', 'products.id = inbound.product_id', 'left')
            ->join('users', 'users.id = inbound.created_by', 'left')
            ->order_by('inbound.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    public function get_recent_outbound($limit = 6)
    {
        if ( ! $this->tables_ready(array('outbound', 'products', 'users'))) {
            return array();
        }

        return $this->db
            ->select('outbound.id, products.name AS product_name, outbound.quantity, outbound.destination, outbound.status, users.name AS creator_name, outbound.created_at')
            ->from('outbound')
            ->join('products', 'products.id = outbound.product_id', 'left')
            ->join('users', 'users.id = outbound.created_by', 'left')
            ->order_by('outbound.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    public function get_outbound_options($approved_only = TRUE)
    {
        if ( ! $this->tables_ready(array('outbound', 'products'))) {
            return array();
        }

        $this->db
            ->select('outbound.id, outbound.quantity, outbound.destination, outbound.status, products.name AS product_name')
            ->from('outbound')
            ->join('products', 'products.id = outbound.product_id', 'left');

        if ($approved_only) {
            $this->db->where('outbound.status', 'approved');
        }

        return $this->db
            ->order_by('outbound.id', 'DESC')
            ->get()
            ->result();
    }

    public function get_available_outbound_for_delivery()
    {
        if ( ! $this->tables_ready(array('outbound', 'products', 'deliveries'))) {
            return array();
        }

        return $this->db
            ->select('outbound.id, outbound.quantity, outbound.destination, products.name AS product_name')
            ->from('outbound')
            ->join('products', 'products.id = outbound.product_id', 'left')
            ->where('outbound.status', 'approved')
            ->where('NOT EXISTS (
                SELECT 1
                FROM deliveries
                WHERE deliveries.outbound_id = outbound.id
                AND deliveries.status IN ("disiapkan", "dalam_pengiriman")
            )', NULL, FALSE)
            ->order_by('outbound.id', 'DESC')
            ->get()
            ->result();
    }

    public function get_delivery_options($kurir_id = NULL)
    {
        if ( ! $this->table_ready('deliveries')) {
            return array();
        }

        $this->db
            ->select('id, destination, delivery_date, status, kurir_id')
            ->from('deliveries')
            ->where_in('status', array('disiapkan', 'dalam_pengiriman'));

        if ($kurir_id !== NULL) {
            $this->db->where('kurir_id', (int) $kurir_id);
        }

        return $this->db
            ->order_by('delivery_date', 'ASC')
            ->order_by('id', 'DESC')
            ->get()
            ->result();
    }

    public function get_recent_deliveries($limit = 6, $kurir_id = NULL)
    {
        if ( ! $this->tables_ready(array('deliveries', 'outbound', 'users'))) {
            return array();
        }

        $this->db
            ->select('deliveries.id, deliveries.destination, deliveries.delivery_date, deliveries.status, deliveries.note, kurir.name AS kurir_name, outbound.quantity')
            ->from('deliveries')
            ->join('outbound', 'outbound.id = deliveries.outbound_id', 'left')
            ->join('users AS kurir', 'kurir.id = deliveries.kurir_id', 'left');

        if ($kurir_id !== NULL) {
            $this->db->where('deliveries.kurir_id', (int) $kurir_id);
        }

        return $this->db
            ->order_by('deliveries.delivery_date', 'DESC')
            ->order_by('deliveries.id', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    public function get_kurir_activity($limit = 5)
    {
        if ( ! $this->tables_ready(array('deliveries', 'users'))) {
            return array();
        }

        return $this->db
            ->select('users.name AS nama_kurir, COUNT(deliveries.id) AS total_delivery', FALSE)
            ->from('users')
            ->join('deliveries', 'deliveries.kurir_id = users.id', 'left')
            ->where('users.role_id', 4)
            ->group_by('users.id')
            ->order_by('total_delivery', 'DESC')
            ->order_by('users.name', 'ASC')
            ->limit($limit)
            ->get()
            ->result();
    }

    public function get_my_delivery_summary($kurir_id)
    {
        $summary = array(
            'assigned' => 0,
            'ready' => 0,
            'on_road' => 0,
            'done' => 0,
        );

        if ( ! $this->table_ready('deliveries')) {
            return $summary;
        }

        $summary['assigned'] = (int) $this->db
            ->where('kurir_id', (int) $kurir_id)
            ->count_all_results('deliveries');

        $rows = $this->db
            ->select('status, COUNT(*) AS total', FALSE)
            ->where('kurir_id', (int) $kurir_id)
            ->group_by('status')
            ->get('deliveries')
            ->result();

        foreach ($rows as $row) {
            if ($row->status === 'disiapkan') {
                $summary['ready'] = (int) $row->total;
            } elseif ($row->status === 'dalam_pengiriman') {
                $summary['on_road'] = (int) $row->total;
            } elseif ($row->status === 'terkirim') {
                $summary['done'] = (int) $row->total;
            }
        }

        return $summary;
    }

    public function get_my_delivery_options($kurir_id)
    {
        return $this->get_delivery_options($kurir_id);
    }

    public function add_stock($product_id, $quantity, $user_id)
    {
        if ( ! $this->tables_ready(array('products', 'inbound', 'users'))) {
            return array(
                'success' => FALSE,
                'message' => 'Tabel stok belum siap dipakai.',
            );
        }

        $product = $this->db
            ->select('id, name, stock')
            ->where('id', (int) $product_id)
            ->get('products')
            ->row();

        if ( ! $product) {
            return array(
                'success' => FALSE,
                'message' => 'Barang yang dipilih tidak ditemukan.',
            );
        }

        $quantity = (int) $quantity;

        if ($quantity <= 0) {
            return array(
                'success' => FALSE,
                'message' => 'Jumlah stok harus lebih dari nol.',
            );
        }

        $this->db->trans_start();

        $this->db
            ->set('stock', 'stock + '.$quantity, FALSE)
            ->where('id', (int) $product_id)
            ->update('products');

        $this->db->insert('inbound', array(
            'supplier_id' => NULL,
            'product_id' => (int) $product_id,
            'quantity' => $quantity,
            'created_by' => (int) $user_id,
            'status' => 'approved',
            'approved_by' => (int) $user_id,
            'approved_at' => date('Y-m-d H:i:s'),
        ));

        $this->db->trans_complete();

        if ( ! $this->db->trans_status()) {
            return array(
                'success' => FALSE,
                'message' => 'Gagal menyimpan penambahan stok.',
            );
        }

        return array(
            'success' => TRUE,
            'message' => 'Stok untuk '.$product->name.' berhasil ditambah '.$quantity.' item.',
        );
    }

    public function create_delivery($payload)
    {
        if ( ! $this->tables_ready(array('deliveries', 'outbound', 'users'))) {
            return array(
                'success' => FALSE,
                'message' => 'Tabel pengiriman belum siap dipakai.',
            );
        }

        $outbound_id = (int) $payload['outbound_id'];
        $kurir_id = (int) $payload['kurir_id'];
        $delivery_date = trim((string) $payload['delivery_date']);
        $destination = trim((string) $payload['destination']);
        $note = trim((string) $payload['note']);

        $outbound = $this->db
            ->where('id', $outbound_id)
            ->get('outbound')
            ->row();

        if ( ! $outbound) {
            return array(
                'success' => FALSE,
                'message' => 'Data outbound yang dipilih tidak ditemukan.',
            );
        }

        if ($outbound->status !== 'approved') {
            return array(
                'success' => FALSE,
                'message' => 'Hanya outbound berstatus approved yang bisa dijadwalkan untuk pengiriman.',
            );
        }

        $kurir = $this->db
            ->where('id', $kurir_id)
            ->where('role_id', 4)
            ->get('users')
            ->row();

        if ( ! $kurir) {
            return array(
                'success' => FALSE,
                'message' => 'Kurir yang dipilih tidak valid.',
            );
        }

        if ($destination === '' && isset($outbound->destination)) {
            $destination = trim((string) $outbound->destination);
        }

        if ($delivery_date === '' || $destination === '') {
            return array(
                'success' => FALSE,
                'message' => 'Tanggal kirim dan tujuan wajib diisi.',
            );
        }

        $existing_delivery = $this->db
            ->where('outbound_id', $outbound_id)
            ->where_in('status', array('disiapkan', 'dalam_pengiriman'))
            ->count_all_results('deliveries');

        if ($existing_delivery > 0) {
            return array(
                'success' => FALSE,
                'message' => 'Outbound ini sudah punya pengiriman aktif. Selesaikan atau ubah statusnya dulu sebelum membuat yang baru.',
            );
        }

        $this->db->insert('deliveries', array(
            'outbound_id' => $outbound_id,
            'kurir_id' => $kurir_id,
            'destination' => $destination,
            'delivery_date' => $delivery_date,
            'status' => 'disiapkan',
            'note' => $note !== '' ? $note : NULL,
        ));

        if ($this->db->affected_rows() < 1) {
            return array(
                'success' => FALSE,
                'message' => 'Gagal menyimpan data pengiriman.',
            );
        }

        return array(
            'success' => TRUE,
            'message' => 'Pengiriman berhasil dibuat untuk kurir '.$kurir->name.'.',
        );
    }

    public function update_my_delivery($delivery_id, $kurir_id, $status, $note)
    {
        return $this->update_delivery($delivery_id, $status, $note, $kurir_id);
    }

    public function update_delivery($delivery_id, $status, $note, $kurir_id = NULL)
    {
        if ( ! $this->table_ready('deliveries')) {
            return array(
                'success' => FALSE,
                'message' => 'Tabel pengiriman belum siap dipakai.',
            );
        }

        $allowed_status = array('disiapkan', 'dalam_pengiriman', 'terkirim', 'gagal');

        if ( ! in_array($status, $allowed_status, TRUE)) {
            return array(
                'success' => FALSE,
                'message' => 'Status pengiriman tidak valid.',
            );
        }

        $delivery = $this->db
            ->where('id', (int) $delivery_id);

        if ($kurir_id !== NULL) {
            $delivery = $delivery->where('kurir_id', (int) $kurir_id);
        }

        $delivery = $delivery->get('deliveries')->row();

        if ( ! $delivery) {
            return array(
                'success' => FALSE,
                'message' => $kurir_id !== NULL
                    ? 'Pengiriman tidak ditemukan atau bukan milik akun ini.'
                    : 'Pengiriman tidak ditemukan.',
            );
        }

        $update = array('status' => $status);

        if (trim((string) $note) !== '') {
            $update['note'] = trim((string) $note);
        }

        $this->db
            ->where('id', (int) $delivery_id);

        if ($kurir_id !== NULL) {
            $this->db->where('kurir_id', (int) $kurir_id);
        }

        $this->db->update('deliveries', $update);

        return array(
            'success' => TRUE,
            'message' => 'Status pengiriman berhasil diperbarui menjadi '.$status.'.',
        );
    }
}
