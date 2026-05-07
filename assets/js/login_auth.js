// Ambil elemen pemicu status gagal/kosong
const statusLogin = document.getElementById('status-login').getAttribute('data-status');

// Logika pop-up SweetAlert untuk gagal
if (statusLogin === 'gagal') {
    Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: 'User tidak ditemukan atau password salah.',
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    });
} else if (statusLogin === 'kosong') {
    Swal.fire({
        icon: 'warning',
        title: 'Peringatan',
        text: 'Username atau Password tidak boleh kosong!',
    });
}