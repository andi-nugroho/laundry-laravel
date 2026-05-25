<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Layanan Laundry') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form method="POST" action="{{ route('services.store') }}" class="space-y-6">
                    @csrf
                    @include('services._form')

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('services.index') }}">
                            <x-secondary-button type="button">Batal</x-secondary-button>
                        </a>
                        <x-primary-button>Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
