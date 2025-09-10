<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article['title'] ?? 'Detail Berita' }} - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            transition: transform 0.1s ease;
            z-index: 50;
        }

        .sticky-header.scrolled {
            transform: translateY(-40%);
        }
    </style>
</head>

<body class="h-full bg-[#0688CE] font-sans antialiased" x-data="{ scrolled: false }"
    @scroll.window="scrolled = window.pageYOffset > 50">

    <!-- MOBILE -->
    <div class="lg:hidden min-h-screen flex flex-col">

        <div class="sticky-header bg-[#02293E] shadow-lg rounded-b-3xl w-full transition-all duration-300"
             :class="{ 'scrolled': scrolled }">
            <div class="px-4 py-3">

                <div class="flex items-center justify-between mb-4">
                    <a href="{{ route('dashboard') }}"
                        class="text-white hover:text-gray-300 transition-colors flex items-center mt-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </a>
                    <h1 class="text-white text-2xl font-bold mt-3">HEALTH NEWS</h1>
                    <div class="w-6"></div>
                </div>

                <h2 class="text-white text-center text-md font-md mb-4 leading-tight text-balance -mt-2">
                    {{ $article['title'] }}
                </h2>

                <div class="mb-3 mx-6">
                    <img src="{{ asset($article['image']) }}"
                        alt="{{ $article['title'] ?? 'Article Image' }}" class="w-full h-48 object-cover rounded-lg">
                </div>

                <div class="text-right mr-6 mb-2">
                    <span class="text-white text-sm opacity-80">{{ $article['source'] }}</span>
                </div>
            </div>
        </div>

        <!-- Scroll area (mobile) -->
        <div class="flex-1 overflow-y-auto custom-scrollbar"
             x-ref="scrollMobile"
             @scroll="scrolled = $refs.scrollMobile.scrollTop > 50">
            <div class="p-4 text-black leading-relaxed">
                <div class="space-y-4 mx-4 text-justify">
                    {!! $article['content'] !!}
                </div>
            </div>
        </div>
    </div>


    <!-- DESKTOP -->
    <!-- Enhanced desktop layout with better responsive design and improved content structure -->
    <div class="hidden lg:block min-h-screen bg-gradient-to-br from-[#0688CE] to-[#02293E] py-6 xl:py-8">
        <div class="container mx-auto px-4 lg:px-6 xl:px-8">
            <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden" x-data="{ scrolled: false }">

                <!-- Enhanced Header Section -->
                <div class="bg-[#02293E] px-6 lg:px-8 xl:px-12 py-6 lg:py-8">
                    <div class="flex items-center justify-between mb-6 lg:mb-8">
                        <button onclick="history.back()" 
                                class="text-white hover:text-gray-300 transition-colors duration-200 p-2 hover:bg-white/10 rounded-lg">
                            <svg class="w-6 h-6 lg:w-7 lg:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <h1 class="text-white text-xl lg:text-2xl xl:text-3xl font-bold tracking-wide">HEALTH NEWS</h1>
                        <div class="w-6 lg:w-7"></div>
                    </div>

                    <!-- Enhanced Title Section -->
                    <div class="text-center mb-6 lg:mb-8">
                        <h2 class="text-white text-2xl lg:text-3xl xl:text-4xl font-bold mb-4 lg:mb-6 leading-tight text-balance max-w-4xl mx-auto">
                            {{ $article['title'] ?? 'Kasus FLU Singapura di Indonesia Meroket, Tembus hingga 5 Ribu' }}
                        </h2>
                        
                        <!-- Enhanced Image Container -->
                        <div class="relative mb-4 lg:mb-6">
                            <img src="{{ asset($article['image']) ?? asset('images/news/news-card1.png') }}"
                                alt="{{ $article['title'] ?? 'Article Image' }}"
                                class="w-full max-w-3xl mx-auto h-64 lg:h-80 xl:h-96 object-cover rounded-xl shadow-lg">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-xl"></div>
                        </div>

                        <!-- Enhanced Source Attribution -->
                        <div class="flex justify-end max-w-3xl mx-auto">
                            <span class="text-white/80 text-sm lg:text-base font-medium bg-white/10 px-3 py-1 rounded-full">
                                {{ $article['source'] ?? 'Sindonews.com' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Content Area -->
                <div class="bg-gradient-to-b from-[#0688CE] to-[#0577B8] min-h-[60vh]">
                    <div class="px-6 lg:px-8 xl:px-12 py-8 lg:py-12">
                        <div class="max-w-4xl mx-auto">
                            <!-- Content with enhanced typography -->
                            <div class="prose prose-lg lg:prose-xl max-w-none text-white">
                                <div class="space-y-6 lg:space-y-8 text-justify leading-relaxed lg:leading-loose text-base lg:text-lg xl:text-xl">
                                    {!! $article['content'] ?? '<p><strong>JAKARTA</strong> - Kasus flu Singapura di Indonesia mengalami peningkatan yang signifikan dalam beberapa bulan terakhir. Data terbaru menunjukkan bahwa jumlah kasus telah mencapai lebih dari 5.000 pasien di berbagai daerah.</p><p>Flu Singapura atau Hand, Foot, and Mouth Disease (HFMD) adalah penyakit menular yang umumnya menyerang anak-anak di bawah usia 5 tahun. Penyakit ini disebabkan oleh virus Coxsackievirus A16 dan Enterovirus 71.</p><p>Gejala yang paling umum meliputi demam, sakit tenggorokan, dan ruam pada tangan, kaki, serta mulut. Dalam kasus yang lebih parah, dapat terjadi komplikasi seperti meningitis atau ensefalitis.</p>' !!}
                                </div>
                            </div>

                            <!-- Enhanced Reading Experience -->
                            <div class="mt-8 lg:mt-12 pt-6 lg:pt-8 border-t border-white/20">
                                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                    <div class="text-white/80 text-sm lg:text-base">
                                        <span>Artikel kesehatan terpercaya dari HOSPITALINK</span>
                                    </div>
                                    <div class="flex gap-3">
                                        <button class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition-colors duration-200 text-sm lg:text-base">
                                            Bagikan
                                        </button>
                                        <button class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition-colors duration-200 text-sm lg:text-base">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
