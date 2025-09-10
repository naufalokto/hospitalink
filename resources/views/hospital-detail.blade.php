<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hospital->name }} - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            transition: transform 0.1s ease;
            z-index: 50;
        }

        .sticky-header.scrolled {
            transform: translateY(-40%);
        }
    </style>
</head>

<body class="h-full bg-[#0688CE] font-sans antialiased lg:bg-gray-100" x-data="{ scrolled: false }"
    @scroll.window="scrolled = window.pageYOffset > 50">

    <!-- Mobile Layout -->
    <div class="lg:hidden min-h-screen flex flex-col">

        <div class="sticky-header bg-[#02293E] shadow-lg rounded-b-3xl w-full transition-all duration-300"
            :class="{ 'scrolled': scrolled }">
            <div class="px-4 py-3">

                <div class="flex items-center justify-between mb-4">
                    <a href="{{ route('hospital') }}"
                        class="text-white hover:text-gray-300 transition-colors flex items-center mt-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </a>
                    <h1 class="text-white text-2xl font-md mt-3">HOSPITAL INFORMATION</h1>
                    <div class="w-6"></div>
                </div>


                <div class="mb-3 mx-6">
                    <img src="{{ \Illuminate\Support\Str::startsWith($hospital->image_url, ['http://', 'https://'])
                        ? $hospital->image_url
                        : (\Illuminate\Support\Str::startsWith($hospital->image_url, ['storage/', 'images/'])
                            ? asset($hospital->image_url)
                            : asset('storage/' . $hospital->image_url)) }}"
                        alt="{{ $hospital->name }}" class="w-full h-48 object-cover rounded-lg">
                </div>


                <div class="text-center mb-2">
                    <span class="text-white text-3xl font-bold opacity-90">{{ $hospital->name }}</span>
                </div>
            </div>
        </div>


        <div class="flex-1 overflow-y-auto custom-scrollbar">
            <div class="p-4 text-black leading-relaxed">
                <div class="space-y-4 mx-4">
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-black mb-2">Tentang</h2>
                        <p class="text-black">{{ $hospital->description }}</p>
                    </div>

                    @if ($hospital->public_service)
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">PELAYANAN PUBLIK</h2>
                            <div class="list-decimal list-inside space-y-1 text-black">
                                @foreach (explode("\n", $hospital->public_service) as $item)
                                    <div>{{ $item }}</div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">PERSYARATAN RAWAT INAP</h2>
                            <ol class="list-decimal list-inside space-y-1 text-black">
                                @foreach (explode("\n", $hospital->admission_requirements) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">HARGA RAWAT INAP</h2>
                            <ul class="list-disc list-inside space-y-1 text-black">
                                @foreach (explode("\n", $hospital->room_prices) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">FASILITAS</h2>
                            <ul class="list-disc list-inside space-y-1 text-black">
                                @foreach (explode("\n", $hospital->facilities) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">WEBSITE</h2>
                            <p class="text-black">{{ $hospital->website_url }}</p>
                        </div>
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">NOMOR TELEPON</h2>
                            <p class="text-black">{{ $hospital->phone_number }}</p>
                        </div>
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">ALAMAT</h2>
                            <p class="text-black">{{ $hospital->address }}</p>
                        </div>
                    @endif
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
        <!-- Main Content -->
        <div class="flex-1 bg-gradient-to-b from-[#00A2FA] via-[#00A2FA] to-[#E8F4FD]">
            <div class="max-w-6xl mx-auto p-8">
                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <a href="{{ route('hospital') }}" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </a>
                    <h1 class="text-white text-3xl font-bold">HOSPITAL INFORMATION</h1>
                </div>

                <!-- Hospital Header Card -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 mb-8">
                    <div class="flex items-start gap-8">
                        <img src="{{ \Illuminate\Support\Str::startsWith($hospital->image_url, ['http://', 'https://'])
                            ? $hospital->image_url
                            : (\Illuminate\Support\Str::startsWith($hospital->image_url, ['storage/', 'images/'])
                                ? asset($hospital->image_url)
                                : asset('storage/' . $hospital->image_url)) }}"
                            alt="{{ $hospital->name }}" class="w-80 h-56 object-cover rounded-xl shadow-lg">
                        <div class="flex-1">
                            <h2 class="text-white text-4xl font-bold mb-4">{{ $hospital->name }}</h2>
                            <div class="space-y-3 text-white/90">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $hospital->address }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                    </svg>
                                    <span>{{ $hospital->phone_number }}</span>
                                </div>
                                @if($hospital->website_url)
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ $hospital->website_url }}" class="hover:text-white transition-colors">{{ $hospital->website_url }}</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <!-- About Section -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm">
                        <h3 class="text-gray-900 text-xl font-bold mb-4">Tentang</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $hospital->description }}</p>
                    </div>

                    @if ($hospital->public_service)
                    <!-- Public Service -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm">
                        <h3 class="text-gray-900 text-xl font-bold mb-4">PELAYANAN PUBLIK</h3>
                        <div class="space-y-2 text-gray-700">
                            @foreach (explode("\n", $hospital->public_service) as $item)
                                <div class="flex items-start gap-2">
                                    <span class="text-blue-600 font-bold">•</span>
                                    <span>{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Admission Requirements -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm">
                        <h3 class="text-gray-900 text-xl font-bold mb-4">PERSYARATAN RAWAT INAP</h3>
                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            @foreach (explode("\n", $hospital->admission_requirements) as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ol>
                    </div>

                    <!-- Room Prices -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm">
                        <h3 class="text-gray-900 text-xl font-bold mb-4">HARGA RAWAT INAP</h3>
                        <ul class="space-y-2 text-gray-700">
                            @foreach (explode("\n", $hospital->room_prices) as $item)
                                <li class="flex items-start gap-2">
                                    <span class="text-green-600 font-bold">•</span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Facilities -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm xl:col-span-2">
                        <h3 class="text-gray-900 text-xl font-bold mb-4">FASILITAS</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach (explode("\n", $hospital->facilities) as $item)
                                <div class="flex items-start gap-2">
                                    <span class="text-blue-600 font-bold">•</span>
                                    <span class="text-gray-700">{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>