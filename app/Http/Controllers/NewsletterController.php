<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\NewsletterRequest;
use App\Models\NewsLetter;

class NewsletterController extends Controller
{
    public function store(NewsletterRequest $request)
    {
        $newsletter = NewsLetter::where('email', $request->email)->first();

        if ($newsletter && $newsletter->is_subscribed) {
            return Redirect::back()
                ->with('error', 'This email is already subscribed to our newsletter.');
        }

        NewsLetter::updateOrCreate(
            [
                'email' => $request->email,
            ],
            [
                'user_id' => auth()->id(),
                'source' => $request->source,
                'privacy_policy_accepted' => $request->privacy_policy,
                'privacy_policy_accepted_at' => now(),
                'is_subscribed' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]
        );

        return Redirect::back()->with('success', 'You have subscribed.');
    }

    public function destroy(NewsLetter $newsletter)
    {
        $newsletter->update([
            'is_subscribed' => false,
            'unsubscribed_at' => now()
        ]);

        return Redirect::back()->with('success', 'You have unsubscribed.');
    }
}
