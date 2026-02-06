@extends('layouts.app')

@section('title', 'Tentang Kami - Amanah Shop')

@section('content')
<!-- Welcome Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left: Image -->
            <div class="order-2 lg:order-1">
                @if($aboutContent && $aboutContent->image)
                    <img src="{{ Storage::url($aboutContent->image) }}" alt="{{ $aboutContent->title }}" class="w-full rounded-3xl shadow-lg">
                @else
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800" alt="Amanah Shop Team" class="w-full rounded-3xl shadow-lg">
                    </div>
                @endif
            </div>

            <!-- Right: Content -->
            <div class="order-1 lg:order-2">
                <h1 class="text-5xl font-bold mb-6 text-[#253D4E]" >
                    Selamat Datang di Amanah Shop
                </h1>
                @if($aboutContent)
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-8">
                        {!! nl2br(e($aboutContent->content)) !!}
                    </div>
                @else
                    <p class="text-lg text-gray-600 leading-relaxed mb-8">
                        Amanah Shop adalah toko online yang menyediakan berbagai kebutuhan rumah tangga & lifestyle berkualitas.
                        Kami menawarkan perabotan, perlengkapan kamar tidur, pakaian, sepatu, tekstil rumah, aksesoris, dan berbagai
                        keperluan rumah tangga lainnya dengan harga terjangkau. Dengan sistem pembayaran yang fleksibel (tunai dan cicilan),
                        kami memudahkan Anda untuk memenuhi kebutuhan tanpa memberatkan keuangan.
                    </p>
                @endif

                <!-- Small Feature Images -->
                <div class="grid grid-cols-3 gap-4 mt-8">
                    <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                        <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400" alt="Home Furniture" class="w-full h-32 object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                        <img src="https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=400" alt="Home Decor" class="w-full h-32 object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                        <img src="https://images.unsplash.com/photo-1484101403633-562f891dc89a?w=400" alt="Home Essentials" class="w-full h-32 object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What We Provide Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-[#253D4E] mb-4" >Apa yang Kami Tawarkan?</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Keunggulan dan layanan terbaik untuk memenuhi kebutuhan rumah tangga Anda
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Produk Berkualitas -->
            <div class="bg-white p-8 rounded-2xl hover:shadow-xl transition-all duration-300 text-center group">
                <div class="w-20 h-20 bg-[#F2FCE4] rounded-2xl mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-circle-check text-4xl text-[#3BB77E]"></i>
                </div>
                <h3 class="text-xl font-bold text-[#253D4E] mb-3">Produk Berkualitas</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Menawarkan produk pilihan berkualitas untuk memenuhi kebutuhan rumah tangga Anda</p>
            </div>

            <!-- Pembayaran Fleksibel -->
            <div class="bg-white p-8 rounded-2xl hover:shadow-xl transition-all duration-300 text-center group">
                <div class="w-20 h-20 bg-[#FFFCEB] rounded-2xl mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-credit-card text-4xl text-[#FDC040]"></i>
                </div>
                <h3 class="text-xl font-bold text-[#253D4E] mb-3">Pembayaran Fleksibel</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Kemudahan pembayaran tunai atau cicilan dengan proses yang mudah dan transparan</p>
            </div>

            <!-- Pengiriman Cepat -->
            <div class="bg-white p-8 rounded-2xl hover:shadow-xl transition-all duration-300 text-center group">
                <div class="w-20 h-20 bg-[#FFE6D2] rounded-2xl mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-truck-fast text-4xl text-[#FF6B35]"></i>
                </div>
                <h3 class="text-xl font-bold text-[#253D4E] mb-3">Pengiriman Cepat</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Proses pengiriman yang cepat dan aman ke seluruh Indonesia</p>
            </div>

            <!-- Harga Terjangkau -->
            <div class="bg-white p-8 rounded-2xl hover:shadow-xl transition-all duration-300 text-center group">
                <div class="w-20 h-20 bg-[#BCE3C9] rounded-2xl mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-tags text-4xl text-[#3BB77E]"></i>
                </div>
                <h3 class="text-xl font-bold text-[#253D4E] mb-3">Harga Terjangkau</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Harga bersaing dengan kualitas terbaik untuk kepuasan pelanggan</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Performance Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left: Images -->
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-3xl overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600" alt="Online Shopping" class="w-full h-80 object-cover">
                </div>
                <div class="rounded-3xl overflow-hidden shadow-lg mt-8">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600" alt="Customer Service" class="w-full h-80 object-cover">
                </div>
            </div>

            <!-- Right: Content -->
            <div>
                <p class="text-[#3BB77E] font-semibold mb-2">Performa Kami</p>
                <h2 class="text-4xl font-bold text-[#253D4E] mb-6" >
                    Mitra Terpercaya untuk Solusi Belanja Online Anda
                </h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Kami berkomitmen untuk memberikan pengalaman berbelanja online terbaik dengan menyediakan berbagai produk berkualitas,
                    sistem pembayaran yang fleksibel, dan layanan pelanggan yang responsif. Kepuasan Anda adalah prioritas utama kami.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Dengan pengalaman bertahun-tahun melayani ribuan pelanggan, kami terus berinovasi untuk memberikan kemudahan dan
                    kenyamanan dalam setiap transaksi. Percayakan kebutuhan rumah tangga Anda kepada Amanah Shop.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Vision, Mission Section -->
