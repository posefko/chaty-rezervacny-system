@extends('layouts.app')

@section('title', 'Registrácia')

@section('content')
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-6">Registrácia</h1>

        <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Meno</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full rounded border px-3 py-2">
                @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded border px-3 py-2">
                @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Heslo</label>
                <input type="password" name="password" required minlength="6"
                       class="w-full rounded border px-3 py-2">
                @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Heslo znova</label>
                <input type="password" name="password_confirmation" required minlength="6"
                       class="w-full rounded border px-3 py-2">
            </div>

            <button type="submit" class="btn-primary w-full">Vytvoriť účet</button>
        </form>
    </div>
@endsection

