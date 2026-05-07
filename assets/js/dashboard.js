// Variable global untuk menyimpan baris yang sedang diedit
let barisYgDiedit = null;

function showPage(pageId) {
	// 1. Pindahkan Konten Halaman
	const pages = document.querySelectorAll(".page-content");
	pages.forEach((p) => p.classList.remove("active"));

	const target = document.getElementById(pageId);
	if (target) target.classList.add("active");

	// 2. Pindahkan Warna Kuning di Sidebar (BOLO!)
	const menuItems = document.querySelectorAll(".nav-item");
	menuItems.forEach((item) => item.classList.remove("active")); // Hapus semua warna kuning

	// Cari tombol yang diklik berdasarkan fungsi onclick-nya
	const clickedMenu = document.querySelector(
		`[onclick="showPage('${pageId}')"]`,
	);
	if (clickedMenu) {
		clickedMenu.classList.add("active"); // Tambah warna kuning ke menu yg diklik
	}
}

// FUNGSI UNTUK BUKA MODAL
function aksiMahasiswa(
	tipe,
	nama = "",
	nim = "",
	jurusan = "",
	status = "Aktif",
	tombol = null,
) {
	const modal = document.getElementById("modalMahasiswa");
	document.getElementById("modalTitle").innerText = tipe + " Mahasiswa";

	// Isi field input
	document.getElementById("inputNIM").value = nim;
	document.getElementById("inputNama").value = nama;
	document.getElementById("inputJurusan").value =
		jurusan || "Teknik Informatika";
	document.getElementById("inputStatus").value = status;

	if (tipe === "Edit" && tombol) {
		barisYgDiedit = tombol.closest("tr"); // Simpan baris mana yang mau diganti
	} else {
		barisYgDiedit = null;
	}

	modal.style.display = "flex";
}

function tutupModal() {
	document.getElementById("modalMahasiswa").style.display = "none";
}

// FUNGSI SIMPAN DATA (PERBAIKAN TOTAL BOLO!)
function simpanData() {
	const nim = document.getElementById("inputNIM").value;
	const nama = document.getElementById("inputNama").value;
	const jurusan = document.getElementById("inputJurusan").value;
	const status = document.getElementById("inputStatus").value;
	const title = document.getElementById("modalTitle").innerText;

	if (nim === "" || nama === "") {
		Swal.fire("Waduh!", "NIM dan Nama nggak boleh kosong bolo!", "error");
		return;
	}

	if (title.includes("Tambah")) {
		// --- LOGIKA TAMBAH ---
		dataLokal.push({ nim, nama, jurusan, status });
		localStorage.setItem("mahasiswa", JSON.stringify(dataLokal));

		Swal.fire("Berhasil!", "Data baru disimpan permanen bolo!", "success").then(
			() => {
				location.reload();
			},
		);
	} else {
		// --- LOGIKA EDIT (BIAR DATA GAK BALIK LAGI) ---
		// Cari apakah data ini sudah ada di memori berdasarkan NIM
		const index = dataLokal.findIndex((mhs) => mhs.nim === nim);

		if (index !== -1) {
			// Kalau sudah ada di memori, kita update
			dataLokal[index] = { nim, nama, jurusan, status };
		} else {
			// Kalau belum ada (kayak Ragil/Haikal bawaan PHP), kita masukin ke memori
			dataLokal.push({ nim, nama, jurusan, status });
		}

		localStorage.setItem("mahasiswa", JSON.stringify(dataLokal));

		Swal.fire(
			"Berhasil!",
			"Perubahan data sudah permanen bolo!",
			"success",
		).then(() => {
			location.reload();
		});
	}
	tutupModal();
}

function hapusMahasiswa(nama, tombol) {
	// Ambil NIM dari baris tabel
	const baris = tombol.closest("tr");
	const nim = baris.cells[0].innerText;

	Swal.fire({
		title: "Yakin mau hapus, bolo?",
		text: "Data " + nama + " bakal hilang dari database!",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#e74c3c",
		cancelButtonColor: "#6c757d",
		confirmButtonText: "Ya, Hapus!",
		cancelButtonText: "Batal",
	}).then((result) => {
		if (result.isConfirmed) {
			// Kita arahkan ke link hapus di Controller Dashboard
			// Ganti 'CodeIgniter' dengan nama folder project kamu kalau beda
			window.location.href =
				"http://localhost/CodeIgniter/dashboard/hapus_mahasiswa/" + nim;
		}
	});
}

// 1. Fungsi Logout (Pastikan penutupnya rapi)
function konfirmasiLogout() {
	Swal.fire({
		title: "Yakin mau logout?",
		text: "Sesi kamu bakal berakhir di sini bolo!",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#d33",
		cancelButtonColor: "#6c757d",
		confirmButtonText: "Ya, Keluar!",
		cancelButtonText: "Batal",
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "http://localhost/CodeIgniter/auth/logout";
		}
	});
} // <--- Pastikan penutup ini ada satu saja

// 2. Fungsi Cek Hash (Untuk Redirect otomatis ke tab)
function cekHash() {
	const hash = window.location.hash.replace("#", "");
	if (hash) {
		showPage(hash);
	}
}

// 3. Jalankan cekHash saat halaman selesai loading
window.addEventListener("load", cekHash);

// Fungsi Pencarian Real-Time Bolo!
function cariMahasiswa() {
	// 1. Ambil nilai dari input nama dan dropdown jurusan
	let inputNama = document.getElementById("inputCari").value.toUpperCase();
	let inputJurusan = document
		.getElementById("filterJurusan")
		.value.toUpperCase();

	let table = document.querySelector("table");
	let tr = table.getElementsByTagName("tr");

	for (let i = 1; i < tr.length; i++) {
		// Ambil kolom Nama (indeks 1) dan kolom Jurusan (indeks 2)
		// Pastikan indeks [2] adalah letak kolom Jurusan di tabelmu bolo
		let tdNama = tr[i].getElementsByTagName("td")[1];
		let tdJurusan = tr[i].getElementsByTagName("td")[2];

		if (tdNama && tdJurusan) {
			let txtNama = tdNama.textContent || tdNama.innerText;
			let txtJurusan = tdJurusan.textContent || tdJurusan.innerText;

			// Logika: Nama cocok DAN Jurusan cocok
			// Kalau Jurusan dipilih "Semua/Kosong", maka dianggap cocok semua
			let namaCocok = txtNama.toUpperCase().indexOf(inputNama) > -1;
			let jurusanCocok =
				inputJurusan === "" ||
				txtJurusan.toUpperCase().indexOf(inputJurusan) > -1;

			if (namaCocok && jurusanCocok) {
				tr[i].style.display = "";
			} else {
				tr[i].style.display = "none";
			}
		}
	}
}
