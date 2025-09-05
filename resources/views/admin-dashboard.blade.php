<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
        .gradient-bg {
            background: linear-gradient(180deg, rgba(80.91, 194.07, 255, 0.80) 0%, rgba(0, 96.01, 147.71, 0.80) 100%);
        }
        .mobile-container {
            width: 375px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        @media (max-width: 375px) {
            .mobile-container {
                width: 100%;
            }
        }
    </style>
</head>

<body class="h-full bg-white font-sans">
    <div class="mobile-container">
        <!-- Header -->
        <div class="w-full bg-[#02293E] flex items-center justify-between px-4" style="height: 76px;">
            <h1 class="text-white text-xl font-bold" style="font-family: Inter; font-weight: 700; font-size: 20px;">Admin Dashboard</h1>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="w-6 h-6 flex items-center justify-center hover:bg-white hover:bg-opacity-20 rounded transition-colors" title="Logout">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 px-5" style="display: flex; flex-direction: column; padding-top: 21px; padding-bottom: 40px;">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Hospital Cards -->
            <div x-data="adminDashboard()" style="display: flex; flex-direction: column; gap: 23px; align-items: center;">
                <!-- Hospital Cards -->
                <template x-for="hospital in hospitals" :key="hospital.id">
                    <div class="gradient-bg rounded-lg shadow-lg flex flex-col justify-between" style="box-shadow: 0px 4px 4px 2px rgba(0, 0, 0, 0.25); width: 336px; height: 260px; padding: 12px;">
                        <div>
                            <h2 class="text-black text-lg font-semibold mb-3" x-text="hospital.name"></h2>
                            
                            <!-- Room Types -->
                            <div class="space-y-2">
                            <!-- VVIP Room -->
                            <div class="flex items-center justify-between">
                                <span class="text-black text-xs font-normal">VVIP Room</span>
                                <div class="flex items-center space-x-1">
                                    <button @click="decreaseQuantity(hospital.id, 'vvip')" class="w-6 h-6 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                        <span class="text-black text-xs">-</span>
                                    </button>
                                    <div class="w-8 h-5 bg-white border border-black rounded text-center flex items-center justify-center">
                                        <span class="text-black text-xs" x-text="hospital.rooms.vvip"></span>
                                    </div>
                                    <button @click="increaseQuantity(hospital.id, 'vvip')" class="w-6 h-6 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                        <span class="text-black text-xs">+</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Class 1 Room -->
                            <div class="flex items-center justify-between">
                                <span class="text-black text-xs font-normal">Class 1 Room</span>
                                <div class="flex items-center space-x-1">
                                    <button @click="decreaseQuantity(hospital.id, 'class1')" class="w-6 h-6 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                        <span class="text-black text-xs">-</span>
                                    </button>
                                    <div class="w-8 h-5 bg-white border border-black rounded text-center flex items-center justify-center">
                                        <span class="text-black text-xs" x-text="hospital.rooms.class1"></span>
                                    </div>
                                    <button @click="increaseQuantity(hospital.id, 'class1')" class="w-6 h-6 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                        <span class="text-black text-xs">+</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Class 2 Room -->
                            <div class="flex items-center justify-between">
                                <span class="text-black text-xs font-normal">Class 2 Room</span>
                                <div class="flex items-center space-x-1">
                                    <button @click="decreaseQuantity(hospital.id, 'class2')" class="w-6 h-6 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                        <span class="text-black text-xs">-</span>
                                    </button>
                                    <div class="w-8 h-5 bg-white border border-black rounded text-center flex items-center justify-center">
                                        <span class="text-black text-xs" x-text="hospital.rooms.class2"></span>
                                    </div>
                                    <button @click="increaseQuantity(hospital.id, 'class2')" class="w-6 h-6 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                        <span class="text-black text-xs">+</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Class 3 Room -->
                            <div class="flex items-center justify-between">
                                <span class="text-black text-xs font-normal">Class 3 Room</span>
                                <div class="flex items-center space-x-1">
                                    <button @click="decreaseQuantity(hospital.id, 'class3')" class="w-6 h-6 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                        <span class="text-black text-xs">-</span>
                                    </button>
                                    <div class="w-8 h-5 bg-white border border-black rounded text-center flex items-center justify-center">
                                        <span class="text-black text-xs" x-text="hospital.rooms.class3"></span>
                                    </div>
                                    <button @click="increaseQuantity(hospital.id, 'class3')" class="w-6 h-6 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                        <span class="text-black text-xs">+</span>
                                    </button>
                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- Update Button -->
                        <button @click="updateHospital(hospital.id)" class="w-full bg-white text-black text-xs font-semibold py-2 rounded-lg" style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25); margin-top: 12px;">
                            Update
                        </button>
                    </div>
                </template>

            </div>
        </div>

    </div>

    <script>
        function adminDashboard() {
            return {
                hospitals: @json($hospitalsData),

                increaseQuantity(hospitalId, roomType) {
                    const hospital = this.hospitals.find(h => h.id === hospitalId);
                    if (hospital && hospital.rooms[roomType] < 999) {
                        hospital.rooms[roomType]++;
                        this.updateRoomQuantity(hospitalId, roomType, hospital.rooms[roomType]);
                    }
                },

                decreaseQuantity(hospitalId, roomType) {
                    const hospital = this.hospitals.find(h => h.id === hospitalId);
                    if (hospital && hospital.rooms[roomType] > 0) {
                        hospital.rooms[roomType]--;
                        this.updateRoomQuantity(hospitalId, roomType, hospital.rooms[roomType]);
                    }
                },

                updateRoomQuantity(hospitalId, roomType, quantity) {
                    // Simple update without AJAX - just update the UI
                    // Data will be saved when user clicks "Update" button
                    console.log(`Updated ${roomType} to ${quantity} for hospital ${hospitalId}`);
                },

                updateHospital(hospitalId) {
                    const hospital = this.hospitals.find(h => h.id === hospitalId);
                    if (!hospital) return;

                    // Create a form and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.update-hospital") }}';
                    
                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);
                    }
                    
                    // Add hospital ID
                    const hospitalInput = document.createElement('input');
                    hospitalInput.type = 'hidden';
                    hospitalInput.name = 'hospital_id';
                    hospitalInput.value = hospitalId;
                    form.appendChild(hospitalInput);
                    
                    // Add rooms data
                    const roomsInput = document.createElement('input');
                    roomsInput.type = 'hidden';
                    roomsInput.name = 'rooms';
                    roomsInput.value = JSON.stringify(hospital.rooms);
                    form.appendChild(roomsInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                },

                getHospitalSlug(hospital) {
                    // Map hospital names to slugs for display
                    const slugMap = {
                        'RSUD Sidoarjo': 'rsud_sidoarjo',
                        'RSUD Dr. Mohammad Soewandhie': 'rsud_soewandhie',
                        'RSUD Dr Wahidin Sudiro Husodo': 'rsud_wahidin'
                    };
                    return slugMap[hospital.name] || hospital.slug;
                }
            }
        }
    </script>
</body>
</html>
