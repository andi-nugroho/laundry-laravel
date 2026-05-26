@can('updateStatus', $booking)
    <form method="POST" action="{{ route('bookings.update-status', $booking) }}" class="{{ $class ?? 'flex items-center justify-end gap-2' }}">
        @csrf
        @method('PATCH')
        <select name="status"
            class="block w-full min-w-36 rounded-xl border-gray-300 text-xs font-semibold shadow-sm focus:border-[#FF6626] focus:ring-[#FF6626] sm:w-40">
            @foreach (\App\Models\Booking::STATUSES as $status)
                <option value="{{ $status }}" @selected($booking->status === $status)>
                    {{ str_replace('_', ' ', ucfirst($status)) }}
                </option>
            @endforeach
        </select>
        <x-secondary-button type="submit" class="!px-3 !py-2 !text-xs">Update</x-secondary-button>
    </form>
@endcan
