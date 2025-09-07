<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-[#0688CE] min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('payment.detail-booking') }}" class="text-black">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-black text-2xl font-bold w-full text-center">Pembayaran</h1>
        </div>

        <!-- Payment Information -->
        <div class="mb-8">
            <p class="text-black font-bold text-xl mb-2">Selesaikan pembayaran Anda</p>
            <p class="text-black text-sm">Silahkan selesaikan pembayaran anda sebelum 23:59</p>
        </div>

        <!-- Bank Information -->
        <div class="bg-white rounded-xl p-6 mb-6">
            <div class="flex items-center mb-4">
                <img src="{{ asset('images/payment/BCA.jpg') }}" alt="BCA" class="h-8 mr-4">
                <div>
                    <p class="text-gray-600 text-sm">Bank BCA</p>
                    <p class="text-gray-600 text-sm">Virtual Account Transfer</p>
                </div>
            </div>

            <!-- Virtual Account Number -->
            <div class="mb-6">
                <p class="text-gray-600 text-sm mb-2">Kode Virtual Account</p>
                <div class="flex items-center justify-between bg-gray-100 p-3 rounded-lg">
                    <span class="text-gray-800 font-mono text-lg">32281651320648146</span>
                    <button class="text-blue-600" onclick="copyToClipboard('32281651320648146')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Complete Button -->
        <div class="mt-9 flex justify-center">
                <a href="{{ route('dashboard') }}"
                    class="block w-full bg-gray-900 text-white font-bold py-3 rounded-3xl text-lg 
          transition-transform duration-300 hover:scale-105 hover:shadow-md text-center">
                    Selesai
                </a>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Nomor Virtual Account berhasil disalin!');
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        }
    </script>
</body>

</html>
