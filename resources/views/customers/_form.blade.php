@php
    $customer = $customer ?? null;
    $currentUser = Auth::user();
@endphp

<div class="space-y-6">
    @if ($currentUser->isUser() && ! $customer)
        <input type="hidden" name="user_id" value="{{ $currentUser->id }}">
    @elseif (! $currentUser->isUser())
        <div>
            <x-input-label for="user_id" value="User Terhubung" />
            <select id="user_id" name="user_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Tidak terhubung ke user</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected((string) old('user_id', $customer?->user_id) === (string) $user->id)>
                        {{ $user->name }} - {{ $user->email }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
        </div>
    @endif

    <div>
        <x-input-label for="name" value="Nama Pelanggan" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
            :value="old('name', $customer?->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="phone" value="Nomor Telepon" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                :value="old('phone', $customer?->phone)" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="gender" value="Gender" />
            <select id="gender" name="gender"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Pilih gender</option>
                <option value="male" @selected(old('gender', $customer?->gender) === 'male')>Male</option>
                <option value="female" @selected(old('gender', $customer?->gender) === 'female')>Female</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>
    </div>

    <div>
        <x-input-label for="address" value="Alamat" />
        <textarea id="address" name="address" rows="3"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            placeholder="Opsional">{{ old('address', $customer?->address) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('address')" />
    </div>

    <div>
        <x-input-label for="notes" value="Catatan" />
        <textarea id="notes" name="notes" rows="3"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            placeholder="Opsional">{{ old('notes', $customer?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>
</div>
