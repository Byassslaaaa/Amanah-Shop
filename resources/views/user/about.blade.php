@extends('layouts.app')

@section('title', 'Tentang Kami - Amanah Shop')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-green-600 to-green-800 text-white">
    <div class="max-w-7xl mx-auto px-4 py-20">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Tentang Kami</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                Mengenal lebih dekat Amanah Shop dan misi kami dalam memberdayakan UMKM lokal
            </p>
        </div>
    </div>
</section>

<!-- About Content Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        @if($aboutContent)
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    @if($aboutContent->image)
                        <div class="mb-8 text-center">
                            <img src="{{ Storage::url($aboutContent->image) }}" alt="{{ $aboutContent->title }}" class="w-full max-w-2xl mx-auto rounded-lg shadow-md">
                        </div>
                    @endif
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">{{ $aboutContent->title }}</h2>
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($aboutContent->content)) !!}
                    </div>
                </div>
            </div>
        @else
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Amanah Shop</h2>
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        <p class="mb-6">
                            Amanah Shop adalah koperasi simpan pinjam yang menyediakan perabotan rumah tangga,
                            pakaian, sepatu, dan berbagai keperluan rumah seperti karpet, kasur, bantal, lemari, dan
                            masih banyak lagi. Kami menyediakan sistem pembayaran tunai dan kredit dengan cicilan
                            yang terjangkau dan fleksibel, memudahkan Anda untuk memenuhi kebutuhan rumah tangga
                            tanpa memberatkan keuangan.
                        </p>

                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">Visi Kami</h3>
                        <p class="mb-6">
                            Menjadi koperasi terdepan dalam memberdayakan UMKM lokal melalui penyediaan
                            produk berkualitas dengan sistem pembayaran yang mudah dan terpercaya.
                        </p>

                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">Misi Kami</h3>
                        <ul class="list-disc pl-6 mb-6">
                            <li class="mb-2">Menyediakan platform digital yang mudah diakses untuk produk UMKM lokal</li>
                            <li class="mb-2">Memberikan kemudahan pembayaran melalui sistem kredit dengan bunga rendah</li>
                            <li class="mb-2">Menjaga kualitas produk lokal dengan standar yang tinggi</li>
                            <li class="mb-2">Membangun kepercayaan melalui sistem pembayaran yang transparan dan amanah</li>
                        </ul>
                        
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">Mengapa Memilih Kami?</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-green-800 mb-2">Produk Berkualitas</h4>
                                <p class="text-sm text-green-700">Semua produk telah melalui proses seleksi ketat untuk menjamin kualitas terbaik</p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-blue-800 mb-2">Sistem Kredit Terpercaya</h4>
                                <p class="text-sm text-blue-700">Nikmati kemudahan pembayaran dengan sistem cicilan bunga rendah dan transparan</p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-yellow-800 mb-2">Pengiriman Terpercaya</h4>
                                <p class="text-sm text-yellow-700">Sistem logistik yang handal untuk memastikan produk sampai dengan aman</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-purple-800 mb-2">Harga Bersaing</h4>
                                <p class="text-sm text-purple-700">Harga langsung dari produsen tanpa markup berlebihan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Team Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Tim Kami</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Didukung oleh tim profesional yang berpengalaman dalam pengembangan ekonomi digital dan pemberdayaan masyarakat
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-r from-green-400 to-green-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">TM</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Tim Management</h3>
                <p class="text-gray-600">Mengelola operasional platform dan kemitraan strategis</p>
            </div>
            
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">TD</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Tim Development</h3>
                <p class="text-gray-600">Mengembangkan dan memelihara platform digital</p>
            </div>
            
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-r from-purple-400 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">TP</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Tim Partnership</h3>
                <p class="text-gray-600">Membangun kerjasama dengan UMKM dan komunitas lokal</p>
            </div>
        </div>
    </div>
</section>
@endsection