<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class NewsletterController extends Controller
{
    // Public subscribe (AJAX)
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email','max:255'],
        ]);

        $email = strtolower(trim($data['email']));

        // avoid duplicates using unique email
        $exists = DB::table('newsletter_subscriptions')->where('email', $email)->exists();

        if (!$exists) {
            DB::table('newsletter_subscriptions')->insert([
                'email'      => $email,
                'status'     => 'new',
                'ip_address' => $request->ip(),
                'user_agent' => substr((string)$request->userAgent(), 0, 1000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'ok' => true,
            'message' => $exists ? 'You are already subscribed' : 'Subscribed successfully',
        ]);
    }

    // Admin list
    public function adminIndex(Request $request)
    {
        if (Schema::hasColumn('newsletter_subscriptions', 'status')) {
            DB::table('newsletter_subscriptions')
                ->where('status', 'new')
                ->update([
                    'status' => 'seen',
                    'updated_at' => now(),
                ]);
        }

        $subs = DB::table('newsletter_subscriptions')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->search . '%');
            })
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.newsletter.index', compact('subs'));
    }
}
