<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOSPITALINK - Login atau Daftar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'hospitalink-blue': '#00A2FA',
                        'hospitalink-green': '#0B9078',
                        'hospitalink-light-green': '#13F6A4'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-hospitalink-blue min-h-screen">

    <div class="lg:hidden flex flex-col items-center justify-center min-h-screen px-6">

        <div class="flex flex-col items-center mb-20">
            <img src="{{ asset('images/Logo-Hospitalink.png') }}" alt="HOSPITALINK Logo" class="w-128 mb-6">

        </div>


        <div class="w-full max-w-sm space-y-4">
            <a href="{{ route('login') }}"
                class="block w-full bg-hospitalink-green text-white text-xl font-semibold py-4 px-8 rounded-full text-center hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl">
                Log In
            </a>
            <a href="{{ route('register') }}"
                class="block w-full bg-hospitalink-light-green text-white text-xl font-semibold py-4 px-8 rounded-full text-center hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl">
                Sign Up
            </a>
        </div>
    </div>

    
    <div class="hidden lg:flex min-h-screen">
        
        <div class="w-2/5 flex flex-col justify-center items-center bg-hospitalink-blue">
            <div class="text-center">
                <img src="{{ asset('images/Logo-Hospitalink.png') }}" alt="HOSPITALINK Logo" class="w-112 mb-8 mx-auto">
                
            </div>
        </div>

        
        <div class="w-3/5 flex flex-col justify-center items-center bg-gray-50">
            <div class="max-w-md w-full px-8">
                <h2 class="text-gray-800 text-3xl font-bold text-center mb-2">Selamat Datang Kembali</h2>
                <p class="text-gray-600 text-center mb-12">Pilih cara untuk melanjutkan</p>

                <div class="flex space-x-4">
                    <a href="{{ route('login') }}"
                        class="flex-1 bg-hospitalink-green text-white text-lg font-semibold py-4 px-6 rounded-full text-center hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl">
                        Log In
                    </a>
                    <a href="{{ route('register') }}"
                        class="flex-1 bg-hospitalink-light-green text-white text-lg font-semibold py-4 px-6 rounded-full text-center hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl">
                        Sign Up
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
