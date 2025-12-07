@extends('layouts.app')

@section('title', 'Upraviť chatu')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Upraviť chatu</h1>
        <a href="{{ route('cottages.index') }}" class="text-sm underline">Späť na zoznam</a>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded border border-red-500 bg-red-50 text-red-700 px-4 py-2">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="cottage-form"
          action="{{ route('cottages.update', $cottage) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-4 max-w-lg">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1" for="name">Názov chaty</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name', $cottage->name) }}"
                   class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
            <p class="text-xs text-red-600 mt-1" data-error-for="name"></p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="location">Lokalita</label>
            <input type="text" id="location" name="location"
                   value="{{ old('location', $cottage->location) }}"
                   class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
            <p class="text-xs text-red-600 mt-1" data-error-for="location"></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="capacity">Kapacita</label>
                <input type="number" id="capacity" name="capacity"
                       value="{{ old('capacity', $cottage->capacity) }}"
                       class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                <p class="text-xs text-red-600 mt-1" data-error-for="capacity"></p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="price_per_night">Cena za noc (€)</label>
                <input type="number" id="price_per_night" name="price_per_night"
                       value="{{ old('price_per_night', $cottage->price_per_night) }}"
                       class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                <p class="text-xs text-red-600 mt-1" data-error-for="price_per_night"></p>
            </div>
        </div>

        <div>
            <span class="block text-sm font-medium mb-1">Typ ubytovania</span>
            @php
                $type = old('is_whole_chalet', $cottage->is_whole_chalet ? '1' : '0');
            @endphp

            <label class="inline-flex items-center gap-2 text-sm mr-4">
                <input type="radio" name="is_whole_chalet" value="1" {{ $type == '1' ? 'checked' : '' }}>
                Celá chata
            </label>
            <label class="inline-flex items-center gap-2 text-sm">
                <input type="radio" name="is_whole_chalet" value="0" {{ $type == '0' ? 'checked' : '' }}>
                Len lôžka
            </label>
            <p class="text-xs text-red-600 mt-1" data-error-for="is_whole_chalet"></p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="image">Hlavná fotografia chaty</label>
            <input
                type="file"
                id="image"
                name="image"
                accept="image/*"
                class="w-full rounded border border-slate-300 px-3 py-2 text-sm bg-white"
            >
            <p class="text-xs text-red-600 mt-1" data-error-for="image"></p>

            @if(!empty($cottage->image_path))
                <div class="mt-2">
                    <p class="text-xs text-slate-500 mb-1">Aktuálna fotka:</p>
                    <img
                        src="{{ asset('storage/' . $cottage->image_path) }}"
                        alt="Fotografia chaty"
                        class="h-20 w-32 object-cover rounded border border-slate-200"
                    >
                </div>
            @endif
        </div>


        <div>
            <label class="block text-sm font-medium mb-1" for="description">Popis</label>
            <textarea id="description" name="description" rows="4"
                      class="w-full rounded border border-slate-300 px-3 py-2 text-sm">{{ old('description', $cottage->description) }}</textarea>
            <p class="text-xs text-red-600 mt-1" data-error-for="description"></p>
        </div>

        <button type="submit" class="btn-primary bg-emerald-500 hover:bg-emerald-600 text-white">
            Uložiť zmeny
        </button>
    </form>

    {{-- rovnaká JS validácia ako v create --}}
    <script>
        (function () {
            const form = document.getElementById('cottage-form');
            if (!form) return;

            const showError = (field, message) => {
                const el = form.querySelector('[data-error-for="' + field + '"]');
                if (el) el.textContent = message || '';
            };

            const clearErrors = () => {
                ['name','location','capacity','price_per_night','is_whole_chalet','description','image']
                    .forEach(f => showError(f, ''));
            };

            form.addEventListener('submit', function (e) {
                clearErrors();
                let valid = true;

                const name = form.name.value.trim();
                const location = form.location.value.trim();
                const capacity = parseInt(form.capacity.value, 10);
                const price = parseInt(form.price_per_night.value, 10);
                const typeChecked = form.querySelector('input[name="is_whole_chalet"]:checked');
                const description = form.description.value.trim();

                if (name.length < 3) { showError('name', 'Názov musí mať aspoň 3 znaky.'); valid = false; }
                if (location.length < 3) { showError('location', 'Lokalita musí mať aspoň 3 znaky.'); valid = false; }
                if (isNaN(capacity) || capacity < 1) { showError('capacity', 'Kapacita musí byť číslo aspoň 1.'); valid = false; }
                if (isNaN(price) || price < 1) { showError('price_per_night', 'Cena musí byť kladné číslo.'); valid = false; }
                if (!typeChecked) { showError('is_whole_chalet', 'Vyber typ ubytovania.'); valid = false; }
                if (description.length > 2000) { showError('description', 'Popis môže mať max 2000 znakov.'); valid = false; }

                if (!valid) e.preventDefault();
            });
        })();
    </script>
@endsection
