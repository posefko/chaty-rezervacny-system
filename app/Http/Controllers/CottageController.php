<?php

namespace App\Http\Controllers;

use App\Models\Cottage;
use Illuminate\Http\Request;

class CottageController extends Controller
{
    public function create()
    {
        return view('cottages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'min:3', 'max:100'],
            'location'        => ['required', 'string', 'min:3', 'max:100'],
            'capacity'        => ['required', 'integer', 'min:1', 'max:200'],
            'price_per_night' => ['required', 'integer', 'min:1', 'max:10000'],
            'is_whole_chalet' => ['required', 'boolean'],
            'description'     => ['nullable', 'string', 'max:2000'],
        ]);

        Cottage::create($validated);

        return redirect()
            ->back()
            ->with('success', 'Chata bola úspešne pridaná.');
    }

    public function index()
    {
        $cottages = Cottage::orderByDesc('id')->get();
        return view('cottages.index', compact('cottages'));
    }

    public function edit(Cottage $cottage)
    {
        return view('cottages.edit', compact('cottage'));
    }

    public function update(Request $request, Cottage $cottage)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'min:3', 'max:100'],
            'location'        => ['required', 'string', 'min:3', 'max:100'],
            'capacity'        => ['required', 'integer', 'min:1', 'max:200'],
            'price_per_night' => ['required', 'integer', 'min:1', 'max:10000'],
            'is_whole_chalet' => ['required', 'boolean'],
            'description'     => ['nullable', 'string', 'max:2000'],
        ]);

        $cottage->update($validated);

        return redirect()
            ->route('cottages.index')
            ->with('success', 'Chata bola úspešne upravená.');
    }

    public function destroy(Cottage $cottage)
    {
        $cottage->delete();

        return redirect()
            ->route('cottages.index')
            ->with('success', 'Chata bola odstránená.');
    }


}

