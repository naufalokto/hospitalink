<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Lunas - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Invoice Header -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">INVOICE LUNAS</h1>
                        <p class="text-gray-600">No. {{ $booking->booking_number }}</p>
                        <p class="text-gray-600">Tanggal: {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        @if($payment)
                        <p class="text-gray-600">Payment ID: {{ $payment->order_id }}</p>
                        <p class="text-gray-600">Tanggal Bayar: {{ $payment->updated_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                            LUNAS
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

            <!-- Payment Information -->
            @if($payment)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pembayaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Metode Pembayaran</p>
                        <p class="font-semibold">{{ strtoupper($payment->payment_type) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status Pembayaran</p>
                        <p class="font-semibold text-green-600">{{ strtoupper($payment->status) }}</p>
                    </div>
                    @if($payment->va_number)
                    <div>
                        <p class="text-sm text-gray-600">Virtual Account</p>
                        <p class="font-semibold">{{ $payment->va_number }}</p>
                    </div>
                    @endif
                    @if($payment->transaction_id)
                    <div>
                        <p class="text-sm text-gray-600">Transaction ID</p>
                        <p class="font-semibold">{{ $payment->transaction_id }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

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
                        <span class="text-green-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                <div class="text-green-600 text-6xl mb-4">✓</div>
                <h3 class="text-xl font-semibold text-green-800 mb-2">Pembayaran Berhasil!</h3>
                <p class="text-green-700">Booking Anda telah dikonfirmasi. Silakan datang ke rumah sakit sesuai jadwal yang telah ditentukan.</p>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-center space-y-4">
                <button onclick="window.print()" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Cetak Invoice
                </button>
                <a href="{{ route('my-bookings') }}" 
                   class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 inline-block">
                    Lihat Semua Booking
                </a>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body { background: white; }
            .no-print { display: none; }
        }
    </style>
</body>
</html>
