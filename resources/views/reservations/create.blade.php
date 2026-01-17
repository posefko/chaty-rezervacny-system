@extends('layouts.app')

@section('title', 'Vytvoriť rezerváciu')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Vytvoriť rezerváciu</h1>

    <form method="POST" action="{{ route('reservations.store') }}" class="space-y-4 max-w-xl">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Chata</label>
            <select name="cottage_id" class="w-full rounded border px-3 py-2">
                @foreach($cottages as $c)
                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->location }})</option>
                @endforeach
            </select>
            @error('cottage_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Od</label>
                <input type="date" name="date_from" class="w-full rounded border px-3 py-2" value="{{ old('date_from') }}">
                @error('date_from') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Do</label>
                <input type="date" name="date_to" class="w-full rounded border px-3 py-2" value="{{ old('date_to') }}">
                @error('date_to') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div id="availability-box" class="hidden mt-2 text-sm px-3 py-2 rounded border"></div>

        <div>
            <label class="block text-sm font-medium mb-1">Počet hostí</label>
            <input type="number" name="guests" min="1" max="50" class="w-full rounded border px-3 py-2" value="{{ old('guests', 1) }}">
            @error('guests') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Poznámka</label>
            <textarea name="note" rows="3" class="w-full rounded border px-3 py-2">{{ old('note') }}</textarea>
            @error('note') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2">
            <button class="btn-primary" type="submit">Uložiť</button>
            <a class="btn-outline" href="{{ route('reservations.index') }}">Späť</a>
        </div>
    </form>
@endsection
