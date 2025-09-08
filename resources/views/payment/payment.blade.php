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
                <img id="bankLogo" src="{{ asset('images/payment/BCA.jpg') }}" alt="BCA" class="h-8 mr-4">
                <div>
                    <p id="bankName" class="text-gray-600 text-sm">Bank BCA</p>
                    <p class="text-gray-600 text-sm">Virtual Account Transfer</p>
                </div>
            </div>

            <!-- Virtual Account Number -->
            <div class="mb-6">
                <p class="text-gray-600 text-sm mb-2">Kode Virtual Account</p>
                <div class="flex items-center justify-between bg-gray-100 p-3 rounded-lg">
                    <span id="vaNumber" class="text-gray-800 font-mono text-lg">Loading...</span>
                    <button class="text-blue-600" onclick="copyToClipboard(document.getElementById('vaNumber').textContent)">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Payment Amount -->
            <div class="mb-6">
                <p class="text-gray-600 text-sm mb-2">Jumlah Pembayaran</p>
                <div class="bg-gray-100 p-3 rounded-lg">
                    <span id="paymentAmount" class="text-gray-800 font-bold text-lg">Loading...</span>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="mb-6">
                <p class="text-gray-600 text-sm mb-2">Status Pembayaran</p>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <span id="paymentStatus" class="text-yellow-800 font-semibold">Menunggu Pembayaran</span>
                </div>
            </div>
        </div>

        <!-- Simulation Buttons (for testing) -->
        <div class="mt-6 flex justify-center space-x-4">
            <button id="simulateSuccess" 
                class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg text-sm 
                transition-transform duration-300 hover:scale-105 hover:shadow-md"
                onclick="simulatePayment('success')">
                Simulasi Berhasil
            </button>
            <button id="simulateFailure" 
                class="bg-red-600 text-white font-bold py-2 px-4 rounded-lg text-sm 
                transition-transform duration-300 hover:scale-105 hover:shadow-md"
                onclick="simulatePayment('failure')">
                Simulasi Gagal
            </button>
        </div>

        <!-- Complete Button -->
        <div class="mt-6 flex justify-center">
                <a href="{{ route('dashboard') }}"
                    class="block w-full bg-gray-900 text-white font-bold py-3 rounded-3xl text-lg 
          transition-transform duration-300 hover:scale-105 hover:shadow-md text-center">
                    Selesai
                </a>
        </div>
    </div>

    <script>
        let orderId = null;
        let checkStatusInterval = null;

        // Get URL parameters
        function getUrlParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Bank mapping for logos and names
        const bankMapping = {
            'BCA': {
                logo: '{{ asset("images/payment/BCA.jpg") }}',
                name: 'Bank BCA'
            },
            'BRI': {
                logo: '{{ asset("images/payment/BRI.png") }}',
                name: 'Bank BRI'
            },
            'CIMB': {
                logo: '{{ asset("images/payment/CIMBNIAGA.jpeg") }}',
                name: 'Bank CIMB Niaga'
            },
            'BNI': {
                logo: '{{ asset("images/payment/BNI.png") }}',
                name: 'Bank BNI'
            },
            'Mandiri': {
                logo: '{{ asset("images/payment/Mandiri.png") }}',
                name: 'Bank Mandiri'
            },
            'BSI': {
                logo: '{{ asset("images/payment/BSI1.png") }}',
                name: 'Bank BSI'
            }
        };

        // Initialize payment data
        function initializePayment() {
            orderId = getUrlParameter('order_id');
            const vaNumber = getUrlParameter('va_number');
            const amount = getUrlParameter('amount');
            const bank = getUrlParameter('bank');

            // Update bank information
            if (bank && bankMapping[bank]) {
                document.getElementById('bankLogo').src = bankMapping[bank].logo;
                document.getElementById('bankLogo').alt = bank;
                document.getElementById('bankName').textContent = bankMapping[bank].name;
            }

            if (vaNumber && vaNumber !== 'null' && vaNumber !== 'undefined' && vaNumber !== 'pending') {
                document.getElementById('vaNumber').textContent = vaNumber;
            } else {
                document.getElementById('vaNumber').textContent = 'Menunggu VA Number...';
                // Try to get VA number from payment status
                if (orderId) {
                    checkPaymentStatus();
                }
            }

            if (amount && amount !== 'undef' && amount !== 'undefined' && amount !== 'pending' && !isNaN(parseInt(amount))) {
                document.getElementById('paymentAmount').textContent = 'Rp ' + parseInt(amount).toLocaleString('id-ID');
            } else {
                document.getElementById('paymentAmount').textContent = 'Menunggu Amount...';
            }

            if (orderId) {
                // Start checking payment status
                checkPaymentStatus();
                checkStatusInterval = setInterval(checkPaymentStatus, 10000); // Check every 10 seconds
            }
        }

        // Check payment status
        async function checkPaymentStatus() {
            if (!orderId) return;

            try {
                const response = await fetch(`{{ url('payment/status') }}/${orderId}`);
                const result = await response.json();

                if (result.status) {
                    updatePaymentStatus(result.status, result.booking_status);
                    
                    // Update VA number if available
                    if (result.va_number && result.va_number !== 'null') {
                        document.getElementById('vaNumber').textContent = result.va_number;
                    }
                    
                    // Update amount if available
                    if (result.amount && !isNaN(parseInt(result.amount))) {
                        document.getElementById('paymentAmount').textContent = 'Rp ' + parseInt(result.amount).toLocaleString('id-ID');
                    }
                    
                    if (result.status === 'settlement' || result.status === 'capture') {
                        clearInterval(checkStatusInterval);
                        showSuccessMessage();
                    } else if (['deny', 'cancel', 'expire', 'failure'].includes(result.status)) {
                        clearInterval(checkStatusInterval);
                        showFailureMessage();
                    }
                }
            } catch (error) {
                console.error('Error checking payment status:', error);
            }
        }

        // Update payment status display
        function updatePaymentStatus(status, bookingStatus) {
            const statusElement = document.getElementById('paymentStatus');
            const statusContainer = statusElement.parentElement;

            switch (status) {
                case 'pending':
                    statusElement.textContent = 'Menunggu Pembayaran';
                    statusContainer.className = 'bg-yellow-100 p-3 rounded-lg';
                    statusElement.className = 'text-yellow-800 font-semibold';
                    break;
                case 'settlement':
                case 'capture':
                    statusElement.textContent = 'Pembayaran Berhasil';
                    statusContainer.className = 'bg-green-100 p-3 rounded-lg';
                    statusElement.className = 'text-green-800 font-semibold';
                    break;
                case 'deny':
                case 'cancel':
                case 'expire':
                case 'failure':
                    statusElement.textContent = 'Pembayaran Gagal';
                    statusContainer.className = 'bg-red-100 p-3 rounded-lg';
                    statusElement.className = 'text-red-800 font-semibold';
                    break;
            }
        }

        // Show success message
        function showSuccessMessage() {
            alert('Pembayaran berhasil! Booking Anda telah dikonfirmasi.');
        }

        // Show failure message
        function showFailureMessage() {
            alert('Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Nomor Virtual Account berhasil disalin!');
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', initializePayment);

        // Simulate payment (for testing)
        async function simulatePayment(type) {
            if (!orderId) {
                alert('Order ID tidak ditemukan');
                return;
            }

            const button = document.getElementById(`simulate${type.charAt(0).toUpperCase() + type.slice(1)}`);
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Memproses...';

            try {
                const response = await fetch(`{{ url('payment/simulate') }}/${type}/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Update status immediately
                    updatePaymentStatus(result.payment_status, result.booking_status);
                    
                    if (type === 'success') {
                        showSuccessMessage();
                    } else {
                        showFailureMessage();
                    }
                } else {
                    alert('Simulasi gagal: ' + result.message);
                }
            } catch (error) {
                console.error('Simulation error:', error);
                alert('Terjadi kesalahan saat simulasi');
            } finally {
                button.disabled = false;
                button.textContent = originalText;
            }
        }

        // Clean up interval when page unloads
        window.addEventListener('beforeunload', function() {
            if (checkStatusInterval) {
                clearInterval(checkStatusInterval);
            }
        });
    </script>
</body>

</html>
