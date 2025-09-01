# Google OAuth Setup Guide

## Masalah yang Ditemukan
Error "invalid_client" dan "Unauthorized" menunjukkan masalah dengan konfigurasi Google OAuth.

## Solusi

### 1. Perbaiki Konfigurasi Google Console

1. **Buka Google Cloud Console**: https://console.cloud.google.com/
2. **Pilih project** yang sesuai dengan Client ID Anda
3. **Buka Credentials**: APIs & Services > Credentials
4. **Edit OAuth 2.0 Client ID** yang digunakan

### 2. Perbaiki Authorized Redirect URIs

Di Google Console, pastikan **Authorized redirect URIs** berisi:
```
http://localhost:8000/auth/google/callback
```

**Jangan gunakan**:
- `http://localhost:8000/api/auth/google/callback` (tidak valid)
- `http://localhost:8000/auth/google/callback/` (dengan trailing slash)

### 3. Periksa Client Secret

Pastikan Client Secret di file `.env` sesuai dengan yang ada di Google Console:
```env
GOOGLE_CLIENT_ID=your_client_id_here
GOOGLE_CLIENT_SECRET=your_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 4. Test Mode (Development)

Untuk testing di development, gunakan:
```
http://localhost:8000/auth/google/callback?code=test&state=test
```

Ini akan membuat test user dan login tanpa perlu Google OAuth yang sebenarnya.

### 5. Production Setup

Untuk production, pastikan:
1. **Authorized domains** di Google Console mencakup domain Anda
2. **Authorized redirect URIs** menggunakan HTTPS
3. **Client Secret** tidak di-commit ke repository

## Status Saat Ini

✅ **Test Mode**: Berfungsi dengan baik
✅ **Routes**: Sudah dikonfigurasi dengan benar
✅ **Database**: Tabel users dan sessions sudah ada
✅ **Error Handling**: Sudah ditambahkan logging yang detail

❌ **Google OAuth Real**: Perlu perbaikan konfigurasi Google Console

## Cara Test

1. **Test Mode**: Akses `http://localhost:8000/auth/google/callback?code=test&state=test`
2. **Real OAuth**: Klik tombol Google di halaman login setelah memperbaiki konfigurasi Google Console
