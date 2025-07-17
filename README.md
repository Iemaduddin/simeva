# Simeva - Sistem Informasi Manajemen Event dan Aset

**Simeva** adalah sistem informasi berbasis web yang dirancang untuk mendukung pengelolaan event dan aset secara terintegrasi di lingkungan institusi, khususnya kampus. Aplikasi ini memfasilitasi berbagai jenis pengguna (multi-role system) dengan kebutuhan spesifik, mulai dari manajemen event, peminjaman aset, hingga pengelolaan peserta dan notifikasi real-time.

---

## 🚀 Fitur Utama

### 1. 🎯 Event Management

Modul ini dikhususkan untuk pengguna dengan role **Organizer**, dengan kemampuan sebagai berikut:

-   CRUD event (buat, ubah, hapus, dan lihat detail event)
-   Otomatisasi peminjaman aset saat pembuatan event
-   Manajemen peserta event
-   Manajemen tamu undangan & pembicara
-   Presensi peserta dan panitia berdasarkan tahapan event
-   Export daftar kehadiran ke dalam format Excel
-   Otomatisasi pembuatan surat peminjaman aset dan undangan untuk tamu
-   Fitur internal calendar untuk keperluan event pribadi non-publik
-   Pengelolaan anggota tim event oleh setiap organizer

### 2. 🏛️ Asset Management

Simeva membagi aset ke dalam dua kategori utama:

-   **Fasilitas Umum (Kaur Rumah Tangga):** Aset yang dapat dipinjam oleh civitas kampus secara gratis.
-   **Fasilitas Jurusan (Admin Jurusan):** Aset khusus jurusan masing-masing, juga dikelola secara gratis.

Fitur:

-   CRUD Aset (tambah, ubah, hapus, dan tampilkan aset)
-   Penempatan aset di beranda untuk publikasi/promosi
-   Klasifikasi aset berdasarkan scope: umum vs jurusan

### 3. 📅 Asset Bookings Management

Proses peminjaman aset melibatkan alur validasi oleh role tertentu:

-   **Organizer:** Meminjam aset internal kampus (fasilitas umum dan jurusan), secara gratis.
-   **Tenant:** Peminjam dari luar kampus, dengan jalur berbayar.

Alur:

-   Pengajuan peminjaman berdasarkan kalender penggunaan aset
-   Booking harian maupun tahunan
-   Status real-time: pending, approved, rejected, cancelled
-   Riwayat peminjaman oleh masing-masing tenant/organizer

---

## ✉️ Notifikasi Sistem & Email

Simeva dilengkapi dengan fitur notifikasi dan email:

-   Notifikasi sistem untuk permintaan booking, approved booking dan lainnya
-   Email otomatis untuk:
    -   Status peminjaman aset
    -   Undangan peserta atau pembicara
    -   Pendaftaran event
    -   Informasi penting lainnya

---

## 👥 Role & Hak Akses

Simeva menyediakan sistem multi-user dengan 7 role utama:

| Role              | Akses & Tanggung Jawab                                                               |
| ----------------- | ------------------------------------------------------------------------------------ |
| **Super Admin**   | Akses penuh ke seluruh sistem, termasuk CRUD user, jurusan, dan prodi                |
| **Organizer**     | Kelola event, peserta, presensi, peminjaman aset, dan anggota tim event              |
| **UPT PU**        | Kelola peminjaman aset eksternal (berbayar), manajemen tenant                        |
| **Kaur RT**       | Kelola aset fasilitas umum, approval booking internal (gratis)                       |
| **Admin Jurusan** | Kelola aset jurusan, approval booking internal (gratis)                              |
| **Participant**   | Mahasiswa atau pihak luar yang bisa mendaftar event, melihat riwayat, harga dinamis  |
| **Tenant**        | Peminjam eksternal aset kampus, dengan fitur kalender booking dan histori peminjaman |

---

## 🧩 Fitur Tambahan

-   Manajemen user berbasis role
-   Participant mendapatkan E-Ticket untuk presensi kehadiran
-   CRUD Jurusan dan Program Studi
-   Harga tiket event otomatis berdasarkan jenis peserta (internal/eksternal)
-   Export data ke Excel
-   Kalender internal user
-   Riwayat peminjaman
-   Validasi booking aset sesuai wewenang
-   Terdapat invoice untuk tenant
-   Dashboard real-time untuk pengguna yang berbeda (super admin, organizer, tenant, dll.)

---

## 🛠️ Teknologi

-   **Backend:** Laravel
-   **Frontend:** Blade
-   **Database:** MySQL

---

## ⚙️ Instalasi

```bash
# Clone repository
git clone https://github.com/Iemaduddin/simeva.git
cd simeva

# Install dependencies
composer install
npm install

# Copy environment
cp .env.example .env

# Generate app key
php artisan key:generate

# Migrate dan seed database
php artisan migrate --seed

# Jalankan server lokal
php artisan serve
```

---

## 📄 Lisensi

Aplikasi ini dikembangkan untuk mendukung proses manajemen event dan aset di lingkungan institusi kampus. Lisensi mengikuti ketentuan dari pemilik repository ini. Kontak jika ada pertanyaan atau permintaan lisensi tambahan.

---

### 📬 Kontak

Silakan hubungi kami untuk pertanyaan, saran, atau kontribusi:

Email: iemaduddin17@gmail.com
