<form wire:submit.prevent="save" 
      x-data="{ uploading: false, progress: 0 }"
      x-on:livewire-upload-start="uploading = true"
      x-on:livewire-upload-finish="uploading = false"
      x-on:livewire-upload-error="uploading = false"
      x-on:livewire-upload-progress="progress = $event.detail.progress">
    
    <div class="mb-4">
        <div class="border-2 border-dashed border-unmaris-blue bg-blue-50 rounded-lg p-6 text-center hover:bg-white transition cursor-pointer relative group overflow-hidden">
            
            <!-- PROGRESS BAR OVERLAY -->
            <div x-show="uploading" class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-white/95 backdrop-blur transition-all" style="display: none;">
                <div class="text-4xl mb-2 animate-bounce">🚀</div>
                <div class="w-3/4 bg-gray-200 rounded-full h-4 mb-2 overflow-hidden border border-gray-300">
                    <div class="bg-unmaris-blue h-full rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                </div>
                <span class="text-sm font-black text-unmaris-blue" x-text="'Mengunggah... ' + progress + '%'"></span>
            </div>

            <div class="text-center w-full">
                @if ($bukti_transfer)
                    <!-- Preview Container -->
                    <div class="relative inline-block group/preview" x-init="uploading = false; progress = 0">
                        <!-- Image Preview -->
                        <img src="{{ $bukti_transfer->temporaryUrl() }}" class="h-40 mx-auto rounded shadow-sm border-2 border-black object-contain bg-gray-200">

                        <!-- Remove Button -->
                        <button type="button" wire:click="$set('bukti_transfer', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 shadow-md border-2 border-white hover:bg-red-600 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-green-600 font-black uppercase tracking-wide flex items-center justify-center gap-1">
                        <span>📸</span> Foto Siap Kirim
                    </p>
                @else
                    <!-- Upload Placeholder -->
                    <div class="flex justify-center mb-2">
                        <span class="text-4xl group-hover:scale-110 transition-transform block grayscale group-hover:grayscale-0">🖼️</span>
                    </div>
                    <div class="flex flex-col items-center justify-center">
                        <label for="file-upload" class="relative cursor-pointer rounded-md font-black text-unmaris-blue hover:underline">
                            <span>Klik Upload Foto</span>
                            <input id="file-upload" wire:model="bukti_transfer" type="file" class="sr-only" accept="image/png, image/jpeg, image/jpg">
                        </label>
                        <p class="text-xs font-bold text-gray-400 mt-1">JPG/PNG (Max 2MB)</p>
                    </div>
                @endif
            </div>
        </div>
        @error('bukti_transfer') 
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-2 mt-2 text-xs font-bold animate-pulse">
                ⚠️ {{ $message }}
            </div>
        @enderror
    </div>

    @if($bukti_transfer)
    <button type="submit" 
            :disabled="uploading"
            wire:loading.attr="disabled" 
            wire:target="save" 
            class="w-full bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-3 px-6 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover hover:-translate-y-0.5 transition-all uppercase tracking-wider flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none disabled:translate-y-0">
        <span x-show="!uploading" wire:loading.remove wire:target="save">🚀 KIRIM BUKTI BAYAR</span>
        <span x-show="uploading">MENUNGGU UPLOAD...</span>
        <span wire:loading wire:target="save" style="display:none;">⏳ MENYIMPAN DATA...</span>
    </button>
    @endif
</form>