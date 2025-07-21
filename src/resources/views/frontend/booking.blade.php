@extends('frontend.layout')

@section('title', 'Sewa ' . $instrument->name . ' - Music Rental')
@section('description', 'Sewa ' . $instrument->name . ' dengan mudah dan cepat. Harga mulai dari Rp ' . number_format($instrument->daily_rate, 0, ',', '.') . ' per hari.')

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
                <a href="{{ route('frontend.instrument-detail', $instrument->id) }}" class="hover:text-blue-600">{{ $instrument->name }}</a>
            </li>
            <li class="flex items-center">
                <svg class="h-4 w-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900 font-medium">Booking</span>
            </li>
        </ol>
    </div>
</nav>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Booking Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Form Pemesanan</h1>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('frontend.store-booking') }}" method="POST" x-data="bookingForm()" @submit="submitForm">
                    @csrf
                    <input type="hidden" name="instrument_id" value="{{ $instrument->id }}">

                    <!-- Customer Information -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penyewa</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Depan *</label>
                                <input type="text" 
                                       name="first_name" 
                                       id="first_name" 
                                       x-model="form.first_name"
                                       required 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-500 @enderror"
                                       placeholder="Nama depan">
                                @error('first_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Belakang *</label>
                                <input type="text" 
                                       name="last_name" 
                                       id="last_name" 
                                       x-model="form.last_name"
                                       required 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-500 @enderror"
                                       placeholder="Nama belakang">
                                @error('last_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       x-model="form.email"
                                       required 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                       placeholder="nama@email.com">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                                <input type="tel" 
                                       name="phone" 
                                       id="phone" 
                                       x-model="form.phone"
                                       required 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                                       placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Kota *</label>
                                <input type="text" 
                                       name="city" 
                                       id="city" 
                                       x-model="form.city"
                                       required 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror"
                                       placeholder="Nama kota">
                                @error('city')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Kode Pos *</label>
                                <input type="text" 
                                       name="postal_code" 
                                       id="postal_code" 
                                       x-model="form.postal_code"
                                       required 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('postal_code') border-red-500 @enderror"
                                       placeholder="12345">
                                @error('postal_code')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="id_card_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor KTP/Identitas *</label>
                                <input type="text" 
                                       name="id_card_number" 
                                       id="id_card_number" 
                                       x-model="form.id_card_number"
                                       required 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_card_number') border-red-500 @enderror"
                                       placeholder="3573xxxxxxxxxx">
                                @error('id_card_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat *</label>
                            <textarea name="address" 
                                      id="address" 
                                      x-model="form.address"
                                      required 
                                      rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror"
                                      placeholder="Masukkan alamat lengkap"></textarea>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Rental Details -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Penyewaan</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="rental_start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                                <input type="date" 
                                       name="rental_start_date" 
                                       id="rental_start_date" 
                                       x-model="form.rental_start_date"
                                       @change="calculateTotal"
                                       required 
                                       min="{{ date('Y-m-d') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('rental_start_date') border-red-500 @enderror">
                                @error('rental_start_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="rental_end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai *</label>
                                <input type="date" 
                                       name="rental_end_date" 
                                       id="rental_end_date" 
                                       x-model="form.rental_end_date"
                                       @change="calculateTotal"
                                       required 
                                       min="{{ date('Y-m-d') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('rental_end_date') border-red-500 @enderror">
                                @error('rental_end_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="delivery_method" class="block text-sm font-medium text-gray-700 mb-2">Metode Pengambilan *</label>
                                <select name="delivery_method" 
                                        id="delivery_method" 
                                        x-model="form.delivery_method"
                                        @change="calculateTotal"
                                        required 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('delivery_method') border-red-500 @enderror">
                                    <option value="">Pilih metode pengambilan</option>
                                    <option value="pickup">Ambil di Toko (Gratis)</option>
                                    <option value="delivery">Antar ke Alamat (+Rp 25.000)</option>
                                </select>
                                @error('delivery_method')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Jumlah *</label>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity" 
                                       x-model="form.quantity"
                                       @input="calculateTotal"
                                       required 
                                       min="1" 
                                       max="1"
                                       value="1"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror">
                                @error('quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Saat ini hanya tersedia 1 unit</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      x-model="form.notes"
                                      rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Catatan khusus untuk penyewaan ini..."></textarea>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-6">
                        <div class="flex items-start">
                            <input type="checkbox" 
                                   name="terms_agreed" 
                                   id="terms_agreed" 
                                   x-model="form.terms_agreed"
                                   required 
                                   class="mt-1 mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="terms_agreed" class="text-sm text-gray-700">
                                Saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-700 underline">syarat dan ketentuan</a> penyewaan alat musik. *
                            </label>
                        </div>
                        @error('terms_agreed')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" 
                                :disabled="!form.terms_agreed || isLoading"
                                :class="form.terms_agreed && !isLoading ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed'"
                                class="w-full md:w-auto px-8 py-3 text-white font-semibold rounded-lg transition duration-300">
                            <span x-show="!isLoading">Buat Pesanan</span>
                            <span x-show="isLoading" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4" x-data="orderSummary()">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                <!-- Instrument Info -->
                <div class="flex items-center gap-3 mb-6 pb-6 border-b">
                    <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                        @if($instrument->image_path)
                            <img src="{{ Storage::url($instrument->image_path) }}" 
                                 alt="{{ $instrument->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $instrument->name }}</h3>
                        <p class="text-xs text-gray-600">{{ $instrument->category->name }}</p>
                        <p class="text-sm font-medium text-blue-600">Rp {{ number_format($instrument->daily_rate, 0, ',', '.') }}/hari</p>
                    </div>
                </div>

                <!-- Cost Breakdown -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Harga per hari:</span>
                        <span class="font-medium">Rp {{ number_format($instrument->daily_rate, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm" x-show="days > 0">
                        <span class="text-gray-600">Jumlah hari:</span>
                        <span class="font-medium" x-text="days + ' hari'"></span>
                    </div>

                    <div class="flex justify-between text-sm" x-show="days > 0">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium" x-text="'Rp ' + subtotal.toLocaleString('id-ID')"></span>
                    </div>

                    <div class="flex justify-between text-sm" x-show="deliveryFee > 0">
                        <span class="text-gray-600">Biaya antar:</span>
                        <span class="font-medium" x-text="'Rp ' + deliveryFee.toLocaleString('id-ID')"></span>
                    </div>

                    <div class="border-t pt-3 flex justify-between text-lg font-bold text-blue-600">
                        <span>Total:</span>
                        <span x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 text-sm">Butuh Bantuan?</h4>
                    <div class="space-y-2 text-xs">
                        <a href="tel:+6281234567890" class="flex items-center text-blue-600 hover:text-blue-700">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            +62 812-3456-7890
                        </a>
                        <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center text-green-600 hover:text-green-700">
                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function bookingForm() {
    return {
        form: {
            first_name: '{{ old('first_name') }}',
            last_name: '{{ old('last_name') }}',
            email: '{{ old('email') }}',
            phone: '{{ old('phone') }}',
            id_card_number: '{{ old('id_card_number') }}',
            city: '{{ old('city') }}',
            postal_code: '{{ old('postal_code') }}',
            rental_start_date: '{{ old('rental_start_date') }}',
            rental_end_date: '{{ old('rental_end_date') }}',
            delivery_method: '{{ old('delivery_method') }}',
            quantity: {{ old('quantity', 1) }},
            notes: '{{ old('notes') }}',
            terms_agreed: {{ old('terms_agreed') ? 'true' : 'false' }}
        },
        isLoading: false,
        
        submitForm(event) {
            this.isLoading = true;
        },
        
        calculateTotal() {
            // Trigger calculation in order summary component
            window.dispatchEvent(new CustomEvent('calculate-total', {
                detail: {
                    rental_start_date: this.form.rental_start_date,
                    rental_end_date: this.form.rental_end_date,
                    delivery_method: this.form.delivery_method,
                    quantity: this.form.quantity
                }
            }));
        }
    }
}

function orderSummary() {
    return {
        dailyRate: {{ $instrument->daily_rate }},
        days: 0,
        subtotal: 0,
        deliveryFee: 0,
        total: 0,
        
        init() {
            // Listen for calculation events
            window.addEventListener('calculate-total', (event) => {
                this.calculateFromForm(event.detail);
            });
            
            // Initial calculation
            this.calculateTotal();
        },
        
        calculateFromForm(formData) {
            if (formData.rental_start_date && formData.rental_end_date) {
                const startDate = new Date(formData.rental_start_date);
                const endDate = new Date(formData.rental_end_date);
                const timeDiff = endDate.getTime() - startDate.getTime();
                this.days = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Include start day
            } else {
                this.days = 0;
            }
            
            this.subtotal = this.days * this.dailyRate * (formData.quantity || 1);
            this.deliveryFee = formData.delivery_method === 'delivery' ? 25000 : 0;
            this.total = this.subtotal + this.deliveryFee;
        },
        
        calculateTotal() {
            // Default calculation for initial load
            this.days = 1;
            this.subtotal = this.dailyRate;
            this.deliveryFee = 0;
            this.total = this.subtotal;
        }
    }
}
</script>
@endpush
