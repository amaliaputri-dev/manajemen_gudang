<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= html_escape($page_title); ?> - Manajemen Gudang</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/role_dashboard.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url('assets/js/sweetalert_custom.js'); ?>"></script>
</head>
<body>
<?php
    $is_admin = $role_slug === 'admin';
    $is_supervisor = $role_slug === 'supervisor';
    $is_gudang = $role_slug === 'gudang';
    $is_kurir = $role_slug === 'kurir';
    $can_manage_delivery = $is_admin || $is_supervisor || $is_kurir;

    $status_class = function ($status) {
        $map = array(
            'approved' => 'badge-approved',
            'pending' => 'badge-pending',
            'rejected' => 'badge-rejected',
            'disiapkan' => 'badge-disiapkan',
            'dalam_pengiriman' => 'badge-dalam_pengiriman',
            'terkirim' => 'badge-terkirim',
            'gagal' => 'badge-gagal',
        );

        return isset($map[$status]) ? $map[$status] : 'badge-admin';
    };
?>
    <div class="layout">
        <div class="hero">
            <div class="topbar">
                <div class="brand">
                    <div class="eyebrow">Warehouse Command Center</div>
                    <h1><?= html_escape($page_title); ?></h1>
                    <p><?= html_escape($page_subtitle); ?></p>
                </div>

                <div class="userbox">
                    <strong><?= html_escape($current_user['name'] ? $current_user['name'] : 'Pengguna'); ?></strong>
                    <div><?= html_escape($current_user['email']); ?></div>
                    <div class="role-pill"><?= html_escape($role_label); ?></div>
                    <div class="hero-actions">
                        <a href="<?= current_url(); ?>" class="btn btn-secondary">Refresh</a>
                        <button class="btn btn-primary" onclick="konfirmasiLogout('<?= base_url('auth_gudang/logout'); ?>')">Logout</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <?php if ( ! $database_ready): ?>
                <div class="alert">
                    Koneksi database belum lengkap. Dashboard akan tampil maksimal setelah `application/config/database.php` tersambung ke database lokal Anda.
                </div>
            <?php endif; ?>

            <?php if ($is_admin): ?>
                <div class="stats">
                    <div class="stat-card">
                        <span>Total Pengguna</span>
                        <strong><?= (int) $user_summary['total_users']; ?></strong>
                        <small>Admin, supervisor, gudang, dan kurir aktif.</small>
                    </div>
                    <div class="stat-card">
                        <span>Produk Terdaftar</span>
                        <strong><?= (int) $stats['total_barang']; ?></strong>
                        <small>Master barang siap dipakai seluruh tim.</small>
                    </div>
                    <div class="stat-card">
                        <span>Approval Pending</span>
                        <strong><?= (int) $flow_summary['inbound_pending'] + (int) $flow_summary['outbound_pending']; ?></strong>
                        <small>Permintaan yang masih menunggu tindakan.</small>
                    </div>
                    <div class="stat-card">
                        <span>Pengiriman Aktif</span>
                        <strong><?= (int) $flow_summary['deliveries_ready'] + (int) $flow_summary['deliveries_on_road']; ?></strong>
                        <small>Order yang sedang disiapkan atau di jalan.</small>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="stack">
                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Komposisi Tim & Hak Akses</h2>
                                    <p>Admin fokus ke kontrol sistem, pemerataan role, dan kestabilan operasional lintas divisi.</p>
                                </div>
                            </div>
                            <div class="mini-grid">
                                <div class="mini-card"><span class="label">Admin</span><span class="value"><?= (int) $user_summary['admin']; ?></span></div>
                                <div class="mini-card"><span class="label">Supervisor</span><span class="value"><?= (int) $user_summary['supervisor']; ?></span></div>
                                <div class="mini-card"><span class="label">Staff Gudang</span><span class="value"><?= (int) $user_summary['gudang']; ?></span></div>
                                <div class="mini-card"><span class="label">Kurir</span><span class="value"><?= (int) $user_summary['kurir']; ?></span></div>
                            </div>
                        </div>

                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Aktivitas Barang Keluar Terbaru</h2>
                                    <p>Ringkasan permintaan distribusi yang paling baru dibuat oleh tim gudang.</p>
                                </div>
                            </div>
                            <?php if ( ! empty($recent_outbound)): ?>
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr><th>Produk</th><th>Qty</th><th>Tujuan</th><th>Status</th><th>Dibuat Oleh</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_outbound as $row): ?>
                                                <tr>
                                                    <td><?= html_escape($row->product_name); ?></td>
                                                    <td><?= (int) $row->quantity; ?></td>
                                                    <td><?= html_escape($row->destination); ?></td>
                                                    <td><span class="badge <?= $status_class($row->status); ?>"><?= html_escape($row->status); ?></span></td>
                                                    <td><?= html_escape($row->creator_name); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Belum ada data outbound terbaru.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="stack">
                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Input Pengiriman</h2>
                                    <p>Admin bisa membuat jadwal pengiriman baru sekaligus mengoreksi status delivery aktif bila ada eskalasi dari lapangan.</p>
                                </div>
                            </div>
                            <form method="post" action="<?= base_url('kurir/buat_pengiriman'); ?>" class="toolbar">
                                <select name="outbound_id" required>
                                    <option value="">Pilih outbound approved</option>
                                    <?php foreach ($available_outbound_for_delivery as $outbound): ?>
                                        <option value="<?= (int) $outbound->id; ?>">
                                            OUT-<?= (int) $outbound->id; ?> - <?= html_escape($outbound->product_name); ?> - <?= (int) $outbound->quantity; ?> item - <?= html_escape($outbound->destination); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="kurir_id" required>
                                    <option value="">Pilih kurir</option>
                                    <?php foreach ($kurir as $item): ?>
                                        <option value="<?= (int) $item->id; ?>"><?= html_escape($item->nama_kurir); ?> - <?= html_escape($item->kontak); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="date" name="delivery_date" required>
                                <input type="text" name="note" placeholder="Catatan awal pengiriman">
                                <button type="submit" class="btn btn-primary">Simpan Pengiriman</button>
                            </form>
                            <form method="post" action="<?= base_url('kurir/input_pengiriman'); ?>" class="toolbar">
                                <select name="delivery_id" required>
                                    <option value="">Pilih pengiriman aktif</option>
                                    <?php foreach ($delivery_options as $delivery): ?>
                                        <option value="<?= (int) $delivery->id; ?>">
                                            DLV-<?= (int) $delivery->id; ?> - <?= html_escape($delivery->destination); ?> - <?= html_escape($delivery->delivery_date); ?> - <?= html_escape($delivery->status); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="status" required>
                                    <option value="">Pilih status baru</option>
                                    <option value="disiapkan">Disiapkan</option>
                                    <option value="dalam_pengiriman">Dalam Pengiriman</option>
                                    <option value="terkirim">Terkirim</option>
                                    <option value="gagal">Gagal</option>
                                </select>
                                <input type="text" name="note" placeholder="Catatan pengiriman / penerima">
                                <button type="submit" class="btn btn-secondary">Update Status</button>
                            </form>
                        </div>

                        <div class="section-card">
                            <h2>Distribusi Status Pengiriman</h2>
                            <p>Panel ini membantu admin membaca bottleneck operasional secara cepat.</p>
                            <div class="summary-list">
                                <div class="summary-item"><span>Siap dikirim</span><strong><?= (int) $flow_summary['deliveries_ready']; ?></strong></div>
                                <div class="summary-item"><span>Dalam pengiriman</span><strong><?= (int) $flow_summary['deliveries_on_road']; ?></strong></div>
                                <div class="summary-item"><span>Selesai terkirim</span><strong><?= (int) $flow_summary['deliveries_done']; ?></strong></div>
                            </div>
                        </div>

                        <div class="section-card">
                            <h2>Produk Perlu Perhatian</h2>
                            <p>Daftar stok kritis yang berpotensi mengganggu operasional bila dibiarkan.</p>
                            <?php if ( ! empty($low_stock_items)): ?>
                                <div class="summary-list">
                                    <?php foreach ($low_stock_items as $item): ?>
                                        <div class="summary-item">
                                            <span><?= html_escape($item->nama_barang); ?> - <?= html_escape($item->sku); ?></span>
                                            <strong><?= (int) $item->stok; ?> <?= html_escape($item->unit); ?></strong>
                                            <small>Segera evaluasi restok atau pembelian.</small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Tidak ada produk kritis saat ini.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php elseif ($is_supervisor): ?>
                <div class="stats">
                    <div class="stat-card">
                        <span>Inbound Pending</span>
                        <strong><?= (int) $flow_summary['inbound_pending']; ?></strong>
                        <small>Barang masuk yang masih menunggu approval.</small>
                    </div>
                    <div class="stat-card">
                        <span>Outbound Pending</span>
                        <strong><?= (int) $flow_summary['outbound_pending']; ?></strong>
                        <small>Permintaan kirim yang perlu dikonfirmasi.</small>
                    </div>
                    <div class="stat-card">
                        <span>Pengiriman Berjalan</span>
                        <strong><?= (int) $flow_summary['deliveries_on_road']; ?></strong>
                        <small>Order yang sedang berada di lapangan.</small>
                    </div>
                    <div class="stat-card">
                        <span>Stok Menipis</span>
                        <strong><?= (int) $stats['barang_menipis']; ?></strong>
                        <small>Area yang perlu pengawasan stok hari ini.</small>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="stack">
                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Antrian Approval Inbound</h2>
                                    <p>Supervisor bertugas memastikan barang masuk diverifikasi sebelum memengaruhi ketersediaan stok.</p>
                                </div>
                            </div>
                            <?php if ( ! empty($recent_inbound)): ?>
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr><th>Produk</th><th>Qty</th><th>Status</th><th>Dibuat Oleh</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_inbound as $row): ?>
                                                <tr>
                                                    <td><?= html_escape($row->product_name); ?></td>
                                                    <td><?= (int) $row->quantity; ?></td>
                                                    <td><span class="badge <?= $status_class($row->status); ?>"><?= html_escape($row->status); ?></span></td>
                                                    <td><?= html_escape($row->creator_name); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Belum ada data inbound untuk diawasi.</div>
                            <?php endif; ?>
                        </div>

                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Pergerakan Pengiriman Terbaru</h2>
                                    <p>Supervisor memantau pengiriman yang siap jalan, sedang berjalan, atau sudah selesai.</p>
                                </div>
                            </div>
                            <?php if ( ! empty($recent_deliveries)): ?>
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr><th>Kurir</th><th>Tujuan</th><th>Tanggal</th><th>Status</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_deliveries as $row): ?>
                                                <tr>
                                                    <td><?= html_escape($row->kurir_name); ?></td>
                                                    <td><?= html_escape($row->destination); ?></td>
                                                    <td><?= html_escape($row->delivery_date); ?></td>
                                                    <td><span class="badge <?= $status_class($row->status); ?>"><?= html_escape($row->status); ?></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Belum ada data delivery terbaru.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="stack">
                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Input Pengiriman</h2>
                                    <p>Supervisor juga bisa membuka pengiriman baru dan membantu update status saat koordinasi lapangan berjalan cepat.</p>
                                </div>
                            </div>
                            <form method="post" action="<?= base_url('kurir/buat_pengiriman'); ?>" class="toolbar">
                                <select name="outbound_id" required>
                                    <option value="">Pilih outbound approved</option>
                                    <?php foreach ($available_outbound_for_delivery as $outbound): ?>
                                        <option value="<?= (int) $outbound->id; ?>">
                                            OUT-<?= (int) $outbound->id; ?> - <?= html_escape($outbound->product_name); ?> - <?= (int) $outbound->quantity; ?> item - <?= html_escape($outbound->destination); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="kurir_id" required>
                                    <option value="">Pilih kurir</option>
                                    <?php foreach ($kurir as $item): ?>
                                        <option value="<?= (int) $item->id; ?>"><?= html_escape($item->nama_kurir); ?> - <?= html_escape($item->kontak); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="date" name="delivery_date" required>
                                <input type="text" name="note" placeholder="Catatan awal pengiriman">
                                <button type="submit" class="btn btn-primary">Simpan Pengiriman</button>
                            </form>
                            <form method="post" action="<?= base_url('kurir/input_pengiriman'); ?>" class="toolbar">
                                <select name="delivery_id" required>
                                    <option value="">Pilih pengiriman aktif</option>
                                    <?php foreach ($delivery_options as $delivery): ?>
                                        <option value="<?= (int) $delivery->id; ?>">
                                            DLV-<?= (int) $delivery->id; ?> - <?= html_escape($delivery->destination); ?> - <?= html_escape($delivery->delivery_date); ?> - <?= html_escape($delivery->status); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="status" required>
                                    <option value="">Pilih status baru</option>
                                    <option value="disiapkan">Disiapkan</option>
                                    <option value="dalam_pengiriman">Dalam Pengiriman</option>
                                    <option value="terkirim">Terkirim</option>
                                    <option value="gagal">Gagal</option>
                                </select>
                                <input type="text" name="note" placeholder="Catatan pengiriman / penerima">
                                <button type="submit" class="btn btn-secondary">Update Status</button>
                            </form>
                        </div>

                        <div class="section-card">
                            <h2>Beban Kurir</h2>
                            <p>Siapa yang paling banyak menangani pengiriman saat ini.</p>
                            <?php if ( ! empty($kurir_activity)): ?>
                                <div class="summary-list">
                                    <?php foreach ($kurir_activity as $item): ?>
                                        <div class="summary-item">
                                            <span><?= html_escape($item->nama_kurir); ?></span>
                                            <strong><?= (int) $item->total_delivery; ?> delivery</strong>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Belum ada distribusi beban kurir.</div>
                            <?php endif; ?>
                        </div>

                        <div class="section-card">
                            <h2>Alert Stok Menipis</h2>
                            <p>Supervisor juga perlu melihat titik rawan stok sebelum approval berjalan terlalu cepat.</p>
                            <?php if ( ! empty($low_stock_items)): ?>
                                <div class="summary-list">
                                    <?php foreach ($low_stock_items as $item): ?>
                                        <div class="summary-item">
                                            <span><?= html_escape($item->nama_barang); ?></span>
                                            <strong><?= (int) $item->stok; ?> <?= html_escape($item->unit); ?></strong>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Belum ada stok kritis.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php elseif ($is_gudang): ?>
                <div class="stats">
                    <div class="stat-card">
                        <span>Total Stok</span>
                        <strong><?= (int) $stats['total_stok']; ?></strong>
                        <small>Akumulasi seluruh stok yang tercatat.</small>
                    </div>
                    <div class="stat-card">
                        <span>Produk Aktif</span>
                        <strong><?= (int) $stats['total_barang']; ?></strong>
                        <small>Jumlah item yang dipantau gudang.</small>
                    </div>
                    <div class="stat-card">
                        <span>Inbound Pending</span>
                        <strong><?= (int) $flow_summary['inbound_pending']; ?></strong>
                        <small>Barang masuk yang perlu ditindaklanjuti.</small>
                    </div>
                    <div class="stat-card">
                        <span>Outbound Pending</span>
                        <strong><?= (int) $flow_summary['outbound_pending']; ?></strong>
                        <small>Permintaan kirim yang perlu disiapkan.</small>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="stack">
                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Monitoring Stok Gudang</h2>
                                    <p>Ini area utama staff gudang: cek produk, stok aman, dan item yang perlu restok.</p>
                                </div>
                                <form method="get" class="toolbar">
                                    <input type="text" name="q" value="<?= html_escape($filters['q']); ?>" placeholder="Cari nama atau SKU barang...">
                                    <select name="stok">
                                        <option value="">Semua stok</option>
                                        <option value="menipis" <?= $filters['stok'] === 'menipis' ? 'selected' : ''; ?>>Stok menipis</option>
                                        <option value="aman" <?= $filters['stok'] === 'aman' ? 'selected' : ''; ?>>Stok aman</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </form>
                            </div>
                            <?php if ( ! empty($barang)): ?>
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr><th>Produk</th><th>SKU</th><th>Stok</th><th>Status</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($barang as $item): ?>
                                                <tr>
                                                    <td><?= html_escape($item->nama_barang); ?></td>
                                                    <td><?= html_escape($item->sku); ?></td>
                                                    <td><?= (int) $item->stok; ?> <?= html_escape($item->unit); ?></td>
                                                    <td>
                                                        <?php if ((int) $item->stok <= 10): ?>
                                                            <span class="badge badge-danger">Perlu restok</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-safe">Aman</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Tidak ada barang yang cocok dengan filter saat ini.</div>
                            <?php endif; ?>
                        </div>

                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Permintaan Barang Keluar</h2>
                                    <p>Staff gudang perlu menyiapkan barang berdasarkan permintaan outbound terbaru.</p>
                                </div>
                            </div>
                            <?php if ( ! empty($recent_outbound)): ?>
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr><th>Produk</th><th>Qty</th><th>Tujuan</th><th>Status</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_outbound as $row): ?>
                                                <tr>
                                                    <td><?= html_escape($row->product_name); ?></td>
                                                    <td><?= (int) $row->quantity; ?></td>
                                                    <td><?= html_escape($row->destination); ?></td>
                                                    <td><span class="badge <?= $status_class($row->status); ?>"><?= html_escape($row->status); ?></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Belum ada permintaan outbound terbaru.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="stack">
                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Input Stok Baru</h2>
                                    <p>Gunakan form ini saat barang fisik benar-benar masuk ke gudang agar stok sistem ikut bertambah.</p>
                                </div>
                            </div>
                            <form method="post" action="<?= base_url('gudang/input_stok'); ?>" class="toolbar">
                                <select name="product_id" required>
                                    <option value="">Pilih barang</option>
                                    <?php foreach ($product_options as $product): ?>
                                        <option value="<?= (int) $product->id; ?>">
                                            <?= html_escape($product->name); ?> (<?= html_escape($product->sku); ?>) - stok <?= (int) $product->stock; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" name="quantity" min="1" placeholder="Jumlah stok masuk" required>
                                <button type="submit" class="btn btn-primary">Simpan Stok</button>
                            </form>
                            <div class="soft-note">
                                Penambahan stok akan langsung menaikkan nilai `products.stock` dan mencatat histori masuk ke tabel `inbound`.
                            </div>
                        </div>

                        <div class="section-card">
                            <h2>Alert Restok</h2>
                            <p>Prioritas kerja gudang dimulai dari item yang stoknya paling rendah.</p>
                            <?php if ( ! empty($low_stock_items)): ?>
                                <div class="summary-list">
                                    <?php foreach ($low_stock_items as $item): ?>
                                        <div class="summary-item">
                                            <span><?= html_escape($item->nama_barang); ?></span>
                                            <strong><?= (int) $item->stok; ?> <?= html_escape($item->unit); ?></strong>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Seluruh stok masih aman.</div>
                            <?php endif; ?>
                        </div>

                        <div class="section-card">
                            <h2>Barang Masuk Terbaru</h2>
                            <p>Daftar inbound membantu gudang menyiapkan ruang, cek kuantitas, dan update stok fisik.</p>
                            <?php if ( ! empty($recent_inbound)): ?>
                                <div class="summary-list">
                                    <?php foreach ($recent_inbound as $row): ?>
                                        <div class="summary-item">
                                            <span><?= html_escape($row->product_name); ?> - <?= html_escape($row->creator_name); ?></span>
                                            <strong><?= (int) $row->quantity; ?> item</strong>
                                            <small>Status: <?= html_escape($row->status); ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Belum ada inbound terbaru.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="stats">
                    <div class="stat-card">
                        <span>Tugas Saya</span>
                        <strong><?= (int) $my_delivery_summary['assigned']; ?></strong>
                        <small>Total delivery yang terdaftar atas nama Anda.</small>
                    </div>
                    <div class="stat-card">
                        <span>Siap Dikirim</span>
                        <strong><?= (int) $my_delivery_summary['ready']; ?></strong>
                        <small>Order yang menunggu Anda ambil.</small>
                    </div>
                    <div class="stat-card">
                        <span>Di Jalan</span>
                        <strong><?= (int) $my_delivery_summary['on_road']; ?></strong>
                        <small>Pengiriman yang sedang berjalan.</small>
                    </div>
                    <div class="stat-card">
                        <span>Selesai</span>
                        <strong><?= (int) $my_delivery_summary['done']; ?></strong>
                        <small>Tugas yang sudah berhasil Anda selesaikan.</small>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="stack">
                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Buat Pengiriman Baru</h2>
                                    <p>Kurir bisa langsung mengambil outbound yang sudah disetujui lalu menjadikannya tugas pengiriman baru dari dashboard ini.</p>
                                </div>
                            </div>
                            <form method="post" action="<?= base_url('kurir/buat_pengiriman'); ?>" class="toolbar">
                                <select name="outbound_id" required>
                                    <option value="">Pilih outbound approved</option>
                                    <?php foreach ($available_outbound_for_delivery as $outbound): ?>
                                        <option value="<?= (int) $outbound->id; ?>">
                                            OUT-<?= (int) $outbound->id; ?> - <?= html_escape($outbound->product_name); ?> - <?= (int) $outbound->quantity; ?> item - <?= html_escape($outbound->destination); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="date" name="delivery_date" required>
                                <input type="text" name="note" placeholder="Catatan awal pengiriman">
                                <button type="submit" class="btn btn-primary">Simpan Pengiriman</button>
                            </form>
                            <div class="soft-note">
                                Tujuan pengiriman akan mengikuti data tujuan di outbound yang dipilih, dan status awal otomatis `disiapkan`.
                            </div>
                            <?php if (empty($available_outbound_for_delivery)): ?>
                                <div class="empty-state">Belum ada outbound approved yang siap dijadikan pengiriman baru.</div>
                            <?php endif; ?>
                        </div>

                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Input Pengiriman Saya</h2>
                                    <p>Kurir mengisi progres pengiriman sendiri dari sini: mulai jalan, berhasil terkirim, atau gagal kirim beserta catatannya.</p>
                                </div>
                            </div>
                            <form method="post" action="<?= base_url('kurir/input_pengiriman'); ?>" class="toolbar">
                                <select name="delivery_id" required>
                                    <option value="">Pilih pengiriman aktif</option>
                                    <?php foreach ($my_delivery_options as $delivery): ?>
                                        <option value="<?= (int) $delivery->id; ?>">
                                            DLV-<?= (int) $delivery->id; ?> - <?= html_escape($delivery->destination); ?> - <?= html_escape($delivery->delivery_date); ?> - <?= html_escape($delivery->status); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="status" required>
                                    <option value="">Pilih status baru</option>
                                    <option value="disiapkan">Disiapkan</option>
                                    <option value="dalam_pengiriman">Dalam Pengiriman</option>
                                    <option value="terkirim">Terkirim</option>
                                    <option value="gagal">Gagal</option>
                                </select>
                                <input type="text" name="note" placeholder="Catatan pengiriman / penerima">
                                <button type="submit" class="btn btn-primary">Update Pengiriman</button>
                            </form>
                            <div class="soft-note">
                                Status akan diperbarui langsung di tabel `deliveries` untuk pengiriman yang memang terhubung ke akun kurir ini.
                            </div>
                        </div>

                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Daftar Tugas Pengiriman Saya</h2>
                                    <p>Kurir tidak perlu lihat semua data admin. Fokus utamanya adalah tujuan, status, dan catatan pengiriman milik sendiri.</p>
                                </div>
                            </div>
                            <?php if ( ! empty($my_deliveries)): ?>
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr><th>Tujuan</th><th>Tanggal</th><th>Status</th><th>Catatan</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($my_deliveries as $row): ?>
                                                <tr>
                                                    <td><?= html_escape($row->destination); ?></td>
                                                    <td><?= html_escape($row->delivery_date); ?></td>
                                                    <td><span class="badge <?= $status_class($row->status); ?>"><?= html_escape($row->status); ?></span></td>
                                                    <td><?= html_escape($row->note ? $row->note : '-'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Belum ada tugas pengiriman yang terhubung ke akun Anda.</div>
                            <?php endif; ?>
                        </div>

                        <div class="section-card">
                            <div class="section-head">
                                <div>
                                    <h2>Pengiriman Tim Kurir</h2>
                                    <p>Sebagai referensi lapangan, Anda masih bisa melihat ritme pengiriman tim tanpa membuka panel admin.</p>
                                </div>
                            </div>
                            <?php if ( ! empty($recent_deliveries)): ?>
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr><th>Kurir</th><th>Tujuan</th><th>Status</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_deliveries as $row): ?>
                                                <tr>
                                                    <td><?= html_escape($row->kurir_name); ?></td>
                                                    <td><?= html_escape($row->destination); ?></td>
                                                    <td><span class="badge <?= $status_class($row->status); ?>"><?= html_escape($row->status); ?></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Belum ada aktivitas delivery tim.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="stack">
                        <div class="section-card">
                            <h2>Status Kerja Hari Ini</h2>
                            <p>Panel cepat untuk membaca posisi kerja Anda sebelum berangkat atau saat update status.</p>
                            <div class="summary-list">
                                <div class="summary-item"><span>Siap diproses</span><strong><?= (int) $my_delivery_summary['ready']; ?></strong></div>
                                <div class="summary-item"><span>Dalam pengiriman</span><strong><?= (int) $my_delivery_summary['on_road']; ?></strong></div>
                                <div class="summary-item"><span>Selesai terkirim</span><strong><?= (int) $my_delivery_summary['done']; ?></strong></div>
                            </div>
                            <div class="soft-note">
                                Fokus utama role kurir adalah ketepatan pengiriman, status barang di lapangan, dan catatan penerimaan.
                            </div>
                        </div>

                        <div class="section-card">
                            <h2>Produk Prioritas Tinggi</h2>
                            <p>Informasi ini membantu kurir mengenali item sensitif atau item yang sedang menjadi prioritas distribusi.</p>
                            <?php if ( ! empty($low_stock_items)): ?>
                                <div class="summary-list">
                                    <?php foreach ($low_stock_items as $item): ?>
                                        <div class="summary-item">
                                            <span><?= html_escape($item->nama_barang); ?></span>
                                            <strong><?= (int) $item->stok; ?> <?= html_escape($item->unit); ?></strong>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">Belum ada item prioritas tinggi yang perlu dicatat.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($this->session->flashdata('success_msg')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil',
                text: 'Selamat datang kembali.',
                timer: 1800,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>

    <?php if ($this->session->flashdata('action_success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: <?= json_encode($this->session->flashdata('action_success')); ?>,
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>

    <?php if ($this->session->flashdata('action_error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: <?= json_encode($this->session->flashdata('action_error')); ?>,
                confirmButtonText: 'Oke'
            });
        </script>
    <?php endif; ?>
</body>
</html>
