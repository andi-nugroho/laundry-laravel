<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Pelanggan') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">{{ $customer->name }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('customers.index') }}">
                    <x-secondary-button type="button">Kembali</x-secondary-button>
                </a>

                @can('update', $customer)
                    <a href="{{ route('customers.edit', $customer) }}">
                        <x-primary-button type="button">Edit</x-primary-button>
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700 ring-1 ring-green-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $customer->name }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $customer->phone ?? '-' }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $customer->address ?? '-' }}</dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $customer->gender ? ucfirst($customer->gender) : '-' }}
                        </dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">User Terhubung</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @if ($customer->user)
                                {{ $customer->user->name }} <span class="text-gray-500">({{ $customer->user->email }})</span>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                        <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $customer->notes ?? '-' }}</dd>
                    </div>
                </dl>

                @can('delete', $customer)
                    <div class="border-t border-gray-100 px-4 py-5 sm:px-8">
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>Hapus Pelanggan</x-danger-button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
