<?php

namespace App\Http\Controllers;

use App\Models\Cottage;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $query = Reservation::with(['cottage','user'])
            ->orderByDesc('created_at');

        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        $reservations = $query->paginate(10);

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

        $cottage = Cottage::findOrFail($validated['cottage_id']);

        // Nájdeme rezervácie, ktoré sa prekrývajú
        $overlapping = Reservation::where('cottage_id', $cottage->id)
            ->where(function ($q) use ($validated) {
                $q->whereBetween('date_from', [$validated['date_from'], $validated['date_to']])
                    ->orWhereBetween('date_to', [$validated['date_from'], $validated['date_to']])
                    ->orWhere(function ($q2) use ($validated) {
                        $q2->where('date_from', '<=', $validated['date_from'])
                            ->where('date_to', '>=', $validated['date_to']);
                    });
            })
            ->get();

        // CELÁ CHATA
        if ($cottage->is_whole_chalet && $overlapping->count() > 0) {
            return back()
                ->withErrors(['cottage_id' => 'Chata je v tomto termíne už obsadená.'])
                ->withInput();
        }

        // LÔŽKA
        if (!$cottage->is_whole_chalet) {
            $usedBeds = $overlapping->sum('guests');

            if ($usedBeds + $validated['guests'] > $cottage->capacity) {
                return back()
                    ->withErrors(['guests' => 'Kapacita lôžok je v tomto termíne už naplnená.'])
                    ->withInput();
            }
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Reservation::create($validated);

        return redirect()->route('reservations.index')
            ->with('success', 'Rezervácia bola vytvorená.');
    }


    public function edit(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }
        $cottages = Cottage::orderBy('name')->get();
        return view('reservations.edit', compact('reservation', 'cottages'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        $rules = [
            'cottage_id' => ['required','exists:cottages,id'],
            'date_from' => ['required','date'],
            'date_to' => ['required','date','after:date_from'],
            'guests' => ['required','integer','min:1','max:50'],
            'note' => ['nullable','string','max:1000'],
        ];

        if (auth()->user()->is_admin) {
            $rules['status'] = ['required','in:pending,confirmed,cancelled'];
        }

        $validated = $request->validate($rules);

        // ===== KONTROLA DOSTUPNOSTI (ako v store), ale bez tejto rezervácie =====
        $cottage = Cottage::findOrFail($validated['cottage_id']);

        $overlapQuery = Reservation::where('cottage_id', $cottage->id)
            ->where('id', '!=', $reservation->id) // dôležité!
            ->whereIn('status', ['pending','confirmed'])
            ->where(function ($q) use ($validated) {
                // prekryv intervalov: start < existing_end AND end > existing_start
                $q->where('date_from', '<', $validated['date_to'])
                    ->where('date_to',   '>', $validated['date_from']);
            });

        if ($cottage->is_whole_chalet) {
            // Celá chata: žiadny prekryv nesmie existovať
            if ($overlapQuery->exists()) {
                return back()
                    ->withErrors(['date_from' => 'Termín nie je dostupný – celá chata je už rezervovaná.'])
                    ->withInput();
            }
        } else {
            // Lôžka: súčet hostí v prekryvoch + požadovaní hostia <= kapacita
            $usedGuests = (int) $overlapQuery->sum('guests');
            $remaining = max(0, (int) $cottage->capacity - $usedGuests);

            if ((int) $validated['guests'] > $remaining) {
                return back()
                    ->withErrors([
                        'guests' => "Nedostatok voľných lôžok. Voľné: {$remaining}/{$cottage->capacity}."
                    ])
                    ->withInput();
            }
        }
        // ===== KONIEC KONTROLY =====

        if (!auth()->user()->is_admin) {
            $validated['status'] = $reservation->status; // user nesmie meniť status
        }

        $reservation->update($validated);

        return redirect()->route('reservations.index')
            ->with('success', 'Rezervácia bola upravená.');
    }


    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }
        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Rezervácia bola zmazaná.');
    }

    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'cottage_id' => ['required','exists:cottages,id'],
            'date_from'  => ['required','date'],
            'date_to'    => ['required','date','after:date_from'],
            'guests'     => ['nullable','integer','min:1','max:50'],
        ]);

        $cottage = Cottage::findOrFail($validated['cottage_id']);
        $requestedGuests = max(1, (int) ($validated['guests'] ?? 1));

        $overlapQuery = Reservation::where('cottage_id', $cottage->id)
            ->whereIn('status', ['pending','confirmed'])
            ->where(function ($q) use ($validated) {
                // prekryv intervalov: start < existing_end AND end > existing_start
                $q->where('date_from', '<', $validated['date_to'])
                    ->where('date_to',   '>', $validated['date_from']);
            });

        // Celá chata: ak existuje aspoň 1 prekrytie, je obsadena
        if ($cottage->is_whole_chalet) {
            $occupied = $overlapQuery->exists();

            return response()->json([
                'available' => !$occupied,
                'mode' => 'whole',
                'capacity' => (int) $cottage->capacity,
                'remaining' => $occupied ? 0 : (int) $cottage->capacity,
                'requested' => $requestedGuests,
            ]);
        }

        // Lôžka: kontrola kapacity
        $usedGuests = (int) $overlapQuery->sum('guests');
        $remaining = max(0, (int)$cottage->capacity - $usedGuests);
        $available = $requestedGuests <= $remaining;

        return response()->json([
            'available' => $available,
            'mode' => 'beds',
            'capacity' => (int) $cottage->capacity,
            'used' => $usedGuests,
            'remaining' => $remaining,
            'requested' => $requestedGuests,
        ]);
    }
}
