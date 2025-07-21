@extends('frontend.layout')

@section('title', $instrument->name . ' - Detail Alat Musik - Music Rental')
@section('description', 'Detail lengkap ' . $instrument->name . ' - ' . Str::limit($instrument->description, 150))

@section('content')
<!-- Breadcrumb -->
<nav class="bg-gray-50 py-4 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('frontend.index') }}" class="hover:text-blue-600">Home</a></li>
            <li class="flex items-center">
                <svg class="h-4 w-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('frontend.catalog') }}" class="hover:text-blue-600">Katalog</a>
            </li>
            <li class="flex items-center">
                <svg class="h-4 w-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('frontend.catalog', ['category' => $instrument->category->id]) }}" class="hover:text-blue-600">{{ $instrument->category->name }}</a>
            </li>
            <li class="flex items-center">
                <svg class="h-4 w-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900 font-medium">{{ $instrument->name }}</span>
            </li>
        </ol>
    </div>
</nav>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Image Section -->
        <div class="space-y-4">
            <!-- Main Image -->
            <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                @if($instrument->image_path)
                    <img src="{{ Storage::url($instrument->image_path) }}" 
                         alt="{{ $instrument->name }}" 
                         class="w-full h-96 object-cover"
                         id="mainImage">
                @else
                    <div class="w-full h-96 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                        <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Thumbnail Gallery (placeholder for future multiple images) -->
            <div class="hidden grid-cols-4 gap-2">
                <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-md overflow-hidden cursor-pointer">
                    <!-- Thumbnails will be added in future updates -->
                </div>
            </div>
        </div>

        <!-- Details Section -->
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full font-medium">
                        {{ $instrument->category->name }}
                    </span>
                    @if($instrument->status === 'available')
                        <span class="inline-block bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full font-medium">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Tersedia
                        </span>
                    @else
                        <span class="inline-block bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full font-medium">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tidak Tersedia
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $instrument->name }}</h1>
                
                <div class="flex items-baseline gap-2 mb-4">
                    <span class="text-3xl font-bold text-blue-600">
                        Rp {{ number_format($instrument->daily_rate, 0, ',', '.') }}
                    </span>
                    <span class="text-lg text-gray-500">/hari</span>
                </div>
            </div>

            <!-- Description -->
            @if($instrument->description)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                <div class="prose prose-sm text-gray-600">
                    {!! nl2br(e($instrument->description)) !!}
                </div>
            </div>
            @endif

            <!-- Specifications -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Spesifikasi</h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kategori:</span>
                        <span class="font-medium text-gray-900">{{ $instrument->category->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium {{ $instrument->status === 'available' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $instrument->status === 'available' ? 'Tersedia' : 'Sedang Disewa' }}
                        </span>
                    </div>
                    @if($instrument->brand)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Brand:</span>
                        <span class="font-medium text-gray-900">{{ $instrument->brand }}</span>
                    </div>
                    @endif
                    @if($instrument->model)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Model:</span>
                        <span class="font-medium text-gray-900">{{ $instrument->model }}</span>
                    </div>
                    @endif
                    @if($instrument->condition)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kondisi:</span>
                        <span class="font-medium text-gray-900">{{ ucfirst($instrument->condition) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Rental Calculator -->
            <div class="bg-blue-50 rounded-lg p-6" x-data="rentalCalculator()">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Kalkulator Sewa</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="days" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Hari Sewa</label>
                        <input type="number" 
                               x-model="days" 
                               @input="calculateTotal"
                               min="1" 
                               max="365"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Masukkan jumlah hari">
                    </div>

                    <div class="border-t pt-4" x-show="days > 0">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Harga per hari:</span>
                            <span class="font-medium">Rp {{ number_format($instrument->daily_rate, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Jumlah hari:</span>
                            <span class="font-medium" x-text="days + ' hari'"></span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-bold text-blue-600 border-t pt-2">
                            <span>Total Biaya:</span>
                            <span x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                @if($instrument->status === 'available')
                    <a href="{{ route('frontend.booking', $instrument->id) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-300 text-center block">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Sewa Sekarang
                    </a>
                @else
                    <button disabled 
                            class="w-full bg-gray-300 text-gray-500 font-semibold py-4 px-6 rounded-lg cursor-not-allowed">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Tidak Tersedia
                    </button>
                @endif

                <button onclick="shareProduct()" 
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-300">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                    Bagikan
                </button>
            </div>

            <!-- Contact Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 mb-2">Butuh Bantuan?</h4>
                <p class="text-sm text-gray-600 mb-3">Hubungi kami untuk informasi lebih lanjut atau pertanyaan khusus.</p>
                <div class="flex flex-col space-y-2 text-sm">
                    <a href="tel:+6281234567890" class="flex items-center text-blue-600 hover:text-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        +62 812-3456-7890
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center text-green-600 hover:text-green-700">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Instruments -->
@if($relatedInstruments->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Alat Musik Serupa</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedInstruments as $related)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                    @if($related->image_path)
                        <img src="{{ Storage::url($related->image_path) }}" 
                             alt="{{ $related->name }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $related->name }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ $related->category->name }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-blue-600">
                            Rp {{ number_format($related->daily_rate, 0, ',', '.') }}/hari
                        </span>
                        <a href="{{ route('frontend.instrument-detail', $related->id) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium transition duration-300">
                            Lihat
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
function rentalCalculator() {
    return {
        days: 1,
        dailyRate: {{ $instrument->daily_rate }},
        total: {{ $instrument->daily_rate }},
        
        calculateTotal() {
            this.total = this.days * this.dailyRate;
        }
    }
}

function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $instrument->name }} - Music Rental',
            text: 'Lihat alat musik {{ $instrument->name }} di Music Rental',
            url: window.location.href
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(function() {
            alert('Link berhasil disalin ke clipboard!');
        }, function() {
            // Fallback if clipboard API is not available
            const textArea = document.createElement('textarea');
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Link berhasil disalin ke clipboard!');
        });
    }
}
</script>
@endpush
