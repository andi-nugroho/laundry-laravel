@can('updateStatus', $booking)
    <form method="POST" action="{{ route('bookings.update-status', $booking) }}" class="{{ $class ?? 'flex items-center justify-end gap-2' }}">
        @csrf
        @method('PATCH')
        <select name="status"
            class="block w-full min-w-40 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
            @foreach (\App\Models\Booking::STATUSES as $status)
                <option value="{{ $status }}" @selected($booking->status === $status)>
                    {{ str_replace('_', ' ', ucfirst($status)) }}
                </option>
            @endforeach
        </select>
        <x-secondary-button type="submit">Update</x-secondary-button>
    </form>
@endcan
