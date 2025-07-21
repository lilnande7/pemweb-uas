@extends('frontend.layout')

@section('title', 'Status Pesanan ' . $order->order_number . ' - Music Rental')
@section('description', 'Lihat status dan detail pesanan penyewaan alat musik Anda.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('frontend.index') }}" class="hover:text-blue-600">Home</a></li>
                <li class="flex items-center">
                    <svg class="h-4 w-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('frontend.track-order-form') }}" class="hover:text-blue-600">Lacak Pesanan</a>
                </li>
                <li class="flex items-center">
                    <svg class="h-4 w-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-900 font-medium">{{ $order->order_number }}</span>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Status Pesanan</h1>
                    <p class="text-gray-600">
                        Kode Pesanan: <span class="font-mono font-semibold">{{ $order->order_number }}</span>
                    </p>
                    <p class="text-sm text-gray-500">
                        Dibuat pada {{ $order->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                
                <div class="text-right">
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold 
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                        @elseif($order->status === 'active') bg-green-100 text-green-800
                        @elseif($order->status === 'completed') bg-purple-100 text-purple-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        @switch($order->status)
                            @case('pending')
                                Menunggu Konfirmasi
                                @break
                            @case('confirmed')
                                Dikonfirmasi
                                @break
                            @case('active')
                                Sedang Disewa
                                @break
                            @case('completed')
                                Selesai
                                @break
                            @case('cancelled')
                                Dibatalkan
                                @break
                            @default
                                {{ ucfirst($order->status) }}
                        @endswitch
                    </span>
                </div>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Status Timeline</h2>
            
            <div class="relative">
                <!-- Timeline line -->
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                
                <div class="space-y-6">
                    <!-- Order Created -->
                    <div class="relative flex items-start">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 relative z-10">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4 min-w-0 flex-1">
                            <h3 class="text-sm font-semibold text-gray-900">Pesanan Dibuat</h3>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                            <p class="text-xs text-gray-500">Pesanan berhasil dibuat dan menunggu konfirmasi</p>
                        </div>
                    </div>

                    <!-- Order Confirmed -->
                    <div class="relative flex items-start">
                        <div class="w-8 h-8 @if(in_array($order->status, ['confirmed', 'active', 'completed'])) bg-green-500 @else bg-gray-300 @endif rounded-full flex items-center justify-center flex-shrink-0 relative z-10">
                            @if(in_array($order->status, ['confirmed', 'active', 'completed']))
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <div class="w-3 h-3 bg-white rounded-full"></div>
                            @endif
                        </div>
                        <div class="ml-4 min-w-0 flex-1">
                            <h3 class="text-sm font-semibold text-gray-900">Pesanan Dikonfirmasi</h3>
                            @if(in_array($order->status, ['confirmed', 'active', 'completed']))
                                <p class="text-sm text-gray-600">{{ $order->updated_at->format('d M Y, H:i') }} WIB</p>
                                <p class="text-xs text-gray-500">Pesanan telah dikonfirmasi dan siap untuk diproses</p>
                            @else
                                <p class="text-sm text-gray-500">Menunggu konfirmasi dari tim kami</p>
                            @endif
                        </div>
                    </div>

                    <!-- Rental Started -->
                    <div class="relative flex items-start">
                        <div class="w-8 h-8 @if(in_array($order->status, ['active', 'completed'])) bg-green-500 @else bg-gray-300 @endif rounded-full flex items-center justify-center flex-shrink-0 relative z-10">
                            @if(in_array($order->status, ['active', 'completed']))
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <div class="w-3 h-3 bg-white rounded-full"></div>
                            @endif
                        </div>
                        <div class="ml-4 min-w-0 flex-1">
                            <h3 class="text-sm font-semibold text-gray-900">Penyewaan Dimulai</h3>
                            @if(in_array($order->status, ['active', 'completed']))
                                <p class="text-sm text-gray-600">{{ $order->rental_start_date ? $order->rental_start_date->format('d M Y') : 'Tanggal belum ditentukan' }}</p>
                                <p class="text-xs text-gray-500">Alat musik telah diserahkan dan penyewaan dimulai</p>
                            @else
                                <p class="text-sm text-gray-500">Akan dimulai pada {{ $order->rental_start_date ? $order->rental_start_date->format('d M Y') : 'Tanggal belum ditentukan' }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Rental Completed -->
                    <div class="relative flex items-start">
                        <div class="w-8 h-8 @if($order->status === 'completed') bg-green-500 @else bg-gray-300 @endif rounded-full flex items-center justify-center flex-shrink-0 relative z-10">
                            @if($order->status === 'completed')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <div class="w-3 h-3 bg-white rounded-full"></div>
                            @endif
                        </div>
                        <div class="ml-4 min-w-0 flex-1">
                            <h3 class="text-sm font-semibold text-gray-900">Penyewaan Selesai</h3>
                            @if($order->status === 'completed')
                                <p class="text-sm text-gray-600">{{ $order->rental_end_date ? $order->rental_end_date->format('d M Y') : 'Tanggal belum ditentukan' }}</p>
                                <p class="text-xs text-gray-500">Alat musik telah dikembalikan dan penyewaan selesai</p>
                            @else
                                <p class="text-sm text-gray-500">Berakhir pada {{ $order->rental_end_date ? $order->rental_end_date->format('d M Y') : 'Tanggal belum ditentukan' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Pesanan</h2>
                
                <!-- Customer Info -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Penyewa</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium">{{ $order->customer->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium">{{ $order->customer->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Telepon:</span>
                            <span class="font-medium">{{ $order->customer->phone }}</span>
                        </div>
                    </div>
                </div>

                <!-- Rental Info -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Detail Penyewaan</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Mulai:</span>
                            <span class="font-medium">{{ $order->rental_start_date ? $order->rental_start_date->format('d M Y') : 'Belum ditentukan' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Selesai:</span>
                            <span class="font-medium">{{ $order->rental_end_date ? $order->rental_end_date->format('d M Y') : 'Belum ditentukan' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Durasi:</span>
                            <span class="font-medium">
                                @if($order->rental_start_date && $order->rental_end_date)
                                    {{ $order->rental_start_date->diffInDays($order->rental_end_date) + 1 }} hari
                                @else
                                    Belum ditentukan
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status Pembayaran:</span>
                            <span class="font-medium">{{ ucfirst($order->payment_status ?? 'pending') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Pembayaran</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($order->delivery_fee > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Biaya Antar:</span>
                            <span class="font-medium">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="border-t pt-2 flex justify-between font-semibold text-blue-600">
                            <span>Total:</span>
                            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items and Actions -->
            <div class="space-y-6">
                <!-- Items -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Alat Musik yang Disewa</h2>
                    
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-4 p-3 border rounded-lg">
                            <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                @if($item->instrument->image_path)
                                    <img src="{{ Storage::url($item->instrument->image_path) }}" 
                                         alt="{{ $item->instrument->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm">{{ $item->instrument->name }}</h3>
                                <p class="text-xs text-gray-600">{{ $item->instrument->category->name }}</p>
                                <p class="text-xs text-blue-600">{{ $item->quantity }}x - Rp {{ number_format($item->daily_rate, 0, ',', '.') }}/hari</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-sm text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Tindakan</h2>
                    
                    <div class="space-y-3">
                        @if($order->status === 'pending')
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-sm font-semibold text-yellow-800">Menunggu Konfirmasi</h3>
                                        <p class="text-sm text-yellow-700 mt-1">
                                            Tim kami akan segera menghubungi Anda untuk konfirmasi pesanan dan detail pembayaran.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif($order->status === 'confirmed')
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-sm font-semibold text-blue-800">Pesanan Dikonfirmasi</h3>
                                        <p class="text-sm text-blue-700 mt-1">
                                            Pesanan Anda telah dikonfirmasi. Silakan lakukan pembayaran dan bersiap untuk pengambilan/pengiriman.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif($order->status === 'active')
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-sm font-semibold text-green-800">Sedang Disewa</h3>
                                        <p class="text-sm text-green-700 mt-1">
                                            Selamat menikmati alat musik Anda! Jangan lupa untuk mengembalikan tepat waktu.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif($order->status === 'completed')
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-purple-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-sm font-semibold text-purple-800">Penyewaan Selesai</h3>
                                        <p class="text-sm text-purple-700 mt-1">
                                            Terima kasih! Penyewaan telah selesai dan alat musik telah dikembalikan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Contact Actions -->
                        <div class="flex flex-col gap-2">
                            <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20bertanya%20tentang%20pesanan%20{{ $order->order_number }}" 
                               target="_blank"
                               class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                Hubungi via WhatsApp
                            </a>

                            <a href="tel:+6281234567890" 
                               class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Telepon Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Track Form -->
        <div class="mt-8 text-center">
            <a href="{{ route('frontend.track-order-form') }}" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 transition duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Lacak Pesanan Lain
            </a>
        </div>
    </div>
</div>
@endsection
