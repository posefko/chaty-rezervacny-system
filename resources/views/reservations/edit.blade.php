@extends('layouts.app')

@section('title', 'Upraviť rezerváciu')

@section('content')
    <div class="max-w-xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Upraviť rezerváciu</h1>
            <a href="{{ route('reservations.index') }}" class="btn-outline text-sm">Späť</a>
        </div>

        @if($errors->any())
            <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-2 text-red-800 text-sm">
                Skontroluj formulár – niektoré polia sú neplatné.
            </div>
        @endif

        <form method="POST" action="{{ route('reservations.update', $reservation) }}" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Chata --}}
            <div>
                <label class="block text-sm font-medium mb-1">Chata</label>
                <select name="cottage_id" class="w-full rounded border px-3 py-2">
                    @foreach($cottages as $c)
                        <option value="{{ $c->id }}"
                            @selected(old('cottage_id', $reservation->cottage_id) == $c->id)>
                            {{ $c->name }} ({{ $c->location }})
                        </option>
                    @endforeach
                </select>
                @error('cottage_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Termín --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Od</label>
                    <input type="date" name="date_from"
                           value="{{ old('date_from', $reservation->date_from) }}"
                           class="w-full rounded border px-3 py-2">
                    @error('date_from')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Do</label>
                    <input type="date" name="date_to"
                           value="{{ old('date_to', $reservation->date_to) }}"
                           class="w-full rounded border px-3 py-2">
                    @error('date_to')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Počet hostí --}}
            <div>
                <label class="block text-sm font-medium mb-1">Počet hostí</label>
                <input type="number" name="guests" min="1" max="50"
                       value="{{ old('guests', $reservation->guests) }}"
                       class="w-full rounded border px-3 py-2">
                @error('guests')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Stav --}}
            @auth
                @if(auth()->user()->is_admin)
                    <div>
                        <label class="block text-sm font-medium mb-1">Stav</label>
                        <select name="status" class="w-full rounded border px-3 py-2">
                            @php $st = old('status', $reservation->status); @endphp
                            <option value="pending"   @selected($st === 'pending')>pending</option>
                            <option value="confirmed" @selected($st === 'confirmed')>confirmed</option>
                            <option value="cancelled" @selected($st === 'cancelled')>cancelled</option>
                        </select>
                        @error('status')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            @endauth

            {{-- Poznámka --}}
            <div>
                <label class="block text-sm font-medium mb-1">Poznámka</label>
                <textarea name="note" rows="3" class="w-full rounded border px-3 py-2">{{ old('note', $reservation->note) }}</textarea>
                @error('note')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2 pt-2">
                <button type="submit" class="btn-primary">Uložiť zmeny</button>
                <a href="{{ route('reservations.index') }}" class="btn-outline">Zrušiť</a>
            </div>
        </form>
    </div>
@endsection
