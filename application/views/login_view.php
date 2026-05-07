<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Gudang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            /* Memanggil gambar tugas.jpg sebagai background */
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?= base_url("assets/tugas.jpg") ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 25px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 25px;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }

        .alert-error {
            background-color: #ff7675;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #636e72;
            font-weight: bold;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 5px rgba(44, 62, 80, 0.2);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #2c3e50;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #34495e;
            transform: translateY(-2px);
        }

        .footer-text {
            margin-top: 20px;
            font-size: 12px;
            color: #b2bec3;
        }
    </style>
    <!-- SweetAlert2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div class="login-container">
        <h2>Sistem Login</h2>

        <!-- Pesan Error jika login gagal -->
        <?php if($this->session->flashdata('message')): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: 'User tidak ditemukan atau password salah.',
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    });
</script>
<?php endif; ?>

        <form action="<?= base_url('auth_gudang/proses_login') ?>" method="post">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email anda..." required>
            </div>

            <div class="input-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" placeholder="Masukkan password..." required>
            </div>

            <button type="submit" class="btn-login">MASUK SEKARANG</button>
        </form>

        <div class="footer-text">
            &copy; 2026 Project UTS Manajemen Gudang
        </div>
    </div>

</body>
</html>