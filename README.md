# SIMPLE TODO API PHP APPLICATION

Aplikasi Simple TODO API adalah aplikasi yang memungkinkan pengguna untuk mengelola daftar tugas (TODO) mereka dengan mudah. Aplikasi ini dibangun menggunakan PHP 8.3+, Composer, dan PostgreSQL sebagai basis data. API ini dirancang untuk memberikan antarmuka yang sederhana dan efisien untuk operasi CRUD (Create, Read, Update, Delete) pada tugas.

## Prerequisites
Sebelum memulai, pastikan Anda memiliki hal-hal berikut:
- PHP 8.3+
- Composer
- X-Debug (untuk debugging)
- PostgreSQL (sebagai basis data)

## Instalasi (Without Docker)
Ikuti langkah-langkah berikut untuk menginstal dan menjalankan aplikasi:

1. **Clone repositori:**
   ```bash
   git clone https://github.com/mocakbarmaulana/todo-api-php.git
   ```
2. Open Project
   ```bash
   code todo-api-php
   ```
3. Instal dependensi menggunakan Composer:
   ```bash
   composer install
   ```
4. Konfigurasi database:
    - Buat database PostgreSQL baru untuk aplikasi ini.
    - Salin file .env.example ke .env dan sesuaikan pengaturan database Anda.

       ```bash
       DB_CONNECTION=pgsql
       DB_HOST=localhost
       DB_PORT=5432
       DB_DATABASE=your_database_name
       DB_USERNAME=your_username
       DB_PASSWORD=your_password
       ```
5. Jalankan migrasi untuk membuat tabel yang diperlukan:
   ```bash
   php artisan migrate
   ```
6. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
7. Buka browser anda:
   ```
   http://localhost:8000
   ```

## Api Dokumentasi
buka `http://localhost:8000/docs` untuk melihat kesuluruhan list api yang dimiliki.

