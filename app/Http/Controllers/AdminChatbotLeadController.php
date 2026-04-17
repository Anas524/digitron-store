<?php

namespace App\Http\Controllers;

use App\Models\ChatbotLead;
use Illuminate\Http\Request;

class AdminChatbotLeadController extends Controller
{
    public function index()
    {
        $leads = ChatbotLead::latest()->paginate(20);
        $newCount = ChatbotLead::where('status', 'new')->count();

        return view('admin.chatbot_leads.index', compact('leads', 'newCount'));
    }

    public function updateStatus(Request $request, ChatbotLead $chatbotLead)
    {
        $request->validate([
            'status' => ['required', 'in:new,reviewed,contacted,converted,closed'],
        ]);

        $chatbotLead->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Lead status updated successfully.');
    }
}
