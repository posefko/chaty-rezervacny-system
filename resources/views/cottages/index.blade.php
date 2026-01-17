@extends('layouts.app')

@section('title', 'Chaty')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Zoznam chát</h1>

        @auth
            @if(auth()->user()->is_admin)
                <a href="{{ route('cottages.create') }}"
                   class="btn-primary bg-emerald-500 hover:bg-emerald-600 text-white">
                    Pridať chatu
                </a>
            @endif
        @endauth
    </div>

    @if(session('success'))
        <div class="mb-4 rounded border border-emerald-500 bg-emerald-50 text-emerald-700 px-4 py-2">
            {{ session('success') }}
        </div>
    @endif

    {{-- AJAX FILTER FORM --}}
    <form id="cottage-filters" class="grid sm:grid-cols-3 gap-3 mb-4">
        <div>
            <label class="text-xs text-slate-500">Lokalita</label>
            <input name="location" type="text" placeholder="napr. Tatry"
                   class="mt-1 w-full border border-slate-200 rounded px-3 py-2 text-sm">
        </div>

        <div>
            <label class="text-xs text-slate-500">Min. kapacita</label>
            <input name="min_capacity" type="number" min="1" placeholder="napr. 4"
                   class="mt-1 w-full border border-slate-200 rounded px-3 py-2 text-sm">
        </div>

        <div>
            <label class="text-xs text-slate-500">Max. cena / noc</label>
            <input name="max_price" type="number" min="0" step="0.01" placeholder="napr. 90"
                   class="mt-1 w-full border border-slate-200 rounded px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-3 flex items-center justify-between">
            <p class="text-xs text-slate-500">Filtrovanie prebieha automaticky bez reloadu (AJAX).</p>
            <button type="reset"
                    class="inline-block text-xs px-3 py-1 rounded border border-slate-300 hover:bg-slate-100">
                Reset
            </button>
        </div>
    </form>

    {{-- sem sa bude AJAX-om prepisovať obsah --}}
    <div id="cottages-list">
        @include('cottages.partials.list', ['cottages' => $cottages])
    </div>
@endsection
