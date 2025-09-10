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

<body class="h-full bg-[#00A2FA] font-sans lg:bg-gray-50">

    <!-- Mobile Layout -->
    <div class="lg:hidden">
        <!-- ... existing mobile code ... -->
        <div class="pt-8 pb-4 px-6 text-center -mt-4">
            <img src="{{ asset('images/Logo-Hospitalink3.png') }}" alt="HOSPITALINK" class="mx-auto h-48 mb-1">
        </div>

        <div class="px-6 mb-4" x-data="carousel()">
            <div class="flex-1 bg-[#B4DBF1] rounded-t-3xl px-6 pt-6 pb-56  -mx-6">
                <div class="w-full max-w-md mx-auto">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-2xl font-bold text-gray-800">HELP CENTER</h2>
                        <a href="{{ route('invoice') }}" class="block">
                            <img src="{{ asset('images/icons/icon-notif.png') }}" alt="Notifications" class="w-7 h-9">
                        </a>
                    </div>
                    <p class="text-sm text-gray-700 mb-6">
                        Jika anda membutuhkan bantuan, silahkan hubungi kontak dibawah.
                    </p>

                    <div class="space-y-4">
                        <div onclick="showContact('hotline')" class="flex items-center bg-[#9AC1D6] rounded-xl px-4 py-3 shadow hover:bg-[#8BB5CD] transition cursor-pointer">
                            <img src="{{ asset('images/icons/icon-hotline.png') }}" alt="Hotline" class="w-7 h-7 mr-3">
                            <span id="hotline-text" class="font-semibold text-gray-800">HOTLINE</span>
                        </div>
                        <div onclick="showContact('message')" class="flex items-center bg-[#9AC1D6] rounded-xl px-4 py-3 shadow hover:bg-[#8BB5CD] transition cursor-pointer">
                            <img src="{{ asset('images/icons/icon-message.png') }}" alt="Message" class="w-7 h-7 mr-3">
                            <span id="message-text" class="font-semibold text-gray-800">MESSAGE</span>
                        </div>
                        <div onclick="showContact('email')" class="flex items-center bg-[#9AC1D6] rounded-xl px-4 py-3 shadow hover:bg-[#8BB5CD] transition cursor-pointer">
                            <img src="{{ asset('images/icons/icon-email.png') }}" alt="Email" class="w-7 h-7 mr-3">
                            <span id="email-text" class="font-semibold text-gray-800">EMAIL</span>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end pr-0">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 bg-red-600 text-white px-6 py-2 rounded-full shadow hover:bg-red-700 transition">
                                <img src="{{ asset('images/icons/icon-logout.png') }}" alt="Logout" class="w-5 h-5">
                                <span>LOG OUT</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200">
            <div class="flex items-center justify-around py-2">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2">
                    <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home" class="w-6 h-6 mb-1 opacity-50">
                    <span class="text-xs text-gray-400">Home</span>
                </a>
                <a href="{{ route('hospital') }}" class="flex flex-col items-center py-2">
                    <img src="{{ asset('images/icons/icon-hospital.png') }}" alt="Hospital" class="w-7 h-7 mb-1 opacity-50">
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

    <!-- <CHANGE> Added complete desktop layout for help page -->
    <div class="hidden lg:flex h-screen">
        <!-- Sidebar Navigation -->
        <div class="w-64 xl:w-72 bg-white shadow-lg flex flex-col">
            <!-- Logo Section -->
            <div class="p-6 xl:p-8 border-b border-gray-200">
                <img src="{{ asset('images/Logo-Hospitalink3.png') }}" alt="HOSPITALINK" class="h-12 xl:h-16 mx-auto">
            </div>
            
            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 xl:p-6">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 xl:py-4 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                            <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home" class="w-6 h-6 xl:w-7 xl:h-7 mr-3 opacity-60 group-hover:opacity-100">
                            <span class="font-medium">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hospital') }}" class="flex items-center px-4 py-3 xl:py-4 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                            <img src="{{ asset('images/icons/icon-hospital.png') }}" alt="Hospital" class="w-6 h-6 xl:w-7 xl:h-7 mr-3 opacity-60 group-hover:opacity-100">
                            <span class="font-medium">Hospital</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('room') }}" class="flex items-center px-4 py-3 xl:py-4 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                            <img src="{{ asset('images/icons/icon-room.png') }}" alt="Room" class="w-6 h-6 xl:w-7 xl:h-7 mr-3 opacity-60 group-hover:opacity-100">
                            <span class="font-medium">Room</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('help') }}" class="flex items-center px-4 py-3 xl:py-4 text-[#00A2FA] bg-blue-50 rounded-lg">
                            <img src="{{ asset('images/icons/icon-help.png') }}" alt="Help" class="w-6 h-6 xl:w-7 xl:h-7 mr-3">
                            <span class="font-medium">Help</span>
                            <div class="ml-auto w-2 h-2 bg-[#00A2FA] rounded-full"></div>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 overflow-y-auto bg-gray-50">
            <!-- Header Section -->
            <div class="bg-[#00A2FA] px-8 xl:px-12 py-8 xl:py-12">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-3xl xl:text-4xl font-bold text-white mb-4">HELP CENTER</h1>
                    <p class="text-lg xl:text-xl text-blue-100">Kami siap membantu Anda 24/7</p>
                </div>
            </div>

            <!-- Help Content Section -->
            <div class="px-8 xl:px-12 py-8 xl:py-12">
                <div class="max-w-4xl mx-auto">
                    <!-- Description -->
                    <div class="text-center mb-12">
                        <p class="text-lg xl:text-xl text-gray-600 leading-relaxed">
                            Jika Anda membutuhkan bantuan, silahkan hubungi kontak di bawah ini.<br>
                            Tim support kami akan dengan senang hati membantu Anda.
                        </p>
                    </div>

                    <!-- Contact Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 xl:gap-8 mb-12">
                        <!-- Hotline Card -->
                        <div onclick="showContactDesktop('hotline')" class="bg-white rounded-2xl xl:rounded-3xl p-6 xl:p-8 shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer group hover:scale-105">
                            <div class="text-center">
                                <div class="w-16 h-16 xl:w-20 xl:h-20 mx-auto mb-4 bg-blue-50 rounded-full flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                                    <img src="{{ asset('images/icons/icon-hotline.png') }}" alt="Hotline" class="w-8 h-8 xl:w-10 xl:h-10">
                                </div>
                                <h3 class="text-xl xl:text-2xl font-bold text-gray-800 mb-2">HOTLINE</h3>
                                <p id="hotline-text-desktop" class="text-lg xl:text-xl text-[#00A2FA] font-semibold">Klik untuk melihat</p>
                                <p class="text-sm xl:text-base text-gray-500 mt-2">Hubungi langsung via telepon</p>
                            </div>
                        </div>

                        <!-- Message Card -->
                        <div onclick="showContactDesktop('message')" class="bg-white rounded-2xl xl:rounded-3xl p-6 xl:p-8 shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer group hover:scale-105">
                            <div class="text-center">
                                <div class="w-16 h-16 xl:w-20 xl:h-20 mx-auto mb-4 bg-green-50 rounded-full flex items-center justify-center group-hover:bg-green-100 transition-colors">
                                    <img src="{{ asset('images/icons/icon-message.png') }}" alt="Message" class="w-8 h-8 xl:w-10 xl:h-10">
                                </div>
                                <h3 class="text-xl xl:text-2xl font-bold text-gray-800 mb-2">WHATSAPP</h3>
                                <p id="message-text-desktop" class="text-lg xl:text-xl text-[#00A2FA] font-semibold">Klik untuk melihat</p>
                                <p class="text-sm xl:text-base text-gray-500 mt-2">Chat via WhatsApp</p>
                            </div>
                        </div>

                        <!-- Email Card -->
                        <div onclick="showContactDesktop('email')" class="bg-white rounded-2xl xl:rounded-3xl p-6 xl:p-8 shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer group hover:scale-105">
                            <div class="text-center">
                                <div class="w-16 h-16 xl:w-20 xl:h-20 mx-auto mb-4 bg-red-50 rounded-full flex items-center justify-center group-hover:bg-red-100 transition-colors">
                                    <img src="{{ asset('images/icons/icon-email.png') }}" alt="Email" class="w-8 h-8 xl:w-10 xl:h-10">
                                </div>
                                <h3 class="text-xl xl:text-2xl font-bold text-gray-800 mb-2">EMAIL</h3>
                                <p id="email-text-desktop" class="text-lg xl:text-xl text-[#00A2FA] font-semibold">Klik untuk melihat</p>
                                <p class="text-sm xl:text-base text-gray-500 mt-2">Kirim email ke support</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Section -->
                    <div class="bg-white rounded-2xl xl:rounded-3xl p-6 xl:p-8 shadow-md mb-8">
                        <h3 class="text-2xl xl:text-3xl font-bold text-gray-800 mb-6 text-center">Frequently Asked Questions</h3>
                        <div class="space-y-4">
                            <div class="border-b border-gray-200 pb-4">
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Bagaimana cara memesan kamar rumah sakit?</h4>
                                <p class="text-gray-600">Anda dapat memesan kamar melalui halaman Room, pilih rumah sakit yang diinginkan, dan ikuti proses pemesanan.</p>
                            </div>
                            <div class="border-b border-gray-200 pb-4">
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Apakah bisa membatalkan pemesanan?</h4>
                                <p class="text-gray-600">Ya, Anda dapat membatalkan pemesanan melalui halaman My Bookings atau menghubungi customer service kami.</p>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">Bagaimana cara melihat riwayat pemesanan?</h4>
                                <p class="text-gray-600">Riwayat pemesanan dapat dilihat di halaman My Bookings yang dapat diakses melalui ikon notifikasi.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Logout Section -->
                    <div class="text-center">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 bg-red-600 text-white px-8 py-3 xl:px-10 xl:py-4 rounded-full shadow-lg hover:bg-red-700 transition-all duration-300 hover:scale-105 mx-auto">
                                <img src="{{ asset('images/icons/icon-logout.png') }}" alt="Logout" class="w-5 h-5 xl:w-6 xl:h-6">
                                <span class="text-lg xl:text-xl font-semibold">LOG OUT</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile contact display
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

        // Desktop contact display
        function showContactDesktop(type) {
            // Reset all
            document.getElementById('hotline-text-desktop').innerText = 'Klik untuk melihat';
            document.getElementById('message-text-desktop').innerText = 'Klik untuk melihat';
            document.getElementById('email-text-desktop').innerText = 'Klik untuk melihat';
            
            if (type === 'hotline') {
                document.getElementById('hotline-text-desktop').innerText = '081-354-011';
            } else if (type === 'message') {
                document.getElementById('message-text-desktop').innerText = '081-354-011';
            } else if (type === 'email') {
                document.getElementById('email-text-desktop').innerText = 'ehospital.app@gmail.com';
            }
        }
    </script>
</body>

</html>