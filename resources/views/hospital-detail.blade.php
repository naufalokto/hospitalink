<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hospital->name }} - HOSPITALINK</title>
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
    </style>
</head>

<body class="h-full bg-[#0688CE] font-sans antialiased">

    <div class="lg:hidden h-full flex flex-col">

        <div class="sticky top-0 z-50 bg-[#02293E] shadow-lg rounded-b-3xl">
            <div class="px-4 py-3">

                <div class="flex items-center justify-between mb-4">
                    <a href="{{ route('hospital') }}"
                        class="text-white hover:text-gray-300 transition-colors flex items-center mt-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </a>
                    <h1 class="text-white text-2xl font-md mt-3">HOSPITAL INFORMATION</h1>
                    <div class="w-6"></div>
                </div>


                <div class="mb-3 mx-6">
                    <img src="{{ asset($hospital->image_url) }}" alt="{{ $hospital->name }}"
                        class="w-full h-48 object-cover rounded-lg">
                </div>


                <div class="text-center mb-2">
                    <span class="text-white text-3xl font-bold opacity-90">{{ $hospital->name }}</span>
                </div>
            </div>
        </div>


        <div class="flex-1 overflow-y-auto custom-scrollbar">
            <div class="p-4 text-black leading-relaxed">
                <div class="space-y-4 mx-4">
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-black mb-2">Tentang</h2>
                        <p class="text-black">{{ $hospital->description }}</p>
                    </div>

                    @if ($hospital->public_service)
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">PELAYANAN PUBLIK</h2>
                            <p class="text-black">{{ $hospital->public_service }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="hidden lg:flex justify-center min-h-screen py-8">
        <div class="w-full max-w-4xl bg-white rounded-lg shadow-xl overflow-hidden">
            Sticky Header for Desktop
            <div class="sticky top-8 z-50 bg-[#02293E] shadow-lg rounded-b-3xl">
                <div class="px-8 py-6">
                    Navigation Bar
                    <div class="flex items-center justify-between mb-6">
                        <button onclick="history.back()" class="text-white hover:text-gray-300 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <h1 class="text-white text-xl font-semibold">HEALTH NEWS</h1>
                        <div class="w-6"></div> Spacer
                    </div>

                    Article Title
                    <h2 class="text-white text-2xl font-bold mb-6 leading-tight text-balance">
                        {{ $article['title'] ?? 'Kasus FLU Singapura di Indonesia Meroket, Tembus hingga 5 Ribu' }}
                    </h2>

                    Article Image
                    <div class="mb-4 mx-8">
                        <img src="{{ $article['image'] ?? '/placeholder.svg?height=300&width=600' }}"
                            alt="{{ $article['title'] ?? 'Article Image' }}"
                            class="w-full h-64 object-cover rounded-lg">
                    </div>

                    News Source
                    <div class="text-right">
                        <span class="text-white text-sm opacity-80">{{ $article['source'] ?? 'Sindonews.com' }}</span>
                    </div>
                </div>
            </div>

            Scrollable Content for Desktop
            <div class="bg-[#0688CE] overflow-y-auto custom-scrollbar" style="max-height: calc(100vh - 200px);">
                <div class="p-8 text-white leading-relaxed">
                    <div class="space-y-6 text-lg mx-8">
                        {!! $article['content'] ??
                            '
                                                                                                                                                <p><strong>JAKARTA</strong> - Kasus flu Singapura di Indonesia dilaporkan melonjak. Di mana pada tanggal 16-11 2024, tercatat tembus hingga 5.461 kasus infeksi yang membuat masyarakat harus waspada terhadap penyebarannya. Ahli Paru sekaligus Bidang Kajian Penanggulangan Penyakit Menular PB IDI Prof. Erlina Burhan mengatakan bahwa kasus tersebut tersebar di beberapa daerah di Indonesia.</p>
                                                                                                                                                
                                                                                                                                                <p>"Jadi pada minggu ke-11 tahun 2024 menurut humas dari Kementerian Kesehatan, sudah terdapat 5.461 orang yang terjangkit flu Singapura di Indonesia. Ada 5.461 kasus dan 738 kasus di antaranya di Provinsi Banten dalam waktu 30 hari, sampai Maret 2024, ini laporan dari Dinas Kesehatan Banten," kata Prof. Erlina saat dihubungi PB IDI. Dinas Kesehatan Provinsi Banten juga telah mengonfirmasi adanya 738 kasus flu Singapura terhitung sejak awal hingga Maret 2024.</p>
                                                                                                                                                
                                                                                                                                                <p>Terbaru, ditemukan 14 kasus yang diduga flu Singapura di Depok, Jawa Barat. Dinas Kesehatan Kota Depok pun telah mencatat ada 10 pasien yang sudah dalam perawatan. Pasien-pasien yang sedang dalam perawatan di rumah sakit biasanya karena mengalami dehidrasi dan memerlukan bantuan pihak medis. "Baru-baru ini juga ada berita kami terima bahwa ada 14 kasus," ujar Prof. Erlina.</p>
                                                                                                                                                
                                                                                                                                                <p>Flu Singapura atau Hand, Foot, and Mouth Disease (HFMD) adalah penyakit infeksi virus yang umum terjadi pada anak-anak, terutama yang berusia di bawah 5 tahun. Penyakit ini disebabkan oleh virus dari keluarga Enterovirus, terutama Coxsackievirus A16 dan Enterovirus 71.</p>
                                                                                                                                                
                                                                                                                                                <p>Gejala utama flu Singapura meliputi demam, sakit tenggorokan, dan ruam atau lepuhan kecil yang muncul di tangan, kaki, dan mulut. Ruam ini biasanya tidak gatal tetapi bisa terasa nyeri. Anak-anak yang terkena flu Singapura juga mungkin mengalami kehilangan nafsu makan dan merasa tidak nyaman.</p>
                                                                                                                                                
                                                                                                                                                <p>Penyakit ini sangat menular dan dapat menyebar melalui kontak langsung dengan cairan tubuh penderita, seperti air liur, ingus, atau cairan dari lepuhan. Virus juga dapat menyebar melalui tetesan udara ketika penderita batuk atau bersin, serta melalui kontak dengan permukaan yang terkontaminasi.</p>
                                                                                                                                                ' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