<section class="py-16 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Who We Are -->
            <div class="text-center">
                <h3 class="text-2xl font-bold text-[#253D4E] mb-4" >Tentang Kami</h3>
                <p class="text-gray-600 leading-relaxed">
                    @if($aboutContent && $aboutContent->content)
                        {{ Str::limit($aboutContent->content, 200) }}
                    @else
                        Amanah Shop adalah toko online yang menyediakan berbagai kebutuhan rumah tangga & lifestyle berkualitas
                        dengan harga terjangkau dan sistem pembayaran yang fleksibel.
                    @endif
                </p>
            </div>

            <!-- Our Vision -->
            <div class="text-center">
                <h3 class="text-2xl font-bold text-[#253D4E] mb-4" >Visi Kami</h3>
                <p class="text-gray-600 leading-relaxed">
                    @if($aboutContent && $aboutContent->vision)
                        {{ $aboutContent->vision }}
                    @else
                        Menjadi toko online terpercaya dan pilihan utama untuk kebutuhan rumah tangga & lifestyle dengan
                        menawarkan produk berkualitas dan sistem pembayaran yang mudah.
                    @endif
                </p>
            </div>

            <!-- Our Mission -->
            <div class="text-center">
                <h3 class="text-2xl font-bold text-[#253D4E] mb-4" >Misi Kami</h3>
                <div class="text-left text-gray-600 leading-relaxed">
                    <ul class="space-y-2">
                        @if($aboutContent && $aboutContent->mission)
                            @foreach(explode("\n", $aboutContent->mission) as $mission)
                                @if(trim($mission))
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-[#3BB77E] mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm">{{ trim($mission) }}</span>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-[#3BB77E] mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm">Produk berkualitas harga terjangkau</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-[#3BB77E] mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm">Pembayaran tunai & cicilan</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-[#3BB77E] mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm">Layanan responsif & profesional</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-[#3BB77E] mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm">Transaksi transparan & aman</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-16 bg-gradient-to-br from-[#3BB77E] to-[#2a9d66]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-8">
            <div class="text-center text-white">
                <div class="text-5xl font-bold mb-2" >{{ $aboutContent->years_operating ?? 5 }}+</div>
                <p class="text-sm opacity-90">Tahun Beroperasi</p>
            </div>
            <div class="text-center text-white">
                <div class="text-5xl font-bold mb-2" >{{ number_format($aboutContent->happy_customers ?? 1000) }}+</div>
                <p class="text-sm opacity-90">Pelanggan Puas</p>
            </div>
            <div class="text-center text-white">
                <div class="text-5xl font-bold mb-2" >{{ number_format($aboutContent->products_sold ?? 500) }}+</div>
                <p class="text-sm opacity-90">Produk Terjual</p>
            </div>
            <div class="text-center text-white">
                <div class="text-5xl font-bold mb-2" >{{ $aboutContent->team_members ?? 10 }}+</div>
                <p class="text-sm opacity-90">Tim Profesional</p>
            </div>
            <div class="text-center text-white">
                <div class="text-5xl font-bold mb-2" >{{ number_format($aboutContent->product_variants ?? 200) }}+</div>
                <p class="text-sm opacity-90">Varian Produk</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-[#3BB77E] font-semibold mb-2">Tim Kami</p>
            <h2 class="text-4xl font-bold text-[#253D4E] mb-4" >Layanan Profesional Kami</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Didukung oleh tim profesional yang siap melayani kebutuhan Anda dengan sepenuh hati
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-8 hover:shadow-xl transition-all duration-300 border border-gray-100 group text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-[#3BB77E] to-[#2a9d66] rounded-2xl mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-headset text-5xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#253D4E] mb-3">Customer Service</h3>
                <p class="text-gray-600 leading-relaxed mb-4">Siap membantu dan menjawab pertanyaan Anda dengan cepat dan ramah</p>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-8 hover:shadow-xl transition-all duration-300 border border-gray-100 group text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-[#3BB77E] to-[#2a9d66] rounded-2xl mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-boxes-stacked text-5xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#253D4E] mb-3">Operasional</h3>
                <p class="text-gray-600 leading-relaxed mb-4">Memastikan pesanan Anda diproses dan dikirim dengan cepat dan aman</p>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-8 hover:shadow-xl transition-all duration-300 border border-gray-100 group text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-[#3BB77E] to-[#2a9d66] rounded-2xl mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-award text-5xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#253D4E] mb-3">Quality Control</h3>
                <p class="text-gray-600 leading-relaxed mb-4">Menjaga kualitas produk agar sesuai dengan standar dan harapan pelanggan</p>
            </div>
        </div>
    </div>
</section>
@endsection