<x-guest-layout>
    <h4 class="pb-4 font-bold text-3xl font-mali">Tambah Tahun</h4>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="/tahun/add">
        @csrf

        <!-- Tahun -->
        <div class="mt-4">
            <x-input-label for="tahun" :value="__('Tahun')" />
            <x-text-input id="tahun" class="block mt-1 w-full" type="text" name="tahun" :value="old('tahun')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4 space-x-3">
            <!-- Log in button -->
            <x-primary-button>
                {{ __('Simpan') }}
            </x-primary-button>
        </div>

    </form>
</x-guest-layout>