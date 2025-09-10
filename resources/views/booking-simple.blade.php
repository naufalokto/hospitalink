<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Booking Kamar Rumah Sakit</h1>
            
            @if($hospital && $roomType)
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <h2 class="text-lg font-semibold text-blue-800">{{ $hospital->name }}</h2>
                <p class="text-blue-600">{{ $roomType->name }} - Rp {{ number_format($hospitalRoomType->price_per_day, 0, ',', '.') }}/hari</p>
            </div>
            @endif

            <form id="bookingForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pasien</label>
                    <input type="text" name="patient_name" value="{{ $user->name ?? '' }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <input type="tel" name="patient_phone" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="patient_email" value="{{ $user->email ?? '' }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="patient_address" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
                        <input type="date" name="check_in_date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
                        <input type="date" name="check_out_date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" id="submitBtn" 
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                        Buat Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('bookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses...';
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            try {
                const response = await fetch(`{{ route('booking.simple.create') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        // Redirect to invoice page
                        window.location.href = `/booking/${result.booking_id}/invoice`;
                    } else {
                        alert('Gagal membuat booking: ' + result.message);
                    }
                } else {
                    const errorText = await response.text();
                    alert('Error: ' + errorText);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Buat Booking';
            }
        });

        // Set default dates
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        const nextWeek = new Date(today);
        nextWeek.setDate(nextWeek.getDate() + 7);
        
        document.querySelector('input[name="check_in_date"]').value = tomorrow.toISOString().split('T')[0];
        document.querySelector('input[name="check_out_date"]').value = nextWeek.toISOString().split('T')[0];
    </script>
</body>
</html>
