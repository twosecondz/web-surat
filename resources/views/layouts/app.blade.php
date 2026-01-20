<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SIPD' }} - Sistem Informasi Perjalanan Dinas</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50">
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

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-gray-600 text-sm">
                &copy; {{ date('Y') }} Badan Pengelolaan Keuangan Aceh. All rights reserved.
            </p>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
