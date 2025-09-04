<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOSPITALINK - Help</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full bg-[#00A2FA] font-sans">

    <!-- ✅ Logo -->
    <div class="pt-8 pb-4 px-6 text-center -mt-4">
        <img src="{{ asset('images/Logo-Hospitalink3.png') }}" alt="HOSPITALINK" class="mx-auto h-48 mb-1">
    </div>

    <div class="px-6 mb-4" x-data="carousel()">

        <!-- ✅ Card Help Center -->
        <div class="flex-1 bg-[#B4DBF1] rounded-t-3xl px-6 pt-6 pb-24 -mx-6">
            <div class="w-full max-w-md mx-auto">

                <!-- Header -->
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-2xl font-bold text-gray-800">HELP CENTER</h2>
                    <div class="relative pr-5 pt-1">
                        <img src="{{ asset('images/icons/icon-notif.png') }}" alt="Notifications" class="w-7 h-9">
                    </div>
                </div>
                <p class="text-sm text-gray-700 mb-6">
                    Jika anda membutuhkan bantuan, silahkan hubungi kontak dibawah.
                </p>

                <!-- ✅ Card Buttons -->
                <div class="space-y-4">
                    <!-- Hotline -->
                    <div onclick="showContact('hotline')"
                        class="flex items-center bg-[#9AC1D6] rounded-xl px-4 py-3 shadow hover:bg-[#8BB5CD] transition cursor-pointer">
                        <img src="{{ asset('images/icons/icon-hotline.png') }}" alt="Hotline" class="w-7 h-7 mr-3">
                        <span id="hotline-text" class="font-semibold text-gray-800">HOTLINE</span>
                    </div>

                    <!-- Message -->
                    <div onclick="showContact('message')"
                        class="flex items-center bg-[#9AC1D6] rounded-xl px-4 py-3 shadow hover:bg-[#8BB5CD] transition cursor-pointer">
                        <img src="{{ asset('images/icons/icon-message.png') }}" alt="Message" class="w-7 h-7 mr-3">
                        <span id="message-text" class="font-semibold text-gray-800">MESSAGE</span>
                    </div>

                    <!-- Email -->
                    <div onclick="showContact('email')"
                        class="flex items-center bg-[#9AC1D6] rounded-xl px-4 py-3 shadow hover:bg-[#8BB5CD] transition cursor-pointer">
                        <img src="{{ asset('images/icons/icon-email.png') }}" alt="Email" class="w-7 h-7 mr-3">
                        <span id="email-text" class="font-semibold text-gray-800">EMAIL</span>
                    </div>
                </div>

                <!-- ✅ Logout -->
                <div class="mt-8 flex justify-end pr-0">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 bg-red-600 text-white px-6 py-2 rounded-full shadow hover:bg-red-700 transition">
                            <img src="{{ asset('images/icons/icon-logout.png') }}" alt="Logout" class="w-5 h-5">
                            <span>LOG OUT</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <!-- ✅ Bottom Navbar -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200">
            <div class="flex items-center justify-around py-2">

                <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2">
                    <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home" class="w-6 h-6 mb-1 opacity-50">
                    <span class="text-xs text-gray-400">Home</span>
                </a>

                <a href="{{ route('hospital') }}" class="flex flex-col items-center py-2">
                    <img src="{{ asset('images/icons/icon-hospital.png') }}" alt="Hospital"
                        class="w-7 h-7 mb-1 opacity-50">
                    <span class="text-xs text-gray-400">Hospital</span>
                </a>

                <a href="{{ route('room') }}" class="flex flex-col items-center py-2">
                    <img src="{{ asset('images/icons/icon-room.png') }}" alt="Room" class="w-7 h-7 mb-1 opacity-50">
                    <span class="text-xs text-gray-400">Room</span>
                </a>

                <div class="flex flex-col items-center py-2">
                    <img src="{{ asset('images/icons/icon-help.png') }}" alt="Help" class="w-7 h-7 mb-1">
                    <span class="text-xs text-[#000000]">Help</span>
                    <div class="w-1 h-1 bg-[#00A2FA] rounded-full mt-1"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Script -->
    <script>
        function showContact(type) {
            if (type === 'hotline') {
                document.getElementById('hotline-text').innerText = '081-354-011';
                document.getElementById('message-text').innerText = 'MESSAGE';
                document.getElementById('email-text').innerText = 'EMAIL';
            } else if (type === 'message') {
                document.getElementById('hotline-text').innerText = 'HOTLINE';
                document.getElementById('message-text').innerText = 'Whatsapp: 081-354-011';
                document.getElementById('email-text').innerText = 'EMAIL';
            } else if (type === 'email') {
                document.getElementById('hotline-text').innerText = 'HOTLINE';
                document.getElementById('message-text').innerText = 'MESSAGE';
                document.getElementById('email-text').innerText = 'ehospital.app@gmail.com';
            }
        }
    </script>
</body>

</html>
