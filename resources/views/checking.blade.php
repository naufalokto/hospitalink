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
                <img src="{{ asset($hospital['image_url']) }}" alt="{{ $hospital['name'] }}"
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
                        <img src="{{ asset($hospital['image_url']) }}" alt="{{ $hospital['name'] }}"
                            class="w-32 h-24 object-cover rounded-lg">
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
                                            <a href="{{ route('checking-detail', ['hospital_id' => $hospital_id, 'room_id' => $room['id']]) }}"
                                                class="bg-[#0B9078] h-8 w-9 rounded-lg flex items-center justify-center hover:bg-[#097A63] transition-colors">
                                                <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </a>
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

    <!-- Real-time Room Availability Updates -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hospitalId = '{{ $hospital_id }}';
            let lastUpdateTime = null;
            
            // Function to update room availability
            async function updateRoomAvailability() {
                try {
                    const response = await fetch(`/api/room-availability/${hospitalId}`);
                    const data = await response.json();
                    
                    if (data.success) {
                        const roomTypes = data.data.room_types;
                        const currentTime = data.data.last_updated;
                        
                        // Only update if data has changed
                        if (lastUpdateTime !== currentTime) {
                            lastUpdateTime = currentTime;
                            
                            // Update mobile layout
                            updateMobileLayout(roomTypes);
                            
                            // Update desktop layout
                            updateDesktopLayout(roomTypes);
                            
                            console.log('Room availability updated:', currentTime);
                        }
                    }
                } catch (error) {
                    console.error('Failed to update room availability:', error);
                }
            }
            
            // Update mobile layout
            function updateMobileLayout(roomTypes) {
                const mobileCards = document.querySelectorAll('.lg\\:hidden .bg-\\[\\#99C1D6\\]');
                roomTypes.forEach((room, index) => {
                    if (mobileCards[index]) {
                        const availableText = mobileCards[index].querySelector('.text-sm.text-black.font-medium');
                        if (availableText) {
                            availableText.textContent = room.available;
                        }
                    }
                });
            }
            
            // Update desktop layout
            function updateDesktopLayout(roomTypes) {
                const desktopCards = document.querySelectorAll('.hidden.lg\\:flex .bg-white\\/90');
                roomTypes.forEach((room, index) => {
                    if (desktopCards[index]) {
                        const availableSpan = desktopCards[index].querySelector('.text-green-600, .text-red-600');
                        if (availableSpan) {
                            availableSpan.textContent = room.available;
                            // Update status class
                            if (room.status === 'Available') {
                                availableSpan.className = 'text-green-600 font-semibold';
                            } else {
                                availableSpan.className = 'text-red-600 font-semibold';
                            }
                        }
                    }
                });
            }
            
            // Update room availability every 30 seconds
            setInterval(updateRoomAvailability, 30000);
            
            // Initial update after 5 seconds
            setTimeout(updateRoomAvailability, 5000);
            
            // Add visual indicator for real-time updates
            const updateIndicator = document.createElement('div');
            updateIndicator.id = 'update-indicator';
            updateIndicator.className = 'fixed top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm opacity-0 transition-opacity duration-300 z-50';
            updateIndicator.textContent = 'Updated';
            document.body.appendChild(updateIndicator);
            
            // Show update indicator when data is refreshed
            function showUpdateIndicator() {
                const indicator = document.getElementById('update-indicator');
                indicator.style.opacity = '1';
                setTimeout(() => {
                    indicator.style.opacity = '0';
                }, 2000);
            }
            
            // Override the update function to show indicator
            const originalUpdate = updateRoomAvailability;
            updateRoomAvailability = async function() {
                await originalUpdate();
                showUpdateIndicator();
            };
        });
    </script>
</body>

</html>
