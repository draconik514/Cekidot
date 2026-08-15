<<<<<<< HEAD
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
=======
# PRD.md

## Role-Based Access Control & Pengarsipan Surat Digital
### Web Persuratan "Cekidot"

---

## 1. Ringkasan
Web persuratan "Cekidot" dikembangkan untuk mengelola surat masuk/keluar di lingkungan kantor. Fitur ini bertujuan membangun sistem Role-Based Access Control (RBAC) dan pengarsipan digital yang terstruktur per bidang, dengan Super Admin sebagai pengelola utama.

---

## 2. Tujuan
| Tujuan | Manfaat |
|--------|---------|
| Keamanan data berbasis peran | Surat bidang A tidak bisa diakses bidang B |
| Digitalisasi arsip | Pencarian cepat, kurangi tumpukan fisik |
| Manajemen user oleh Super Admin | Mudah kelola pegawai baru/pindah/keluar |
| Upload terbatas per bidang | Hindari salah penempatan arsip |

---

## 3. Target Pengguna

| Role | Deskripsi |
|------|-----------|
| **Super Admin** | Mengelola semua user, melihat semua arsip (read-only) |
| **Admin Kepegawaian** | Akses & upload arsip bidang kepegawaian |
| **Admin Keuangan** | Akses & upload arsip bidang keuangan |
| **Admin Program** | Akses & upload arsip bidang program |
| **Admin Ekraf** | Akses & upload arsip bidang ekraf |
| **Admin Destinasi** | Akses & upload arsip bidang destinasi |
| **Admin Pemasaran** | Akses & upload arsip bidang pemasaran |
| **Admin SDM** | Akses & upload arsip bidang SDM |

---

## 4. Fitur

### Super Admin
- Login khusus Super Admin
- Dashboard rekap arsip per bidang
- CRUD user (buat, edit, nonaktifkan, hapus)
- Lihat semua arsip dari semua bidang (read-only)
- Cetak laporan arsip semua bidang

### Admin Bidang
- Login sesuai bidang masing-masing
- Dashboard arsip bidang sendiri
- Upload surat (file + metadata)
- Lihat & cari arsip bidang sendiri
- Unduh file surat
- Hapus arsip milik sendiri

---

## 5. Struktur Database

### Tabel `bidang`
```sql
CREATE TABLE bidang (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_bidang VARCHAR(100) NOT NULL,
    kode_bidang VARCHAR(10) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


Tabel 
INSERT INTO bidang (nama_bidang, kode_bidang) VALUES
('Kepegawaian', 'KEP'),
('Keuangan', 'KEU'),
('Program', 'PRO'),
('Ekraf', 'EKR'),
('Destinasi', 'DES'),
('Pemasaran', 'PEM'),
('SDM', 'SDM');

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100) NOT NULL,
    role ENUM('super_admin', 'admin_bidang') DEFAULT 'admin_bidang',
    bidang_id INT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (bidang_id) REFERENCES bidang(id) ON DELETE SET NULL
);

CREATE TABLE arsip_surat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bidang_id INT NOT NULL,
    nomor_surat VARCHAR(100) NOT NULL,
    tanggal_surat DATE NOT NULL,
    perihal VARCHAR(255) NOT NULL,
    jenis_surat ENUM('masuk', 'keluar', 'internal') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_size INT,
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    keterangan TEXT,
    is_deleted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (bidang_id) REFERENCES bidang(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

6. Aturan Bisnis
Admin bidang HANYA bisa akses arsip dengan bidang_id sesuai akunnya

Super Admin bisa lihat semua arsip, dan bisa hapus

Upload file: PDF, JPG, PNG, DOC/DOCX (maks 10MB)

Arsip di-soft delete (flag is_deleted), tidak hilang permanen

API Endpoints
Auth
Method	Endpoint	Deskripsi
POST	/api/auth/login	Login
POST	/api/auth/logout	Logout
GET	/api/auth/me	Profil user

User Management (Super Admin only)
Method	Endpoint	Deskripsi
GET	/api/users	List user
POST	/api/users	Buat user
GET	/api/users/{id}	Detail user
PUT	/api/users/{id}	Update user
DELETE	/api/users/{id}	Hapus user

Arsip Management
Method	Endpoint	Deskripsi	Akses
GET	/api/arsip	List arsip (filter otomatis)	All
POST	/api/arsip	Upload arsip	Admin Bidang
GET	/api/arsip/{id}	Detail arsip	All
DELETE	/api/arsip/{id}	Hapus arsip	Admin Bidang (milik sendiri)
GET	/api/arsip/search	Cari arsip	All
GET	/api/arsip/export	Export Excel	All

Dashboard
Method	Endpoint	Deskripsi	Akses
GET	/api/dashboard/admin	Statistik semua bidang	Super Admin
GET	/api/dashboard/bidang	Statistik bidang sendiri	Admin Bidang

Kriteria Penerimaan
No	Skenario	Kriteria Sukses
1	Login SA	Redirect ke dashboard SA
2	Login Admin Bidang	Redirect ke dashboard bidang, hanya lihat arsip bidangnya
3	SA buat user	User bisa login sesuai bidang dipilih
4	Admin upload surat	File tersimpan, bidang_id otomatis dari user
5	Akses arsip bidang lain	Tidak muncul, akses langsung dapat 403
6	SA lihat semua arsip	Bisa lihat semua surat
7	Pencarian	Filter sesuai keyword
8	Hapus arsip	Hanya admin pemilik arsip, SA tidak bisa
9	Upload file	Format didukung, <10MB, folder sesuai bidang & tahun

Keamanan
Password di-hash (bcrypt)

Middleware RBAC di setiap endpoint

Validasi ekstensi & ukuran file

Prepared statement / ORM anti SQL Injection

Sanitasi input anti XSS

Soft delete untuk arsip

Struktur Folder Upload
storage/arsip/
├── KEP/
│   └── 2026/
│       └── 08/
├── KEU/
├── PRO/
├── EKR/
├── DES/
├── PEM/
└── SDM/

Catatan Developer (OpenCode Agent)
Pakai Plan mode dulu sebelum eksekusi

Prioritas: Auth → User → Arsip

Middleware RBAC wajib di semua endpoint arsip

Filter query: WHERE bidang_id = user.bidang_id

Folder: storage/arsip/{kode_bidang}/{tahun}/{bulan}/

SA hanya read, tidak bisa hapus arsip

Pakai soft delete (is_deleted)
>>>>>>> fd1683fb08e1dafd358aeaeb27a3fbc12f877618
