@extends('frontend.layout')

@section('title', 'Pesanan Berhasil - Music Rental')
@section('description', 'Pesanan Anda telah berhasil dibuat. Lihat detail pesanan dan langkah selanjutnya.')

@section('content')
<div class="min-h-screen bg-gray-50 py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pesanan Berhasil Dibuat!</h1>
            <p class="text-lg text-gray-600">
                Terima kasih! Pesanan Anda telah berhasil dibuat dan sedang diproses.
            </p>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <!-- Header -->
            <div class="bg-blue-600 text-white px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">Detail Pesanan</h2>
                        <p class="text-blue-100">Kode Pesanan: <span class="font-mono">{{ $order->order_number }}</span></p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-sm font-medium">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Customer Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penyewa</h3>
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
                            <div class="flex justify-between">
                                <span class="text-gray-600">Alamat:</span>
                                <span class="font-medium text-right max-w-xs">{{ $order->customer->address }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rental Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Penyewaan</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Mulai:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($order->rental_start_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Selesai:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($order->rental_end_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Durasi:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($order->rental_start_date)->diffInDays(\Carbon\Carbon::parse($order->rental_end_date)) + 1 }} hari</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pengambilan:</span>
                                <span class="font-medium">
                                    {{ $order->delivery_method === 'pickup' ? 'Ambil di Toko' : 'Antar ke Alamat' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Alat Musik yang Disewa</h3>
                    <div class="border rounded-lg overflow-hidden">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-4 p-4 {{ !$loop->last ? 'border-b' : '' }}">
                            <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                @if($item->instrument->image_path)
                                    <img src="{{ Storage::url($item->instrument->image_path) }}" 
                                         alt="{{ $item->instrument->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $item->instrument->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $item->instrument->category->name }}</p>
                                <p class="text-sm text-blue-600">{{ $item->quantity }}x - Rp {{ number_format($item->daily_rate, 0, ',', '.') }}/hari</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h3>
                    <div class="space-y-2">
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
                        <div class="border-t pt-2 flex justify-between text-lg font-bold text-blue-600">
                            <span>Total:</span>
                            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($order->notes)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Catatan</h3>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Langkah Selanjutnya</h2>
            
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-semibold text-blue-600">1</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Konfirmasi Pesanan</h3>
                        <p class="text-sm text-gray-600">
                            Tim kami akan menghubungi Anda dalam 1-2 jam untuk konfirmasi pesanan dan detail pembayaran.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-semibold text-blue-600">2</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Pembayaran</h3>
                        <p class="text-sm text-gray-600">
                            Lakukan pembayaran sesuai dengan instruksi yang diberikan oleh tim kami.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-semibold text-blue-600">3</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Pengambilan/Pengiriman</h3>
                        <p class="text-sm text-gray-600">
                            @if($order->delivery_method === 'pickup')
                                Ambil alat musik di toko kami sesuai dengan jadwal yang telah disepakati.
                            @else
                                Alat musik akan diantar ke alamat Anda sesuai dengan jadwal yang telah disepakati.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Actions -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('frontend.track-order-form') }}" 
               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 text-center">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Lacak Pesanan
            </a>

            <a href="{{ route('frontend.catalog') }}" 
               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-300 text-center">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Sewa Lagi
            </a>

            <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20bertanya%20tentang%20pesanan%20{{ $order->order_number }}" 
               target="_blank"
               class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 text-center">
                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                </svg>
                WhatsApp
            </a>
        </div>

        <!-- Important Notes -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.865-.833-2.635 0L4.182 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-yellow-800 mb-1">Penting untuk Diingat:</h3>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Simpan kode pesanan Anda: <strong>{{ $order->order_number }}</strong></li>
                        <li>• Pastikan Anda dapat dihubungi di nomor {{ $order->customer->phone }}</li>
                        <li>• Alat musik harus dikembalikan tepat waktu untuk menghindari denda</li>
                        <li>• Laporkan segera jika ada kerusakan pada alat musik</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
