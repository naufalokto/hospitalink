<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Mobile Layout -->
    <div class="lg:hidden">
        <!-- Header -->
        <div class="bg-white shadow-sm px-4 py-3">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}"
                    class="text-gray-600 hover:text-gray-800 transition-colors flex items-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-gray-900">My Bookings</h1>
                <div class="w-6"></div>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="p-4 space-y-4">
            @forelse($bookings as $booking)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Booking Header -->
                    <div class="p-4 border-b border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $booking->room_name }}</h3>
                                <p class="text-sm text-gray-600">{{ $booking->hospital->name }}</p>
                            </div>
                            <div class="text-right">
                                @if($booking->status === 'confirmed')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                        ✅ Dikonfirmasi
                                    </span>
                                @elseif($booking->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">
                                        ⏳ Menunggu
                                    </span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                                        ❌ Dibatalkan
                                    </span>
                                @else
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                        ✅ Selesai
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="p-4">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">No. Booking:</span>
                                <span class="font-medium">{{ $booking->booking_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-in:</span>
                                <span class="font-medium">{{ $booking->check_in_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-out:</span>
                                <span class="font-medium">{{ $booking->check_out_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Durasi:</span>
                                <span class="font-medium">{{ $booking->duration_days }} hari</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-bold text-blue-600">{{ $booking->formatted_total_price }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2 mt-4">
                            <a href="{{ route('booking.invoice', $booking->id) }}"
                                class="flex-1 bg-blue-500 text-white text-center py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                                📄 Lihat Invoice
                            </a>
                            @if($booking->status === 'confirmed' && $booking->check_in_date > now())
                                <button class="bg-red-500 text-white py-2 px-3 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                    ❌ Batalkan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">📋</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada booking</h3>
                    <p class="text-gray-600 mb-4">Anda belum melakukan booking kamar rumah sakit</p>
                    <a href="{{ route('room') }}"
                        class="bg-blue-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-600 transition-colors">
                        Lihat Kamar Tersedia
                    </a>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="mt-6">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Desktop Layout -->
    <div class="hidden lg:block">
        <div class="max-w-6xl mx-auto p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
                    <p class="text-gray-600 mt-1">Kelola semua booking kamar rumah sakit Anda</p>
                </div>
                <a href="{{ route('room') }}"
                    class="bg-blue-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-600 transition-colors">
                    + Booking Baru
                </a>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                @forelse($bookings as $booking)
                    <div class="border-b border-gray-200 last:border-b-0">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900">{{ $booking->room_name }}</h3>
                                            <p class="text-gray-600">{{ $booking->hospital->name }}</p>
                                            <p class="text-sm text-gray-500">No. {{ $booking->booking_number }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600">Check-in</p>
                                            <p class="font-medium">{{ $booking->check_in_date->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600">Check-out</p>
                                            <p class="font-medium">{{ $booking->check_out_date->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600">Total</p>
                                            <p class="font-bold text-blue-600">{{ $booking->formatted_total_price }}</p>
                                        </div>
                                        <div class="text-center">
                                            @if($booking->status === 'confirmed')
                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                                    ✅ Dikonfirmasi
                                                </span>
                                            @elseif($booking->status === 'pending')
                                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                                    ⏳ Menunggu
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
                                    </div>
                                </div>
                                <div class="flex gap-2 ml-6">
                                    <a href="{{ route('booking.invoice', $booking->id) }}"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                                        📄 Invoice
                                    </a>
                                    @if($booking->status === 'confirmed' && $booking->check_in_date > now())
                                        <button class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                            ❌ Batalkan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div class="text-gray-400 text-8xl mb-6">📋</div>
                        <h3 class="text-2xl font-medium text-gray-900 mb-4">Belum ada booking</h3>
                        <p class="text-gray-600 mb-8">Anda belum melakukan booking kamar rumah sakit</p>
                        <a href="{{ route('room') }}"
                            class="bg-blue-500 text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-600 transition-colors">
                            Lihat Kamar Tersedia
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</body>

</html>
