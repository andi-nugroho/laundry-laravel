<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-black leading-tight text-neutral-900">
                    {{ Auth::user()->isUser() ? __('Data Saya') : __('Data Pelanggan') }}
                </h2>
                <p class="mt-1 text-sm font-medium text-neutral-500">
                    {{ Auth::user()->isUser() ? 'Kelola profil pelanggan Anda' : 'Kelola data pelanggan laundry' }}
                </p>
            </div>

            @if (Auth::user()->isAdmin() || Auth::user()->isKasir())
                <a href="{{ route('customers.create') }}">
                    <x-primary-button type="button">
                        + Tambah Pelanggan
                    </x-primary-button>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <x-list-panel storage-key="vaultCustomersView" title="{{ Auth::user()->isUser() ? 'Profil Pelanggan' : 'Daftar Pelanggan' }}" description="Gunakan table untuk data lengkap atau card untuk tampilan ringkas.">
                <x-slot name="table">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="sticky top-0 z-10 bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left">Nama</th>
                                    <th scope="col" class="px-6 py-4 text-left">Kontak</th>
                                    <th scope="col" class="px-6 py-4 text-left">User</th>
                                    <th scope="col" class="px-6 py-4 text-left">Gender</th>
                                    <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($customers as $customer)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-black text-neutral-900">{{ $customer->name }}</div>
                                            @if ($customer->address)
                                                <div class="mt-1 max-w-xs truncate text-sm font-medium text-neutral-500">{{ $customer->address }}</div>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-neutral-600">
                                            {{ $customer->phone ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-neutral-600">
                                            @if ($customer->user)
                                                <div class="font-bold text-neutral-900">{{ $customer->user->name }}</div>
                                                <div class="text-xs text-neutral-500">{{ $customer->user->email }}</div>
                                            @else
                                                <span class="text-neutral-400">Tidak terhubung</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @if ($customer->gender)
                                                <span class="inline-flex rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-black capitalize text-neutral-700">
                                                    {{ $customer->gender }}
                                                </span>
                                            @else
                                                <span class="text-sm text-neutral-400">-</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                @can('view', $customer)
                                                    <a href="{{ route('customers.show', $customer) }}" class="vault-action-secondary">Detail</a>
                                                @endcan

                                                @can('update', $customer)
                                                    <a href="{{ route('customers.edit', $customer) }}" class="vault-action-primary">Edit</a>
                                                @endcan

                                                @can('delete', $customer)
                                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="vault-action-danger">Hapus</button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-neutral-500">
                                            Belum ada data pelanggan.
                                            @if (Auth::user()->isAdmin() || Auth::user()->isKasir())
                                                <a href="{{ route('customers.create') }}" class="font-black text-[#FF6626] hover:underline">Tambah pelanggan pertama</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot>

                <x-slot name="cards">
                    <div class="grid gap-4 p-4 lg:grid-cols-2">
                        @forelse ($customers as $customer)
                            <article class="vault-record-card">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-base font-black text-neutral-950">{{ $customer->name }}</h3>
                                        <p class="mt-1 line-clamp-2 text-sm font-medium text-neutral-500">{{ $customer->address ?: 'Alamat belum diisi.' }}</p>
                                    </div>
                                    @if ($customer->gender)
                                        <span class="shrink-0 rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-black capitalize text-neutral-700">{{ $customer->gender }}</span>
                                    @endif
                                </div>

                                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                    <x-card-field label="Telepon" :value="$customer->phone ?? '-'" />
                                    <x-card-field label="User">
                                        @if ($customer->user)
                                            <span class="block">{{ $customer->user->name }}</span>
                                            <span class="block text-xs font-medium text-neutral-500">{{ $customer->user->email }}</span>
                                        @else
                                            <span class="text-neutral-400">Tidak terhubung</span>
                                        @endif
                                    </x-card-field>
                                </div>

                                <div class="mt-5 flex flex-wrap gap-2">
                                    @can('view', $customer)
                                        <a href="{{ route('customers.show', $customer) }}" class="vault-action-secondary">Detail</a>
                                    @endcan

                                    @can('update', $customer)
                                        <a href="{{ route('customers.edit', $customer) }}" class="vault-action-primary">Edit</a>
                                    @endcan

                                    @can('delete', $customer)
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="vault-action-danger">Hapus</button>
                                        </form>
                                    @endcan
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-dashed border-[#E8DCCB] p-8 text-center text-sm font-medium text-neutral-500 lg:col-span-2">
                                Belum ada data pelanggan.
                                @if (Auth::user()->isAdmin() || Auth::user()->isKasir())
                                    <a href="{{ route('customers.create') }}" class="font-black text-[#FF6626] hover:underline">Tambah pelanggan pertama</a>
                                @endif
                            </div>
                        @endforelse
                    </div>
                </x-slot>

                @if ($customers->hasPages())
                    <x-slot name="footer">
                        {{ $customers->links() }}
                    </x-slot>
                @endif
            </x-list-panel>
        </div>
    </div>
</x-app-layout>
