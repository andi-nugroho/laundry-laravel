<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-black leading-tight text-neutral-900">
                    {{ __('Layanan Laundry') }}
                </h2>
                <p class="mt-1 text-sm font-medium text-neutral-500">Kelola data master layanan laundry</p>
            </div>
            <a href="{{ route('services.create') }}">
                <x-primary-button type="button">
                    + Tambah Layanan
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <x-list-panel storage-key="vaultServicesView" title="Daftar Layanan" description="Pilih mode table untuk scan cepat atau card untuk tampilan vertikal.">
                <x-slot name="table">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="sticky top-0 z-10 bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left">Nama</th>
                                    <th scope="col" class="px-6 py-4 text-left">Harga/Kg</th>
                                    <th scope="col" class="px-6 py-4 text-left">Estimasi</th>
                                    <th scope="col" class="px-6 py-4 text-left">Status</th>
                                    <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($services as $service)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-black text-neutral-900">{{ $service->name }}</div>
                                            @if ($service->description)
                                                <div class="mt-1 max-w-xs truncate text-sm font-medium text-neutral-500">{{ $service->description }}</div>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-neutral-900">
                                            Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-neutral-600">
                                            {{ $service->estimated_days }} hari
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @if ($service->is_active)
                                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-black text-green-800">Aktif</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-black text-neutral-600">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('services.edit', $service) }}" class="vault-action-primary">Edit</a>
                                                <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus layanan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="vault-action-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-neutral-500">
                                            Belum ada layanan. <a href="{{ route('services.create') }}" class="font-black text-[#FF6626] hover:underline">Tambah layanan pertama</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot>

                <x-slot name="cards">
                    <div class="grid gap-4 p-4 sm:grid-cols-2 xl:grid-cols-3">
                        @forelse ($services as $service)
                            <article class="vault-record-card">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-base font-black text-neutral-950">{{ $service->name }}</h3>
                                        <p class="mt-1 line-clamp-2 text-sm font-medium text-neutral-500">{{ $service->description ?: 'Tidak ada deskripsi.' }}</p>
                                    </div>
                                    @if ($service->is_active)
                                        <span class="shrink-0 rounded-full bg-green-100 px-2.5 py-1 text-xs font-black text-green-800">Aktif</span>
                                    @else
                                        <span class="shrink-0 rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-black text-neutral-600">Nonaktif</span>
                                    @endif
                                </div>

                                <div class="mt-5 grid grid-cols-2 gap-4">
                                    <x-card-field label="Harga/Kg" :value="'Rp '.number_format($service->price_per_kg, 0, ',', '.')" />
                                    <x-card-field label="Estimasi" :value="$service->estimated_days.' hari'" />
                                </div>

                                <div class="mt-5 flex flex-wrap gap-2">
                                    <a href="{{ route('services.edit', $service) }}" class="vault-action-primary">Edit</a>
                                    <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus layanan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="vault-action-danger">Hapus</button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-dashed border-[#E8DCCB] p-8 text-center text-sm font-medium text-neutral-500 sm:col-span-2 xl:col-span-3">
                                Belum ada layanan. <a href="{{ route('services.create') }}" class="font-black text-[#FF6626] hover:underline">Tambah layanan pertama</a>
                            </div>
                        @endforelse
                    </div>
                </x-slot>

                @if ($services->hasPages())
                    <x-slot name="footer">
                        {{ $services->links() }}
                    </x-slot>
                @endif
            </x-list-panel>
        </div>
    </div>
</x-app-layout>
