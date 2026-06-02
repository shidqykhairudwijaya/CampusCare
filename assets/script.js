// assets/script.js

// ================= FUNGSI PREVIEW GAMBAR =================
// Menampilkan preview foto sebelum form di-submit
function previewImage() {
    const image = document.querySelector('#foto');
    const imgPreview = document.querySelector('.img-preview');

    if (image.files && image.files[0]) {
        imgPreview.style.display = 'block';
        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);
        oFReader.onload = function(oFREvent) {
            imgPreview.src = oFREvent.target.result;
        }
    }
}

// ================= FUNGSI BUKA GAMBAR DI TAB BARU =================
function openImageNewTab(src) {
    window.open(src, '_blank');
}

// ================= VALIDASI FORM BOOTSTRAP =================
// Mengaktifkan peringatan merah/hijau dari Bootstrap jika input form kosong
function enableBootstrapValidation() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
}

// ================= MENAMPILKAN MODAL DETAIL =================
// Mencegah modal terbuka dua kali jika salah klik
function showDetail(event, id) {
    if (event.target.closest('button') || event.target.closest('a')) {
        return;
    }
    var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal' + id));
    myModal.show();
}

// ================= FUNGSI FILTER TABEL =================
// Menyembunyikan baris data tabel sesuai status yang diklik (Semua, Selesai, dll)
function filterTable(status, element = null) {
    if (element) {
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
        element.classList.add('active');
    }

    const rows = document.querySelectorAll('.report-row');
    
    let tbody = document.querySelector('#tableLaporan tbody');
    let colspanVal = 8; // Rata-rata kolom tabel admin
    
    if (!tbody) {
        tbody = document.querySelector('#tablePekerjaan tbody');
        colspanVal = 5; // Rata-rata kolom tabel teknisi
    }

    if (!tbody) {
        tbody = document.querySelector('#mhsTableLaporan tbody');
        colspanVal = 7; // Rata-rata kolom tabel mahasiswa
    }

    if (!tbody) return; 

    let found = 0;
    const oldMsg = document.getElementById('tempNoData');
    if (oldMsg) oldMsg.remove();

    const noDataDefault = document.getElementById('noDataDefault');
    if (noDataDefault) noDataDefault.style.display = 'none';

    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        let match = false;

        if (status === 'all') { match = true; } 
        else { if (rowStatus === status) match = true; }

        if (match) {
            row.style.display = '';
            found++;
        } else {
            row.style.display = 'none';
        }
    });

    if (found === 0) {
        const newRow = tbody.insertRow();
        newRow.id = 'tempNoData';
        newRow.innerHTML = `<td colspan="${colspanVal}" class="text-center py-5 text-muted">Tidak ada data dengan status "${status}".</td>`;
        if (status === 'all' && noDataDefault) {
            newRow.remove();
            noDataDefault.style.display = '';
        }
    }
}

// ================= KUMPULAN FUNGSI HAPUS (SWEETALERT) =================

// Hapus Laporan
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Laporan?',
        text: "Data dan foto bukti akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => { if (result.isConfirmed) { window.location.href = "?hapus=" + id; } });
}

// Hapus Akun Mahasiswa
function confirmDeleteMahasiswa(id) {
    Swal.fire({
        title: 'Hapus Mahasiswa?',
        text: "Data akun dan seluruh riwayat laporannya akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#800000',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => { if (result.isConfirmed) { window.location.href = "?hapus=" + id; } });
}

// Hapus Akun Teknisi
function confirmDeleteTeknisi(id) {
    Swal.fire({
        title: 'Hapus Akun Teknisi?',
        text: "Data akun akan dihapus permanen dari sistem!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#800000',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => { if (result.isConfirmed) { window.location.href = "?hapus=" + id; } });
}

// Hapus Master Ruangan
function confirmDeleteRuangan(id, fakultas) {
    Swal.fire({
        title: 'Hapus Ruangan?',
        text: "Ruangan ini akan dihapus permanen dari sistem!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => { if (result.isConfirmed) { window.location.href = "?hapus_ruangan=" + id + "&fak=" + encodeURIComponent(fakultas); } });
}

// Hapus Master Fakultas
function confirmDeleteFakultas(fakultas) {
    Swal.fire({
        title: 'Hapus Fakultas?',
        text: "Data fakultas dan SEMUA ruangan di dalamnya akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#800000',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus Semua!'
    }).then((result) => { if (result.isConfirmed) { window.location.href = "?hapus_fakultas=" + encodeURIComponent(fakultas); } });
}

// ================= INIT EVENT LISTENER SAAT HALAMAN DILOAD =================
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Jalankan Validasi Form Bootstrap di semua halaman
    enableBootstrapValidation();

    // 2. Fungsi Toggle Menu Sidebar untuk tampilan HP (Mobile)
    const sidebarToggle = document.getElementById('sidebarToggle');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar && sidebarOverlay && closeSidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
        });
        closeSidebar.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }

    // 3. Logika Dropdown Dinamis Bertingkat KHUSUS halaman Tambah Laporan
    const selectFakultas = document.getElementById('fakultas');
    const selectLantai = document.getElementById('lantai');
    const selectRuangan = document.getElementById('ruangan');
    const formLaporan = document.getElementById('formLaporan');

    // Mengecek apakah elemen formnya ada, dan variabel data master lokasinya sudah ter-load dari PHP
    if (selectFakultas && selectLantai && selectRuangan && window.dataMasterLokasi) {
        
        // Panggil data dari objek window global
        const dataMasterLokasi = window.dataMasterLokasi;

        // 1. Isi opsi Fakultas (LOGIKA ASLI)
        for (let fakultas in dataMasterLokasi) {
            selectFakultas.add(new Option(fakultas, fakultas));
        }

        // 2. Event Fakultas -> Lantai (LOGIKA ASLI)
        selectFakultas.addEventListener('change', function() {
            selectLantai.innerHTML = '<option value="">Pilih Lantai...</option>';
            selectRuangan.innerHTML = '<option value="">Pilih Ruangan...</option>';
            selectRuangan.disabled = true;
            if (this.value !== "") {
                selectLantai.disabled = false;
                for (let lantai in dataMasterLokasi[this.value]) {
                    selectLantai.add(new Option("Lantai " + lantai, lantai));
                }
            } else { selectLantai.disabled = true; }
        });

        // 3. Event Lantai -> Ruangan (LOGIKA ASLI)
        selectLantai.addEventListener('change', function() {
            selectRuangan.innerHTML = '<option value="">Pilih Ruangan...</option>';
            if (this.value !== "") {
                selectRuangan.disabled = false;
                const listRuangan = dataMasterLokasi[selectFakultas.value][this.value];
                listRuangan.forEach(function(r) {
                    selectRuangan.add(new Option(r.nama, r.id));
                });
            } else { selectRuangan.disabled = true; }
        });

        // 4. UNLOCK DISABLED FIELDS SAAT SUBMIT (Agar id_ruangan kekirim ke PHP)
        if (formLaporan) {
            formLaporan.addEventListener('submit', function() {
                if(this.checkValidity()) {
                    selectLantai.disabled = false;
                    selectRuangan.disabled = false;
                }
            });
        }
    }
});
