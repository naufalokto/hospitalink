<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOSPITALINK - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full bg-[#00A2FA] font-sans">

    <div class="lg:hidden min-h-screen flex flex-col">
        <!-- ... existing mobile code ... -->
        <div class="pt-8 pb-4 px-6 text-center -mt-4">
            <img src="{{ asset('images/Logo-Hospitalink3.png') }}" alt="HOSPITALINK" class="mx-auto h-48 mb-1">
        </div>

        <div class="px-6 mb-4" x-data="carousel()">
            <div class="flex-1 bg-[#B4DBF1] rounded-t-3xl px-6 pt-6 pb-40 -mx-6 ">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-2xl font-bold text-gray-800 pl-4">HOSPITAL INFORMATION</h2>
                    <a href="{{ route('invoice') }}" class="block">
                        <img src="{{ asset('images/icons/icon-notif.png') }}" alt="Notifications" class="w-7 h-9">
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach ($hospitals as $hospital)
                        <a href="{{ route('hospital.detail', ['slug' => $hospital->slug]) }}" class="block">
                            <div
                                class="bg-[#9AC1D6] rounded-2xl p-4 shadow-md mx-auto hover:bg-[#8BB5CD] transition-colors">
                                <div class="flex items-center gap-4 h-full">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-sm text-gray-800 mb-2 leading-tight line-clamp-2">
                                            {{ $hospital->name }}</h3>
                                        <p class="text-xs text-gray-600 mb-1 line-clamp-2">
                                            {{ Str::limit($hospital->address, 50) }}</p>
                                        <p class="text-xs text-blue-600 truncate">{{ $hospital->website_url }}</p>
                                    </div>
                                    <div class="flex-shrink-0 w-28">
                                        <div class="aspect-[4/3] bg-gray-200 rounded-lg overflow-hidden">
                                            <img src="{{ \Illuminate\Support\Str::startsWith($hospital->image_url, ['http://', 'https://']) ? $hospital->image_url : (\Illuminate\Support\Str::startsWith($hospital->image_url, ['storage/', 'images/']) ? asset($hospital->image_url) : asset('storage/' . $hospital->image_url)) }}"
                                                alt="{{ $hospital->name }}" class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200">
                <div class="flex items-center justify-around py-2">
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2">
                        <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home"
                            class="w-6 h-6 mb-1 opacity-50">
                        <span class="text-xs text-gray-400">Home</span>
                    </a>
                    <div class="flex flex-col items-center py-2">
                        <img src="{{ asset('images/icons/icon-hospital.png') }}" alt="Hospital" class="w-7 h-7 mb-1">
                        <span class="text-xs text-[#000000]">Hospital</span>
                        <div class="w-1 h-1 bg-[#00A2FA] rounded-full mt-1"></div>
                    </div>
                    <a href="{{ route('room') }}" class="flex flex-col items-center py-2">
                        <img src="{{ asset('images/icons/icon-room.png') }}" alt="Room"
                            class="w-7 h-7 mb-1 opacity-50">
                        <span class="text-xs text-gray-400">Room</span>
                    </a>
                    <a href="{{ route('help') }}" class="flex flex-col items-center py-2">
                        <img src="{{ asset('images/icons/icon-help.png') }}" alt="Help"
                            class="w-7 h-7 mb-1 opacity-50">
                        <span class="text-xs text-gray-400">Help</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- <CHANGE> Enhanced desktop layout with better structure and responsive design -->
    <div class="hidden lg:flex h-screen bg-gray-50">
        <!-- Sidebar Navigation -->
        <div class="w-64 xl:w-72 bg-white shadow-lg flex flex-col">
            <!-- Logo Section -->
            <div class="p-6 xl:p-8 border-b border-gray-200">
                <img src="{{ asset('images/Logo-Hospitalink3.png') }}" alt="HOSPITALINK" class="h-12 xl:h-16 mx-auto">
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
                            class="flex items-center px-4 py-3 xl:py-4 text-[#00A2FA] bg-blue-50 rounded-lg">
                            <img src="{{ asset('images/icons/icon-hospital.png') }}" alt="Hospital"
                                class="w-6 h-6 xl:w-7 xl:h-7 mr-3">
                            <span class="font-medium">Hospital</span>
                            <div class="ml-auto w-2 h-2 bg-[#00A2FA] rounded-full"></div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('room') }}"
                            class="flex items-center px-4 py-3 xl:py-4 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                            <img src="{{ asset('images/icons/icon-room.png') }}" alt="Room"
                                class="w-6 h-6 xl:w-7 xl:h-7 mr-3 opacity-60 group-hover:opacity-100">
                            <span class="font-medium">Room</span>
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
                        <h1 class="text-3xl xl:text-4xl font-bold text-white mb-2">HOSPITAL INFORMATION</h1>
                        <p class="text-lg xl:text-xl text-blue-100">Temukan rumah sakit terbaik untuk kebutuhan
                            kesehatan Anda</p>
                    </div>

                    <!-- Search Bar -->
                    <div class="max-w-md mx-auto">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" placeholder="Cari rumah sakit..."
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
                        @foreach ($hospitals as $hospital)
                            <a href="{{ route('hospital.detail', ['slug' => $hospital->slug]) }}"
                                class="block group">
                                <div
                                    class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer">
                                    <div class="flex gap-6">
                                        <div class="flex-1">
                                            <h3
                                                class="font-bold text-lg text-gray-800 mb-3 leading-tight group-hover:text-[#00A2FA] transition-colors">
                                                {{ $hospital->name }}
                                            </h3>
                                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                                {{ $hospital->address }}</p>
                                            <p class="text-sm text-blue-600 mb-4 hover:underline">
                                                {{ $hospital->website_url }}</p>
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                    </path>
                                                </svg>
                                                <span>Lihat Detail</span>
                                            </div>
                                        </div>
                                        <div class="w-32 h-24 flex-shrink-0">
                                            <img src="{{ \Illuminate\Support\Str::startsWith($hospital->image_url, ['http://', 'https://']) ? $hospital->image_url : (\Illuminate\Support\Str::startsWith($hospital->image_url, ['storage/', 'images/']) ? asset($hospital->image_url) : asset('storage/' . $hospital->image_url)) }}"
                                                alt="{{ $hospital->name }}"
                                                class="w-full h-full object-cover rounded-xl group-hover:scale-110 transition-transform duration-300">
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function carousel() {
            return {
                currentSlide: 0,
                slides: [1, 2, 3],
                autoSlideInterval: null,

                init() {
                    this.startAutoSlide();
                },

                startAutoSlide() {
                    this.autoSlideInterval = setInterval(() => {
                        this.nextSlide();
                    }, 4000);
                },

                stopAutoSlide() {
                    if (this.autoSlideInterval) {
                        clearInterval(this.autoSlideInterval);
                    }
                },

                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                },

                goToSlide(index) {
                    this.currentSlide = index;
                    this.stopAutoSlide();
                    setTimeout(() => {
                        this.startAutoSlide();
                    }, 5000);
                }
            }
        }
    </script>
</body>

</html>
