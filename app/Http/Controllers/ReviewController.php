<?php

namespace App\Http\Controllers;

use App\Models\Cottage;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Cottage $cottage)
    {
        $validated = $request->validate([
            'rating' => ['required','integer','min:1','max:5'],
            'comment' => ['nullable','string','max:1000'],
        ]);

        Review::updateOrCreate(
            [
                'cottage_id' => $cottage->id,
                'user_id' => auth()->id(),
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return redirect()->back()->with('success', 'Hodnotenie bolo uložené.');
    }

    public function destroy(Review $review)
    {
        // user môže zmazať len svoje hodnotenie, admin môže všetko
        if ($review->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        $review->delete();
        return redirect()->back()->with('success', 'Hodnotenie bolo zmazané.');
    }
}
