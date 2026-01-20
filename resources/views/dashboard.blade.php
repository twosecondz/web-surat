<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Sistem Informasi Perjalanan Dinas</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-600 text-white px-3 py-2 rounded font-bold text-lg">
                            bpka
                        </div>
                        <div class="text-blue-600 text-sm font-medium">
                            Badan Pengelolaan Keuangan Aceh
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button 
                            type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Title Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">
                Buat Surat Dinas Anda
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Pilih jenis surat yang ingin Anda buat. Sistem akan memandu Anda mengisi data yang diperlukan.
            </p>
        </div>

        <!-- Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <!-- Surat Tugas Card -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="flex flex-col items-center text-center">
                    <!-- Icon -->
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">
                        Surat Tugas
                    </h2>
                    
                    <!-- Description -->
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Buat surat tugas untuk pegawai yang akan melaksanakan kegiatan dinas
                    </p>
                    
                    <!-- Button -->
                    <a href="{{ route('surat-tugas.create') }}" 
                       class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors text-center">
                        Buat Surat Tugas
                    </a>
                </div>
            </div>

            <!-- Surat Dinas Card -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="flex flex-col items-center text-center">
                    <!-- Icon -->
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">
                        Surat Dinas
                    </h2>
                    
                    <!-- Description -->
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Buat surat perjalanan dinas (SPD) untuk perjalanan pegawai
                    </p>
                    
                    <!-- Button -->
                    <a href="{{ route('spd.index') }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors text-center">
                        Lihat Semua
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-gray-600 text-sm">
                &copy; {{ date('Y') }} Badan Pengelolaan Keuangan Aceh. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>
