<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full bg-[#00A2FA]">
    <!-- Mobile Layout -->
    <div class="lg:hidden min-h-screen flex flex-col">
        <!-- ... existing mobile code ... -->
        <div class="bg-[#00A2FA] px-4 pt-8 pb-6">
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('images/Logo-Hospitalink3.png') }}" alt="Hospitalink" class="mx-auto h-36 mb-0">
            </div>
        </div>

        <div class="bg-[#00A2FA] px-4 pb-4 mb-2 -mt-6 flex justify-center">
            <div class="relative w-44" x-data="{ open: false, selectedService: '{{ request()->query('service', 'ALL') }}' }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between bg-white px-3 py-1.5 rounded-3xl text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 shadow-md hover:shadow-lg transition-all duration-200">
                    <span class="font-medium" x-text="selectedService">ALL</span>
                    <svg :class="{ 'transform rotate-180': open }" class="w-4 h-4 ml-2 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="absolute left-0 right-0 mt-1 bg-white rounded-xl shadow-lg z-50">
                    <div class="py-1">
                        <a href="{{ route('room', ['service' => 'ALL']) }}"
                            class="block w-full text-left px-3 py-1.5 text-sm rounded-xl text-gray-700 hover:bg-gray-100 hover:text-gray-900">ALL</a>
                        <a href="{{ route('room', ['service' => 'IGD 24 Jam']) }}"
                            class="block w-full text-left px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">IGD
                            24 Jam</a>
                        <a href="{{ route('room', ['service' => 'Poliklinik']) }}"
                            class="block w-full text-left px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">Poliklinik
                            Spesialis</a>
                        <a href="{{ route('room', ['service' => 'Farmasi 24 Jam']) }}"
                            class="block w-full text-left px-3 py-1.5 text-sm rounded-xl text-gray-700 hover:bg-gray-100 hover:text-gray-900">Farmasi
                            24 Jam</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 mb-1" x-data="carousel()">
            <div class="flex-1 bg-[#B4DBF1] rounded-t-3xl px-6 pt-6 pb-40 -mx-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">CHECKING AND BOOKING ROOM</h2>
                    <div class="relative pr-1 pt-1">
                        <a href="{{ route('invoice') }}" class="block">
                            <img src="{{ asset('images/icons/icon-notif.png') }}" alt="Notifications" class="w-7 h-9">
                        </a>
                    </div>
                </div>

                <div class="space-y-4 pb-20">
                    @foreach ($hospitalsData as $hospital)
                        <a href="{{ route('checking', ['hospital_id' => $hospital['slug']]) }}" class="block">
                            <div
                                class="bg-[#9AC1D6] rounded-2xl p-4 shadow-md hover:shadow-lg transition-all duration-200 hover:bg-[#8BB5CD] cursor-pointer">
                                <div class="flex items-center gap-4 h-full">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-800 mb-1 line-clamp-1">{{ $hospital['name'] }}
                                        </h3>
                                        <div
                                            class="text-xs font-medium px-0 py-1 rounded mb-2 inline-block {{ $hospital['status_class'] }}">
                                            {{ $hospital['status'] }}</div>
                                        <p class="text-xs text-blue-600 mb-1 truncate">
                                            {{ Str::limit($hospital['website_url'], 28) }}</p>
                                        <p class="text-xs text-gray-500">{{ date('l, d F Y') }}</p>
                                    </div>
                                    <div class="flex-shrink-0 w-28">
                                        <div class="aspect-[4/3] bg-gray-200 rounded-lg overflow-hidden">
                                            <img src="{{ asset($hospital['image_url']) }}"
                                                alt="{{ $hospital['name'] }}" class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200">
            <div class="flex items-center justify-around py-2">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2">
                    <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home"
                        class="w-6 h-6 mb-1 opacity-50">
                    <span class="text-xs text-gray-400">Home</span>
                </a>
                <a href="{{ route('hospital') }}" class="flex flex-col items-center py-2">
                    <img src="{{ asset('images/icons/icon-hospital.png') }}" alt="Hospital"
                        class="w-7 h-7 mb-1 opacity-50">
                    <span class="text-xs text-gray-400">Hospital</span>
                </a>
                <a href="{{ route('room') }}" class="flex flex-col items-center py-2">
                    <img src="{{ asset('images/icons/icon-room.png') }}" alt="Room" class="w-7 h-7 mb-1">
                    <span class="text-xs text-[#000000]">Room</span>
                    <div class="w-1 h-1 bg-[#00A2FA] rounded-full mt-1"></div>
                </a>
                <a href="{{ route('help') }}" class="flex flex-col items-center py-2">
                    <img src="{{ asset('images/icons/icon-help.png') }}" alt="Help"
                        class="w-7 h-7 mb-1 opacity-50">
                    <span class="text-xs text-gray-400">Help</span>
                </a>
            </div>
        </div>
    </div>

    <!-- <CHANGE> Enhanced desktop layout with improved design and responsiveness -->
    <div class="hidden lg:flex h-screen bg-gray-50">
        <!-- Sidebar Navigation -->
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

        <!-- Main Content Area -->
        <div class="flex-1 overflow-y-auto">
            <!-- Header Section -->
            <div class="bg-[#00A2FA] px-8 xl:px-12 py-8 xl:py-12">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl xl:text-4xl font-bold text-white mb-2">CHECKING AND BOOKING ROOM</h1>
                        <p class="text-lg xl:text-xl text-blue-100">Periksa ketersediaan kamar dan lakukan pemesanan
                        </p>
                    </div>

                    <!-- Search Bar -->
                    <div class="max-w-md mx-auto">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" placeholder="Cari rekomendasi"
                                class="w-full pl-12 pr-4 py-3 xl:py-4 bg-white rounded-full text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 shadow-lg">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hospital Cards Section -->
            <div class="px-8 xl:px-12 py-8 xl:py-12 bg-gray-50">
                <div class="max-w-6xl mx-auto">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl xl:text-3xl font-bold text-gray-800">Daftar Rumah Sakit</h2>
                        <a href="{{ route('my-bookings') }}"
                            class="relative p-2 hover:bg-gray-200 rounded-full transition-colors">
                            <img src="{{ asset('images/icons/icon-notif.png') }}" alt="Notifications"
                                class="w-7 h-9 xl:w-7 xl:h-7">
                        </a>
                    </div>

                    <!-- Hospital Grid -->
                    <div class="grid gap-6">
                        @foreach ($hospitalsData as $hospital)
                            <div
                                class="bg-gray-50 rounded-2xl p-6 shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 pr-6">
                                        <h3
                                            class="text-xl font-semibold text-gray-800 mb-2 group-hover:text-[#00A2FA] transition-colors">
                                            {{ $hospital['name'] }}
                                        </h3>

                                        <div
                                            class="text-sm font-medium px-3 py-1 rounded mb-3 inline-block {{ $hospital['total_rooms'] > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                            {{ $hospital['status'] }}
                                        </div>

                                        <p class="text-sm text-blue-600 mb-2 hover:underline">
                                            {{ $hospital['website_url'] }}</p>
                                        <p class="text-sm text-gray-500">{{ date('l, d F Y') }}</p>
                                    </div>

                                    <div class="w-32 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                        <img src="{{ asset($hospital['image_url']) }}" alt="{{ $hospital['name'] }}"
                                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
    </div>


</body>

</html>
