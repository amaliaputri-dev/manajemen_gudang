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

        .password-field {
            position: relative;
        }

        .password-field input {
            padding-right: 50px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            color: #636e72;
            cursor: pointer;
            border-radius: 50%;
            transition: 0.2s;
        }

        .toggle-password:hover {
            background: rgba(44, 62, 80, 0.08);
            color: #2c3e50;
        }

        .toggle-password:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.16);
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
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

        @media (max-width: 480px) {
            body {
                padding: 20px;
            }

            .login-container {
                padding: 30px 20px;
            }

            .login-container h2 {
                font-size: 20px;
                letter-spacing: 1px;
            }

            .btn-login {
                padding: 14px;
                font-size: 14px;
            }
        }
    </style>
    <!-- SweetAlert2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

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
                <div class="password-field">
                    <input type="password" id="password" name="password" placeholder="Masukkan password..." required>
                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
                        <svg id="eyeOpen" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 5c5.23 0 9.27 3.11 11 7-1.73 3.89-5.77 7-11 7S2.73 15.89 1 12c1.73-3.89 5.77-7 11-7Zm0 2C8.18 7 5.12 9.11 3.18 12 5.12 14.89 8.18 17 12 17s6.88-2.11 8.82-5C18.88 9.11 15.82 7 12 7Zm0 2.5A2.5 2.5 0 1 1 12 14.5 2.5 2.5 0 0 1 12 9.5Z"/>
                        </svg>
                        <svg id="eyeClosed" viewBox="0 0 24 24" aria-hidden="true" style="display: none;">
                            <path d="m2.71 3.79 17.5 17.5-1.42 1.42-3.05-3.05A12.78 12.78 0 0 1 12 19c-5.23 0-9.27-3.11-11-7a12.8 12.8 0 0 1 4.12-4.76L1.29 5.21l1.42-1.42ZM6.6 8.72A9.9 9.9 0 0 0 3.18 12C5.12 14.89 8.18 17 12 17a9.7 9.7 0 0 0 2.02-.21l-1.65-1.65A3.5 3.5 0 0 1 8.86 11.63ZM12 7c3.82 0 6.88 2.11 8.82 5a12.45 12.45 0 0 1-3.65 4.06l-1.44-1.44A3.48 3.48 0 0 0 16 13.5 3.5 3.5 0 0 0 10.5 8l-1.6-1.6A10.4 10.4 0 0 1 12 7Zm-.47 3.36 3.11 3.11A1.49 1.49 0 0 0 15 12.5 1.5 1.5 0 0 0 12.5 11a1.49 1.49 0 0 0-.97-.64Z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">MASUK SEKARANG</button>
        </form>

        <div class="footer-text">
            &copy; 2026 Project UTS Manajemen Gudang
        </div>
    </div>

</body>
<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    togglePassword.addEventListener('click', function () {
        const isHidden = passwordInput.type === 'password';

        passwordInput.type = isHidden ? 'text' : 'password';
        togglePassword.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        eyeOpen.style.display = isHidden ? 'none' : 'block';
        eyeClosed.style.display = isHidden ? 'block' : 'none';
    });
</script>
</html>
