<div class="min-h-screen bg-gray-50">
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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Buat Surat Perjalanan Dinas (SPD)</h1>
            <p class="text-gray-600 mt-1">Melanjutkan dari Surat Tugas: <strong>{{ $suratTugas->nomor_surat }}</strong></p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <p class="font-medium mb-2">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form wire:submit.prevent="simpan">
            <!-- Informasi Dasar -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Dasar</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nomor SPD -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Nomor SPD <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="nomor_spd"
                            placeholder="/spd/"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('nomor_spd') border-red-500 @enderror"
                        >
                        @error('nomor_spd')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pejabat Pembuat Komitmen -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Pejabat Pembuat Komitmen
                        </label>
                        <div class="relative" x-data="{ open: false }">
                            <input 
                                type="text" 
                                wire:model="ppk_nama"
                                @click="open = !open"
                                placeholder="Pilih PPK..."
                                class="w-full px-4 py-2.5 pr-10 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer"
                                readonly
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            
                            <!-- Dropdown -->
                            <div 
                                x-show="open"
                                @click.away="open = false"
                                class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                                style="display: none;"
                            >
                                @forelse($availableUsers as $user)
                                    <div 
                                        wire:click="$set('ppk_id', {{ $user['id'] }})"
                                        @click="open = false"
                                        class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                    >
                                        <div class="font-medium text-gray-900">{{ $user['name'] }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ $user['nip'] ?? '-' }} | {{ $user['jabatan'] ?? '-' }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-4 py-3 text-gray-500 text-center">Tidak ada data</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Pegawai -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Data Pegawai</h2>

                <!-- Nama Pegawai -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Nama Pegawai <span class="text-red-500">*</span>
                    </label>
                    <select 
                        wire:model.live="pegawai_id"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 @error('pegawai_id') border-red-500 @enderror"
                    >
                        <option value="">Pilih Pegawai dari Surat Tugas</option>
                        @foreach($availablePeserta as $peserta)
                            <option value="{{ $peserta['id'] }}">
                                {{ $peserta['pivot_nama'] }} - {{ $peserta['pivot_jabatan'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('pegawai_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Pangkat/Golongan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Pangkat/Golongan
                        </label>
                        <input 
                            type="text" 
                            wire:model="pangkat_golongan"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed"
                            readonly
                        >
                    </div>

                    <!-- NIP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            NIP
                        </label>
                        <input 
                            type="text" 
                            wire:model="nip_pegawai"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed"
                            readonly
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Jabatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Jabatan
                        </label>
                        <input 
                            type="text" 
                            wire:model="jabatan_pegawai"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed"
                            readonly
                        >
                    </div>

                    <!-- Tingkat Biaya -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Tingkat Biaya
                        </label>
                        <div class="relative">
                            <select 
                                wire:model="tingkat_biaya"
                                class="w-full px-4 py-2.5 pr-10 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 appearance-none"
                            >
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail perjalanan -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Detail perjalanan</h2>

                <!-- Maksud Perjalanan Dinas -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Maksud Perjalanan Dinas <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        wire:model="maksud_perjalanan"
                        rows="3"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 @error('maksud_perjalanan') border-red-500 @enderror"
                    ></textarea>
                    @error('maksud_perjalanan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alat Transportasi -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Alat Transportasi
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model="alat_transportasi"
                            class="w-full px-4 py-2.5 pr-10 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Tempat Tujuan -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Tempat Tujuan <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model="tempat_tujuan"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 @error('tempat_tujuan') border-red-500 @enderror"
                    >
                    @error('tempat_tujuan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Mulai & Selesai -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            wire:model="tanggal_mulai"
                            placeholder="dd/bb/tttt"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 @error('tanggal_mulai') border-red-500 @enderror"
                        >
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            wire:model="tanggal_selesai"
                            placeholder="dd/bb/tttt"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 @error('tanggal_selesai') border-red-500 @enderror"
                        >
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Lamanya (hari) -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Lamanya (hari)
                    </label>
                    <input 
                        type="number" 
                        wire:model="lamanya_hari"
                        min="1"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                </div>
            </div>

            <!-- Pembebanan Anggaran -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Pembebanan Anggaran</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kegiatan/Sub Kegiatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Kegiatan/Sub Kegiatan
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model="kegiatan_sub_kegiatan"
                                class="w-full px-4 py-2.5 pr-10 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Kode Rekening -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Kode Rekening
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model="kode_rekening"
                                class="w-full px-4 py-2.5 pr-10 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Penandatanganan (PPTK) -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Penandatanganan (PPTK)</h2>

                <!-- Nama PPTK -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Nama PPTK
                    </label>
                    <div class="relative" x-data="{ open: false }">
                        <input 
                            type="text" 
                            wire:model="nama_pptk"
                            @click="open = !open"
                            placeholder="Pilih PPTK..."
                            class="w-full px-4 py-2.5 pr-10 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer"
                            readonly
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        
                        <!-- Dropdown -->
                        <div 
                            x-show="open"
                            @click.away="open = false"
                            class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                            style="display: none;"
                        >
                            @forelse($availableUsers as $user)
                                <div 
                                    wire:click="$set('pptk_id', {{ $user['id'] }})"
                                    @click="open = false"
                                    class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                >
                                    <div class="font-medium text-gray-900">{{ $user['name'] }}</div>
                                    <div class="text-sm text-gray-600">
                                        {{ $user['nip'] ?? '-' }} | {{ $user['jabatan'] ?? '-' }}
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-3 text-gray-500 text-center">Tidak ada data</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Pangkat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Pangkat
                        </label>
                        <input 
                            type="text" 
                            wire:model="pangkat_pptk"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed"
                            readonly
                        >
                    </div>

                    <!-- NIP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            NIP
                        </label>
                        <input 
                            type="text" 
                            wire:model="nip_pptk"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed"
                            readonly
                        >
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between gap-4">
                <button 
                    type="button"
                    wire:click="batal"
                    class="px-8 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                >
                    Batal
                </button>
                <button 
                    type="submit"
                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium"
                >
                    Preview surat
                </button>
            </div>
        </form>
    </div>
</div>
