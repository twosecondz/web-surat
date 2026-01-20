<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
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

        <form wire:submit.prevent="simpan">
            <!-- Informasi Surat Tugas (Read-only) -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="font-bold text-blue-900 mb-3">Informasi dari Surat Tugas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-blue-700 font-medium">Maksud:</span>
                        <p class="text-blue-900">{{ $suratTugas->maksud }}</p>
                    </div>
                    <div>
                        <span class="text-blue-700 font-medium">Tempat Tujuan:</span>
                        <p class="text-blue-900">{{ $suratTugas->tempat_tujuan }}</p>
                    </div>
                    <div>
                        <span class="text-blue-700 font-medium">Tanggal:</span>
                        <p class="text-blue-900">
                            {{ $suratTugas->tanggal_mulai->format('d/m/Y') }} - 
                            {{ $suratTugas->tanggal_selesai->format('d/m/Y') }}
                        </p>
                    </div>
                    <div>
                        <span class="text-blue-700 font-medium">Peserta:</span>
                        <p class="text-blue-900">{{ count($availablePeserta) }} orang</p>
                    </div>
                </div>
            </div>

            <!-- SPD Form -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Detail Surat Perjalanan Dinas</h2>

                <!-- Nomor SPD & Pegawai -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Nomor SPD <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="nomor_spd"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        >
                        @error('nomor_spd')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Pegawai yang Melakukan Perjalanan <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model="pegawai_id"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        >
                            <option value="">Pilih Pegawai</option>
                            @foreach($availablePeserta as $peserta)
                                <option value="{{ $peserta['id'] }}">
                                    {{ $peserta['name'] }} - {{ $peserta['pivot']['jabatan'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('pegawai_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- PPK & PPTK -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Pejabat Pembuat Komitmen (PPK)
                        </label>
                        <select 
                            wire:model="ppk_id"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        >
                            <option value="">Pilih PPK</option>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user['id'] }}">
                                    {{ $user['name'] }} - {{ $user['jabatan'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            PPTK
                        </label>
                        <select 
                            wire:model="pptk_id"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        >
                            <option value="">Pilih PPTK</option>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user['id'] }}">
                                    {{ $user['name'] }} - {{ $user['jabatan'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Tingkat Biaya & Alat Transportasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Tingkat Biaya Perjalanan Dinas
                        </label>
                        <select 
                            wire:model="tingkat_biaya"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        >
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Alat Transportasi
                        </label>
                        <input 
                            type="text" 
                            wire:model="alat_transportasi"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        >
                    </div>
                </div>

                <!-- Tempat Berangkat & Tujuan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Tempat Berangkat
                        </label>
                        <input 
                            type="text" 
                            wire:model="tempat_berangkat"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Tempat Tujuan
                        </label>
                        <input 
                            type="text" 
                            wire:model="tempat_tujuan"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        >
                    </div>
                </div>

                <!-- Tanggal & Lama Perjalanan -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Tanggal Berangkat
                        </label>
                        <input 
                            type="date" 
                            wire:model="tanggal_berangkat"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Tanggal Kembali
                        </label>
                        <input 
                            type="date" 
                            wire:model="tanggal_kembali"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Lama Perjalanan (Hari)
                        </label>
                        <input 
                            type="number" 
                            wire:model="lama_perjalanan_hari"
                            min="1"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        >
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Keterangan
                    </label>
                    <textarea 
                        wire:model="keterangan"
                        rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        placeholder="Keterangan tambahan (opsional)"
                    ></textarea>
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
                    class="px-8 py-3 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg transition-colors font-medium"
                >
                    Simpan SPD
                </button>
            </div>
        </form>
    </div>
</div>
