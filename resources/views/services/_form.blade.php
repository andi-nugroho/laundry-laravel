@php
    $service = $service ?? null;
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="name" value="Nama Layanan" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
            :value="old('name', $service?->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="description" value="Deskripsi" />
        <textarea id="description" name="description" rows="3"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            placeholder="Opsional">{{ old('description', $service?->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="price_per_kg" value="Harga per Kg (Rp)" />
            <x-text-input id="price_per_kg" name="price_per_kg" type="number" step="0.01" min="0"
                class="mt-1 block w-full" :value="old('price_per_kg', $service?->price_per_kg)" required />
            <x-input-error class="mt-2" :messages="$errors->get('price_per_kg')" />
        </div>

        <div>
            <x-input-label for="estimated_days" value="Estimasi Selesai (hari)" />
            <x-text-input id="estimated_days" name="estimated_days" type="number" min="1"
                class="mt-1 block w-full" :value="old('estimated_days', $service?->estimated_days ?? 2)" required />
            <x-input-error class="mt-2" :messages="$errors->get('estimated_days')" />
        </div>
    </div>

    <div class="flex items-center gap-3">
        <input type="hidden" name="is_active" value="0" />
        <input id="is_active" name="is_active" type="checkbox" value="1"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
            @checked(old('is_active', $service?->is_active ?? true)) />
        <x-input-label for="is_active" value="Layanan aktif" class="!mb-0" />
        <x-input-error class="mt-2" :messages="$errors->get('is_active')" />
    </div>
</div>
