@extends('frontend.layout')

@section('title', 'Music Rental - Sewa Alat Musik Berkualitas')
@section('description', 'Sewa alat musik berkualitas dengan harga terjangkau. Tersedia berbagai macam instrumen musik untuk kebutuhan Anda.')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-blue-600 to-purple-700 hero-pattern">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                Sewa Alat Musik <span class="text-yellow-400">Berkualitas</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-3xl mx-auto">
                Wujudkan passion musik Anda dengan koleksi alat musik terlengkap dan berkualitas tinggi
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('frontend.catalog') }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-8 py-4 rounded-lg transition duration-300 transform hover:scale-105">
                    Lihat Katalog
                </a>
                <a href="#featured" class="border-2 border-white text-white hover:bg-white hover:text-gray-900 font-semibold px-8 py-4 rounded-lg transition duration-300">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white" id="featured">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Mengapa Pilih Music Rental?
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Kami menyediakan layanan penyewaan alat musik terbaik dengan berbagai keunggulan
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center p-6 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-300">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Kualitas Terjamin</h3>
                <p class="text-gray-600">
                    Semua alat musik kami selalu dalam kondisi prima dan terawat dengan baik oleh teknisi berpengalaman.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center p-6 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-300">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Harga Terjangkau</h3>
                <p class="text-gray-600">
                    Nikmati harga sewa yang kompetitif dengan berbagai pilihan paket yang sesuai budget Anda.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center p-6 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-300">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Pelayanan Cepat</h3>
                <p class="text-gray-600">
                    Proses booking mudah dan cepat, dengan layanan antar-jemput untuk kemudahan Anda.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Popular Instruments Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Alat Musik Populer
            </h2>
            <p class="text-xl text-gray-600">
                Koleksi alat musik terpopuler dengan kualitas terbaik
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @if($featuredInstruments->isNotEmpty())
                @foreach($featuredInstruments as $instrument)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                        @if($instrument->image_path)
                            <img src="{{ Storage::url($instrument->image_path) }}" alt="{{ $instrument->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $instrument->name }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $instrument->category->name }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-blue-600">
                                Rp {{ number_format($instrument->daily_rate, 0, ',', '.') }}/hari
                            </span>
                            <a href="{{ route('frontend.instrument-detail', $instrument->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-300">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada alat musik</h3>
                    <p class="text-gray-600">Alat musik akan segera tersedia.</p>
                </div>
            @endif
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('frontend.catalog') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition duration-300 inline-flex items-center">
                Lihat Semua Alat Musik
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Kategori Alat Musik
            </h2>
            <p class="text-xl text-gray-600">
                Jelajahi berbagai kategori alat musik yang tersedia
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @if($categories->isNotEmpty())
                @foreach($categories as $category)
                <a href="{{ route('frontend.catalog', ['category' => $category->id]) }}" class="group">
                    <div class="bg-gray-50 rounded-lg p-6 text-center hover:bg-blue-50 transition duration-300 group-hover:scale-105">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-200">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 group-hover:text-blue-600">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $category->instruments_count }} alat</p>
                    </div>
                </a>
                @endforeach
            @else
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-600">Kategori akan segera tersedia.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Cara Menyewa
            </h2>
            <p class="text-xl text-gray-600">
                Proses sederhana dalam 4 langkah mudah
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-white">1</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Pilih Alat Musik</h3>
                <p class="text-gray-600">
                    Browse katalog dan pilih alat musik yang Anda inginkan
                </p>
            </div>

            <!-- Step 2 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-white">2</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Tentukan Tanggal</h3>
                <p class="text-gray-600">
                    Pilih tanggal mulai dan durasi penyewaan sesuai kebutuhan
                </p>
            </div>

            <!-- Step 3 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-white">3</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Lakukan Pembayaran</h3>
                <p class="text-gray-600">
                    Bayar secara online atau cash saat pengambilan alat musik
                </p>
            </div>

            <!-- Step 4 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-white">4</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Nikmati Musik</h3>
                <p class="text-gray-600">
                    Ambil alat musik dan mulai berkreasi dengan musik favorit Anda
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-purple-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Siap Memulai Perjalanan Musik Anda?
        </h2>
        <p class="text-xl text-gray-200 mb-8 max-w-2xl mx-auto">
            Jangan tunda lagi! Sewa alat musik impian Anda sekarang dan rasakan pengalaman bermusik yang tak terlupakan.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('frontend.catalog') }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-8 py-4 rounded-lg transition duration-300 transform hover:scale-105">
                Mulai Sewa Sekarang
            </a>
            <a href="{{ route('frontend.track-order-form') }}" class="border-2 border-white text-white hover:bg-white hover:text-gray-900 font-semibold px-8 py-4 rounded-lg transition duration-300">
                Lacak Pesanan
            </a>
        </div>
    </div>
</section>
@endsection
