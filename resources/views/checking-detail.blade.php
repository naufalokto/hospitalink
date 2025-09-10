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

<body class="bg-[#00A2FA] min-h-screen">
    @if(request()->boolean('paid'))
    <div id="successOverlay" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div id="successModal" class="relative" style="width:254px; height:359px; background:#D9D9D9; border-radius:10px;">
            <button onclick="document.getElementById('successOverlay').remove()" aria-label="Close"
                style="position:absolute; top:10px; left:10px; background:transparent; border:none; font-size:18px; line-height:1; cursor:pointer">✕</button>
            <div style="padding:18px; text-align:center; display:flex; flex-direction:column; justify-content:center; align-items:center; height:100%; gap:12px;">
                <div style="font-weight:700;">BOOKING ROOM SUCCESS</div>
                <div style="font-size:12px; line-height:1.4;">
                    SILAHKAN MENGHUBUNGI NOMOR DIBAWAH INI UNTUK MELAKUKAN KONFIRMASI BOOKING ROOM DAN PEMBAYARAN BOOKING ROOM
                </div>
                <div style="font-size:12px; line-height:1.4;">
                    ({{ $hospital->name }})<br>
                    BPJS - 0318961649<br>
                    Non BPJS - 0218961649
                </div>
                <div style="font-size:12px; font-weight:700;">Berlaku hingga 10 menit</div>
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
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <img src="/images/logo-hospitalink.png" alt="HOSPITALINK" class="h-8 mb-8">

                <nav class="space-y-2">
                    <a href="{{ route('home') }}"
                        class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                        Home
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Hospital
                    </a>
                    <a href="{{ route('room') }}"
                        class="flex items-center gap-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 5a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Room
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-1.106-1.106A6.003 6.003 0 004 10c0 .639.1 1.255.283 1.836l1.875-1.875zM15.493 6.344a6.003 6.003 0 00-8.835 1.843l1.474 1.474a3.98 3.98 0 012.252-.763 4.01 4.01 0 013.161-.763l1.948-1.791z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Help
                    </a>
                </nav>
            </div>
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