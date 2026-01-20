<div class="min-h-screen bg-gray-50">
    <!-- Header -->
  

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Buat Surat Tugas</h1>
            <p class="text-gray-600 mt-1">Isi formulir di bawah untuk membuat surat tugas perjalanan dinas</p>
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
            <!-- Informasi Surat -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Surat</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nomor Surat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Nomor Surat <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="nomor_surat"
                            placeholder="800.1.11.1/ST/"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('nomor_surat') border-red-500 @enderror"
                        >
                        @error('nomor_surat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                                placeholder="Pilih Kode Rekening ..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('kode_rekening') border-red-500 @enderror"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('kode_rekening')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Dasar -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Dasar
                    </label>
                    <textarea 
                        wire:model="dasar_hukum"
                        rows="4"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('dasar_hukum') border-red-500 @enderror"
                        placeholder="Masukkan dasar hukum surat tugas..."
                    ></textarea>
                    @error('dasar_hukum')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Maksud Perjalanan Dinas -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Maksud Perjalanan Dinas <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        wire:model="maksud"
                        rows="4"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('maksud') border-red-500 @enderror"
                        placeholder="Masukkan maksud perjalanan dinas..."
                    ></textarea>
                    @error('maksud')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempat Tujuan -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Tempat Tujuan <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model="tempat_tujuan"
                        placeholder="Masukkan tempat tujuan..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('tempat_tujuan') border-red-500 @enderror"
                    >
                    @error('tempat_tujuan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Mulai & Selesai -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            wire:model="tanggal_mulai"
                            placeholder="dd/bb/tttt"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('tanggal_mulai') border-red-500 @enderror"
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
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent @error('tanggal_selesai') border-red-500 @enderror"
                        >
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Data Pegawai -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Data Pegawai</h2>
                    <button 
                        type="button"
                        wire:click="addPeserta"
                        class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Pegawai
                    </button>
                </div>

                @if (count($peserta) === 0)
                    <div class="text-center py-8 text-gray-500">
                        <p>Belum ada pegawai ditambahkan.</p>
                        <p class="text-sm mt-1">Klik "Tambah Pegawai" untuk menambahkan pegawai.</p>
                    </div>
                @else
                    @foreach($peserta as $index => $participant)
                        <div class="bg-gray-50 rounded-lg p-6 mb-4 border border-gray-200" wire:key="peserta-{{ $index }}">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-medium text-gray-900">Pegawai #{{ $index + 1 }}</h3>
                                @if(count($peserta) > 1)
                                    <button 
                                        type="button"
                                        wire:click="removePeserta({{ $index }})"
                                        class="text-red-600 hover:text-red-700 transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            <!-- Nama Pegawai with Dropdown -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-900 mb-2">
                                    Nama Pegawai <span class="text-red-500">*</span>
                                </label>
                                <div class="relative" x-data="{ open: @entangle('showDropdown.' . $index) }">
                                    <input 
                                        type="text" 
                                        wire:model.live="peserta.{{ $index }}.nama"
                                        @click="open = !open"
                                        placeholder="Pilih atau ketik nama pegawai..."
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent cursor-pointer"
                                        readonly
                                    >
                                    
                                    <!-- Dropdown -->
                                    <div 
                                        x-show="open"
                                        @click.away="open = false"
                                        class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                                        style="display: none;"
                                    >
                                        @forelse($availableUsers as $user)
                                            <div 
                                                wire:click="selectUser({{ $index }}, {{ $user['id'] }})"
                                                class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                            >
                                                <div class="font-medium text-gray-900">{{ $user['name'] }}</div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $user['nip'] ?? '-' }} | {{ $user['jabatan'] ?? '-' }}
                                                </div>
                                            </div>
                                        @empty
                                            <div class="px-4 py-3 text-gray-500 text-center">
                                                Tidak ada pegawai ditemukan
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                @error('peserta.' . $index . '.nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pangkat/Golongan & NIP -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-900 mb-2">
                                        Pangkat/Golongan
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="peserta.{{ $index }}.pangkat"
                                        placeholder="Auto-filled"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                        readonly
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-900 mb-2">
                                        NIP
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="peserta.{{ $index }}.nip"
                                        placeholder="Auto-filled"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                        readonly
                                    >
                                </div>
                            </div>

                            <!-- Jabatan -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-900 mb-2">
                                    Jabatan
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="peserta.{{ $index }}.jabatan"
                                    placeholder="Auto-filled"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                    readonly
                                >
                            </div>

                            <!-- Peran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">
                                    Peran
                                </label>
                                <select 
                                    wire:model="peserta.{{ $index }}.peran"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                                >
                                    <option value="ketua">Ketua</option>
                                    <option value="anggota">Anggota</option>
                                    <option value="pendamping">Pendamping</option>
                                </select>
                            </div>
                        </div>
                    @endforeach
                @endif
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
                    Preview surat
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Alpine.js is included with Livewire by default
</script>
@endpush
