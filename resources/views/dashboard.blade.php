<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOSPITALINK - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-hospitalink-blue {
            background-color: #00A2FA;
        }
        .bg-hospitalink-green {
            background-color: #0B9078;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-center font-bold text-lg">
                    OJO TUMBANG YO JOI CEMUNGUT
                </div>
            @endif
            
            <!-- Logout Button -->
            <div class="flex justify-center mb-6">
                <button onclick="logout()" class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors font-semibold">
                    Logout
                </button>
            </div>

            <!-- mangat joi-->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">AAHAHAH COK SUMPAH NGAKAK AKU</h2>
                <div class="flex justify-center">
                    <div class="relative">
                        <img src="{{ asset('images/semoga_fe_ne_gak_tumbang.jpeg') }}" 
                             alt="Foto Teman" 
                             class="w-80 h-96 object-cover rounded-lg border-4 border-hospitalink-green shadow-lg"
                             onerror="this.src='{{ asset('images/Logo-Hospitalink.png') }}'">
                    </div>
                </div>
                <div class="text-center mt-4">
                    <h3 class="text-lg font-semibold text-gray-800">Semoga FE Ne Gak Tumbang</h3>
                    <p class="text-gray-600">sek ono piro page maneh? SEMANGATTT JOOIIII</p>
                </div>
            </div>


        </div>
    </div>

    <script>
        function logout() {
            // Clear token from localStorage
            localStorage.removeItem('auth_token');
            
            // Redirect to login page
            window.location.href = '/login';
        }
    </script>
</body>
</html>
