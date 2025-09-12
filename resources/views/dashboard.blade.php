<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOSPITALINK - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full bg-[#00A2FA] font-sans">

    <!-- Mobile Layout (unchanged) -->
    <div class="lg:hidden min-h-screen flex flex-col">
        <div class="pt-8 pb-4 px-6 text-center -mt-4">
            <img src="{{ asset('images/Logo-Hospitalink2.png') }}" alt="HOSPITALINK" class="mx-auto h-24 mb-1">
        </div>

        <div class="px-6 mb-4" x-data="carousel()">
            <!-- Container Iklan -->
            <div class="bg-[#0B9078] rounded-2xl mb-4 overflow-hidden max-w-md mx-auto shadow-lg">
            <!-- Slider Container with proper overflow handling -->
            <div class="mx-6 mt-4 rounded-2xl overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out"
                    :style="`transform: translateX(-${currentSlide * 100}%)`">

                    <!-- Slide 1 -->
                    <div class="w-full flex-shrink-0 relative rounded-2xl overflow-hidden">
                        <div class="absolute inset-0 bg-cover bg-center"
                            style="background-image: url('/images/Iklan/Iklan-Slide1.jpg');">
                            <div class="absolute inset-0 bg-black/40"></div>
                        </div>
                        <div class="relative z-10 flex items-center justify-between p-3 h-32">
                            <div class="text-white text-[10px] leading-tight">
                                <h3 class="font-bold text-xs mb-1">TURUN HARGA</h3>
                                <h2 class="font-bold text-sm mb-1">BESAR-BESARAN</h2>
                                <p>
                                    SEMUA PRODUK<br>TURUN HINGGA 50%<br>BURUAN SEBELUM<br>KEHABISAN
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="w-full flex-shrink-0 relative rounded-2xl overflow-hidden">
                       <div class="absolute inset-0 bg-cover bg-center"
                            style="background-image: url('/images/Iklan/Iklan-Slide2.png');">
                            <div class="absolute inset-0 bg-black/40"></div>
                        </div>
                        <div class="relative z-10 flex items-center justify-between p-3 h-32">
                            <div class="text-white text-[10px] leading-tight">
                                <h3 class="font-bold text-xs mb-1">PROMO SPESIAL</h3>
                                <h2 class="font-bold text-sm mb-1">ALAT KESEHATAN</h2>
                                <p>
                                    DAPATKAN DISKON<br>HINGGA 40%<br>UNTUK SEMUA<br>PRODUK PILIHAN
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="w-full flex-shrink-0 relative rounded-2xl overflow-hidden">
                        <div class="absolute inset-0 bg-cover bg-center"
                            style="background-image: url('/images/Iklan/Iklan-Slide3.jpg');">
                            <div class="absolute inset-0 bg-black/40"></div>
                        </div>
                        <div class="relative z-10 flex items-center justify-between p-3 h-32">
                            <div class="text-white text-[10px] leading-tight">
                                <h3 class="font-bold text-xs mb-1">KONSULTASI</h3>
                                <h2 class="font-bold text-sm mb-1">GRATIS</h2>
                                <p>
                                    KONSULTASI DENGAN<br>DOKTER AHLI<br>TANPA BIAYA<br>TAMBAHAN
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dots indikator -->
            <div class="flex justify-center space-x-2 py-2">
                <template x-for="(slide, index) in slides" :key="index">
                    <button class="w-2.5 h-2.5 rounded-full transition-all"
                        :class="currentSlide === index ? 'bg-white' : 'bg-white/50'" 
                        @click="goToSlide(index)">
                    </button>
                </template>
            </div>

            <!-- CTA -->
            <div class="flex items-center justify-between px-4 py-2 bg-[#0B9078] text-white rounded-b-2xl">
                <span class="font-medium text-sm">Anda Miliki Iklan?</span>
                <button
                    class="border border-white px-3 py-1 rounded-lg text-sm hover:bg-white hover:text-green-700 transition">
                    Masuk di sini
                </button>
            </div>
        </div>


            <div class="flex-1 bg-[#B4DBF1] rounded-t-3xl px-6 pt-6 pb-20 -mx-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-3xl font-bold text-gray-800 pl-4">HEALTH NEWS</h2>
                    <a href="{{ route('invoice') }}" class="block">
                        <img src="{{ asset('images/icons/icon-notif.png') }}" alt="Notifications" class="w-7 h-9">
                    </a>
                </div>

                <div class="space-y-4">
                    <a href="{{ route('news.detail', ['slug' => 'flu-singapura-indonesia']) }}" class="block">
                        <div
                            class="bg-[#9AC1D6] rounded-2xl p-4 shadow-md mx-auto hover:bg-[#8BB5CD] transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 pr-6">
                                    <h3 class="font-bold text-sm text-gray-800 mb-2 leading-tight line-clamp-3">Kasus
                                        FLU Singapura
                                        di
                                        Indonesia Meroket, Tembus hingga 5 Ribu</h3>
                                    <p class="text-xs text-gray-600">SINDONEWS.com</p>
                                </div>
                                <div class="flex-shrink-0 w-28">
                                    <div class="aspect-[4/3] bg-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ asset('images/news/news-card1.png') }}" alt="Flu Singapura"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('news.detail', ['slug' => 'kanker-usia-muda']) }}" class="block">
                        <div
                            class="bg-[#9AC1D6] rounded-2xl p-4 shadow-md mx-auto hover:bg-[#8BB5CD] transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 pr-6">
                                    <h3 class="font-bold text-sm text-gray-800 mb-2 leading-tight line-clamp-3">
                                        Kebiasaan yang Tak
                                        Disadari Picu Kanker Usus Besar di Usia Muda</h3>
                                    <p class="text-xs text-gray-600">Detikhealth.com</p>
                                </div>
                                <div class="flex-shrink-0 w-28">
                                    <div class="aspect-[4/3] bg-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ asset('images/news/news-card2.jpg') }}" alt="Kanker Usus"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('news.detail', ['slug' => 'olahraga-kanker']) }}" class="block">
                        <div
                            class="bg-[#9AC1D6] rounded-2xl p-4 shadow-md mx-auto hover:bg-[#8BB5CD] transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 pr-6">
                                    <h3 class="font-bold text-sm text-gray-800 mb-2 leading-tight line-clamp-3">Olahraga
                                        Lari Bisa
                                        Turunkan Risiko Kanker dan Kematian Dini</h3>
                                    <p class="text-xs text-gray-600">KOMPAS.com</p>
                                </div>
                                <div class="flex-shrink-0 w-28">
                                    <div class="aspect-[4/3] bg-gray-200 rounded-lg overflow-hidden">
                                        <img src="{{ asset('images/news/news-card3.jpg') }}" alt="Olahraga Lari"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Mobile Bottom Navigation -->
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200">
                <div class="flex items-center justify-around py-2">
                    <div class="flex flex-col items-center py-2">
                        <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home" class="w-6 h-6 mb-1">
                        <span class="text-xs text-black">Home</span>
                        <div class="w-1 h-1 bg-[#00A2FA] rounded-full mt-1"></div>
                    </div>

                    <a href="{{ route('hospital') }}" class="flex flex-col items-center py-2">
                        <img src="{{ asset('images/icons/icon-hospital.png') }}" alt="Hospital"
                            class="w-7 h-7 mb-1 opacity-50 hover:opacity-100">
                        <span class="text-xs text-gray-400 hover:text-black">Hospital</span>
                    </a>

                    <a href="{{ route('room') }}" class="flex flex-col items-center py-2">
                        <img src="{{ asset('images/icons/icon-room.png') }}" alt="Room"
                            class="w-7 h-7 mb-1 opacity-50">
                        <span class="text-xs text-gray-400">Room</span>
                    </a>

                    <a href="{{ route('help') }}" class="flex flex-col items-center py-2">
                        <img src="{{ asset('images/icons/icon-help.png') }}" alt="Help"
                            class="w-7 h-7 mb-1 opacity-50">
                        <span class="text-xs text-gray-400">Help</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Desktop Layout with better responsive design -->
    <div class="hidden lg:flex h-screen bg-gray-50">
        <!-- Enhanced Sidebar with better spacing and hover effects -->
        <div class="w-64 xl:w-72 bg-white shadow-lg flex flex-col">
            <!-- Added logo to desktop sidebar -->
            <div class="p-6 xl:p-8 border-b border-gray-200">
                <img src="{{ asset('images/Logo-Hospitalink2.png') }}" alt="HOSPITALINK"
                    class="h-12 xl:h-16 mx-auto">
            </div>

            <nav class="flex-1 p-4 xl:p-6">
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center px-4 py-3 xl:py-4 text-[#00A2FA] bg-blue-50 rounded-lg">
                            <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home"
                                class="w-6 h-6 xl:w-7 xl:h-7 mr-3">
                            <span class="font-medium">Home</span>
                            <div class="ml-auto w-2 h-2 bg-[#00A2FA] rounded-full"></div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hospital') }}"
                            class="flex items-center px-4 py-3 xl:py-4 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                            <img src="{{ asset('images/icons/icon-hospital.png') }}" alt="Hospital"
                                class="w-6 h-6 xl:w-7 xl:h-7 mr-3 opacity-60 group-hover:opacity-100">
                            <span class="font-medium">Hospital</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('room') }}"
                            class="flex items-center px-4 py-3 xl:py-4 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                            <img src="{{ asset('images/icons/icon-room.png') }}" alt="Room"
                                class="w-6 h-6 xl:w-7 xl:h-7 mr-3 opacity-60 group-hover:opacity-100">
                            <span class="font-medium">Room</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('help') }}"
                            class="flex items-center px-4 py-3 xl:py-4 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                            <img src="{{ asset('images/icons/icon-help.png') }}" alt="Help"
                                class="w-6 h-6 xl:w-7 xl:h-7 mr-3 opacity-60 group-hover:opacity-100">
                            <span class="font-medium">Help</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Enhanced Main Content with better responsive layout -->
        <div class="flex-1 overflow-y-auto">
            <!-- Enhanced Header with better typography and spacing -->
            <div class="bg-[#00A2FA] px-8 xl:px-12 py-8 xl:py-12 text-center">
                <img src="{{ asset('images/logo-hospitalink.png') }}" alt="HOSPITALINK"
                    class="mx-auto h-32 xl:h-40 2xl:h-48 mb-3 xl:mb-4">

                <!-- Enhanced Carousel Section with better responsive design -->
                <div class="overflow-hidden rounded-2xl mb-4 max-w-3xl mx-auto relativ" x-data="carousel()">
                    <!-- Enhanced Carousel Container with better styling -->
                    <div class="rounded-2xl mb-4 max-w-3xl mx-auto">
                        <div class="flex transition-transform duration-500 ease-in-out"
                            :style="`transform: translateX(-${currentSlide * 100}%)`">

                            <!-- Slide 1 -->
                            <div class="w-full flex-shrink-0 relative rounded-2xl overflow-hidden h-48">
                                <div class="absolute inset-0 bg-cover bg-center"
                                    style="background-image: url('/images/Iklan/Iklan-Slide1.jpg');">
                                    <div class="absolute inset-0 bg-black/40"></div>
                                </div>
                                <div class="relative z-10 flex items-center h-full p-4">
                                    <div class="text-white">
                                        <h3 class="font-bold text-sm mb-1">TURUN HARGA</h3>
                                        <h2 class="font-bold text-lg mb-1">BESAR-BESARAN</h2>
                                        <p class="text-xs leading-tight">
                                            SEMUA PRODUK<br>TURUN HINGGA 50%<br>BURUAN SEBELUM<br>DAN KEHABISAN
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 2 -->
                            <div class="w-full flex-shrink-0 relative rounded-2xl overflow-hidden h-48">
                                <div class="absolute inset-0 bg-cover bg-center"
                                    style="background-image: url('/images/Iklan/Iklan-Slide2.png');">
                                    <div class="absolute inset-0 bg-black/40"></div>
                                </div>
                                <div class="relative z-10 flex items-center h-full p-4">
                                    <div class="text-white">
                                        <h3 class="font-bold text-sm mb-1">PROMO SPESIAL</h3>
                                        <h2 class="font-bold text-lg mb-1">ALAT KESEHATAN</h2>
                                        <p class="text-xs leading-tight">
                                            DAPATKAN DISKON<br>HINGGA 40%<br>UNTUK SEMUA<br>PRODUK PILIHAN
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 3 -->
                            <div class="w-full flex-shrink-0 relative rounded-2xl overflow-hidden h-48">
                                <div class="absolute inset-0 bg-cover bg-center"
                                    style="background-image: url('/images/Iklan/Iklan-Slide3.jpg');">
                                    <div class="absolute inset-0 bg-black/40"></div>
                                </div>
                                <div class="relative z-10 flex items-center h-full p-4">
                                    <div class="text-white">
                                        <h3 class="font-bold text-sm mb-1">KONSULTASI</h3>
                                        <h2 class="font-bold text-lg mb-1">GRATIS</h2>
                                        <p class="text-xs leading-tight">
                                            KONSULTASI DENGAN<br>DOKTER AHLI<br>TANPA BIAYA<br>TAMBAHAN
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dot indicators -->
                    <div class="flex justify-center space-x-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button class="w-2 h-2 rounded-full transition-colors duration-200"
                                :class="currentSlide === index ? 'bg-white' : 'bg-white/50'" @click="goToSlide(index)">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Enhanced Health News Section -->
            <div
                class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-t-3xl xl:rounded-t-[2rem] px-8 xl:px-12 2xl:px-16 pt-8 xl:pt-12 pb-8 xl:pb-12 min-h-[50vh]">
                <!-- Enhanced Header with better spacing -->
                <div class="flex items-center justify-between mb-8 xl:mb-12 max-w-7xl mx-auto">
                    <h2 class="text-4xl xl:text-5xl 2xl:text-6xl font-bold text-gray-800">HEALTH NEWS</h2>
                    <div class="relative group cursor-pointer">
                        <a href="{{ route('my-bookings') }}" class="p-2 xl:p-3 rounded-xl">
                            <img src="{{ asset('images/icons/icon-notif.png') }}" alt="Notifications"
                                class="w-8 h-8 xl:w-10 xl:h-10">
                        </a>
                    </div>
                </div>

                <!-- Enhanced News Cards Grid with better responsive layout -->
                <div
                    class="grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 gap-6 xl:gap-8 2xl:gap-10 max-w-7xl mx-auto">
                    <!-- Enhanced News Card 1 -->
                    <a href="{{ route('news.detail', ['slug' => 'flu-singapura-indonesia']) }}" class="block group">
                        <div
                            class="bg-white rounded-2xl xl:rounded-3xl p-6 xl:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                            <div class="mb-4 xl:mb-6">
                                <img src="{{ asset('images/news/news-card1.png') }}" alt="Flu Singapura"
                                    class="w-full h-32 xl:h-40 2xl:h-48 object-cover rounded-xl xl:rounded-2xl mb-4 xl:mb-6 group-hover:scale-105 transition-transform duration-300">
                                <h3
                                    class="font-bold text-base xl:text-lg 2xl:text-xl text-gray-800 mb-3 xl:mb-4 leading-tight group-hover:text-[#00A2FA] transition-colors">
                                    Kasus FLU Singapura di Indonesia Meroket, Tembus hingga 5 Ribu</h3>
                                <p class="text-sm xl:text-base text-gray-600 font-medium">SINDONEWS.com</p>
                            </div>
                        </div>
                    </a>

                    <!-- Enhanced News Card 2 -->
                    <a href="{{ route('news.detail', ['slug' => 'kanker-usia-muda']) }}" class="block group">
                        <div
                            class="bg-white rounded-2xl xl:rounded-3xl p-6 xl:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                            <div class="mb-4 xl:mb-6">
                                <img src="{{ asset('images/news/news-card2.jpg') }}" alt="Kanker Usus"
                                    class="w-full h-32 xl:h-40 2xl:h-48 object-cover rounded-xl xl:rounded-2xl mb-4 xl:mb-6 group-hover:scale-105 transition-transform duration-300">
                                <h3
                                    class="font-bold text-base xl:text-lg 2xl:text-xl text-gray-800 mb-3 xl:mb-4 leading-tight group-hover:text-[#00A2FA] transition-colors">
                                    Kebiasaan yang Tak Disadari Picu Kanker Usus Besar di Usia Muda</h3>
                                <p class="text-sm xl:text-base text-gray-600 font-medium">Detikhealth.com</p>
                            </div>
                        </div>
                    </a>

                    <!-- Enhanced News Card 3 -->
                    <a href="{{ route('news.detail', ['slug' => 'olahraga-kanker']) }}" class="block group">
                        <div
                            class="bg-white rounded-2xl xl:rounded-3xl p-6 xl:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                            <div class="mb-4 xl:mb-6">
                                <img src="{{ asset('images/news/news-card3.jpg') }}" alt="Olahraga Lari"
                                    class="w-full h-32 xl:h-40 2xl:h-48 object-cover rounded-xl xl:rounded-2xl mb-4 xl:mb-6 group-hover:scale-105 transition-transform duration-300">
                                <h3
                                    class="font-bold text-base xl:text-lg 2xl:text-xl text-gray-800 mb-3 xl:mb-4 leading-tight group-hover:text-[#00A2FA] transition-colors">
                                    Olahraga Lari Bisa Turunkan Risiko Kanker dan Kematian Dini</h3>
                                <p class="text-sm xl:text-base text-gray-600 font-medium">KOMPAS.com</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function carousel() {
            return {
                currentSlide: 0,
                slides: [1, 2, 3],
                autoSlideInterval: null,

                init() {
                    this.startAutoSlide();
                },

                startAutoSlide() {
                    this.autoSlideInterval = setInterval(() => {
                        this.nextSlide();
                    }, 4000);
                },

                stopAutoSlide() {
                    if (this.autoSlideInterval) {
                        clearInterval(this.autoSlideInterval);
                    }
                },

                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                },

                goToSlide(index) {
                    this.currentSlide = index;
                    this.stopAutoSlide();
                    setTimeout(() => {
                        this.startAutoSlide();
                    }, 5000);
                }
            }
        }
    </script>
</body>

</html>
