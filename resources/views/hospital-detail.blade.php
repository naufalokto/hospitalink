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

        <div class="bg-[#02293E] shadow-lg rounded-b-3xl">
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
                    <img src="{{ \Illuminate\Support\Str::startsWith($hospital->image_url, ['http://', 'https://'])
                        ? $hospital->image_url
                        : (\Illuminate\Support\Str::startsWith($hospital->image_url, ['storage/', 'images/'])
                            ? asset($hospital->image_url)
                            : asset('storage/' . $hospital->image_url)) }}"
                        alt="{{ $hospital->name }}" class="w-full h-48 object-cover rounded-lg">
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
                            <div class="list-decimal list-inside space-y-1 text-black">
                                @foreach (explode("\n", $hospital->public_service) as $item)
                                    <div>{{ $item }}</div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">PERSYARATAN RAWAT INAP</h2>
                            <ol class="list-decimal list-inside space-y-1 text-black">
                                @foreach (explode("\n", $hospital->admission_requirements) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">HARGA RAWAT INAP</h2>
                            <ul class="list-disc list-inside space-y-1 text-black">
                                @foreach (explode("\n", $hospital->room_prices) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">FASILITAS</h2>
                            <ul class="list-disc list-inside space-y-1 text-black">
                                @foreach (explode("\n", $hospital->facilities) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">WEBSITE</h2>
                            <p class="text-black">{{ $hospital->website_url }}</p>
                        </div>
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">NOMOR TELEPON</h2>
                            <p class="text-black">{{ $hospital->phone_number }}</p>
                        </div>
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-black mb-2">ALAMAT</h2>
                            <p class="text-black">{{ $hospital->address }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</body>

</html>
