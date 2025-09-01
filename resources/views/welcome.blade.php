<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOSPITALINK - Peduli Akses Kesehatan Anda</title>
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

        <div class="flex flex-col items-center mb-16">
            <img src="{{ asset('images/Logo-Hospitalink.png') }}" alt="HOSPITALINK Logo" class="w-128 mb-6">

        </div>


        <div class="w-full max-w-sm">
            <a href="{{ route('auth') }}"
                class="block w-full bg-hospitalink-green text-white text-xl font-semibold py-4 px-8 rounded-full text-center hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl">
                Get Started
            </a>
        </div>
    </div>

    
    <div class="hidden lg:flex min-h-screen">
        
        <div class="w-1/2 flex flex-col justify-center items-start px-16">
            <div class="max-w-md">
                <img src="{{ asset('images/logo-hospitalink.png') }}" alt="HOSPITALINK Logo" class="w-112 mb-6">
                

                <a href="{{ route('auth') }}"
                    class="inline-block bg-hospitalink-green text-white text-xl font-semibold py-4 px-12 rounded-full hover:bg-opacity-90 transition-all duration-200">
                    Get Started
                </a>
            </div>
        </div>

        
        <div class="w-1/2 flex items-center justify-center bg-white bg-opacity-10">
            <div class="text-center">
                <div class="w-80 h-80 bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                </div>
                <p class="text-white text-xl font-medium">Akses Kesehatan Terpercaya</p>
            </div>
        </div>
    </div>
</body>

</html>
