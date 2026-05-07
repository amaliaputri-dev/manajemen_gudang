<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Supervisor</title>
    <!-- Tambahkan ini di semua dashboard agar popup logout & berhasil login jalan -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="font-family: sans-serif; text-align: center; padding-top: 50px;">

    <h1>HALAMAN DASHBOARD SUPERVISOR</h1>
    
    <p>Selamat Datang, <b><?= $this->session->userdata('name') ?></b></p>[cite: 1]
    
    <hr style="width: 50%;">
    
    <a href="javascript:void(0)" onclick="konfirmasiLogout('<?= base_url('auth_gudang/logout') ?>')" style="color: red; font-weight: bold;">
    Keluar / Logout
</a>
    <?php if($this->session->flashdata('success_msg')): ?>
<!-- 1. Panggil Library SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- 2. Panggil File Custom JS yang tadi kita buat -->
<script src="<?= base_url('assets/js/sweetalert_custom.js') ?>"></script>

<!-- 3. Jalankan logika PHP untuk centang hijau di sini -->
<script>
    <?php if($this->session->flashdata('success_msg')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil!',
            text: 'Selamat datang kembali, Bolo!',
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>
</script>
<?php endif; ?>

</body>
</html>