<?php

namespace App\Http\Controllers;

use App\Models\Cottage;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['cottage', 'user'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $cottages = Cottage::orderBy('name')->get();
        return view('reservations.create', compact('cottages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cottage_id' => ['required','exists:cottages,id'],
            'date_from' => ['required','date'],
            'date_to' => ['required','date','after:date_from'],
            'guests' => ['required','integer','min:1','max:50'],
            'note' => ['nullable','string','max:1000'],
        ]);

        // DOČASNE: kým nemáme auth
        $validated['user_id'] = 1;
        $validated['status'] = 'pending';

        Reservation::create($validated);

        return redirect()->route('reservations.index')
            ->with('success', 'Rezervácia bola vytvorená.');
    }

    public function edit(Reservation $reservation)
    {
        $cottages = Cottage::orderBy('name')->get();
        return view('reservations.edit', compact('reservation', 'cottages'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'cottage_id' => ['required','exists:cottages,id'],
            'date_from' => ['required','date'],
            'date_to' => ['required','date','after:date_from'],
            'guests' => ['required','integer','min:1','max:50'],
            'status' => ['required','in:pending,confirmed,cancelled'],
            'note' => ['nullable','string','max:1000'],
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.index')
            ->with('success', 'Rezervácia bola upravená.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Rezervácia bola zmazaná.');
    }
}
