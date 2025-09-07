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
                    <a href="{{ route('payment.detail-booking', ['hospital_id' => $hospital->slug, 'room_id' => $room['id']]) }}"
                        class="w-40 bg-[#0B9078] text-white font-bold py-3 rounded-xl text-base hover:bg-[#097A63] transition-colors shadow-2xl mb-7 text-center">
                        BOOKING ROOM
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Layout -->
    <div class="hidden lg:block">
        <!-- Desktop content here -->
    </div>
</body>

</html>
