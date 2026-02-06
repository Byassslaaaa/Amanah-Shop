@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran - Amanah Shop')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('user.credit.index') }}" class="hover:text-green-600">Kredit</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('user.credit.show', $installment->order) }}" class="hover:text-green-600">Detail</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900 font-medium">Upload Bukti</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Upload Bukti Pembayaran</h1>
            <p class="text-gray-600 mt-1 text-sm sm:text-base">Cicilan ke-{{ $installment->installment_number }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembayaran</h2>

                    <!-- Payment Details -->
                    <div class="bg-gradient-to-br from-blue-50 to-green-50 rounded-lg p-4 mb-6 border border-blue-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">No. Order</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $installment->order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Cicilan Ke-</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $installment->installment_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Jatuh Tempo</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $installment->due_date->format('d M Y') }}</p>
                                @if($installment->due_date->isPast() && $installment->status !== 'paid')
                                    <p class="text-xs text-red-600 font-semibold mt-1">
                                        Lewat {{ $installment->due_date->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Jumlah Tagihan</p>
                                <p class="text-lg font-bold text-green-600">Rp{{ number_format($installment->amount_due, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($installment->amount_paid > 0)
                            <div class="mt-4 pt-4 border-t border-blue-200">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-xs text-gray-600 mb-1">Sudah Dibayar</p>
                                        <p class="text-sm font-bold text-gray-900">Rp{{ number_format($installment->amount_paid, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 mb-1 text-right">Sisa Tagihan</p>
                                        <p class="text-sm font-bold text-red-600">Rp{{ number_format($installment->amount_due - $installment->amount_paid, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Current Payment Proof -->
                    @if($installment->payment_proof)
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Bukti Pembayaran Sudah Diupload</h3>
                                    <p class="text-xs text-gray-700 mb-2">
                                        @if($installment->status === 'pending')
                                            Status: <span class="font-semibold text-yellow-700">Menunggu Verifikasi Admin</span>
                                        @elseif($installment->status === 'paid')
                                            Status: <span class="font-semibold text-green-700">Terverifikasi & Lunas</span>
                                        @else
                                            Status: <span class="font-semibold text-gray-700">{{ ucfirst($installment->status) }}</span>
                                        @endif
                                    </p>
                                    <a href="{{ Storage::url($installment->payment_proof) }}" target="_blank"
                                       class="text-xs text-blue-600 hover:text-blue-800 font-medium inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat Bukti yang Sudah Diupload
                                    </a>
                                    @if($installment->notes)
                                        <p class="text-xs text-gray-600 mt-2">
                                            <span class="font-medium">Catatan Anda:</span> {{ $installment->notes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Upload Form -->
                    <form action="{{ route('user.credit.upload-payment-proof', $installment) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h3 class="font-semibold text-gray-900 mb-4">
                            {{ $installment->payment_proof ? 'Upload Bukti Pembayaran Baru' : 'Upload Bukti Pembayaran' }}
                        </h3>

                        <!-- Payment Proof Image -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Transfer <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label for="payment_proof" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag & drop</p>
                                        <p class="text-xs text-gray-500">PNG, JPG atau JPEG (Maks. 2MB)</p>
                                    </div>
                                    <input id="payment_proof" name="payment_proof" type="file" class="hidden" accept="image/jpeg,image/png,image/jpg" required onchange="previewImage(event)" />
                                </label>
                            </div>
                            @error('payment_proof')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Image Preview -->
                            <div id="image-preview" class="mt-4 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                                <img id="preview-img" src="" alt="Preview" class="max-w-full h-auto rounded-lg border border-gray-300">
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan (Opsional)
                            </label>
                            <textarea name="notes" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Tambahkan catatan jika ada...">{{ old('notes', $installment->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                    class="flex-1 inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Upload Bukti Pembayaran
                            </button>
                            <a href="{{ route('user.credit.show', $installment->order) }}"
                               class="flex-1 inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition-colors">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Info Box -->
                <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-200 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Informasi Penting
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Upload foto bukti transfer yang jelas</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Pastikan nominal yang ditransfer sesuai dengan tagihan</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Admin akan memverifikasi dalam 1x24 jam</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Anda dapat mengupload ulang jika ada kesalahan</span>
                        </li>
                    </ul>
                </div>

                <!-- Quick Link -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">Butuh Bantuan?</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Jika Anda mengalami kendala, silakan hubungi customer service kami.
                    </p>
                    <a href="{{ route('contact') }}"
                       class="block w-full text-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                        Hubungi CS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
