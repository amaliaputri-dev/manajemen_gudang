<!DOCTYPE html>
<html>
<head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - AssetPro</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            /* Reset & Base */
            body { font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; background: #f4f7f6; min-height: 100vh; overflow-x: hidden; }

            .sidebar { 
                width: 240px; 
                background: #1a2a20; 
                color: white; 
                display: flex; 
                flex-direction: column; 
                padding: 20px 0;
                box-sizing: border-box;
                flex-shrink: 0;
                height: 100vh; 
                position: sticky;
                top: 0;
                transition: transform 0.3s ease;
                z-index: 1000;
            }

            .main-content { flex-grow: 1; padding: 40px; overflow-y: auto; width: 100%; }

            @media (max-width: 768px) {
                body { flex-direction: column; }
                .sidebar { 
                    width: 100%; 
                    height: auto; 
                    position: relative; 
                    padding: 10px 0;
                }
                .nav-menu { flex-direction: row; flex-wrap: wrap; justify-content: center; }
                .nav-item { padding: 10px; font-size: 12px; }
                .main-content { padding: 20px; }
                .stat-card { min-width: 100%; }
            }

            color: #ff6b6b;
            text-decoration: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            font-weight: bold;
            margin-top: auto; 
            cursor: pointer;
        }

        .brand { text-align: center; padding-bottom: 20px; }
        .brand h2 { color: #d4df3d; margin: 0; font-size: 22px; font-weight: bold; letter-spacing: 1px; }

        .nav-menu { flex-grow: 1; display: flex; flex-direction: column; gap: 5px; }
        .nav-item {
            padding: 12px 20px;
            text-decoration: none;
            color: #adb5bd;
            display: flex;
            align-items: center;
            font-size: 14px;
            transition: 0.2s;
            cursor: pointer;
        }
        .nav-item i { width: 25px; font-size: 16px; margin-right: 10px; }
        .nav-item.active {
            background: #d4df3d; 
            color: #1a2a20;
            font-weight: bold;
            border-radius: 10px;
            margin: 0 10px;
        }
        .nav-item:hover:not(.active) { color: white; background: rgba(255,255,255,0.05); }

        .main-content { flex-grow: 1; padding: 40px; overflow-y: auto; }
        .main-header h2 { margin-bottom: 30px; color: #2c3e50; font-weight: 600; }

        .stat-container { display: flex; gap: 20px; flex-wrap: wrap; }
        .stat-card { 
            background: white; 
            padding: 25px; 
            border-radius: 15px; 
            flex: 1; 
            min-width: 250px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card h4 { margin: 0; color: #7f8c8d; font-size: 14px; }
        .stat-card .value { font-size: 28px; font-weight: bold; color: #2c3e50; margin: 15px 0; }
        .stat-card .desc { font-size: 12px; color: #bdc3c7; }

        .page-content { display: none; }
        .page-content.active { display: block; }

        .swal2-container {
            display: flex !important;
            position: fixed !important;
            z-index: 300000 !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100vw !important;
            height: 100vh !important;
            top: 0 !important;
            left: 0 !important;
        }

        .swal2-popup {
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            width: 32em !important;
            max-width: 90% !important;
            padding: 20px !important;
            border-radius: 15px !important;
            background: #fff !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;
            font-family: 'Segoe UI', sans-serif !important;
        }

        @media (max-width: 480px) {
            table thead { display: none; }
            table tr { display: block; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 10px; padding: 10px; }
            table td { display: flex; justify-content: space-between; padding: 10px 5px; border-bottom: 1px dashed #eee; text-align: right; }
            table td:last-child { border-bottom: none; }
            table td::before { content: attr(data-label); font-weight: bold; text-align: left; color: #7f8c8d; }
            #inputCari { width: 100% !important; margin-bottom: 10px; }
            #filterJurusan { width: 100% !important; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-box" style="color: #d4df3d; font-size: 30px; margin-bottom: 10px;"></i>
            <h2>AssetPro</h2>
        </div>

        <div class="nav-menu">
            <a onclick="showPage('dashboard')" class="nav-item active">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a onclick="showPage('data-aset')" class="nav-item">
                <i class="fas fa-archive"></i> Data Aset
            </a>
            <a onclick="showPage('tambah-aset')" class="nav-item">
                <i class="fas fa-plus"></i> Tambah Aset
            </a>
            <a onclick="showPage('kategori')" class="nav-item">
                <i class="fas fa-layer-group"></i> Kategori Aset
            </a>
            <a onclick="showPage('kantor')" class="nav-item">
                <i class="fas fa-landmark"></i> Kantor
            </a>
            <a onclick="showPage('akun')" class="nav-item">
                <i class="fas fa-user-shield"></i> Manajemen Akun
            </a>
            <a onclick="showPage('maintenance')" class="nav-item">
                <i class="fas fa-tools"></i> Maintenance
            </a>
            <a onclick="showPage('mutasi')" class="nav-item">
                <i class="fas fa-sync-alt"></i> Mutasi
            </a>
            <a onclick="showPage('laporan')" class="nav-item">
                <i class="fas fa-file-pdf"></i> Laporan
            </a>
        </div>

        <!-- Perbaikan: href diubah ke javascript:void(0) dan ditambahkan ID btnLogout -->
        <a href="javascript:void(0);" id="btnLogout" class="nav-logout">
            <i class="fas fa-power-off"></i> Logout
        </a>
    </div>

    <div class="main-content">
        <div id="dashboard" class="page-content active">
            <div class="main-header">
                <h2>Dashboard Manajemen Aset</h2>
            </div>
            <div class="stat-container">
                <div class="stat-card">
                    <h4>Total Aset</h4>
                    <div class="value">3</div>
                    <div class="desc">Aset yang sedang digunakan</div>
                </div>
                <div class="stat-card">
                    <h4>Maintenance Aktif</h4>
                    <div class="value">2</div>
                    <div class="desc">Perlu perhatian</div>
                </div>
                <div class="stat-card">
                    <h4>Nilai Aset</h4>
                    <div class="value">Rp 100.000.000</div>
                    <div class="desc">Total estimasi</div>
                </div>
            </div>
        </div>

        <div id="data-aset" class="page-content">
            <div class="main-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 style="color: #2c3e50; font-weight: 600;">Data Mahasiswa</h2>
                <button onclick="aksiMahasiswa('Tambah', 'Mahasiswa Baru')" style="background: #4e73df; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">
                    <i class="fas fa-plus"></i> Tambah Mahasiswa
                </button>
            </div>

            <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <div style="display: flex; gap: 15px; margin-bottom: 25px; align-items: center;">
                    <input type="text" id="inputCari" onkeyup="cariMahasiswa()" placeholder="Cari Nama Mahasiswa..." style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; width: 280px; outline: none;">
                    <select id="filterJurusan" onchange="cariMahasiswa()" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; color: #7f8c8d; outline: none;">
                        <option value="">Jurusan</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Manajemen Informatika">Manajemen Informatika</option>
                        <option value="Teknik Komputer">Teknik Komputer</option>
                        <option value="komputerisasi akuntansi">komputerisasi akuntansi</option>
                    </select>
                </div>

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 2px solid #f4f7f6; color: #7f8c8d; font-size: 14px;">
                            <th style="padding: 15px;">NIM</th>
                            <th style="padding: 15px;">Nama Mahasiswa</th>
                            <th style="padding: 15px;">Jurusan</th>
                            <th style="padding: 15px;">Status</th>
                            <th style="padding: 15px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px; color: #2c3e50;">
                        <?php if(!empty($mahasiswa)) : ?>
                            <?php foreach($mahasiswa as $m) : ?>
                            <tr style="border-bottom: 1px solid #f4f7f6;">
                                <td data-label="NIM" style="padding: 15px;"><?= $m->nim; ?></td>
                                <td data-label="Nama" style="padding: 15px;"><strong><?= $m->nama; ?></strong></td>
                                <td data-label="Jurusan" style="padding: 15px;"><?= $m->jurusan; ?></td>
                                <td data-label="Status" style="padding: 15px;">
                                    <span style="background: #e1f7e3; color: #28a745; padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: bold;">Aktif</span>
                                </td>
                                <td data-label="Aksi" style="padding: 15px; display: flex; gap: 5px; align-items: center; min-width: 150px;">
                                    <button onclick="aksiMahasiswa('Edit', '<?= $m->nama; ?>', '<?= $m->nim; ?>', '<?= $m->jurusan; ?>', 'Aktif', this)" style="background: #f3ae12; color: white; border: none; width: 35px; height: 35px; border-radius: 5px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="hapusMahasiswa('<?= $m->nama; ?>', this)" 
                                           style="background: #e74c3c; color: white; border: none; width: 35px; height: 35px; border-radius: 5px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button onclick="aksiMahasiswa('Detail', '<?= $m->nama; ?>', '<?= $m->nim; ?>', '<?= $m->jurusan; ?>', 'Aktif')" style="background: #3498db; color: white; border: none; width: 35px; height: 35px; border-radius: 5px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px; color: #999;">Data mahasiswa belum ada di database bolo.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div style="margin-top: 25px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #7f8c8d;">
                    <span>Menampilkan <?= count($mahasiswa); ?> data</span>
                    <div>
                        <button style="padding: 6px 12px; border: 1px solid #ddd; background: white; border-radius: 5px; cursor: pointer; margin-right: 5px;">Prev</button>
                        <button style="padding: 6px 12px; border: 1px solid #ddd; background: #f4f7f6; border-radius: 5px; cursor: pointer;">Next</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="tambah-aset" class="page-content"><div class="main-header"><h2>Tambah Aset Baru</h2></div></div>
        <div id="kategori" class="page-content"><div class="main-header"><h2>Kategori Aset</h2></div></div>
        <div id="kantor" class="page-content"><div class="main-header"><h2>Kantor</h2></div></div>
        <div id="akun" class="page-content"><div class="main-header"><h2>Manajemen Akun</h2></div></div>
        <div id="maintenance" class="page-content"><div class="main-header"><h2>Maintenance</h2></div></div>
        <div id="mutasi" class="page-content"><div class="main-header"><h2>Mutasi</h2></div></div>
        <div id="laporan" class="page-content"><div class="main-header"><h2>Laporan</h2></div></div>
    </div>

    <!-- Modal Mahasiswa -->
    <div id="modalMahasiswa" style="display:none; position:fixed; z-index:400000; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); align-items:center; justify-content:center;">
        <div style="background:white; padding:30px; border-radius:15px; width:450px; box-shadow:0 10px 25px rgba(0,0,0,0.2);">
            <form action="<?= base_url('dashboard/simpan_mahasiswa'); ?>" method="POST">
                <h3 id="modalTitle" style="margin-top:0; color:#2c3e50;">Tambah Mahasiswa</h3>
                <hr style="border: 0.5px solid #eee; margin-bottom: 20px;">
                
                <div style="margin-bottom:15px;">
                    <label style="display:block; font-size:12px; margin-bottom:5px; font-weight: bold;">NIM</label>
                    <input type="text" name="nim" id="inputNIM" placeholder="Contoh: 20260003" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box; outline: none;">
                </div>

                <div style="margin-bottom:15px;">
                    <label style="display:block; font-size:12px; margin-bottom:5px; font-weight: bold;">Nama Lengkap</label>
                    <input type="text" name="nama" id="inputNama" placeholder="Masukkan nama..." style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box; outline: none;">
                </div>

                <div style="margin-bottom:15px;">
                    <label style="display:block; font-size:12px; margin-bottom:5px; font-weight: bold;">Jurusan</label>
                    <select name="jurusan" id="inputJurusan" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; outline: none;">
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Manajemen Informatika">Manajemen Informatika</option>
                        <option value="Teknik Komputer">Teknik Komputer</option>
                        <option value="Komputerisasi Akuntansi">Komputerisasi Akuntansi</option>
                    </select>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:12px; margin-bottom:5px; font-weight: bold;">Status</label>
                    <select name="status" id="inputStatus" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; outline: none;">
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" onclick="tutupModal()" style="padding:10px 20px; background:#bdc3c7; color:white; border:none; border-radius:8px; cursor:pointer; font-weight: bold;">Batal</button>
                    <button type="submit" style="padding:10px 20px; background:#4e73df; color:white; border:none; border-radius:8px; cursor:pointer; font-weight: bold;">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div id="status-login" data-status="<?= $this->session->flashdata('status_login'); ?>"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const statusLogin = document.getElementById('status-login').dataset.status;
        if (statusLogin === 'sukses') {
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: 'Selamat datang kembali, Bolo!',
                showConfirmButton: false,
                timer: 2000
            });
        }

        // Script untuk Konfirmasi Logout
        document.getElementById('btnLogout').addEventListener('click', function(e) {
            Swal.fire({
                title: 'Apa anda yakin mau keluar?',
                text: "Sesi anda akan diakhiri!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1a2a20',
                cancelButtonColor: '#e74c3c',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('auth/logout'); ?>";
                }
            })
        });
    </script>

    <?php if($this->session->flashdata('pesan')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Mantap Bolo!',
            text: '<?= $this->session->flashdata('pesan'); ?>',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    <?php endif; ?>

    <script src="<?= base_url('assets/js/dashboard.js'); ?>"></script>

</body>
</html>