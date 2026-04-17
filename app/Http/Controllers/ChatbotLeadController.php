<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotLead;

class ChatbotLeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['nullable', 'integer'],
            'product_name' => ['nullable', 'string', 'max:255'],
            'product_sku' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'source_type' => ['nullable', 'string', 'max:50'],
            'button_label' => ['nullable', 'string', 'max:100'],
            'page_type' => ['nullable', 'string', 'max:50'],
        ]);

        $lead = ChatbotLead::create([
            'product_id' => $validated['product_id'] ?? null,
            'product_name' => $validated['product_name'] ?? null,
            'product_sku' => $validated['product_sku'] ?? null,
            'message' => $validated['message'],
            'source_type' => $validated['source_type'] ?? null,
            'button_label' => $validated['button_label'] ?? null,
            'page_type' => $validated['page_type'] ?? null,
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'ok' => true,
            'lead_id' => $lead->id,
        ]);
    }
}
