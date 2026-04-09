<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QuoteController extends Controller
{
    // Public page
    public function index()
    {
        return view('pages.quote');
    }

    // Public submit (AJAX)
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:50'],
            'area'       => ['nullable', 'string', 'max:100'],
            'quote_type' => ['required', 'string', 'max:100'],
            'use_case'   => ['nullable', 'array'],
            'use_case.*' => ['string', 'max:50'],
            'budget'     => ['nullable', 'numeric', 'min:0'],
            'message'    => ['nullable', 'string', 'max:5000'],

            // details can come as JSON string or array
            'details'    => ['nullable'],

            // MULTIPLE files
            'attachments'   => ['nullable', 'array', 'max:5'], // limit count
            'attachments.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,txt'], // 10MB each
        ]);

        // decode details if JSON string
        $details = $request->input('details');
        if (is_string($details)) {
            $decoded = json_decode($details, true);
            $details = is_array($decoded) ? $decoded : null;
        }

        // bulletproof: ensure primary_use_case is saved even if JS fails
        if (!is_array($details)) $details = [];

        $useCases = $request->input('use_case', []);
        if (!is_array($useCases)) $useCases = [];

        // if hidden field empty, fill from checkbox values
        $existing = trim((string)($details['primary_use_case'] ?? ''));
        if ($existing === '' && count($useCases)) {
            $details['primary_use_case'] = implode(', ', $useCases);
        }

        // optional: store the raw array too (helps later if you want tags easily)
        $details['use_case'] = $useCases;

        // insert quote and get id
        $quoteId = DB::table('quotes')->insertGetId([
            'full_name'  => $data['full_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'area'       => $data['area'] ?? null,
            'quote_type' => $data['quote_type'] ?? null,
            'budget'     => $data['budget'] ?? null,
            'message'    => $data['message'] ?? null,
            'details'    => $details ? json_encode($details, JSON_UNESCAPED_UNICODE) : null,
            'source'     => $request->input('source', 'quote_page'),
            'status'     => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string)$request->userAgent(), 0, 1000),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // store attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (!$file || !$file->isValid()) continue;

                $path = $file->store("quotes/{$quoteId}", 'public');

                DB::table('quote_attachments')->insert([
                    'quote_id' => $quoteId,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['ok' => true, 'message' => 'Quote request submitted, We will contact you soon.']);
    }

    // Admin list
    public function adminIndex(Request $request)
    {
        if (Schema::hasColumn('quotes', 'status')) {
            DB::table('quotes')
                ->where('status', 'new')
                ->update([
                    'status' => 'seen',
                    'updated_at' => now(),
                ]);
        }

        $quotes = DB::table('quotes')
            ->leftJoin('quote_attachments', 'quotes.id', '=', 'quote_attachments.quote_id')
            ->select('quotes.*', DB::raw('COUNT(quote_attachments.id) as att_count'))
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('quotes.full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('quotes.email', 'like', '%' . $request->search . '%')
                        ->orWhere('quotes.quote_type', 'like', '%' . $request->search . '%');
                });
            })
            ->groupBy('quotes.id')
            ->orderByDesc('quotes.id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.quotes.index', compact('quotes'));
    }

    public function adminDestroy($id)
    {
        // get attachment paths before delete (to remove files from storage)
        $paths = DB::table('quote_attachments')
            ->where('quote_id', $id)
            ->pluck('path')
            ->toArray();

        // delete quote (attachments rows will cascade delete)
        $deleted = DB::table('quotes')->where('id', $id)->delete();

        if (!$deleted) {
            return response()->json(['ok' => false, 'message' => 'Quote not found.'], 404);
        }

        // delete files from storage
        foreach ($paths as $p) {
            if ($p) Storage::disk('public')->delete($p);
        }

        return response()->json(['ok' => true, 'message' => 'Quote deleted.']);
    }

    public function adminAttachments($id)
    {
        $items = DB::table('quote_attachments')
            ->where('quote_id', $id)
            ->orderBy('id')
            ->get()
            ->map(function ($a) {
                return [
                    'name' => $a->original_name ?: 'attachment',
                    'url'  => asset('storage/' . $a->path),
                    'mime' => $a->mime,
                    'size' => $a->size,
                ];
            });

        return response()->json(['ok' => true, 'items' => $items]);
    }

    public function markSeen($id)
    {
        $quote = DB::table('quotes')->where('id', $id)->first();

        if (!$quote) {
            return response()->json(['ok' => false, 'message' => 'Quote not found.'], 404);
        }

        if (Schema::hasColumn('quotes', 'status')) {
            DB::table('quotes')
                ->where('id', $id)
                ->update([
                    'status' => 'seen',
                    'updated_at' => now(),
                ]);
        }

        return response()->json(['ok' => true]);
    }
}
