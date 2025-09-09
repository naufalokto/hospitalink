<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <h2 class="text-black font-bold text-lg" id="hospitalName">Rumah Sakit</h2>
                <p class="text-black text-sm" id="hospitalMeta"></p>
            </div>
        </div>

        <!-- Main Container with Alpine.js -->
        <div x-data="{ 
            days: 5, 
            pricePerDay: 300000, 
            selected: '',
            roomPrices: {
                vvip: 800000,
                class1: 500000,
                class2: 300000,
                class3: 200000
            },
            selectedRoomType: 'class2',
            get totalPrice() { return this.days * this.pricePerDay; },
            async init() {
                // Load room prices from API
                await this.loadRoomPrices();
                // Set initial price based on selected room type
                this.updatePricePerDay();
            },
            async loadRoomPrices() {
                try {
                    const hospitalId = this.getHospitalId();
                    const response = await fetch(`/api/hospital/${hospitalId}/room-prices`);
                    const data = await response.json();
                    if (data.status === 'success') {
                        this.roomPrices = data.data.room_prices;
                        this.updatePricePerDay();
                        // Fill hospital info from API
                        const name = data.data.hospital_name ? data.data.hospital_name : `Hospital ID ${hospitalId}`;
                        document.getElementById('hospitalName').textContent = name;
                        const center = document.getElementById('hospitalNameCenter');
                        if (center) center.textContent = name;
                        document.getElementById('hospitalMeta').textContent = '';
                    }
                } catch (error) {
                    console.error('Failed to load room prices:', error);
                }
            },
            getHospitalId() {
                // Extract hospital ID from URL or use default
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get('hospital_id') || 1;
            },
            updatePricePerDay() {
                this.pricePerDay = this.roomPrices[this.selectedRoomType] || 300000;
            },
            selectRoomType(roomType) {
                this.selectedRoomType = roomType;
                this.updatePricePerDay();
            }
        }">
            
            <!-- Booking Details -->
            <div class="bg-[#034C74] rounded-3xl p-6 mb-6">
                <div class="divide-y divide-gray-500">
                    <div class="py-4">
                        <h3 class="text-white text-sm mb-1">Nama Pasien</h3>
                        <p class="text-white font-semibold">{{ $user->name ?? 'Guest User' }}</p>
                    </div>
                    <div class="py-4">
                        <h3 class="text-white text-sm mb-1">Nama Rumah Sakit</h3>
                        <p class="text-white font-semibold" id="hospitalNameCenter">-</p>
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
                        <h3 class="text-white text-sm mb-3">Tipe Kamar</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="selectRoomType('vvip')" 
                                :class="selectedRoomType === 'vvip' ? 'bg-blue-600 border-blue-400' : 'bg-gray-700 border-gray-500'"
                                class="border-2 rounded-lg p-3 text-left transition-colors">
                                <div class="text-white font-semibold text-sm">VVIP</div>
                                <div class="text-gray-300 text-xs" x-text="'Rp ' + roomPrices.vvip.toLocaleString('id-ID') + '/hari'"></div>
                            </button>
                            <button @click="selectRoomType('class1')" 
                                :class="selectedRoomType === 'class1' ? 'bg-blue-600 border-blue-400' : 'bg-gray-700 border-gray-500'"
                                class="border-2 rounded-lg p-3 text-left transition-colors">
                                <div class="text-white font-semibold text-sm">Kelas 1</div>
                                <div class="text-gray-300 text-xs" x-text="'Rp ' + roomPrices.class1.toLocaleString('id-ID') + '/hari'"></div>
                            </button>
                            <button @click="selectRoomType('class2')" 
                                :class="selectedRoomType === 'class2' ? 'bg-blue-600 border-blue-400' : 'bg-gray-700 border-gray-500'"
                                class="border-2 rounded-lg p-3 text-left transition-colors">
                                <div class="text-white font-semibold text-sm">Kelas 2</div>
                                <div class="text-gray-300 text-xs" x-text="'Rp ' + roomPrices.class2.toLocaleString('id-ID') + '/hari'"></div>
                            </button>
                            <button @click="selectRoomType('class3')" 
                                :class="selectedRoomType === 'class3' ? 'bg-blue-600 border-blue-400' : 'bg-gray-700 border-gray-500'"
                                class="border-2 rounded-lg p-3 text-left transition-colors">
                                <div class="text-white font-semibold text-sm">Kelas 3</div>
                                <div class="text-gray-300 text-xs" x-text="'Rp ' + roomPrices.class3.toLocaleString('id-ID') + '/hari'"></div>
                            </button>
                        </div>
                    </div>
                    <div class="py-4">
                        <h3 class="text-white text-sm mb-1">Harga per Hari</h3>
                        <p class="text-white font-semibold" x-text="'Rp ' + pricePerDay.toLocaleString('id-ID')">
                        </p>
                    </div>
                    <div class="py-4">
                        <h3 class="text-white text-sm mb-1">Total Harga</h3>
                        <p class="text-white font-semibold" x-text="'Rp ' + totalPrice.toLocaleString('id-ID')">
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <!-- Metode pembayaran ditangani di halaman Midtrans -->
        </div>


        

        <!-- Pay Button -->
        <div class="mt-6 flex justify-center">
            <button id="payButton" 
                class="block w-[320px] bg-gray-900 text-white font-bold py-3 rounded-3xl text-lg 
          transition-transform duration-300 hover:scale-105 hover:shadow-md text-center"
                onclick="processPayment()">
                Bayar
            </button>
            </div>
        </div>
    </div>

    <script>
        // Get booking data from URL parameter or use default
        function getBookingData() {
            const urlParams = new URLSearchParams(window.location.search);
            const bookingId = urlParams.get('booking_id');
            
            if (bookingId) {
                // In real app, fetch from backend
                return {
                    id: parseInt(bookingId),
                    patient_name: '{{ $user->name ?? "Guest User" }}',
                    total_price: 1500000,
                    booking_number: 'BK20250908Dx8uXK'
                };
            }
            
            // No booking id yet
            return { id: null };
        }
        
        let bookingData = getBookingData();

        async function processPayment() {
            let totalPrice = 0;
            let days = 5;
            let pricePerDay = 300000;
            let selectedRoomType = 'class2';
            
            try {
                // Get Alpine.js data from the main container
                const alpineElement = document.querySelector('[x-data]');
                if (alpineElement && Alpine.$data) {
                    const alpineData = Alpine.$data(alpineElement);
                    totalPrice = alpineData ? alpineData.totalPrice : 0;
                    days = alpineData ? alpineData.days : 5;
                    pricePerDay = alpineData ? alpineData.pricePerDay : 300000;
                    selectedRoomType = alpineData ? alpineData.selectedRoomType : 'class2';
                }
                
                // Fallback: calculate manually if Alpine.js data not available
                if (!totalPrice || totalPrice === 0) {
                    totalPrice = days * pricePerDay;
                }
                
                console.log('Alpine.js data:', { totalPrice, days, pricePerDay });
            } catch (error) {
                console.error('Error getting Alpine.js data:', error);
                // Use fallback values
                totalPrice = 5 * 300000; // Default 5 days
            }
            
            console.log('Final values:', { totalPrice });

            const payButton = document.getElementById('payButton');
            payButton.disabled = true;
            payButton.textContent = 'Memproses...';

            try {
                // Ensure we have a booking id for the selected hospital and room type
                const hospitalId = new URLSearchParams(window.location.search).get('hospital_id');
                if (!bookingData.id && hospitalId) {
                    const createRes = await fetch(`/api/debug/create-booking/${hospitalId}/${selectedRoomType}`);
                    const createJson = await createRes.json();
                    if (createJson.status === 'success') {
                        bookingData.id = createJson.data.booking_id;
                    } else {
                        throw new Error(createJson.message || 'Gagal membuat booking');
                    }
                }

                console.log('Sending payment request:', {
                    booking_id: bookingData.id,
                    amount: totalPrice,
                    room_type: selectedRoomType,
                    days: days,
                    price_per_day: pricePerDay
                });

                // Call real create payment endpoint
                const response = await fetch(`{{ route('payment.create') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        booking_id: bookingData.id,
                        amount: totalPrice
                    })
                });

                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('Payment response:', result);

                if ((result.success === true) && result.redirect_url) {
                    console.log('Redirecting to Midtrans:', result.redirect_url);
                    window.location.href = result.redirect_url;
                } else {
                    alert('Gagal membuat pembayaran: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Payment error:', error);
                console.error('Error details:', {
                    message: error.message,
                    stack: error.stack
                });
                alert('Terjadi kesalahan saat memproses pembayaran: ' + error.message);
            } finally {
                payButton.disabled = false;
                payButton.textContent = 'Bayar';
            }
        }

        // Debug function to check Alpine.js data
        function debugAlpineData() {
            const alpineData = Alpine.$data(document.querySelector('[x-data]'));
            console.log('Alpine.js data:', alpineData);
            console.log('Selected bank:', alpineData ? alpineData.selected : 'No data');
            console.log('Days:', alpineData ? alpineData.days : 'No data');
            console.log('Price per day:', alpineData ? alpineData.pricePerDay : 'No data');
            console.log('Total price:', alpineData ? alpineData.totalPrice : 'No data');
        }
    </script>
</body>

</html>
