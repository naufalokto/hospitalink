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
                <h2 class="text-black font-bold text-lg" id="hospitalName">
                    {{ $hospital ? $hospital->name : 'Rumah Sakit' }}
                </h2>
                <p class="text-black text-sm" id="hospitalMeta">
                    {{ $hospital ? $hospital->address : '' }}
                </p>
            </div>
        </div>

        <!-- Main Container with Alpine.js -->
        <div x-data="{ 
            days: 5, 
            pricePerDay: {{ $hospitalRoomType ? $hospitalRoomType->price_per_day : 300000 }}, 
            selected: '',
            roomPrices: {
                vvip: {{ $hospital && $hospital->roomTypes->where('roomType.code', 'vvip')->first() ? $hospital->roomTypes->where('roomType.code', 'vvip')->first()->price_per_day : 300000 }},
                class1: {{ $hospital && $hospital->roomTypes->where('roomType.code', 'class1')->first() ? $hospital->roomTypes->where('roomType.code', 'class1')->first()->price_per_day : 200000 }},
                class2: {{ $hospital && $hospital->roomTypes->where('roomType.code', 'class2')->first() ? $hospital->roomTypes->where('roomType.code', 'class2')->first()->price_per_day : 150000 }},
                class3: {{ $hospital && $hospital->roomTypes->where('roomType.code', 'class3')->first() ? $hospital->roomTypes->where('roomType.code', 'class3')->first()->price_per_day : 100000 }}
            },
            selectedRoomType: '{{ $roomType ? $roomType->code : 'class2' }}',
            get totalPrice() { return this.days * this.pricePerDay; },
            async init() {
                // Load room prices from API as fallback
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
                        // Update room prices from API
                        this.roomPrices = data.data.room_prices;
                        this.updatePricePerDay();
                    }
                } catch (error) {
                    console.error('Failed to load room prices:', error);
                    // Keep the server-side prices as fallback
                }
            },
            getHospitalId() {
                // Extract hospital ID from URL or use hospital ID from server
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get('hospital_id') || {{ $hospital ? $hospital->id : 1 }};
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
                        <p class="text-white font-semibold" id="hospitalNameCenter">
                            {{ $hospital ? $hospital->name : '-' }}
                        </p>
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


        

        <!-- Booking Button -->
        <div class="mt-6 flex flex-col items-center space-y-3">
            <button id="bookButton" 
                class="block w-[320px] bg-gray-900 text-white font-bold py-3 rounded-3xl text-lg 
          transition-transform duration-300 hover:scale-105 hover:shadow-md text-center"
                onclick="bookNow()">
                Booking
            </button>
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
                    total_price: {{ $hospitalRoomType ? $hospitalRoomType->price_per_day * 5 : 1500000 }},
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
            let pricePerDay = {{ $hospitalRoomType ? $hospitalRoomType->price_per_day : 300000 }};
            let selectedRoomType = '{{ $roomType ? $roomType->code : 'class2' }}';
            
            try {
                // Get Alpine.js data from the main container
                const alpineElement = document.querySelector('[x-data]');
                if (alpineElement && Alpine.$data) {
                    const alpineData = Alpine.$data(alpineElement);
                    totalPrice = alpineData ? alpineData.totalPrice : 0;
                    days = alpineData ? alpineData.days : 5;
                    pricePerDay = alpineData ? alpineData.pricePerDay : {{ $hospitalRoomType ? $hospitalRoomType->price_per_day : 300000 }};
                    selectedRoomType = alpineData ? alpineData.selectedRoomType : '{{ $roomType ? $roomType->code : 'class2' }}';
                }
                
                // Fallback: calculate manually if Alpine.js data not available
                if (!totalPrice || totalPrice === 0) {
                    totalPrice = days * pricePerDay;
                }
                
                console.log('Alpine.js data:', { totalPrice, days, pricePerDay });
            } catch (error) {
                console.error('Error getting Alpine.js data:', error);
                // Use fallback values
                totalPrice = 5 * {{ $hospitalRoomType ? $hospitalRoomType->price_per_day : 300000 }}; // Default 5 days
            }
            
            console.log('Final values:', { totalPrice });

            const payButton = document.getElementById('payButton');
            payButton.disabled = true;
            payButton.textContent = 'Memproses...';

            try {
                // Ensure we have a booking id for the selected hospital and room type
                const hospitalId = new URLSearchParams(window.location.search).get('hospital_id');
                const roomId = new URLSearchParams(window.location.search).get('room_id');
                
                if (!bookingData.id && hospitalId && roomId) {
                    // Create booking through normal booking process
                    const createRes = await fetch(`/booking/${hospitalId}/room/${roomId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            patient_name: '{{ $user->name ?? "Guest User" }}',
                            patient_phone: '081234567890',
                            patient_email: '{{ $user->email ?? "guest@example.com" }}',
                            check_in_date: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString().split('T')[0], // Tomorrow
                            check_out_date: new Date(Date.now() + 6 * 24 * 60 * 60 * 1000).toISOString().split('T')[0], // 6 days from now
                            notes: 'Booking from payment page',
                            room_type: '{{ $roomType ? $roomType->code : 'class2' }}',
                            price_per_day: {{ $hospitalRoomType ? $hospitalRoomType->price_per_day : 300000 }}
                        })
                    });
                    
                    if (createRes.ok) {
                        // If booking is successful, redirect to booking invoice
                        window.location.href = createRes.url;
                        return;
                    } else {
                        throw new Error('Gagal membuat booking. Silakan coba lagi.');
                    }
                }

                console.log('Sending payment request:', {
                    booking_id: bookingData.id,
                    amount: totalPrice,
                    room_type: selectedRoomType,
                    days: days,
                    price_per_day: pricePerDay,
                    hospital_id: {{ $hospital ? $hospital->id : 'null' }},
                    room_id: {{ $roomType ? $roomType->id : 'null' }}
                });
                
                // If no booking ID, we need to create one first
                if (!bookingData.id) {
                    console.log('No booking ID, need to create booking first');
                    alert('Silakan buat booking terlebih dahulu');
                    return;
                }

                // Call real create payment endpoint
                const response = await fetch(`{{ route('payment.create') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        booking_id: bookingData.id,
                        amount: totalPrice,
                        hospital_id: {{ $hospital ? $hospital->id : 'null' }},
                        room_id: {{ $roomType ? $roomType->id : 'null' }},
                        room_type: selectedRoomType,
                        price_per_day: pricePerDay
                    })
                });

                console.log('Response status:', response.status);
                console.log('Response URL:', response.url);
                console.log('Response headers:', Object.fromEntries(response.headers.entries()));

                if (!response.ok) {
                    let errorText;
                    try {
                        errorText = await response.text();
                        console.error('Error response text:', errorText);
                        
                        // Try to parse as JSON for better error handling
                        try {
                            const errorJson = JSON.parse(errorText);
                            console.error('Error response JSON:', errorJson);
                            throw new Error(`HTTP error! status: ${response.status}, message: ${errorJson.message || errorText}`);
                        } catch (parseError) {
                            throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);
                        }
                    } catch (textError) {
                        throw new Error(`HTTP error! status: ${response.status}, message: Failed to read error response`);
                    }
                }

                let result;
                try {
                    const responseText = await response.text();
                    console.log('Raw response text:', responseText);
                    result = JSON.parse(responseText);
                    console.log('Parsed payment response:', result);
                } catch (parseError) {
                    console.error('Failed to parse response as JSON:', parseError);
                    throw new Error('Invalid response format from server');
                }

                if (result.success === true && result.redirect_url) {
                    console.log('Redirecting to Midtrans:', result.redirect_url);
                    console.log('Redirect URL validation:', {
                        hasProtocol: result.redirect_url.startsWith('http'),
                        isMidtrans: result.redirect_url.includes('midtrans.com'),
                        urlLength: result.redirect_url.length
                    });
                    
                    // Try multiple redirect methods
                    try {
                        // Force redirect with timeout
                        setTimeout(() => {
                            window.location.href = result.redirect_url;
                        }, 100);
                    } catch (e) {
                        console.log('window.location.href failed, trying window.open');
                        window.open(result.redirect_url, '_blank');
                    }
                } else {
                    console.error('Payment failed:', result);
                    console.error('Success value:', result.success);
                    console.error('Success type:', typeof result.success);
                    console.error('Redirect URL:', result.redirect_url);
                    console.error('Redirect URL type:', typeof result.redirect_url);
                    alert('Gagal membuat pembayaran: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Payment error:', error);
                console.error('Error details:', {
                    message: error.message,
                    stack: error.stack
                });
                console.error('Error name:', error.name);
                console.error('Error constructor:', error.constructor.name);
                alert('Terjadi kesalahan saat memproses pembayaran: ' + error.message);
            } finally {
                payButton.disabled = false;
                payButton.textContent = 'Bayar';
            }
        }

        function bookNow() {
            try {
                const serverHospitalSlug = '{{ $hospital ? ($hospital->slug ?? '') : '' }}';
                const urlParams = new URLSearchParams(window.location.search);
                const hospitalParam = serverHospitalSlug || urlParams.get('hospital_id');
                const roomId = urlParams.get('room_id');
                if (!hospitalParam || !roomId) {
                    alert('Parameter rumah sakit atau tipe kamar tidak valid');
                    return;
                }

                // Get Alpine data if available
                let days = 5;
                try {
                    const alpineElement = document.querySelector('[x-data]');
                    if (alpineElement && Alpine.$data) {
                        const alpineData = Alpine.$data(alpineElement);
                        days = alpineData ? alpineData.days : 5;
                    }
                } catch (e) {}

                // Use existing data on the page (no extra inputs)
                const patientName = '{{ $user->name ?? "Guest User" }}';
                const patientPhone = '{{ $user->phone ?? '081234567890' }}';
                const patientEmail = '{{ $user->email ?? 'guest@example.com' }}';
                const patientAddress = '';

                const today = new Date();
                const checkIn = new Date(today);
                checkIn.setDate(checkIn.getDate() + 1);
                const checkOut = new Date(checkIn);
                checkOut.setDate(checkOut.getDate() + days);

                // Build and submit a real form so browser follows redirects
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/booking/${hospitalParam}/room/${roomId}`;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrf);

                const fields = {
                    patient_name: patientName,
                    patient_phone: patientPhone,
                    patient_email: patientEmail,
                    patient_address: patientAddress,
                    check_in_date: checkIn.toISOString().split('T')[0],
                    check_out_date: checkOut.toISOString().split('T')[0],
                    notes: 'Booking dari halaman detail booking',
                };

                Object.entries(fields).forEach(([name, value]) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = value;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                const btn = document.getElementById('bookButton');
                if (btn) { btn.disabled = true; btn.textContent = 'Memproses...'; }
                form.submit();
            } catch (error) {
                alert('Terjadi kesalahan saat membuat booking: ' + error.message);
            }
        }

        // Debug function for testing payment flow
        async function debugPaymentFlow() {
            console.log('=== DEBUG PAYMENT FLOW ===');
            
            try {
                // Test debug endpoint
                const debugResponse = await fetch('/debug/payment-flow', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                console.log('Debug response status:', debugResponse.status);
                
                if (debugResponse.ok) {
                    const debugResult = await debugResponse.json();
                    console.log('Debug result:', debugResult);
                    
                    if (debugResult.status === 'success' && debugResult.redirect_url) {
                        console.log('Debug redirect URL:', debugResult.redirect_url);
                        alert('Debug: Payment flow test successful! Check console for details.');
                        
                        // Optionally redirect to test URL
                        if (confirm('Do you want to test redirect to Midtrans?')) {
                            window.location.href = debugResult.redirect_url;
                        }
                    } else {
                        console.error('Debug failed:', debugResult);
                        alert('Debug: Payment flow test failed - ' + (debugResult.message || 'Unknown error'));
                    }
                } else {
                    const errorText = await debugResponse.text();
                    console.error('Debug request failed:', errorText);
                    alert('Debug: Request failed - ' + errorText);
                }
            } catch (error) {
                console.error('Debug error:', error);
                alert('Debug: Error - ' + error.message);
            }
        }

    </script>
</body>

</html>
