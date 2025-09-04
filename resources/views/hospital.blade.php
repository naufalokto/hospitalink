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

    <div class="lg:hidden min-h-screen flex flex-col">

        <div class="pt-8 pb-4 px-6 text-center -mt-4">
            <img src="{{ asset('images/Logo-Hospitalink3.png') }}" alt="HOSPITALINK" class="mx-auto h-48 mb-1">

        </div>


        <div class="px-6 mb-4" x-data="carousel()">



            <div class="flex-1 bg-[#B4DBF1] rounded-t-3xl px-6 pt-6 pb-20 -mx-6">

                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-2xl font-bold text-gray-800 pl-4">HOSPITAL INFORMATION</h2>
                    <div class="relative pr-5 pt-1">
                        <img src="{{ asset('images/icons/icon-notif.png') }}" alt="Notifications" class="w-7 h-9">

                    </div>
                </div>


                <div class="space-y-4">
                    @foreach ($hospitals as $hospital)
                        <a href="{{ route('hospital.detail', ['slug' => $hospital->slug]) }}" class="block">
                            <div
                                class="bg-[#9AC1D6] rounded-2xl p-4 shadow-md max-w-[360px] mx-auto hover:bg-[#8BB5CD] transition-colors">
                                <div class="flex gap-8">
                                    <div class="flex-1 max-w-[55%]">
                                        <h3 class="font-bold text-sm text-gray-800 mb-2 leading-tight">
                                            {{ $hospital->name }}</h3>
                                        <p class="text-xs text-gray-600">{{ Str::limit($hospital->address, 50) }}</p>
                                        <p class="text-xs text-blue-600 mb-1 truncate">{{ $hospital->website_url }}</p>
                                    </div>
                                    <img src="{{ \Illuminate\Support\Str::startsWith($hospital->image_url, ['http://', 'https://'])
                                        ? $hospital->image_url
                                        : (\Illuminate\Support\Str::startsWith($hospital->image_url, ['storage/', 'images/'])
                                            ? asset($hospital->image_url)
                                            : asset('storage/' . $hospital->image_url)) }}"
                                        alt="{{ $hospital->name }}"
                                        class="w-28 h-22 object-cover rounded-lg flex-shrink-0">
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>


            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200">
                <div class="flex items-center justify-around py-2">

                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2">
                        <img src="{{ asset('images/icons/icon-home.png') }}" alt="Home"
                            class="w-6 h-6 mb-1 opacity-50">
                        <span class="text-xs text-gray-400">Home</span>
                    </a>

                    <div class="flex flex-col items-center py-2">
                        <img src="{{ asset('images/icons/icon-hospital.png') }}" alt="Hospital" class="w-7 h-7 mb-1">
                        <span class="text-xs text-[#000000]">Hospital</span>
                        <div class="w-1 h-1 bg-[#00A2FA] rounded-full mt-1"></div>
                    </div>

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


        <div class="hidden lg:flex h-screen">

            <div class="w-[15%] bg-white shadow-lg flex flex-col items-center py-8">
                <div class="space-y-8">

                    <div class="flex flex-col items-center">
                        <img src="{{ asset('images/icons/home.png') }}" alt="Home" class="w-8 h-8 mb-2">
                        <div class="w-2 h-2 bg-[#00A2FA] rounded-full"></div>
                    </div>

                    <div class="flex flex-col items-center">
                        <img src="{{ asset('images/icons/icon-hospital.png') }}" alt="Hospital"
                            class="w-8 h-8 mb-2 opacity-50">

                    </div>

                    <div class="flex flex-col items-center">
                        <img src="{{ asset('images/icons/icon-room.png') }}" alt="Room" class="w-8 h-8 opacity-50">
                    </div>

                    <div class="flex flex-col items-center">
                        <img src="{{ asset('images/icons/icon-help.png') }}" alt="Help" class="w-8 h-8 opacity-50">
                    </div>
                </div>
            </div>

            Main Content
            <div class="w-[85%] bg-[#00A2FA] overflow-y-auto">
                Header
                <div class="pt-8 pb-6 px-8 text-center">
                    <img src="{{ asset('images/logo-hospitalink.png') }}" alt="HOSPITALINK" class="mx-auto h-32 mb-3">
                    <h1 class="text-3xl font-bold text-red-500">HOSPITALINK</h1>
                    <p class="text-lg text-red-400">Peduli Akses Kesehatan Anda</p>
                </div>

                Carousel Section
                <div class="px-8 mb-8" x-data="carousel()">
                    Carousel Container
                    <div class="bg-teal-600 rounded-3xl p-8 mb-4 overflow-hidden relative max-w-4xl mx-auto">
                        <div class="flex transition-transform duration-500 ease-in-out"
                            :style="`transform: translateX(-${currentSlide * 100}%)`">
                            Slide 1
                            <div class="w-full flex-shrink-0 flex items-center justify-between">
                                <div class="text-white">
                                    <h3 class="font-bold text-lg mb-2">TURUN HARGA</h3>
                                    <h2 class="font-bold text-3xl mb-3">BESAR-BESARAN</h2>
                                    <p class="text-base leading-relaxed">SEMUA PRODUK<br>TURUN HINGGA 50%<br>BURUAN
                                        SEBELUM<br>DAN KEHABISAN</p>
                                </div>
                                <div class="w-40 h-32 bg-white/20 rounded-2xl flex items-center justify-center">
                                    <div class="w-32 h-24 bg-white/30 rounded-xl"></div>
                                </div>
                            </div>
                            Slide 2
                            <div class="w-full flex-shrink-0 flex items-center justify-between">
                                <div class="text-white">
                                    <h3 class="font-bold text-lg mb-2">PROMO SPESIAL</h3>
                                    <h2 class="font-bold text-3xl mb-3">ALAT KESEHATAN</h2>
                                    <p class="text-base leading-relaxed">DAPATKAN DISKON<br>HINGGA 40%<br>UNTUK
                                        SEMUA<br>PRODUK PILIHAN</p>
                                </div>
                                <div class="w-40 h-32 bg-white/20 rounded-2xl flex items-center justify-center">
                                    <div class="w-32 h-24 bg-white/30 rounded-xl"></div>
                                </div>
                            </div>
                            Slide 3
                            <div class="w-full flex-shrink-0 flex items-center justify-between">
                                <div class="text-white">
                                    <h3 class="font-bold text-lg mb-2">KONSULTASI</h3>
                                    <h2 class="font-bold text-3xl mb-3">GRATIS</h2>
                                    <p class="text-base leading-relaxed">KONSULTASI DENGAN<br>DOKTER AHLI<br>TANPA
                                        BIAYA<br>TAMBAHAN</p>
                                </div>
                                <div class="w-40 h-32 bg-white/20 rounded-2xl flex items-center justify-center">
                                    <div class="w-32 h-24 bg-white/30 rounded-xl"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    Pagination Dots
                    <div class="flex justify-center space-x-3 mb-6">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button class="w-3 h-3 rounded-full transition-colors duration-200"
                                :class="currentSlide === index ? 'bg-white' : 'bg-white/50'" @click="goToSlide(index)">
                            </button>
                        </template>
                    </div>

                    Ad Banner
                    <div class="flex items-center justify-center space-x-6">
                        <span class="text-white text-lg">Anda Miliki Iklan?</span>
                        <button
                            class="border-2 border-white text-white px-6 py-2 rounded-full text-lg hover:bg-white hover:text-[#00A2FA] transition-colors">
                            Masuk di sini
                        </button>
                    </div>
                </div>

                Health News Section
                <div class="bg-gray-100 rounded-t-3xl px-8 pt-8 pb-8 min-h-[50vh]">
                    Header
                    <div class="flex items-center justify-between mb-8 max-w-6xl mx-auto">
                        <h2 class="text-4xl font-bold text-gray-800">HEALTH NEWS</h2>
                        <div class="relative">
                            <img src="{{ asset('images/icons/notification.png') }}" alt="Notifications"
                                class="w-8 h-8">
                            <div
                                class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">3</span>
                            </div>
                        </div>
                    </div>

                    News Cards Grid
                    <div class="grid grid-cols-1 xl:grid-cols-3 lg:grid-cols-2 gap-6 max-w-6xl mx-auto">
                        News Card 1
                        <div class="bg-[#D9D9D9] rounded-2xl p-6 shadow-sm">
                            <div class="mb-4">
                                <div class="w-full h-32 bg-gray-300 rounded-xl mb-4"></div>
                                <h3 class="font-bold text-base text-gray-800 mb-3 leading-tight">Kasus FLU Singapura di
                                    Indonesia Meroket, Tembus hingga 5 Ribu</h3>
                                <p class="text-sm text-gray-600">SINDONEWS.com</p>
                            </div>
                        </div>

                        News Card 2
                        <div class="bg-[#D9D9D9] rounded-2xl p-6 shadow-sm">
                            <div class="mb-4">
                                <div class="w-full h-32 bg-gray-300 rounded-xl mb-4"></div>
                                <h3 class="font-bold text-base text-gray-800 mb-3 leading-tight">Kebiasaan yang Tak
                                    Disadari Picu Kanker Usus Besar di Usia Muda</h3>
                                <p class="text-sm text-gray-600">Detikhealth.com</p>
                            </div>
                        </div>

                        News Card 3
                        <div class="bg-[#D9D9D9] rounded-2xl p-6 shadow-sm">
                            <div class="mb-4">
                                <div class="w-full h-32 bg-gray-300 rounded-xl mb-4"></div>
                                <h3 class="font-bold text-base text-gray-800 mb-3 leading-tight">Olahraga Lari Bisa
                                    Turunkan Risiko Kanker dan Kematian Dini</h3>
                                <p class="text-sm text-gray-600">KOMPAS.com</p>
                            </div>
                        </div>
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
