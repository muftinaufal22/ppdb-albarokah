# Sistem Informasi Manajemen Sekolah & PPDB Online - RA Al-Barokah 🎓

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)

Sistem Informasi ini merupakan solusi **Transformasi Digital** untuk **RA Al-Barokah** yang mengintegrasikan portal informasi publik dengan sistem administrasi sekolah terpadu. Proyek ini bertujuan untuk mendigitalkan penyebaran profil institusi serta mengefisiensikan proses Penerimaan Peserta Didik Baru (PPDB) secara mandiri dan sistematis.

## 📈 Latar Belakang & Analisis Masalah
Berdasarkan observasi pada RA Al-Barokah, sebelumnya pengelolaan informasi dan pendaftaran masih bergantung pada media konvensional (brosur/spanduk) yang memiliki keterbatasan jangkauan. Sistem ini hadir untuk mengatasi kendala tersebut melalui:
* **Digitalisasi Administrasi:** Migrasi proses pendaftaran dari manual ke sistem berbasis web untuk mengurangi redundansi data.
* **Aksesibilitas Informasi:** Memungkinkan wali murid mengakses visi-misi, galeri kegiatan, dan agenda sekolah secara *real-time*.
* **Sentralisasi Data:** Tata kelola database guru, siswa, dan keuangan yang terpusat untuk akurasi laporan akademik.

## 📸 Dokumentasi Antarmuka

### 🖥️ Halaman Utama (Landing Page)
*identitas digital sekolah, informasi profil, dan berita terbaru.*
![WhatsApp Image 2026-02-03 at 14 11 20](https://github.com/user-attachments/assets/20194639-ac29-41e2-92f0-f423f72b1606)

### 📝 Alur Pendaftaran Siswa Baru (PPDB)
*Modul pendaftaran online mandiri untuk memudahkan wali murid melakukan registrasi.*
<img width="1333" height="677" alt="Screenshot 2026-02-04 071213" src="https://github.com/user-attachments/assets/a6ed4d03-15b0-483a-a8bf-bd6b81ad9229" />


## 🚀 Fitur Utama

### 👤 Portal Publik (Frontend)
* **Company Profile:** Publikasi Visi Misi, Sejarah, Galeri, dan Struktur Organisasi secara interaktif.
* **Portal Berita & Event:** Manajemen konten dinamis untuk informasi kegiatan sekolah.
* **Modul PPDB Online:** Formulir registrasi digital yang terintegrasi langsung dengan database sekolah.

### 🔐 Dashboard Administrasi (Backend)
* **Content Management System (CMS):** Kontrol penuh konten website tanpa perlu menyentuh kodingan (CRUD).
* **Verifikasi PPDB:** Antarmuka validasi berkas pendaftar dan manajemen status penerimaan siswa.
* **Manajemen Akademik:** Pengelolaan database guru, siswa aktif, dan kelas secara terpusat.
* **Laporan Keuangan:** Sistem pencatatan tagihan dan pembayaran SPP oleh Bendahara.

## 🛠️ Arsitektur Teknologi
* **Backend Framework:** Laravel 10 (Arsitektur MVC).
* **Frontend Styling:** Bootstrap 5 (Responsive Design).
* **Database:** MySQL (Relational Database).
* **Testing:** Black Box Testing untuk memastikan fungsionalitas fitur pendaftaran dan login.

## 💻 Panduan Instalasi (Localhost)
1. **Clone & Install Dependencies**
   ```bash
   git clone [https://github.com/muftinaufal22/ppdb-albarokah.git](https://github.com/muftinaufal22/ppdb-albarokah.git)
   cd ppdb-albarokah
   composer install && npm install

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**
    * Copy file `.env.example` menjadi `.env`.
    * Sesuaikan konfigurasi database (DB_DATABASE, DB_USERNAME, dll).
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Database Migration & Seeding**
    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Jalankan Aplikasi**
    ```bash
    npm run build
    php artisan serve
    ```

6.  **Akses Website**
    Buka browser: `http://localhost:8000`

---

**Developer:** Mufti Hashfi Naufal and Tim
