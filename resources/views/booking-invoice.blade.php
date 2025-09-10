<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <!-- Header Actions - Desktop -->
        <div class="no-print hidden md:flex justify-between items-center mb-6">
            <a href="{{ route('invoice') }}"
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
                @if ($booking->status === 'pending')
                    <button id="payButton-desktop"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors">
                        💳 Bayar Sekarang
                    </button>
                @endif
            </div>
        </div>

        <!-- Header Actions - Mobile -->
        <div class="md:hidden no-print">
            <div class="flex justify-between items-start mb-4">
                <a href="{{ route('invoice') }}"
                    class="flex-1 mx-1 bg-blue-500 text-white px-2 py-2 rounded-lg hover:bg-blue-600 transition-colors text-center">
                    <span class="block text-xl mb-0.5">←</span>
                    <span class="block text-xs">Daftar Booking</span>
                </a>
                <button onclick="window.print()"
                    class="flex-1 mx-1 bg-green-500 text-white px-2 py-2 rounded-lg hover:bg-green-600 transition-colors text-center">
                    <span class="block text-xl mb-0.5">🖨️</span>
                    <span class="block text-xs">Cetak</span>
                </button>
                <a href="{{ route('booking.download', $booking->id) }}"
                    class="flex-1 mx-1 bg-purple-500 text-white px-2 py-2 rounded-lg hover:bg-purple-600 transition-colors text-center">
                    <span class="block text-xl mb-0.5">📄</span>
                    <span class="block text-xs">Download</span>
                </a>
            </div>
        </div>

         <!-- Invoice Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-20 md:mb-6">
                <!-- Invoice Header -->
                <div class="bg-gradient-to-r from-[#02293E] to-[#034C74] text-white p-4 md:p-6">
                    <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 md:gap-0">
                        <div class="text-left">
                            <h2 class="text-xl md:text-2xl font-bold">INVOICE</h2>
                            <p class="text-blue-100 text-sm">No: {{ $booking->booking_number }}</p>
                            <p class="text-blue-100 text-sm">Tanggal: {{ $booking->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Invoice Content -->
                <div class="p-4 md:p-6">
                    <!-- Status Badge -->
                    <div class="mb-4 md:mb-6 text-left">
                        @if ($booking->status === 'confirmed')
                            <span
                                class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                                ✅ Dikonfirmasi
                            </span>
                        @elseif($booking->status === 'pending')
                            <span
                                class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-medium">
                                ⏳ Menunggu Konfirmasi
                            </span>
                        @elseif($booking->status === 'cancelled')
                            <span
                                class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-medium">
                                ❌ Dibatalkan
                            </span>
                        @else
                            <span
                                class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-medium">
                                ✅ Selesai
                            </span>
                        @endif
                    </div>

                    <!-- Patient Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                
                                Informasi Pasien
                            </h3>
                            <div class="space-y-3">
                                <p class="flex flex-col md:flex-row md:items-center">
                                    <span class="font-medium md:w-24">Nama:</span>
                                    <span class="text-gray-700">{{ $booking->patient_name }}</span>
                                </p>
                                @if ($booking->patient_email)
                                    <p class="flex flex-col md:flex-row md:items-center">
                                        <span class="font-medium md:w-24">Email:</span>
                                        <span class="text-gray-700">{{ $booking->patient_email }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                
                                Informasi Booking
                            </h3>
                            <div class="space-y-3">
                                <p class="flex flex-col md:flex-row md:items-center">
                                    <span class="font-medium md:w-24">Rumah Sakit:</span>
                                    <span class="text-gray-700">{{ $booking->hospital->name }}</span>
                                </p>
                                <p class="flex flex-col md:flex-row md:items-center">
                                    <span class="font-medium md:w-24">Tipe Kamar:</span>
                                    <span class="text-gray-700">{{ $booking->room_name }}</span>
                                </p>
                                <p class="flex flex-col md:flex-row md:items-center">
                                    <span class="font-medium md:w-24">Check-in:</span>
                                    <span class="text-gray-700">{{ $booking->check_in_date->format('d/m/Y') }}</span>
                                </p>
                                <p class="flex flex-col md:flex-row md:items-center">
                                    <span class="font-medium md:w-24">Check-out:</span>
                                    <span class="text-gray-700">{{ $booking->check_out_date->format('d/m/Y') }}</span>
                                </p>
                                <p class="flex flex-col md:flex-row md:items-center">
                                    <span class="font-medium md:w-24">Durasi:</span>
                                    <span class="text-gray-700">{{ $booking->duration_days }} hari</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                           
                            Rincian Pembayaran
                        </h3>
                        <div class="space-y-3">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                <span class="text-gray-600">{{ $booking->room_name }}</span>
                                <span class="font-medium mt-1 md:mt-0">{{ $booking->formatted_price }}</span>
                            </div>
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                <span class="text-gray-600">Durasi {{ $booking->duration_days }} hari</span>
                                <span class="font-medium mt-1 md:mt-0">× {{ $booking->duration_days }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                <span class="font-bold text-lg">Total Pembayaran:</span>
                                <span
                                    class="text-blue-600 font-bold text-lg mt-1 md:mt-0">{{ $booking->formatted_total_price }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                   
                                    Kontak Rumah Sakit
                                </h4>
                                <p class="text-gray-600 mb-2">{{ $booking->hospital->name }}</p>
                                @if ($booking->hospital->address)
                                    <p class="text-gray-600">{{ $booking->hospital->address }}</p>
                                @endif
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                   
                                    Kontak HOSPITALINK
                                </h4>
                                <p class="text-gray-600 flex items-center mb-2">
                                    <span class="mr-2">📧</span>
                                    ehospital.app@gmail.com
                                </p>
                                <p class="text-gray-600 flex items-center">
                                    <span class="mr-2">📞</span>
                                    081-354-011
                                </p>
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
    @if (request()->boolean('paid') || $booking->status === 'confirmed')
        <div id="paidModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 no-print">
            <div class="bg-white rounded-xl shadow-2xl w-11/12 max-w-md p-6 text-center">
                <div class="mx-auto mb-4 w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Pembayaran Berhasil</h3>
                <p class="text-gray-600 mb-6">Booking <span
                        class="font-semibold">#{{ $booking->booking_number }}</span> telah dikonfirmasi.</p>
                <div class="flex items-center justify-center gap-3">
                    <button onclick="document.getElementById('paidModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300">Tutup</button>
                    <button onclick="window.print()"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Cetak Invoice</button>
                </div>
            </div>
        </div>
        <script>
            // Ensure modal is visible on load when condition matched
            window.addEventListener('load', function() {
                const m = document.getElementById('paidModal');
                if (m) m.classList.remove('hidden');
            });
        </script>
    @endif

    @if ($booking->status === 'pending')
        <script>
            document.querySelectorAll('#payButton-desktop, #payButton-mobile')?.addEventListener('click', async function() {
                const btn = this;
                btn.disabled = true;
                btn.textContent = 'Memproses...';
                try {
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '{{ csrf_token() }}';
                    const res = await fetch(`{{ route('payment.create') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            booking_id: {{ $booking->id }},
                            amount: {{ $booking->total_price }}
                        })
                    });
                    if (!res.ok) {
                        const txt = await res.text();
                        alert('Gagal membuat pembayaran: ' + txt);
                        btn.disabled = false;
                        btn.textContent = '💳 Bayar Sekarang';
                        return;
                    }
                    const json = await res.json();
                    if (json.success && json.redirect_url) {
                        window.location.href = json.redirect_url;
                    } else {
                        alert('Gagal membuat pembayaran: ' + (json.message || 'Unknown error'));
                        btn.disabled = false;
                        btn.textContent = '💳 Bayar Sekarang';
                    }
                } catch (e) {
                    alert('Terjadi kesalahan: ' + e.message);
                    btn.disabled = false;
                    btn.textContent = '💳 Bayar Sekarang';
                }
            });
        </script>
    @endif
    </div>
    </div>
    </div>

        <!-- Mobile Pay Button -->
    @if ($booking->status === 'pending')
        <div class="md:hidden fixed bottom-0 left-0 right-0 p-2 mb-4">
            <button id="payButton-mobile"
                class="w-full bg-[#02293E] text-white px-3 py-2.5 rounded-2xl hover:bg-[#034C74] transition-colors text-base font-medium">
                Bayar
            </button>
        </div>
    @endif
    </div>

    @if ($booking->status === 'pending')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const payButtons = document.querySelectorAll('#payButton-desktop, #payButton-mobile');

        payButtons.forEach(btn => {
            btn.addEventListener('click', async function () {
                this.disabled = true;
                this.textContent = 'Memproses...';

                try {
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '{{ csrf_token() }}';

                    const res = await fetch(`{{ route('payment.create') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            booking_id: {{ $booking->id }},
                            amount: {{ $booking->total_price }}
                        })
                    });

                    if (!res.ok) {
                        const txt = await res.text();
                        alert('Gagal membuat pembayaran: ' + txt);
                        this.disabled = false;
                        this.textContent = '💳 Bayar Sekarang';
                        return;
                    }

                    const json = await res.json();
                    if (json.success && json.redirect_url) {
                        window.location.href = json.redirect_url;
                    } else {
                        alert('Gagal membuat pembayaran: ' + (json.message || 'Unknown error'));
                        this.disabled = false;
                        this.textContent = '💳 Bayar Sekarang';
                    }
                } catch (e) {
                    alert('Terjadi kesalahan: ' + e.message);
                    this.disabled = false;
                    this.textContent = '💳 Bayar Sekarang';
                }
            });
        });
    });
</script>
@endif

</body>

</html>
