@extends('layouts.app')

@section('title', 'Rezervácie')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Rezervácie</h1>
        <a href="{{ route('reservations.create') }}" class="btn-primary">Vytvoriť</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded border border-emerald-200 bg-emerald-50 px-4 py-2 text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded border border-slate-200/70 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50">
            <tr>
                <th class="text-left px-4 py-3">Chata</th>
                <th class="text-left px-4 py-3">Používateľ</th>
                <th class="text-left px-4 py-3">Od</th>
                <th class="text-left px-4 py-3">Do</th>
                <th class="text-left px-4 py-3">Hostia</th>
                <th class="text-left px-4 py-3">Stav</th>
                <th class="text-right px-4 py-3">Akcie</th>
            </tr>
            </thead>
            <tbody>
            @forelse($reservations as $r)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $r->cottage?->name }}</td>
                    <td class="px-4 py-3">{{ $r->user?->name }}</td>
                    <td class="px-4 py-3">{{ $r->date_from }}</td>
                    <td class="px-4 py-3">{{ $r->date_to }}</td>
                    <td class="px-4 py-3">{{ $r->guests }}</td>
                    <td class="px-4 py-3">{{ $r->status }}</td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('reservations.edit', $r) }}" class="btn-outline text-xs">Upraviť</a>
                        <form class="inline" method="POST" action="{{ route('reservations.destroy', $r) }}"
                              onsubmit="return confirm('Naozaj zmazať rezerváciu?')">
                            @csrf
                            @method('DELETE')
                            <button class="inline-block text-xs px-3 py-1 rounded border border-red-300 text-red-700 hover:bg-red-50">
                                Zmazať
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-4 py-6 text-slate-500" colspan="7">Zatiaľ žiadne rezervácie.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $reservations->links() }}
    </div>
@endsection
