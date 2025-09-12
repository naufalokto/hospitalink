<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $room['name'] }} - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-[#00A2FA] min-h-screen ">
    @if(request()->boolean('paid'))
    <div id="successOverlay" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div id="successModal" class="relative" style="width:254px; height:359px; background:#D9D9D9; border-radius:10px;">
            <button onclick="document.getElementById('successOverlay').remove()" aria-label="Close"
                style="position:absolute; top:10px; left:10px; background:transparent; border:none; font-size:18px; line-height:1; cursor:pointer">✕</button>
            <div style="padding:18px; text-align:center; display:flex; flex-direction:column; justify-content:center; align-items:center; height:100%; gap:12px;">
                <div style="font-weight:700;">SIMULASI PEMBAYARAN BERHASIL!</div>
                <div style="font-size:13px; line-height:1.4; text-align:center;">
                    Proses simulasi pembayaran Anda telah kami catat. Perlu diketahui bahwa ini adalah bagian dari uji coba sistem dan bukan merupakan transaksi yang sebenarnya. Fitur pembayaran penuh saat ini sedang dalam tahap pengembangan.
                </div>
                <div style="font-size:12px; font-weight:700; margin-top: 20px;">Terima Kasih.</div>
            </div>
        </div>
    </div>
    @endif
    <!-- Mobile Layout -->
    <div class="lg:hidden">
        <!-- Header -->
        <div class="px-4 py-3">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('checking', ['hospital_id' => $hospital->slug]) }}"
                    class="text-black hover:text-gray-600 transition-colors flex items-center mt-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-black text-2xl font-bold mt-5">{{ $room['name'] }}</h1>
                <div class="w-6"></div>
            </div>

            <!-- Room Image -->
            <div class="mb-3 mx-6">
                <img src="{{ asset($room['image']) }}" alt="{{ $room['name'] }}"
                    class="w-full h-48 object-cover rounded-lg">
            </div>

            <!-- Hospital Name -->
            <div class="text-center mb-1">
                <h2 class="text-black text-3xl font-bold">{{ $hospital->name }}</h2>
            </div>

            <!-- Location -->
            <p class="text-black text-md font-semibold mb-4 text-center">{{ $room['location'] }}</p>
        </div>

        <!-- Details Section -->
        <div class="bg-[#B4DBF0] px-4 py-4 rounded-t-3xl">
            <div class="space-y-4 mx-4">
                <!-- Tentang Section -->
                <div>
                    <h3 class="text-black text-lg font-bold mb-2">TENTANG</h3>
                    <p class="text-black text-sm leading-relaxed text-justify">
                        {{ $room['description'] }}
                    </p>
                </div>

                <!-- Fasilitas Section -->
                <div>
                    <h3 class="text-black text-lg font-bold mb-2">FASILITAS</h3>
                    <ul class="text-black text-sm space-y-2">
                        @foreach($room['facilities'] as $facility)
                            <li>• {{ $facility }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Harga Section -->
                <div>
                    <h3 class="text-black text-lg font-bold mb-2">HARGA</h3>
                    <p class="text-black text-sm">{{ $room['price'] }}</p>
                </div>

                <!-- Booking Button -->
                <div class="flex justify-end">
                    <a href="{{ route('payment.detail-booking', ['hospital_id' => $hospital->id, 'room_id' => $room['id']]) }}"
                        class="w-40 bg-[#0B9078] text-white font-bold py-3 rounded-xl text-base hover:bg-[#097A63] transition-colors shadow-2xl mb-7 text-center">
                        BOOKING ROOM
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Layout -->
    <div class="hidden lg:flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 xl:w-72 bg-white shadow-lg flex flex-col">
            <!-- Logo Section -->
            <div class="p-6 xl:p-8 border-b border-gray-200">
                <img src="{{ asset('images/Logo-Hospitalink3.png') }}" alt="HOSPITALINK"
                    class="h-12 xl:h-16 mx-auto">
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 xl:p-6">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center px-4 py-3 xl:py-4 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                            <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home"
                                class="w-6 h-6 xl:w-7 xl:h-7 mr-3 opacity-60 group-hover:opacity-100">
                            <span class="font-medium">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hospital') }}"
                            class="flex items-center px-4 py-3 xl:py-4 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                            <img src="{{ asset('images/icons/icon-hospital.png') }}" alt="Hospital"
                                class="w-6 h-6 xl:w-7 xl:h-7 mr-3 opacity-60 group-hover:opacity-100">
                            <span class="font-medium">Hospital</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('room') }}"
                            class="flex items-center px-4 py-3 xl:py-4 text-[#00A2FA] bg-blue-50 rounded-lg">
                            <img src="{{ asset('images/icons/icon-room.png') }}" alt="Room"
                                class="w-6 h-6 xl:w-7 xl:h-7 mr-3">
                            <span class="font-medium">Room</span>
                            <div class="ml-auto w-2 h-2 bg-[#00A2FA] rounded-full"></div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('help') }}"
                            class="flex items-center px-4 py-3 xl:py-4 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                            <img src="{{ asset('images/icons/icon-help.png') }}" alt="Help"
                                class="w-6 h-6 xl:w-7 xl:h-7 mr-3 opacity-60 group-hover:opacity-100">
                            <span class="font-medium">Help</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 bg-gradient-to-b from-[#00A2FA] via-[#00A2FA] to-[#E8F4FD]">
            <div class="max-w-5xl mx-auto p-8">
                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <a href="{{ route('checking', ['hospital_id' => $hospital->slug]) }}" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </a>
                    <h1 class="text-white text-3xl font-bold">{{ $room['name'] }}</h1>
                </div>

                <!-- Room Header Card -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 mb-8">
                    <div class="flex items-start gap-8">
                        <img src="{{ asset($room['image']) }}" alt="{{ $room['name'] }}"
                            class="w-96 h-64 object-cover rounded-xl shadow-lg">
                        <div class="flex-1">
                            <h2 class="text-white text-4xl font-bold mb-2">{{ $room['name'] }}</h2>
                            <h3 class="text-white text-2xl font-semibold mb-4">{{ $hospital->name }}</h3>
                            <div class="flex items-center gap-3 text-white/90 mb-6">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-lg">{{ $room['location'] }}</span>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                                <div class="text-white/80 text-sm mb-1">Harga per malam</div>
                                <div class="text-white text-2xl font-bold">{{ $room['price'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Room Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- About Section -->
                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm">
                            <h3 class="text-gray-900 text-xl font-bold mb-4">TENTANG</h3>
                            <p class="text-gray-700 leading-relaxed text-justify">
                                {{ $room['description'] }}
                            </p>
                        </div>

                        <!-- Facilities Section -->
                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm">
                            <h3 class="text-gray-900 text-xl font-bold mb-4">FASILITAS</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($room['facilities'] as $facility)
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                        <span class="text-gray-700">{{ $facility }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Booking Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm sticky top-8">
                            <div class="text-center mb-6">
                                <div class="text-gray-600 text-sm mb-2">Harga per malam</div>
                                <div class="text-gray-900 text-3xl font-bold mb-4">{{ $room['price'] }}</div>
                                <div class="text-gray-500 text-sm">Sudah termasuk pajak dan biaya layanan</div>
                            </div>

                            <a href="{{ route('payment.detail-booking', ['hospital_id' => $hospital->id, 'room_id' => $room['id']]) }}"
                                class="w-full bg-[#0B9078] text-white font-bold py-4 rounded-xl text-lg hover:bg-[#097A63] transition-colors shadow-lg text-center block">
                                BOOKING ROOM
                            </a>

                            <div class="mt-6 space-y-3 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Pembatalan gratis</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Konfirmasi instan</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Dukungan 24/7</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>