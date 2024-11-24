# Personal Light Instagram

**Personal Light Instagram** adalah aplikasi berbasis Laravel yang meniru beberapa fitur dari Instagram, seperti feed berbasis foto/video, like, dan komentar. Aplikasi ini memungkinkan pengguna untuk mengupload foto/video, memberikan like pada post, dan menambahkan komentar.

## Fitur Utama

- **Registrasi & Login**: Pengguna dapat mendaftar menggunakan username, email, dan password.
- **Feed**: Pengguna dapat mengupload gambar atau video dengan caption.
- **Like & Comment**: Pengguna dapat memberikan like dan komentar pada setiap post.
- **Profile**: Pengguna dapat mengupdate profil, termasuk mengubah foto profil dan bio.

## Cara Menjalankan Aplikasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi ini secara lokal.

### 1. Clone Repository

Clone repository ini ke komputer Anda dengan perintah berikut:

```bash
git clone https://github.com/agus-triono08/personal-light-instagram.git
```

### 2. Install Dependencies

Masuk ke direktori proyek dan install semua dependensi yang diperlukan menggunakan Composer:

```bash
cd personal-light-instagram
composer install
```

### 3. Salin File ```.env```

Buat file ```.env``` baru dari file ```.env.example``` yang ada di direktori root proyek Anda:

```bash
cp .env.example .env
```

### 4. Konfigurasi Database

Setelah itu, buka file ```.env``` dan konfigurasi pengaturan database sesuai dengan database yang Anda gunakan. Contoh pengaturan untuk MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=personallightinstagram
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Kunci Enkripsi

Menambahkan kunci di file ```env``` dengan nilai pada ```APP_KEY```.

```bash
php artisan key:generate
```

### 6. Jalankan Symlink

Ini akan membuat symlink baru di ```public/storage``` yang akan menunjuk ke ```storage/app/public```.

```bash
php artisan storage:link
```

### 7. Jalankan Migrasi Database

Setelah mengonfigurasi database, jalankan migrasi untuk membuat struktur tabel di database Anda:

```bash
php artisan migrate
```

Jika Anda ingin mengisi database dengan data dummy, Anda dapat menjalankan perintah berikut untuk menjalankan seeder:

```bash
php artisan db:seed
```

### 8. Jalankan Aplikasi Laravel

Setelah langkah-langkah di atas selesai, Anda dapat menjalankan aplikasi menggunakan perintah berikut:

```bash
php artisan serve
```
Aplikasi akan berjalan di http://localhost:8000. Anda dapat mengaksesnya melalui browser.

### 9. Menjalankan Uji Coba
Cobalah untuk membuat beberapa akun pengguna, upload beberapa foto/video, beri like dan komentar pada post, dan lihat bagaimana aplikasi berfungsi.

## Teknologi yang Digunakan

- **Laravel 11**: Framework PHP yang digunakan untuk membangun aplikasi ini.
- **MySQL**: Sistem manajemen database relasional yang digunakan untuk menyimpan data.
- **HTML, CSS, JavaScript**: Untuk membangun antarmuka pengguna (frontend).
- **Composer**: Manajer ketergantungan PHP untuk mengelola paket-paket yang digunakan dalam proyek.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Kontak
Jika Anda memiliki pertanyaan atau masalah terkait aplikasi ini, silakan hubungi saya melalui [GitHub Issues](https://github.com/agus-triono08/personal-light-instagram/issues).
