<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <div class="flex-1 px-5 py-4 space-y-6">
            <!-- Hospital Cards -->
            <div x-data="adminDashboard()">
                <!-- RSUD Sidoarjo Card -->
                <div class="gradient-bg rounded-lg shadow-lg p-4 mb-6" style="box-shadow: 0px 4px 4px 2px rgba(0, 0, 0, 0.25); height: 260px;">
                    <h2 class="text-black text-xl font-semibold mb-4">RSUD Sidoarjo</h2>
                    
                    <!-- Room Types -->
                    <div class="space-y-3 mb-4">
                        <!-- VVIP Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">VVIP Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_sidoarjo', 'vvip')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_sidoarjo.vvip"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_sidoarjo', 'vvip')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Class 1 Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">Class 1 Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_sidoarjo', 'class1')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_sidoarjo.class1"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_sidoarjo', 'class1')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Class 2 Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">Class 2 Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_sidoarjo', 'class2')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_sidoarjo.class2"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_sidoarjo', 'class2')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Class 3 Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">Class 3 Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_sidoarjo', 'class3')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_sidoarjo.class3"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_sidoarjo', 'class3')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Update Button -->
                    <button @click="updateHospital('rsud_sidoarjo')" class="w-full bg-white text-black text-sm font-semibold py-2 rounded-lg" style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                        Update
                    </button>
                </div>

                <!-- RSUD Dr. Mohammad Soewandhie Card -->
                <div class="gradient-bg rounded-lg shadow-lg p-4 mb-6" style="box-shadow: 0px 4px 4px 2px rgba(0, 0, 0, 0.25); height: 260px;">
                    <h2 class="text-black text-lg font-semibold mb-4">RSUD Dr. Mohammad Soewandhie</h2>
                    
                    <!-- Room Types -->
                    <div class="space-y-3 mb-4">
                        <!-- VVIP Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">VVIP Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_soewandhie', 'vvip')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_soewandhie.vvip"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_soewandhie', 'vvip')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Class 1 Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">Class 1 Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_soewandhie', 'class1')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_soewandhie.class1"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_soewandhie', 'class1')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Class 2 Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">Class 2 Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_soewandhie', 'class2')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_soewandhie.class2"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_soewandhie', 'class2')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Class 3 Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">Class 3 Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_soewandhie', 'class3')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_soewandhie.class3"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_soewandhie', 'class3')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Update Button -->
                    <button @click="updateHospital('rsud_soewandhie')" class="w-full bg-white text-black text-sm font-semibold py-2 rounded-lg" style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                        Update
                    </button>
                </div>

                <!-- RSUD Dr Wahidin Sudiro Husodo Card -->
                <div class="gradient-bg rounded-lg shadow-lg p-4 mb-6" style="box-shadow: 0px 4px 4px 2px rgba(0, 0, 0, 0.25); height: 260px;">
                    <h2 class="text-black text-lg font-semibold mb-4">RSUD Dr Wahidin Sudiro Husodo</h2>
                    
                    <!-- Room Types -->
                    <div class="space-y-3 mb-4">
                        <!-- VVIP Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">VVIP Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_wahidin', 'vvip')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_wahidin.vvip"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_wahidin', 'vvip')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Class 1 Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">Class 1 Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_wahidin', 'class1')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_wahidin.class1"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_wahidin', 'class1')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Class 2 Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">Class 2 Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_wahidin', 'class2')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_wahidin.class2"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_wahidin', 'class2')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Class 3 Room -->
                        <div class="flex items-center justify-between">
                            <span class="text-black text-sm font-normal">Class 3 Room</span>
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity('rsud_wahidin', 'class3')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">-</span>
                                </button>
                                <div class="w-10 h-6 bg-white border border-black rounded text-center flex items-center justify-center">
                                    <span class="text-black text-xs" x-text="hospitals.rsud_wahidin.class3"></span>
                                </div>
                                <button @click="increaseQuantity('rsud_wahidin', 'class3')" class="w-7 h-7 bg-[#D9D9D9] rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-black text-sm">+</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Update Button -->
                    <button @click="updateHospital('rsud_wahidin')" class="w-full bg-white text-black text-sm font-semibold py-2 rounded-lg" style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                        Update
                    </button>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-center py-4">
            <div class="flex items-center space-x-4">
                <button class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded transition-colors">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <span class="text-sm text-gray-600 font-medium">10 / 33</span>
                <button class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded transition-colors">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        function adminDashboard() {
            return {
                hospitals: {
                    rsud_sidoarjo: {
                        vvip: 10,
                        class1: 10,
                        class2: 10,
                        class3: 10
                    },
                    rsud_soewandhie: {
                        vvip: 10,
                        class1: 10,
                        class2: 10,
                        class3: 10
                    },
                    rsud_wahidin: {
                        vvip: 10,
                        class1: 10,
                        class2: 10,
                        class3: 10
                    }
                },

                increaseQuantity(hospital, roomType) {
                    if (this.hospitals[hospital][roomType] < 99) {
                        this.hospitals[hospital][roomType]++;
                    }
                },

                decreaseQuantity(hospital, roomType) {
                    if (this.hospitals[hospital][roomType] > 0) {
                        this.hospitals[hospital][roomType]--;
                    }
                },

                updateHospital(hospital) {
                    // Here you would typically send the data to your backend
                    console.log(`Updating ${hospital}:`, this.hospitals[hospital]);
                    
                    // Show success message (you can replace this with a proper notification)
                    alert(`Hospital ${hospital} updated successfully!`);
                }
            }
        }
    </script>
</body>
</html>
