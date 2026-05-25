<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Booking Laundry') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form method="POST" action="{{ route('bookings.update', $booking) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    @include('bookings._form', ['booking' => $booking, 'customers' => $customers, 'services' => $services])

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('bookings.show', $booking) }}">
                            <x-secondary-button type="button">Batal</x-secondary-button>
                        </a>
                        <x-primary-button>Perbarui</x-primary-button>
                    </div>
                </form>
            </div>

            @can('updateStatus', $booking)
                <div class="mt-6 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-base font-semibold text-gray-900">Update Status Laundry</h3>
                    <div class="mt-4">
                        @include('bookings._status-form', ['booking' => $booking, 'class' => 'flex flex-col gap-3 sm:flex-row sm:items-center'])
                    </div>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>
