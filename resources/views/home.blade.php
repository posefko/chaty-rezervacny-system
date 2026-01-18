@extends('layouts.app')

@section('title', 'CabinConnect')

@section('content')
    <div class="grid gap-8 md:grid-cols-2 items-center">
        <div>
            <p class="text-sm uppercase md:text-base tracking-wide text-emerald-600 font-semibold mb-2">
                CabinConnect
            </p>
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                Rezervačný systém pre horské chaty
            </h1>
            <p class="mb-4 hero-subtitle">
                Nájdite si ideálne ubytovanie v horách, pozrite si dostupnosť a vytvorte si rezerváciu na pár kliknutí.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ url('/chaty') }}" class="btn-primary">
                    Pozrieť ponuku chát
                </a>
                <a href="{{ url('/o-nas') }}" class="btn-outline">
                    Viac o projekte
                </a>
            </div>
        </div>

        <div class="top-rated-box rounded-xl border p-5 theme-light:bg-white theme-dark:bg-slate-900 theme-light:border-slate-200 theme-dark:border-slate-700">
            <h2 class="text-lg font-semibold mb-4 theme-light:text-slate-900 theme-dark:text-slate-100">
                Najlepšie hodnotené
            </h2>

            @if(($topCottages ?? collect())->isEmpty())
                <p class="text-sm theme-light:text-slate-600 theme-dark:text-slate-300">
                    Zatiaľ nie sú dostupné žiadne hodnotenia.
                </p>
            @else
                <div class="space-y-3">
                    @foreach($topCottages as $cottage)
                        <a href="{{ route('cottages.show', $cottage) }}"
                           class="block rounded-lg border p-3 transition
                          theme-light:border-slate-200 theme-dark:border-slate-700
                          theme-light:bg-slate-50 theme-dark:bg-slate-800
                          theme-light:hover:bg-slate-100 theme-dark:hover:bg-slate-700">

                            <div class="font-medium theme-light:text-slate-900 theme-dark:text-slate-100">
                                {{ $cottage->name }}
                            </div>

                            <div class="flex justify-between text-sm theme-light:text-slate-600 theme-dark:text-slate-300">
                                <span>{{ $cottage->location }}</span>
                                <span class="theme-light:text-slate-900 theme-dark:text-slate-200 font-semibold">
                            {{ number_format($cottage->reviews_avg_rating ?? 0, 1) }} ★
                            <span class="font-normal theme-light:text-slate-500 theme-dark:text-slate-400">
                                ({{ $cottage->reviews_count }})
                            </span>
                        </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
@endsection
