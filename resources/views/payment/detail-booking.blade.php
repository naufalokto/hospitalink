<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Booking - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .payment-method {
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-[#0688CE] min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6 relative">
            <a href="{{ route('room') }}" class="text-black absolute left-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-black text-2xl font-bold w-full text-center">Detail Booking</h1>
        </div>

        <!-- Hospital Info -->
        <div class="flex items-start space-x-4 mb-6">
            <img src="{{ asset('images/rooms/vvip-room.jpg') }}" alt="Hospital"
                class="w-48 h-24 object-cover rounded-lg">
            <div>
                <h2 class="text-black font-bold text-lg">RSUD Dr. Mohammad Soewandhie</h2>
                <p class="text-black text-sm">Gedung Lama Lt. 4</p>
            </div>
        </div>

        <!-- Booking Details -->
        <div x-data="{ days: 5, pricePerDay: 300000 }" class="bg-[#034C74] rounded-3xl p-6 mb-6">
            <div class="divide-y divide-gray-500">
                <div class="py-4">
                    <h3 class="text-white text-sm mb-1">Nama Pasien</h3>
                    <p class="text-white font-semibold">Naufal Oktora Siswanto</p>
                </div>
                <div class="py-4">
                    <h3 class="text-white text-sm mb-1">Nama Rumah Sakit</h3>
                    <p class="text-white font-semibold">RSUD Dr. Mohammad Soewandhie</p>
                </div>
                <div class="py-4">
                    <h3 class="text-white text-sm mb-1">Waktu Inap</h3>
                    <div class="flex items-center">
                        <p class="text-white font-semibold mr-8" x-text="days + ' hari'"></p>
                        <div class="flex items-center space-x-5">
                            <button @click="days = Math.max(1, days-1)"
                                class="bg-gray-800 text-white w-9 h-9 rounded-full flex items-center justify-center focus:outline-none hover:bg-gray-700 text-xl font-medium">
                                -
                            </button>
                            <button @click="days++"
                                class="bg-gray-800 text-white w-9 h-9 rounded-full flex items-center justify-center focus:outline-none hover:bg-gray-700 text-xl font-medium">
                                +
                            </button>
                        </div>
                    </div>
                </div>
                <div class="py-4">
                    <h3 class="text-white text-sm mb-1">Total Harga</h3>
                    <p class="text-white font-semibold" x-text="'Rp ' + (days * pricePerDay).toLocaleString('id-ID')">
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div x-data="{ selected: '' }">
            <h3 class="text-black text-xl font-bold text-center mb-6">Metode Pembayaran</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">

                <!-- BCA -->
                <div @click="selected = 'BCA'" :class="selected === 'BCA' ? 'ring-2 ring-black' : ''"
                    class="cursor-pointer bg-white rounded-lg shadow flex items-center justify-center 
                   w-[115px] h-[74px] mx-auto transition-transform duration-300 
                   hover:scale-105 hover:shadow-md">
                    <img src="{{ asset('images/payment/BCA.jpg') }}" alt="BCA"
                        class="w-full h-full object-contain p-2" />
                </div>

                <!-- BRI -->
                <div @click="selected = 'BRI'" :class="selected === 'BRI' ? 'ring-2 ring-black' : ''"
                    class="cursor-pointer bg-white rounded-lg shadow flex items-center justify-center 
                   w-[115px] h-[74px] mx-auto transition-transform duration-300 
                   hover:scale-105 hover:shadow-md">
                    <img src="{{ asset('images/payment/BRI.png') }}" alt="BRI"
                        class="w-full h-full object-contain p-2" />
                </div>

                <!-- CIMB -->
                <div @click="selected = 'CIMB'" :class="selected === 'CIMB' ? 'ring-2 ring-black' : ''"
                    class="cursor-pointer bg-white rounded-lg shadow flex items-center justify-center 
                   w-[115px] h-[74px] mx-auto transition-transform duration-300 
                   hover:scale-105 hover:shadow-md">
                    <img src="{{ asset('images/payment/CIMBNIAGA.jpeg') }}" alt="CIMB"
                        class="w-full h-full object-contain p-2" />
                </div>

                <!-- BNI -->
                <div @click="selected = 'BNI'" :class="selected === 'BNI' ? 'ring-2 ring-black' : ''"
                    class="cursor-pointer bg-white rounded-lg shadow flex items-center justify-center 
                   w-[115px] h-[74px] mx-auto transition-transform duration-300 
                   hover:scale-105 hover:shadow-md">
                    <img src="{{ asset('images/payment/BNI.png') }}" alt="BNI"
                        class="w-full h-full object-contain p-2" />
                </div>

                <!-- Mandiri -->
                <div @click="selected = 'Mandiri'" :class="selected === 'Mandiri' ? 'ring-2 ring-black' : ''"
                    class="cursor-pointer bg-white rounded-lg shadow flex items-center justify-center 
                   w-[115px] h-[74px] mx-auto transition-transform duration-300 
                   hover:scale-105 hover:shadow-md">
                    <img src="{{ asset('images/payment/Mandiri.png') }}" alt="Mandiri"
                        class="w-full h-full object-contain p-2" />
                </div>

                <!-- BSI -->
                <div @click="selected = 'BSI'" :class="selected === 'BSI' ? 'ring-2 ring-black' : ''"
                    class="cursor-pointer bg-white rounded-lg shadow flex items-center justify-center 
                   w-[115px] h-[74px] mx-auto transition-transform duration-300 
                   hover:scale-105 hover:shadow-md">
                    <img src="{{ asset('images/payment/BSI1.png') }}" alt="BSI"
                        class="w-full h-full object-contain p-2" />
                </div>
            </div>
        </div>


        <!-- Pay Button -->
        <div class="mt-9 flex justify-center">
            <a href="{{ route('payment.pay') }}"
                class="block w-[320px] bg-gray-900 text-white font-bold py-3 rounded-3xl text-lg 
          transition-transform duration-300 hover:scale-105 hover:shadow-md text-center">
                Bayar
            </a>
        </div>
    </div>
</body>

</html>
