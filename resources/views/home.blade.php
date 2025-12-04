@extends('layouts.app')

@section('title', 'Domov – Horské chaty')

@section('content')
    <div class="grid gap-8 md:grid-cols-2 items-center">
        <div>
            <p class="text-xs uppercase tracking-wide text-emerald-600 font-semibold mb-2">
                CabinConnect
            </p>
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                Rezervačný systém pre horské chaty
            </h1>
            <p class="mb-4 text-slate-700 dark:text-slate-200">
                Nájdite si ideálne ubytovanie v horách, pozrite si dostupnosť a vytvorte si rezerváciu na pár kliknutí.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ url('/chaty') }}"
                   class="inline-block px-5 py-2 rounded bg-emerald-500 text-white font-medium hover:bg-emerald-600">
                    Pozrieť ponuku chát
                </a>
                <a href="{{ url('/o-nas') }}"
                   class="inline-block px-5 py-2 rounded border border-slate-300 text-slate-700 dark:border-slate-600 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800">
                    Viac o projekte
                </a>
            </div>
        </div>

        <div class="hidden md:block">
            <div class="rounded-2xl border border-dashed border-emerald-400/70 p-6 text-sm text-slate-600 dark:text-slate-200">
                Tu môže byť neskôr mapa horských oblastí, náhodne vybrané chaty alebo prehľad najlepšie hodnotených pobytov.
            </div>
        </div>
    </div>
@endsection
