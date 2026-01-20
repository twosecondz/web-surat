<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Informasi Perjalanan Dinas</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white flex items-center justify-center">
    <div class="w-full max-w-6xl mx-auto px-8">
        <div class="flex items-center justify-between gap-16">
            <!-- Left Side - Login Form -->
            <div class="w-full max-w-md">
                <h1 class="text-5xl font-bold text-gray-900 mb-12">Login</h1>

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- NIP/NIK/Email/Username Input -->
                    <div>
                        <label for="identity" class="block text-base font-medium text-gray-900 mb-2">
                            NIP
                        </label>
                        <input 
                            type="text" 
                            id="identity" 
                            name="identity" 
                            value="{{ old('identity') }}"
                            placeholder="Masukkan NIP" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('identity') border-red-500 @enderror"
                            required
                            autofocus
                        >
                        @error('identity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-base font-medium text-gray-900 mb-2">
                            Kata Sandi
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan kata sandi" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('password') border-red-500 @enderror"
                            required
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me (Optional) -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember" 
                            class="w-4 h-4 text-yellow-400 border-gray-300 rounded focus:ring-yellow-400"
                        >
                        <label for="remember" class="ml-2 text-sm text-gray-700">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-medium py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2"
                    >
                        Login
                    </button>
                </form>

                <!-- Help Text -->
                <div class="mt-6 text-center text-sm text-gray-600">
                    <p>Anda dapat login menggunakan:</p>
                    <p class="font-medium">NIP, NIK, Email, atau Username</p>
                </div>
            </div>

            <!-- Right Side - Logo -->
            <div class="hidden lg:block w-full max-w-lg">
                <img 
                    src="{{ asset('images/logo-pancacita.png') }}" 
                    alt="Logo Pancacita BPKA" 
                    class="w-full h-auto"
                    onerror="this.style.display='none'"
                >
            </div>
        </div>
    </div>
</body>
</html>
