# Facebook OAuth Setup Guide

## Masalah yang Ditemukan
Error: "Invalid Scopes: email. This message is only shown to developers."

## Solusi yang Diterapkan

### 1. Menghapus Scope Eksplisit
Facebook OAuth tidak memerlukan scope eksplisit untuk `public_profile` karena ini adalah default scope.

### 2. Menggunakan Stateless Mode
Menambahkan `stateless()` untuk konsistensi dengan Google OAuth.

### 3. Konfigurasi Facebook App yang Diperlukan

#### Di Facebook Developer Console:
1. Buka [Facebook Developers](https://developers.facebook.com/)
2. Pilih aplikasi Anda
3. Pergi ke **Facebook Login** > **Settings**
4. Pastikan **Valid OAuth Redirect URIs** berisi:
   ```
   http://localhost:8000/auth/facebook/callback
   ```

#### Permissions yang Diperlukan:
- **public_profile** (default, tidak perlu request eksplisit)
- **email** (opsional, hanya jika diperlukan)

#### App Review (Jika Diperlukan):
- Jika aplikasi dalam mode development, hanya pengembang yang terdaftar bisa login
- Untuk production, perlu submit untuk review permissions

### 4. Environment Variables
Pastikan di `.env`:
```env
FACEBOOK_CLIENT_ID=your_app_id
FACEBOOK_CLIENT_SECRET=your_app_secret
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
```

### 5. Testing
1. Pastikan server Laravel berjalan di `http://localhost:8000`
2. Test dengan akun Facebook yang terdaftar sebagai developer
3. Check logs di `storage/logs/laravel.log` untuk debugging

## Troubleshooting

### Jika Masih Error "Invalid Scopes":
1. Pastikan tidak ada scope yang didefinisikan di kode
2. Check Facebook App settings
3. Pastikan redirect URI sesuai

### Jika Error "App Not Setup":
1. Pastikan Facebook App sudah diaktifkan
2. Check App ID dan Secret di .env
3. Pastikan domain sudah ditambahkan di Facebook App

### Jika Error "Redirect URI Mismatch":
1. Pastikan redirect URI di .env sama dengan di Facebook App
2. Check apakah ada trailing slash atau tidak
3. Pastikan menggunakan http (bukan https) untuk localhost
