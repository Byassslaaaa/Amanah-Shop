@extends('layouts.app')

@section('title', 'Hubungi Kami - Amanah Shop')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <!-- Background Pattern Image -->
        <img src="{{ asset('images/patterns/hero-pattern.png') }}"
             alt="Hero Background"
             class="absolute inset-0 w-full h-full object-cover"
             onerror="this.parentElement.classList.add('bg-gradient-to-r', 'from-[#3BB77E]', 'to-[#2a9d65]')">

        <div class="relative z-10 py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-semibold text-sm">Layanan Pelanggan</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
                    Hubungi Kami
                </h1>
                <p class="text-lg sm:text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">
                    Ada pertanyaan atau butuh bantuan? Tim kami siap membantu Anda
                </p>
            </div>
        </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">

        <!-- Contact Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <!-- WhatsApp -->
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center hover:shadow-xl hover:border-[#3BB77E] transition-all duration-300">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-2xl mx-auto mb-5">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#253D4E] mb-2">WhatsApp</h3>
                <p class="text-gray-600 mb-4">Chat langsung dengan kami</p>
                @if($whatsappNumber)
                    <p class="font-semibold text-[#3BB77E] text-lg">{{ \App\Helpers\WhatsappHelper::getDisplayPhoneNumber($whatsappNumber) }}</p>
                @else
                    <p class="text-gray-400">Belum diatur</p>
                @endif
            </div>

            <!-- Jam Operasional -->
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center hover:shadow-xl hover:border-[#3BB77E] transition-all duration-300">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mx-auto mb-5">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#253D4E] mb-2">Jam Operasional</h3>
                <p class="text-gray-600 mb-4">Waktu layanan kami</p>
                <p class="font-semibold text-[#253D4E]">Senin - Sabtu</p>
                <p class="text-gray-600">08:00 - 21:00 WIB</p>
            </div>

            <!-- Respon Cepat -->
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center hover:shadow-xl hover:border-[#3BB77E] transition-all duration-300">
                <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-2xl mx-auto mb-5">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#253D4E] mb-2">Respon Cepat</h3>
                <p class="text-gray-600 mb-4">Kami balas pesan Anda</p>
                <p class="font-semibold text-[#253D4E]">Maksimal 1x24 Jam</p>
                <p class="text-gray-600">Di hari kerja</p>
            </div>
        </div>

        <!-- CTA WhatsApp -->
        <div class="bg-gradient-to-r from-[#3BB77E] to-[#2a9d65] rounded-2xl p-10 text-center mb-16">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">
                Butuh Bantuan Sekarang?
            </h2>
            <p class="text-white/90 mb-8 max-w-2xl mx-auto">
                Hubungi kami via WhatsApp untuk respon lebih cepat. Kami siap membantu pertanyaan seputar produk, pemesanan, dan pengiriman.
            </p>
            @if($whatsappUrl)
                <a href="{{ $whatsappUrl }}" target="_blank"
                   class="inline-flex items-center bg-white text-[#3BB77E] px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Chat via WhatsApp
                </a>
            @else
                <div class="bg-white/20 rounded-xl p-5 max-w-lg mx-auto">
                    <p class="text-white font-medium">
                        Nomor WhatsApp belum diatur. Silakan hubungi administrator.
                    </p>
                </div>
            @endif
        </div>

        <!-- FAQ Section -->
        <div>
            <h2 class="text-3xl sm:text-4xl font-bold text-[#253D4E] text-center mb-12">
                Pertanyaan Umum
            </h2>
            <div class="max-w-3xl mx-auto space-y-5" x-data="{ openFaq: null }">
                <!-- FAQ 1 -->
                <div class="bg-white rounded-2xl border border-gray-100 hover:border-[#3BB77E] hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full text-left p-6 flex items-center justify-between">
                        <h3 class="font-bold text-[#253D4E] text-lg pr-4">Bagaimana cara memesan produk?</h3>
                        <svg class="w-6 h-6 text-[#3BB77E] flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="px-6 pb-6">
                        <p class="text-gray-600 leading-relaxed">Pilih produk yang diinginkan, masukkan ke keranjang, lalu lanjutkan ke checkout. Isi alamat pengiriman dan pilih metode pembayaran. Setelah pembayaran dikonfirmasi, pesanan akan segera diproses.</p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white rounded-2xl border border-gray-100 hover:border-[#3BB77E] hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full text-left p-6 flex items-center justify-between">
                        <h3 class="font-bold text-[#253D4E] text-lg pr-4">Metode pembayaran apa saja yang tersedia?</h3>
                        <svg class="w-6 h-6 text-[#3BB77E] flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="px-6 pb-6">
                        <p class="text-gray-600 leading-relaxed">Kami menerima pembayaran melalui Transfer Bank, Virtual Account, E-Wallet (GoPay, OVO, Dana), dan sistem kredit/cicilan untuk pembelian tertentu.</p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white rounded-2xl border border-gray-100 hover:border-[#3BB77E] hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full text-left p-6 flex items-center justify-between">
                        <h3 class="font-bold text-[#253D4E] text-lg pr-4">Berapa lama pengiriman pesanan?</h3>
                        <svg class="w-6 h-6 text-[#3BB77E] flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="px-6 pb-6">
                        <p class="text-gray-600 leading-relaxed">Waktu pengiriman tergantung lokasi dan kurir yang dipilih. Estimasi pengiriman akan ditampilkan saat checkout. Umumnya 2-7 hari kerja untuk area Jawa dan 5-14 hari kerja untuk luar Jawa.</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-white rounded-2xl border border-gray-100 hover:border-[#3BB77E] hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full text-left p-6 flex items-center justify-between">
                        <h3 class="font-bold text-[#253D4E] text-lg pr-4">Bagaimana jika barang rusak atau tidak sesuai?</h3>
                        <svg class="w-6 h-6 text-[#3BB77E] flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 4" x-collapse class="px-6 pb-6">
                        <p class="text-gray-600 leading-relaxed">Segera hubungi kami via WhatsApp dengan menyertakan foto/video kondisi barang maksimal 1x24 jam setelah barang diterima. Kami akan membantu proses pengembalian atau penggantian.</p>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="bg-white rounded-2xl border border-gray-100 hover:border-[#3BB77E] hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <button @click="openFaq = openFaq === 5 ? null : 5" class="w-full text-left p-6 flex items-center justify-between">
                        <h3 class="font-bold text-[#253D4E] text-lg pr-4">Apakah bisa COD (Bayar di Tempat)?</h3>
                        <svg class="w-6 h-6 text-[#3BB77E] flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 5" x-collapse class="px-6 pb-6">
                        <p class="text-gray-600 leading-relaxed">Saat ini kami belum menyediakan layanan COD. Semua pembayaran dilakukan di muka melalui metode pembayaran yang tersedia untuk keamanan transaksi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
