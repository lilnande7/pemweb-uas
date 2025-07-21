@extends('frontend.layout')

@section('title', 'Katalog Alat Musik - Music Rental')
@section('description', 'Jelajahi katalog lengkap alat musik berkualitas tinggi yang tersedia untuk disewa dengan harga terjangkau.')

@section('content')
<!-- Header Section -->
<section class="bg-gray-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Katalog Alat Musik</h1>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                Temukan alat musik impian Anda dari koleksi terlengkap dengan kualitas terbaik
            </p>
        </div>
    </div>
</section>

<!-- Filters Section -->
<section class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form method="GET" action="{{ route('frontend.catalog') }}" class="space-y-4" x-data="{ showFilters: false }">
            <!-- Search Bar -->
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Cari alat musik</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Cari alat musik..." 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <button type="button" @click="showFilters = !showFilters" 
                        class="md:hidden bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-lg font-medium transition duration-300">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                    Filter
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition duration-300">
                    Cari
                </button>
            </div>

            <!-- Advanced Filters -->
            <div x-show="showFilters" x-cloak class="md:block">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg">
                    <!-- Category Filter -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="category" id="category" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Availability Filter -->
                    <div>
                        <label for="availability" class="block text-sm font-medium text-gray-700 mb-2">Ketersediaan</label>
                        <select name="availability" id="availability" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua</option>
                            <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="rented" {{ request('availability') == 'rented' ? 'selected' : '' }}>Sedang Disewa</option>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Minimum</label>
                        <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" 
                               placeholder="0" min="0" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Maksimum</label>
                        <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" 
                               placeholder="1000000" min="0" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Results Section -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    @if(request('search'))
                        Hasil pencarian untuk "{{ request('search') }}"
                    @elseif(request('category'))
                        @php
                            $selectedCategory = $categories->find(request('category'));
                        @endphp
                        {{ $selectedCategory ? $selectedCategory->name : 'Kategori' }}
                    @else
                        Semua Alat Musik
                    @endif
                </h2>
                <p class="text-gray-600 mt-1">{{ $instruments->total() }} alat musik ditemukan</p>
            </div>

            <!-- Sort Options -->
            <div class="mt-4 md:mt-0">
                <form method="GET" action="{{ route('frontend.catalog') }}" class="flex items-center">
                    @foreach(request()->except('sort') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    
                    <label for="sort" class="text-sm font-medium text-gray-700 mr-3">Urutkan:</label>
                    <select name="sort" id="sort" onchange="this.form.submit()" 
                            class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Instruments Grid -->
        @if($instruments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($instruments as $instrument)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                    <!-- Image -->
                    <div class="aspect-w-16 aspect-h-12 bg-gray-200 relative">
                        @if($instrument->image_path)
                            <img src="{{ Storage::url($instrument->image_path) }}" 
                                 alt="{{ $instrument->name }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if($instrument->status === 'available')
                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    Tersedia
                                </span>
                            @else
                                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    Disewa
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <div class="mb-3">
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">
                                {{ $instrument->category->name }}
                            </span>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            {{ $instrument->name }}
                        </h3>

                        @if($instrument->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                {{ $instrument->description }}
                            </p>
                        @endif

                        <!-- Price and Stock -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xl font-bold text-blue-600">
                                    Rp {{ number_format($instrument->daily_rate, 0, ',', '.') }}
                                </span>
                                <span class="text-sm text-gray-500">/hari</span>
                            </div>
                            
                            <!-- Stock Info -->
                            <div class="flex items-center text-sm">
                                @if($instrument->is_available && $instrument->quantity_available > 0)
                                    <span class="flex items-center text-green-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Tersedia {{ $instrument->quantity_available }}
                                    </span>
                                @else
                                    <span class="flex items-center text-red-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Tidak Tersedia
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('frontend.instrument-detail', $instrument->id) }}" 
                               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-md text-sm font-medium text-center transition duration-300">
                                Detail
                            </a>

                            @if($instrument->is_available && $instrument->quantity_available > 0)
                                <a href="{{ route('frontend.booking', $instrument->id) }}" 
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium text-center transition duration-300">
                                    Sewa Sekarang
                                </a>
                            @else
                                <button disabled 
                                        class="flex-1 bg-gray-300 text-gray-500 px-3 py-2 rounded-md text-sm font-medium cursor-not-allowed">
                                    Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $instruments->withQueryString()->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-16">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak ada alat musik ditemukan</h3>
                <p class="text-gray-600 mb-6">
                    @if(request('search') || request('category') || request('min_price') || request('max_price'))
                        Coba ubah kriteria pencarian atau filter untuk menemukan alat musik yang sesuai.
                    @else
                        Alat musik akan segera tersedia. Silakan cek kembali nanti.
                    @endif
                </p>
                
                @if(request('search') || request('category') || request('min_price') || request('max_price'))
                    <a href="{{ route('frontend.catalog') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition duration-300">
                        Lihat Semua Alat Musik
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
