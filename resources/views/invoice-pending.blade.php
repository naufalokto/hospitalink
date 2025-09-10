<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Invoice Header -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">INVOICE</h1>
                        <p class="text-gray-600">No. {{ $booking->booking_number }}</p>
                        <p class="text-gray-600">Tanggal: {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ strtoupper($booking->status) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patient Information -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pasien</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Pasien</p>
                        <p class="font-semibold">{{ $booking->patient_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. Telepon</p>
                        <p class="font-semibold">{{ $booking->patient_phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold">{{ $booking->patient_email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-semibold">{{ $booking->patient_address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Hospital & Room Information -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Kamar</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Rumah Sakit</p>
                        <p class="font-semibold">{{ $booking->hospital->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tipe Kamar</p>
                        <p class="font-semibold">{{ $booking->roomType->name ?? $booking->room_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Check-in</p>
                        <p class="font-semibold">{{ $booking->check_in_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Check-out</p>
                        <p class="font-semibold">{{ $booking->check_out_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Durasi</p>
                        <p class="font-semibold">{{ $booking->duration_days }} hari</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Harga per Hari</p>
                        <p class="font-semibold">Rp {{ number_format($booking->price_per_day, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Ringkasan Pembayaran</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Harga per Hari</span>
                        <span>Rp {{ number_format($booking->price_per_day, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Durasi ({{ $booking->duration_days }} hari)</span>
                        <span>{{ $booking->duration_days }} x Rp {{ number_format($booking->price_per_day, 0, ',', '.') }}</span>
                    </div>
                    <hr class="my-3">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total Pembayaran</span>
                        <span class="text-blue-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Button -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                @if($booking->status === 'pending')
                    <button id="payButton" 
                            class="bg-green-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                        Bayar Sekarang
                    </button>
                    <p class="text-sm text-gray-600 mt-2">Klik tombol di atas untuk melakukan pembayaran melalui Midtrans</p>
                @elseif($booking->status === 'confirmed')
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg">
                        <p class="font-semibold">Pembayaran Berhasil!</p>
                        <p class="text-sm">Booking Anda telah dikonfirmasi</p>
                    </div>
                @else
                    <div class="bg-red-100 text-red-800 px-4 py-2 rounded-lg">
                        <p class="font-semibold">Pembayaran Gagal</p>
                        <p class="text-sm">Silakan coba lagi atau hubungi customer service</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.getElementById('payButton')?.addEventListener('click', async function() {
            const payButton = this;
            payButton.disabled = true;
            payButton.textContent = 'Memproses...';

            try {
                const response = await fetch(`{{ route('payment.create') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        booking_id: {{ $booking->id }},
                        amount: {{ $booking->total_price }}
                    })
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(errorText || ('HTTP ' + response.status));
                }

                const result = await response.json();

                // Prefer direct redirect_url from backend
                if (result.success && result.redirect_url) {
                    window.location.href = result.redirect_url;
                    return;
                }

                // Fallback: build VT-Web URL from snap_token
                if (result.success && result.snap_token) {
                    const base = {{ config('midtrans.is_production') ? "'https://app.midtrans.com'" : "'https://app.sandbox.midtrans.com'" }};
                    window.location.href = base + '/snap/v2/vtweb/' + result.snap_token;
                    return;
                }

                throw new Error(result.message || 'Gagal membuat pembayaran');
            } catch (error) {
                alert('Terjadi kesalahan: ' + (error.message || error));
            } finally {
                payButton.disabled = false;
                payButton.textContent = 'Bayar Sekarang';
            }
        });
    </script>
</body>
</html>
