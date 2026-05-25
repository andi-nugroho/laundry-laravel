<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ Auth::user()->isUser() ? __('Data Saya') : __('Data Pelanggan') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
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

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700 ring-1 ring-green-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($customers as $customer)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                        @if ($customer->address)
                                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ $customer->address }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $customer->phone ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if ($customer->user)
                                            <div class="text-gray-900">{{ $customer->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $customer->user->email }}</div>
                                        @else
                                            <span class="text-gray-400">Tidak terhubung</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($customer->gender)
                                            <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 capitalize">
                                                {{ $customer->gender }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        @can('view', $customer)
                                            <a href="{{ route('customers.show', $customer) }}" class="text-gray-600 hover:text-gray-900">Detail</a>
                                        @endcan

                                        @can('update', $customer)
                                            <a href="{{ route('customers.edit', $customer) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        @endcan

                                        @can('delete', $customer)
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                        Belum ada data pelanggan.
                                        @if (Auth::user()->isAdmin() || Auth::user()->isKasir())
                                            <a href="{{ route('customers.create') }}" class="text-indigo-600 hover:underline">Tambah pelanggan pertama</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($customers->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
