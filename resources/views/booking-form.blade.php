<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Room - HOSPITALINK</title>
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
                <a href="{{ route('checking-detail', ['hospital_id' => $hospital->slug, 'room_id' => $room['id']]) }}"
                    class="text-black hover:text-gray-600 transition-colors flex items-center mt-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-black text-2xl font-bold mt-5">BOOKING ROOM</h1>
                <div class="w-6"></div>
            </div>

            <!-- Room Info Card -->
            <div class="bg-white rounded-2xl p-4 mb-4 shadow-lg">
                <div class="flex items-center gap-4">
                    <img src="{{ asset($room['image']) }}" alt="{{ $room['name'] }}"
                        class="w-20 h-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-gray-900">{{ $room['name'] }}</h2>
                        <p class="text-sm text-gray-600">{{ $hospital->name }}</p>
                        <p class="text-sm text-gray-600">{{ $room['location'] }}</p>
                        <p class="text-sm font-semibold text-green-600">{{ $room['available'] }} Kamar Tersedia</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="bg-white rounded-t-3xl px-4 py-6 min-h-screen">
            <form action="{{ route('booking.process', ['hospital_id' => $hospital->slug, 'room_id' => $room['id']]) }}" 
                  method="POST" class="space-y-4">
                @csrf

                <!-- Patient Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900">Informasi Pasien</h3>
                    
                    <div>
                        <label for="patient_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" id="patient_name" name="patient_name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan nama lengkap pasien">
                        @error('patient_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="patient_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon *</label>
                        <input type="tel" id="patient_phone" name="patient_phone" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan nomor telepon">
                        @error('patient_phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="patient_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="patient_email" name="patient_email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan email (opsional)">
                        @error('patient_email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="patient_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea id="patient_address" name="patient_address" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan alamat (opsional)"></textarea>
                        @error('patient_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Booking Dates -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900">Tanggal Pemesanan</h3>
                    
                    <div>
                        <label for="check_in_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Check-in *</label>
                        <input type="date" id="check_in_date" name="check_in_date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            min="{{ date('Y-m-d') }}">
                        @error('check_in_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="check_out_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Check-out *</label>
                        <input type="date" id="check_out_date" name="check_out_date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('check_out_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Ringkasan Harga</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga per hari:</span>
                            <span class="font-semibold">Rp {{ number_format($room['price'], 0, ',', '.') }},-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Durasi:</span>
                            <span class="font-semibold" id="duration-display">- hari</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span id="total-price">Rp 0,-</span>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Khusus</label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan catatan khusus (opsional)"></textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-[#0B9078] text-white font-bold py-3 rounded-xl text-lg hover:bg-[#097A63] transition-colors shadow-lg">
                    BOOKING SEKARANG
                </button>
            </form>
        </div>
    </div>

    <!-- Desktop Layout -->
    <div class="hidden lg:block">
        <!-- Desktop content here -->
    </div>

    <script>
        // Calculate total price when dates change
        document.addEventListener('DOMContentLoaded', function() {
            const checkInInput = document.getElementById('check_in_date');
            const checkOutInput = document.getElementById('check_out_date');
            const durationDisplay = document.getElementById('duration-display');
            const totalPriceDisplay = document.getElementById('total-price');
            const pricePerDay = {{ $room['price'] }};

            function calculateTotal() {
                const checkIn = new Date(checkInInput.value);
                const checkOut = new Date(checkOutInput.value);
                
                if (checkIn && checkOut && checkOut > checkIn) {
                    const timeDiff = checkOut.getTime() - checkIn.getTime();
                    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    const totalPrice = daysDiff * pricePerDay;
                    
                    durationDisplay.textContent = daysDiff + ' hari';
                    totalPriceDisplay.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID') + ',-';
                } else {
                    durationDisplay.textContent = '- hari';
                    totalPriceDisplay.textContent = 'Rp 0,-';
                }
            }

            checkInInput.addEventListener('change', calculateTotal);
            checkOutInput.addEventListener('change', calculateTotal);
        });
    </script>
</body>

</html>
