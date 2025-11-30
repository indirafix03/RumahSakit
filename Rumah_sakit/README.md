# Sistem Manajemen Rumah Sakit

Sistem manajemen rumah sakit berbasis web yang dibangun dengan Laravel untuk mengelola operasional rumah sakit secara efisien. Sistem ini dirancang untuk memfasilitasi interaksi antara pasien, dokter, dan administrator rumah sakit.

## 🚀 Fitur Utama

### 👥 Manajemen Pengguna
- **Admin**: Mengelola pengguna, poli, obat, dan data rumah sakit
- **Dokter**: Mengelola jadwal, janji temu, dan rekam medis
- **Pasien**: Membuat janji temu, melihat rekam medis, dan memberikan feedback

### 📅 Sistem Janji Temu (Appointments)
- Pembuatan janji temu online
- Manajemen status janji temu (pending, confirmed, completed, cancelled)
- Integrasi dengan jadwal dokter

### 🏥 Manajemen Rekam Medis
- Pembuatan dan pengelolaan rekam medis pasien
- Riwayat pengobatan lengkap
- Akses terbatas berdasarkan role

### 💊 Sistem Resep Obat
- Manajemen obat dan stok
- Pembuatan resep digital
- Konfirmasi pengambilan obat

### 📊 Dashboard dan Laporan
- Dashboard khusus untuk setiap role
- Statistik janji temu dan aktivitas
- Sistem feedback dari pasien

### 🔐 Sistem Keamanan
- Autentikasi dan autorisasi berbasis role
- Middleware untuk proteksi route
- Enkripsi data sensitif

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates, Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **JavaScript**: Alpine.js
- **Build Tool**: Vite

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL atau database yang kompatibel
- Web server (Apache/Nginx)

## 🚀 Instalasi

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd rumah-sakit
   ```

2. **Install dependencies PHP**
   ```bash
   composer install
   ```

3. **Install dependencies JavaScript**
   ```bash
   npm install
   ```

4. **Konfigurasi environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Konfigurasi database**
   - Buat database baru di MySQL
   - Update file `.env` dengan kredensial database

6. **Jalankan migrasi dan seeder**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```

## 📖 Penggunaan

### Akses Sistem
- **Guest**: Dapat melihat informasi poli dan dokter tanpa login
- **Admin**: Login untuk mengakses panel admin di `/admin/dashboard`
- **Dokter**: Login untuk mengakses panel dokter di `/dokter-dashboard`
- **Pasien**: Login untuk mengakses panel pasien di `/pasien/dashboard`

### Fitur Utama
1. **Pendaftaran Akun**: Pasien dapat mendaftar akun baru
2. **Membuat Janji Temu**: Pilih poli, dokter, dan waktu yang tersedia
3. **Manajemen Jadwal**: Dokter dapat mengatur jadwal praktik
4. **Rekam Medis**: Dokter dapat membuat dan mengupdate rekam medis
5. **Resep Obat**: Sistem terintegrasi untuk manajemen resep
6. **Feedback**: Pasien dapat memberikan feedback setelah konsultasi

## 🗄️ Struktur Database

Sistem menggunakan beberapa tabel utama:
- `users`: Data pengguna dengan role (admin, dokter, pasien)
- `polis`: Data poli/departemen
- `appointments`: Data janji temu
- `medical_records`: Rekam medis pasien
- `medicines`: Data obat
- `prescriptions`: Data resep
- `schedules`: Jadwal dokter
- `feedbacks`: Feedback dari pasien

## 🔧 Konfigurasi

### Environment Variables
Pastikan file `.env` memiliki konfigurasi yang benar:
```env
APP_NAME="Rumah Sakit"
APP_ENV=local
APP_KEY=base64:key
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rumah_sakit
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Permissions
Pastikan direktori storage memiliki permission yang tepat:
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## 🧪 Testing

Jalankan test suite:
```bash
php artisan test
```

## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📝 Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lebih lanjut.

## 📞 Dukungan

Untuk pertanyaan atau dukungan, silakan hubungi tim development atau buat issue di repository ini.

## 🔄 Update Log

### Versi 1.0.0
- Fitur dasar sistem manajemen rumah sakit
- Manajemen pengguna dengan 3 role
- Sistem janji temu
- Rekam medis dan resep obat
- Dashboard untuk setiap role
- Sistem feedback

---

**Catatan**: Pastikan semua dependencies terinstall dengan benar sebelum menjalankan aplikasi. Untuk production deployment, pastikan `APP_ENV=production` dan `APP_DEBUG=false`.
