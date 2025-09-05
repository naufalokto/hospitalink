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
        <!-- Header with Logo and Illustration -->
        <div class="bg-[#00A2FA] px-4 pt-8 pb-6">

            <!-- Logo -->
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('images/Logo-Hospitalink3.png') }}" alt="Hospitalink" class="mx-auto h-36 mb-0">
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-[#00A2FA] px-4 pb-4 mb-2 -mt-6 flex justify-center">
            <div class="relative w-3/4">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" placeholder="Cari rekomendasi"
                    class="w-full pl-10 pr-4 py-2 bg-white rounded-full text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 shadow-md hover:shadow-lg transition-shadow duration-200">
            </div>
        </div>

        <!-- Content Area -->
        <div class="px-6 mb-1" x-data="carousel()">
            <div class="flex-1 bg-[#B4DBF1] rounded-t-3xl px-6 pt-6 pb-10 -mx-6">
                <!-- Section Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">CHECKING AND BOOKING ROOM</h2>
                    <div class="relative pr-1 pt-1">
                        <img src="{{ asset('images/icons/icon-notif.png') }}" alt="Notifications" class="w-7 h-9">

                    </div>
                </div>

                <!-- Hospital Cards -->
                <div class="space-y-4 pb-20">
                    @foreach($hospitalsData as $hospital)
                    <a href="{{ route('checking', ['hospital_id' => $hospital['slug']]) }}" class="block">
                        <div
                            class="bg-[#9AC1D6] rounded-2xl p-4 shadow-md hover:shadow-lg h-[120px] transition-all duration-200 hover:bg-[#8BB5CD] cursor-pointer">
                            <div class="flex justify-between items-start h-full">
                                <div class="flex-1 pr-4 flex flex-col">
                                    <h3 class="font-bold text-gray-800 mb-1 line-clamp-1">{{ $hospital['name'] }}</h3>
                                    <div class="text-xs font-medium px-0 py-1 rounded mb-2 inline-block {{ $hospital['status_class'] }}">
                                        {{ $hospital['status'] }}
                                    </div>
                                    <p class="text-xs text-blue-600 mb-1 truncate">{{ $hospital['website_url'] }}</p>
                                    <p class="text-xs text-gray-500 mt-auto">{{ date('l, d F Y') }}</p>
                                </div>
                                <div class="relative w-28 flex-shrink-0">
                                    <div class="aspect-[4/3] bg-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ asset($hospital['image_url']) }}" alt="{{ $hospital['name'] }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach

                </div>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200">
            <div class="flex items-center justify-around py-2">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2">
                    <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home" class="w-6 h-6 mb-1 opacity-50">
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

    <!-- Desktop Layout -->
    <div class="hidden lg:flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b">
                <img src="{{ asset('images/logo-hospitalink.png') }}" alt="HOSPITALINK" class="h-8">
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('home') }}"
                            class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-home mr-3"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-hospital mr-3"></i>
                            Hospital
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('room') }}"
                            class="flex items-center px-4 py-3 text-blue-600 bg-blue-50 rounded-lg">
                            <i class="fas fa-bed mr-3"></i>
                            Room
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-question-circle mr-3"></i>
                            Help
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 bg-gray-50 overflow-auto">
            <div class="max-w-4xl mx-auto p-8">
                <!-- Header -->
                <div class="bg-[#00A2FA] rounded-2xl p-8 mb-8">
                    <div class="text-center mb-6">
                        <img src="{{ asset('images/logo-hospitalink.png') }}" alt="HOSPITALINK"
                            class="h-10 mx-auto mb-4">
                        <img src="/placeholder.svg?height=150&width=350" alt="Hospital Room Illustration"
                            class="mx-auto">
                    </div>

                    <!-- Search Bar -->
                    <div class="relative max-w-md mx-auto">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" placeholder="Cari rekomendasi"
                            class="w-full pl-10 pr-4 py-3 bg-white rounded-full text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                </div>

                <!-- Content -->
                <div class="bg-white rounded-2xl p-8">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800">CHECKING AND BOOKING ROOM</h2>
                        <div class="relative">
                            <i class="fas fa-bell text-gray-600 text-xl"></i>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></div>
                        </div>
                    </div>

                    <!-- Hospital Cards Grid -->
                    <div class="grid gap-6">
                        @foreach($hospitalsData as $hospital)
                        <div class="bg-gray-50 rounded-2xl p-6 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 pr-6">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $hospital['name'] }}</h3>
                                    <div class="text-sm font-medium px-3 py-1 rounded mb-3 inline-block {{ $hospital['total_rooms'] > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        {{ $hospital['status'] }}
                                    </div>
                                    <p class="text-sm text-blue-600 mb-2">{{ $hospital['website_url'] }}</p>
                                    <p class="text-sm text-gray-500">{{ date('l, d F Y') }}</p>
                                </div>
                                <div class="w-32 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                    <img src="{{ asset($hospital['image_url']) }}" alt="{{ $hospital['name'] }}"
                                        class="w-full h-full object-cover">
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
