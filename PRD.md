1. Deskripsi Fitur
Fitur ini adalah Modul Pengarsipan Hierarkis dan Multi-peran yang berfungsi untuk mengelola dokumen kepegawaian serta capaian kinerja secara digital dalam platform CEKIDOT. Sistem ini meniru struktur folder Windows Explorer untuk kerapian penyimpanan dan menerapkan kontrol akses berjenjang.
2. Tujuan Pengembangan
Meningkatkan efisiensi dan transparansi administrasi dinas.
Memudahkan pencarian dan pengelompokan dokumen secara terpusat.
Menjaga kerahasiaan data dengan membatasi akses sesuai jabatan dan bidang masing-masing.
3. Goals (Tujuan Utama)
Membuat sistem direktori folder yang dapat menampung dokumen bertingkat.
Mengimplementasikan akses berbasis multi-peran (Super Admin, Admin, dan User).
Menyediakan log aktivitas untuk setiap interaksi dengan dokumen.
4. Non-Goals (Batasan Ruang Lingkup)
Tidak mencakup fitur penggajian pegawai secara langsung.
Tidak menyediakan fungsi pengeditan dokumen teks di dalam aplikasi.
5. User Stories
Sebagai Super Admin, saya ingin dapat membuat dan mengelola akun Kepala Bidang (Admin) agar setiap bidang memiliki penanggung jawab pengarsipan.
Sebagai Admin, saya ingin membuat dan mengatur struktur folder utama serta mendaftarkan staf (User) di bidang saya agar dokumen dapat diunggah ke tempat yang tepat.
Sebagai User, saya ingin dapat mengunggah dan mengunduh dokumen ke folder bidang saya agar pekerjaan administrasi tercatat dengan rapi.
6. Acceptance Criteria
Sistem hanya menampilkan folder kepegawaian yang sesuai dengan hak akses peran pengguna yang login.
Proses unggah gagal jika tipe file tidak diizinkan atau ukuran file melebihi batas.
Ada notifikasi sukses setelah dokumen berhasil disimpan ke server.
7. Arsitektur Multi-Peran dan Hak Akses
Super Admin (Administrator Sistem Tertinggi):
Memiliki hak akses penuh (CRUD - Create, Read, Update, Delete) pada seluruh sistem.
Dapat membuat, mengedit, dan menghapus akun Admin untuk setiap Kepala Bidang.
Dapat melihat seluruh struktur folder dan dokumen dari semua bidang untuk keperluan audit dan monitoring.
Admin (Kepala Bidang):
Didaftarkan oleh Super Admin.
Memiliki hak CRUD hanya pada folder utama dan sub-folder di bidangnya sendiri.
Dapat mendaftarkan, mengedit, dan menghapus akun User untuk staf di bidangnya masing-masing.
User (Staf Kepegawaian Bidang):
Didaftarkan oleh Admin.
Memiliki hak untuk melihat (Read) dan mengunggah (Create) dokumen baru ke dalam folder yang telah ditentukan.
Dapat mengunduh (Download) dokumen yang ada di bidangnya.
Tidak memiliki hak untuk menghapus dokumen atau memodifikasi struktur folder utama.
8. Spesifikasi Struktur Folder dan Direktori
Struktur folder hierarkis harus dapat menampung 'Parent Folder' (nama bidang) dan beberapa 'Child Folder' di dalamnya.
Kategori folder standar yang harus tersedia pada inisialisasi awal meliputi: Surat Masuk, Surat Keluar, SK (Surat Keputusan), dan Rencana Hasil Kerja (RHK).
Penamaan dan pembuatan sub-folder lebih lanjut diizinkan melalui aksi 'Tambah Folder' oleh Admin atau User yang memiliki hak akses.
9. Alur Kerja Pengguna (User Flow)
Inisialisasi Data: Super Admin membuatkan akun untuk setiap Kepala Bidang, kemudian Kepala Bidang membuatkan akun untuk staf di bawahnya.
Unggah Dokumen: Pengguna (User) masuk ke aplikasi, membuka menu Arsip, memilih direktori bidangnya, mencari atau membuat sub-folder tujuan, lalu mengisi formulir unggah dokumen dengan menyertakan nama file, deskripsi, dan lampiran berkas.
Pencarian Dokumen: Fitur pencarian global dan pencarian spesifik per folder harus tersedia untuk mempercepat penemuan berkas berdasarkan nama file atau tanggal unggah.
10. Spesifikasi Antarmuka Pengguna (UI/UX)
Halaman Utama Arsip: Terletak pada navigasi utama dashboard, menampilkan daftar bidang dalam bentuk pohon navigasi yang dapat dilipat (collapsible) dan diperluas (expandable).
Tampilan Dokumen: Daftar file di dalam folder harus disajikan dalam bentuk tabel yang memuat kolom: Nama File, Jenis, Tanggal Unggah, Ukuran File, dan Tombol Tindakan.
Pratinjau Berkas (File Preview): Terdapat fitur untuk melihat isi dokumen (khususnya PDF dan gambar) langsung di dalam browser tanpa harus mengunduh berkas terlebih dahulu.
11. Persyaratan Teknis (Technical Requirements)
Validasi File: Sistem harus melakukan validasi tipe dan ukuran file pada proses unggah untuk mencegah masuknya file berbahaya (seperti eksekusi script PHP).
Penyimpanan Fisik: Berkas dokumen harus disimpan di dalam direktori penyimpanan server, sementara informasi path atau alamat penyimpanannya dicatat dalam tabel database.
Log Aktivitas (Audit Trail): Setiap aksi unggah, modifikasi, dan unduh harus dicatat otomatis ke dalam tabel log yang berisi ID Pengguna, Waktu Aksi, Jenis Aksi, dan ID Dokumen terkait demi alasan akuntabilitas instansi.