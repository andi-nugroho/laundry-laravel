@php
    $flowStatuses = [
        'booking_masuk',
        'diterima',
        'dicuci',
        'dikeringkan',
        'disetrika',
        'selesai',
        'diambil',
    ];
    $currentIndex = array_search($booking->status, $flowStatuses, true);
    $isCanceled = $booking->status === 'dibatalkan';
@endphp

<div class="bg-white shadow sm:rounded-lg">
    <div class="p-4 sm:p-8">
        <h3 class="text-base font-semibold text-gray-900">Monitoring Status</h3>
        <div class="mt-6 overflow-x-auto">
            <ol class="flex min-w-max items-start">
                @foreach ($flowStatuses as $index => $status)
                    @php
                        $isDone = ! $isCanceled && $currentIndex !== false && $index <= $currentIndex;
                    @endphp
                    <li class="flex items-start {{ $loop->last ? '' : 'w-40' }}">
                        <div class="flex flex-col items-center">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold {{ $isDone ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-500' }}">
                                {{ $index + 1 }}
                            </span>
                            <span class="mt-2 w-28 text-center text-xs font-medium {{ $isDone ? 'text-gray-900' : 'text-gray-500' }}">
                                {{ str_replace('_', ' ', ucfirst($status)) }}
                            </span>
                        </div>
                        @if (! $loop->last)
                            <div class="mt-4 h-0.5 flex-1 {{ $isDone && $index < $currentIndex ? 'bg-indigo-600' : 'bg-gray-200' }}"></div>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>

        @if ($isCanceled)
            <div class="mt-6 rounded-md bg-red-50 p-4 text-sm text-red-700 ring-1 ring-red-100">
                Booking ini dibatalkan.
            </div>
        @endif
    </div>
</div>
