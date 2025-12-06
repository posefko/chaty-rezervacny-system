@extends('layouts.app')

@section('title', 'Chaty')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Zoznam chát</h1>

        <a href="{{ route('cottages.create') }}"
           class="btn-primary bg-emerald-500 hover:bg-emerald-600 text-white">
            Pridať chatu
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded border border-emerald-500 bg-emerald-50 text-emerald-700 px-4 py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded border border-slate-200/70 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50">
            <tr>
                <th class="text-left px-4 py-3">Názov</th>
                <th class="text-left px-4 py-3">Lokalita</th>
                <th class="text-left px-4 py-3">Kapacita</th>
                <th class="text-left px-4 py-3">Cena / noc</th>
                <th class="text-left px-4 py-3">Typ</th>
                <th class="text-right px-4 py-3">Akcie</th>
            </tr>
            </thead>
            <tbody>
            @forelse($cottages as $c)
                <tr class="border-t">
                    <td class="px-4 py-3 font-medium">{{ $c->name }}</td>
                    <td class="px-4 py-3">{{ $c->location }}</td>
                    <td class="px-4 py-3">{{ $c->capacity }}</td>
                    <td class="px-4 py-3">{{ $c->price_per_night }} €</td>
                    <td class="px-4 py-3">
                        {{ $c->is_whole_chalet ? 'Celá chata' : 'Len lôžka' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('cottages.edit', $c) }}"
                           class="inline-block text-xs px-3 py-1 rounded border border-slate-300 hover:bg-slate-100">
                            Upraviť
                        </a>

                        <form action="{{ route('cottages.destroy', $c) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Naozaj chceš odstrániť túto chatu?');">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="inline-block text-xs px-3 py-1 rounded border border-red-300 text-red-700 hover:bg-red-50">
                                Zmazať
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-4 py-6 text-slate-500" colspan="5">
                        Zatiaľ nie sú pridané žiadne chaty.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
