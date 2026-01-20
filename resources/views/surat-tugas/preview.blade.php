<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Surat Tugas - {{ $suratTugas->nomor_surat }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                            <div class="bg-blue-600 text-white px-3 py-2 rounded font-bold text-lg">
                                bpka
                            </div>
                            <div class="text-blue-600 text-sm font-medium">
                                Badan Pengelolaan Keuangan Aceh
                            </div>
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Preview Surat Tugas</h2>
                    <p class="mt-1 text-sm text-gray-600">Nomor: {{ $suratTugas->nomor_surat }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('pdf.surat-tugas.download', $suratTugas->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </a>
                    <a href="{{ route('surat-dinas.create', $suratTugas->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        Lanjut ke Surat Perjalanan Dinas
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        <strong>Surat Tugas berhasil dibuat!</strong> 
                        Anda dapat melihat preview PDF di bawah, download PDF, atau melanjutkan ke pembuatan Surat Perjalanan Dinas.
                    </p>
                </div>
            </div>
        </div>

        <!-- Document Info Card -->
        <div class="bg-white shadow rounded-lg mb-6 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Surat Tugas</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nomor Surat</p>
                    <p class="font-medium">{{ $suratTugas->nomor_surat }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Surat</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($suratTugas->tanggal_surat)->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Maksud</p>
                    <p class="font-medium">{{ $suratTugas->maksud }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tempat Tujuan</p>
                    <p class="font-medium">{{ $suratTugas->tempat_tujuan }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Periode</p>
                    <p class="font-medium">
                        {{ \Carbon\Carbon::parse($suratTugas->tanggal_mulai)->translatedFormat('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($suratTugas->tanggal_selesai)->translatedFormat('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jumlah Peserta</p>
                    <p class="font-medium">{{ $suratTugas->peserta->count() }} orang</p>
                </div>
            </div>
        </div>

        <!-- PDF Preview -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview PDF</h3>
            <div class="border rounded-lg overflow-hidden" style="height: 800px;">
                <iframe 
                    src="{{ route('pdf.surat-tugas.preview', $suratTugas->id) }}" 
                    class="w-full h-full"
                    frameborder="0"
                    title="Preview Surat Tugas PDF">
                </iframe>
            </div>
            <div class="mt-4 text-sm text-gray-600 text-center">
                <p>Jika preview tidak muncul, <a href="{{ route('pdf.surat-tugas.download', $suratTugas->id) }}" class="text-blue-600 hover:underline">klik di sini untuk download PDF</a></p>
            </div>
        </div>

        <!-- Action Buttons (Bottom) -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50">
                Kembali ke Dashboard
            </a>
            <a href="{{ route('surat-dinas.create', $suratTugas->id) }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700">
                Lanjut ke Surat Perjalanan Dinas
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
</body>
</html>
