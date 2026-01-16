@extends('layouts.app')

@section('title', $cottage->name . ' | CabinConnect')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">{{ $cottage->name }}</h1>
                <p class="text-slate-600 mt-1">{{ $cottage->location }}</p>
            </div>
            <a href="{{ route('cottages.index') }}" class="btn-outline text-sm">
                ← Späť na zoznam
            </a>
        </div>

        {{-- IMAGE + INFO --}}
        <div class="grid md:grid-cols-2 gap-6">
            <div class="rounded-xl overflow-hidden border border-slate-200 bg-white">
                @if($cottage->image_path)
                    <img src="{{ asset('storage/' . $cottage->image_path) }}"
                         alt="Fotka chaty"
                         class="w-full h-72 object-cover">
                @else
                    <div class="w-full h-72 flex items-center justify-center text-slate-400">
                        Bez fotografie
                    </div>
                @endif
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Kapacita</span>
                    <span class="font-medium">{{ $cottage->capacity }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Cena / noc</span>
                    <span class="font-medium">{{ number_format($cottage->price_per_night, 2, ',', ' ') }} €</span>
                </div>
                @if($cottage->description)
                    <div class="pt-3 border-t border-slate-100 text-sm text-slate-700">
                        {{ $cottage->description }}
                    </div>
                @endif
            </div>
        </div>

        {{-- REVIEWS --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Hodnotenia</h2>

                @php
                    $avg = $cottage->reviews->avg('rating');
                @endphp

                <div class="text-sm text-slate-600">
                    Priemer:
                    <span class="font-semibold">
                    {{ $avg ? number_format($avg, 1) : '—' }}
                </span>
                    / 5
                    <span class="text-slate-400">({{ $cottage->reviews->count() }})</span>
                </div>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-4 text-sm px-3 py-2 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form: add/update review --}}
            @auth
                <form method="POST" action="{{ route('reviews.store', $cottage) }}" class="mb-6 space-y-3">
                    @csrf

                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium">Hodnotenie (1–5)</label>
                            <select name="rating" class="mt-1 w-full border rounded px-3 py-2">
                                @for($i=5; $i>=1; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error('rating')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium">Komentár (voliteľné)</label>
                            <input type="text" name="comment" value="{{ old('comment') }}"
                                   class="mt-1 w-full border rounded px-3 py-2"
                                   placeholder="Napr. čisté, výborná poloha...">
                            @error('comment')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button class="btn-primary text-sm">Uložiť hodnotenie</button>
                </form>
            @else
                <div class="mb-6 text-sm text-slate-600">
                    Pre pridanie hodnotenia sa musíš <a href="{{ route('login') }}" class="text-emerald-600 underline">prihlásiť</a>.
                </div>
            @endauth

            {{-- List --}}
            <div class="space-y-4">
                @forelse($cottage->reviews()->latest()->get() as $review)
                    <div class="border border-slate-100 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm">
                                <span class="font-medium">{{ $review->user->name ?? 'Používateľ' }}</span>
                                <span class="text-slate-400">•</span>
                                <span class="text-slate-600">{{ $review->created_at->format('d.m.Y') }}</span>
                            </div>

                            <div class="text-sm font-semibold">
                                {{ $review->rating }} / 5
                            </div>
                        </div>

                        @if($review->comment)
                            <div class="mt-2 text-sm text-slate-700">
                                {{ $review->comment }}
                            </div>
                        @endif

                        @auth
                            @if($review->user_id === auth()->id() || auth()->user()->is_admin)
                                <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs px-3 py-1 rounded border border-red-200 text-red-700 hover:bg-red-50">
                                        Zmazať
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @empty
                    <div class="text-sm text-slate-500">Zatiaľ bez hodnotení.</div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
