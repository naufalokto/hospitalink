<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checking Room - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-b from-[#00A2FA] via-[#00A2FA] to-[#E8F4FD] min-h-screen">
    <!-- Mobile Layout -->
    <div class="lg:hidden">
        <!-- Header -->

        <div class="px-4 py-3">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('room') }}"
                    class="text-black hover:text-gray-300 transition-colors flex items-center mt-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-black text-2xl font-lg mt-5">CHECKING ROOM</h1>
                <div class="w-6"></div>
            </div>

            <!-- Hospital Image -->
            <div class="mb-3 mx-6">
                <img src="{{ asset($hospital['image_url']) }}" 
                    alt="{{ $hospital['name'] }}"
                    class="w-full h-full object-cover rounded-lg">
            </div>

            <!-- Hospital Name -->
            <div class="text-center -mb-4">
                <span class="text-black text-3xl font-bold opacity-90">{{ $hospital['name'] ?? 'RSUD Sidoarjo' }}</span>
            </div>
        </div>


        <!-- Room Types Section -->
        <div class="bg-[#00A2FA] py-4">
            <div class="bg-[#B4DBF0] px-4 py-4 space-y-3 rounded-t-3xl -mb-6">
                @foreach ($roomTypes as $room)
                    <div class="bg-[#99C1D6] backdrop-blur-sm rounded-2xl p-4 shadow-sm">
                        <div class="flex justify-between">
                            <div class="flex-1">
                                <h3 class="text-gray-900 text-lg font-bold -mb-1">{{ $room['name'] }}</h3>
                                <p class="text-gray-600 text-sm mb-2 mt-3">{{ $room['location'] }}</p>
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-black font-medium">
                                        {{ $room['available'] }}
                                    </p>
                                    <a href="{{ route('checking-detail', ['hospital_id' => $hospital_id, 'room_id' => $room['id']]) }}"
                                        class="bg-[#0B9078] h-8 w-9 rounded-lg flex items-center justify-center hover:bg-[#097A63] transition-colors">
                                        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="ml-4">
                                <img src="{{ asset($room['image']) }}" alt="{{ $room['name'] }}"
                                    class="w-32 h-24 object-cover rounded-lg">
                            </div>
                        </div>
                    </div>
                @endforeach
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
            <div class="max-w-4xl mx-auto p-8">
                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <a href="{{ route('room') }}" class="text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </a>
                    <h1 class="text-white text-2xl font-bold">CHECKING ROOM</h1>
                </div>

                <!-- Hospital Info -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mb-8">
                    <div class="flex items-center gap-6">
                        <img src="{{ $hospital['image'] ?? '/placeholder.svg?height=150&width=200' }}"
                            alt="{{ $hospital['name'] ?? 'Hospital' }}" class="w-48 h-32 object-cover rounded-xl">
                        <div>
                            <h2 class="text-white text-3xl font-bold">{{ $hospital['name'] ?? 'RSUD Sidoarjo' }}</h2>
                            <p class="text-white/80 mt-2">
                                {{ $hospital['address'] ?? 'Jl. Mojopahit No. 667, Sidoarjo' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Room Types Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($roomTypes as $room)
                        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-gray-900 text-xl font-bold mb-2">{{ $room['name'] }}</h3>
                                    <p class="text-gray-600 mb-4">{{ $room['location'] }}</p>

                                    <div class="flex items-center gap-3">
                                        @if ($room['status'] === 'Available')
                                            <div class="bg-green-500 rounded-full p-2">
                                                <svg class="w-5 h-5 text-white" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-green-600 font-semibold">{{ $room['available'] }}</span>
                                        @else
                                            <div class="bg-red-500 rounded-full p-2">
                                                <svg class="w-5 h-5 text-white" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-red-600 font-semibold">{{ $room['available'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <img src="{{ asset($room['image']) }}" alt="{{ $room['name'] }}"
                                class="w-full h-32 object-cover rounded-lg">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>

</html>
