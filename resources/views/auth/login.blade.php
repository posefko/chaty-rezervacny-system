@extends('layouts.app')

@section('title', 'Prihlásenie')

@section('content')
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-6">Prihlásenie</h1>

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded border px-3 py-2">
                @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Heslo</label>
                <input type="password" name="password" required
                       class="w-full rounded border px-3 py-2">
                @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remember" class="rounded border">
                    Zapamätať si ma
                </label>
                <a href="{{ route('register') }}" class="text-sm text-emerald-700 hover:underline">
                    Nemáš účet?
                </a>
            </div>

            <button type="submit" class="btn-primary w-full">Prihlásiť sa</button>
        </form>
    </div>
@endsection

