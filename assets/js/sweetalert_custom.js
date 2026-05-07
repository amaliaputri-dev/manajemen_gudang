// Fungsi untuk konfirmasi Logout
function konfirmasiLogout(url) {
	Swal.fire({
		title: "Apa anda yakin mau keluar?",
		text: "Sesi anda akan diakhiri!",
		icon: "question",
		showCancelButton: true,
		confirmButtonColor: "#1a2a1a",
		cancelButtonColor: "#e74c3c",
		confirmButtonText: "Ya, Keluar!",
		cancelButtonText: "Batal",
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = url;
		}
	});
}
