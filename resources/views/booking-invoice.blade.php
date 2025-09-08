<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Booking - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto p-4">
        <!-- Header Actions -->
        <div class="no-print flex justify-between items-center mb-6">
            <a href="{{ route('my-bookings') }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                ← Kembali ke Daftar Booking
            </a>
            <div class="space-x-2">
                <button onclick="window.print()" 
                        class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                    🖨️ Cetak Invoice
                </button>
                <a href="{{ route('booking.download', $booking->id) }}" 
                   class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors">
                    📄 Download PDF
                </a>
            </div>
        </div>

        <!-- Invoice Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Invoice Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold">HOSPITALINK</h1>
                        <p class="text-blue-100 mt-1">Sistem Informasi Rumah Sakit</p>
                    </div>
                    <div class="text-right">
                        <h2 class="text-2xl font-bold">INVOICE</h2>
                        <p class="text-blue-100">No: {{ $booking->booking_number }}</p>
                        <p class="text-blue-100">Tanggal: {{ $booking->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Content -->
            <div class="p-6">
                <!-- Status Badge -->
                <div class="mb-6">
                    @if($booking->status === 'confirmed')
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            ✅ Dikonfirmasi
                        </span>
                    @elseif($booking->status === 'pending')
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                            ⏳ Menunggu Konfirmasi
                        </span>
                    @elseif($booking->status === 'cancelled')
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                            ❌ Dibatalkan
                        </span>
                    @else
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            ✅ Selesai
                        </span>
                    @endif
                </div>

                <!-- Patient Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Informasi Pasien</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Nama:</span> {{ $booking->patient_name }}</p>
                            <p><span class="font-medium">Telepon:</span> {{ $booking->patient_phone }}</p>
                            @if($booking->patient_email)
                                <p><span class="font-medium">Email:</span> {{ $booking->patient_email }}</p>
                            @endif
                            @if($booking->patient_address)
                                <p><span class="font-medium">Alamat:</span> {{ $booking->patient_address }}</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Informasi Booking</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Rumah Sakit:</span> {{ $booking->hospital->name }}</p>
                            <p><span class="font-medium">Tipe Kamar:</span> {{ $booking->room_name }}</p>
                            <p><span class="font-medium">Check-in:</span> {{ $booking->check_in_date->format('d/m/Y') }}</p>
                            <p><span class="font-medium">Check-out:</span> {{ $booking->check_out_date->format('d/m/Y') }}</p>
                            <p><span class="font-medium">Durasi:</span> {{ $booking->duration_days }} hari</p>
                        </div>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Rincian Pembayaran</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">{{ $booking->room_name }}</span>
                            <span class="font-medium">{{ $booking->formatted_price }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Durasi {{ $booking->duration_days }} hari</span>
                            <span class="font-medium">× {{ $booking->duration_days }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total Pembayaran:</span>
                            <span class="text-blue-600">{{ $booking->formatted_total_price }}</span>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($booking->notes)
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Catatan Khusus</h3>
                        <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $booking->notes }}</p>
                    </div>
                @endif

                <!-- Payment Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-lg font-bold text-blue-900 mb-2">📋 Instruksi Pembayaran</h3>
                    <div class="text-blue-800 space-y-1">
                        <p>1. Silakan datang ke rumah sakit pada tanggal check-in yang telah ditentukan</p>
                        <p>2. Tunjukkan invoice ini kepada petugas resepsionis</p>
                        <p>3. Lakukan pembayaran sesuai dengan total yang tertera</p>
                        <p>4. Setelah pembayaran, Anda akan diarahkan ke kamar yang telah dipesan</p>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-bold text-gray-900 mb-2">Kontak Rumah Sakit</h4>
                            <p class="text-gray-600">{{ $booking->hospital->name }}</p>
                            @if($booking->hospital->address)
                                <p class="text-gray-600">{{ $booking->hospital->address }}</p>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-2">Kontak HOSPITALINK</h4>
                            <p class="text-gray-600">📧 info@hospitalink.com</p>
                            <p class="text-gray-600">📞 +62 123 456 7890</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-6 no-print">
            <p>Terima kasih telah menggunakan layanan HOSPITALINK</p>
            <p>Invoice ini dibuat secara otomatis pada {{ $booking->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    {{-- Payment Success Modal --}}
    @if(request()->boolean('paid') || ($booking->status === 'confirmed'))
    <div id="paidModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 no-print">
        <div class="bg-white rounded-xl shadow-2xl w-11/12 max-w-md p-6 text-center">
            <div class="mx-auto mb-4 w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">Pembayaran Berhasil</h3>
            <p class="text-gray-600 mb-6">Booking <span class="font-semibold">#{{ $booking->booking_number }}</span> telah dikonfirmasi.</p>
            <div class="flex items-center justify-center gap-3">
                <button onclick="document.getElementById('paidModal').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300">Tutup</button>
                <button onclick="window.print()" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Cetak Invoice</button>
            </div>
        </div>
    </div>
    <script>
        // Ensure modal is visible on load when condition matched
        window.addEventListener('load', function () {
            const m = document.getElementById('paidModal');
            if (m) m.classList.remove('hidden');
        });
    </script>
    @endif
</body>

</html>
