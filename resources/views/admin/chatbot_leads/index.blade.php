@extends('layouts.admin')

@section('title', 'Chatbot Leads | Digitron Command Center')
@section('adminContentClass', 'p-0 h-full overflow-y-auto')

@section('content')
<div class="bg-[#050508] text-white relative min-h-full">
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute inset-0 grid-bg opacity-30"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-admin-primary/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-admin-secondary/10 rounded-full blur-[100px] animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="relative z-10">
        <header class="sticky top-0 z-30 glass-panel border-b border-white/10 px-4 py-4 lg:px-8">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <button
                        type="button"
                        class="lg:hidden w-11 h-11 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition shrink-0"
                        @click.stop="sidebarOpen = true"
                        aria-label="Open sidebar">
                        <i class="bi bi-list text-xl"></i>
                    </button>

                    <div class="min-w-0">
                        <h1 class="text-xl lg:text-2xl font-bold admin-gradient-text leading-tight">Chatbot Leads</h1>
                        <p class="text-xs lg:text-sm text-gray-400">Track product interest and follow up with potential customers.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <div class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl border border-white/10 bg-white/5">
                        <span class="w-2.5 h-2.5 rounded-full bg-admin-danger"></span>
                        <span class="text-sm text-gray-300">New: {{ $newCount ?? 0 }}</span>
                    </div>

                    <div class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl border border-white/10 bg-white/5">
                        <span class="w-2.5 h-2.5 rounded-full bg-admin-success"></span>
                        <span class="text-sm text-gray-300">Total: {{ $totalCount ?? ($leads->total() ?? 0) }}</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-4 lg:p-8 space-y-6">
            @if(session('success'))
            <div class="rounded-2xl border border-admin-success/30 bg-admin-success/10 px-4 py-3 text-admin-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="glass-panel rounded-2xl border border-white/10 p-4 lg:p-6">
                <form method="GET" action="{{ route('admin.chatbot-leads.index') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-gray-400 mb-2">Search</label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Product, SKU, message..."
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-admin-primary focus:outline-none text-sm text-white">
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider text-gray-400 mb-2">Status</label>
                        <select
                            name="status"
                            class="w-full px-4 py-3 rounded-xl bg-[#12141b] border border-white/10 focus:border-admin-primary focus:outline-none text-sm text-white">
                            <option value="">All Statuses</option>
                            @foreach(['new', 'reviewed', 'contacted', 'converted', 'closed'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider text-gray-400 mb-2">Source</label>
                        <select
                            name="source_type"
                            class="w-full px-4 py-3 rounded-xl bg-[#12141b] border border-white/10 focus:border-admin-primary focus:outline-none text-sm text-white">
                            <option value="">All Sources</option>
                            @foreach(['default_message', 'quick_button', 'custom_message'] as $source)
                            <option value="{{ $source }}" {{ request('source_type') === $source ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $source)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider text-gray-400 mb-2">From</label>
                        <input
                            type="date"
                            name="date_from"
                            value="{{ request('date_from') }}"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-admin-primary focus:outline-none text-sm text-white">
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider text-gray-400 mb-2">To</label>
                        <input
                            type="date"
                            name="date_to"
                            value="{{ request('date_to') }}"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-admin-primary focus:outline-none text-sm text-white">
                    </div>

                    <div class="md:col-span-2 xl:col-span-5 flex flex-wrap gap-3 pt-1">
                        <button
                            type="submit"
                            class="px-5 py-3 rounded-xl bg-admin-primary text-white font-semibold hover:bg-admin-primary/80 transition">
                            Apply Filters
                        </button>

                        <a
                            href="{{ route('admin.chatbot-leads.index') }}"
                            class="px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-gray-300 hover:bg-white/10 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden">
                <div class="px-4 lg:px-6 py-4 border-b border-white/10 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-white">Lead Records</h2>
                        <p class="text-sm text-gray-400">User interactions captured from the website chatbot.</p>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full min-w-[1200px] text-sm">
                        <thead class="bg-white/[0.03] border-b border-white/10">
                            <tr class="text-left text-gray-400 cursor-pointer group">
                                <th class="px-4 lg:px-6 py-4 font-semibold">#</th>
                                <th class="px-4 lg:px-6 py-4 font-semibold">Date / Time</th>
                                <th class="px-4 lg:px-6 py-4 font-semibold">Product</th>
                                <th class="px-4 lg:px-6 py-4 font-semibold">SKU</th>
                                <th class="px-4 lg:px-6 py-4 font-semibold">Message</th>
                                <th class="px-4 lg:px-6 py-4 font-semibold">Source</th>
                                <th class="px-4 lg:px-6 py-4 font-semibold">Button</th>
                                <th class="px-4 lg:px-6 py-4 font-semibold">Status</th>
                                <th class="px-4 lg:px-6 py-4 font-semibold">IP</th>
                                <th class="px-4 lg:px-6 py-4 font-semibold">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($leads as $index => $lead)
                            @php
                            $statusColor = match($lead->status) {
                            'new' => 'admin-danger',
                            'reviewed' => 'admin-warning',
                            'contacted' => 'admin-primary',
                            'converted' => 'admin-success',
                            'closed' => 'admin-secondary',
                            default => 'gray-400',
                            };

                            $sourceLabel = ucfirst(str_replace('_', ' ', $lead->source_type ?? 'unknown'));
                            $shortMessage = \Illuminate\Support\Str::limit($lead->message, 55);
                            $detailRowId = 'lead-detail-' . $lead->id;
                            @endphp

                            <tr
                                class="border-b border-white/5 hover:bg-white/[0.03] transition cursor-pointer"
                                onclick="toggleLeadDetail('{{ $detailRowId }}', 'arrow-{{ $lead->id }}')">
                                <td class="px-4 lg:px-6 py-4 text-white font-semibold">
                                    {{ $leads->firstItem() + $index }}
                                </td>

                                <td class="px-4 lg:px-6 py-4 text-gray-300 whitespace-nowrap">
                                    <div>{{ optional($lead->created_at)->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ optional($lead->created_at)->format('h:i A') }}</div>
                                </td>

                                <td class="px-4 lg:px-6 py-4">
                                    <div class="text-white font-medium max-w-[220px] truncate">
                                        {{ $lead->product_name ?: 'General Inquiry' }}
                                    </div>
                                </td>

                                <td class="px-4 lg:px-6 py-4 text-gray-300">
                                    {{ $lead->product_sku ?: '—' }}
                                </td>

                                <td class="px-4 lg:px-6 py-4">
                                    <div class="max-w-[220px] text-gray-300 truncate">
                                        {{ $shortMessage }}
                                    </div>
                                </td>

                                <td class="px-4 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-gray-200">
                                        {{ $sourceLabel }}
                                    </span>
                                </td>

                                <td class="px-4 lg:px-6 py-4 text-gray-300">
                                    {{ $lead->button_label ?: '—' }}
                                </td>

                                <td class="px-4 lg:px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-{{ $statusColor }}/20 text-{{ $statusColor }}">
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                </td>

                                <td class="px-4 lg:px-6 py-4 text-gray-400">
                                    <div class="max-w-[150px] truncate">{{ $lead->ip_address ?: '—' }}</div>
                                </td>

                                <td class="px-4 lg:px-6 py-4 text-gray-400">
                                    <div class="flex items-center gap-2 text-gray-400">
                                        <span class="text-xs group-hover:text-white transition">Details</span>
                                        <i class="bi bi-chevron-down text-xs transition-transform duration-300"
                                            id="arrow-{{ $lead->id }}"></i>
                                    </div>
                                </td>
                            </tr>

                            <tr id="{{ $detailRowId }}"
                                class="hidden border-b border-white/5 bg-white/[0.02] transition-all duration-300">
                                <td colspan="10" class="px-4 lg:px-6 py-5">
                                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
                                        <div class="xl:col-span-2 space-y-4">
                                            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                                <div class="text-xs uppercase tracking-wider text-gray-500 mb-2">Full Message</div>
                                                <div class="text-sm text-gray-200 leading-7 break-words whitespace-pre-line">
                                                    {{ $lead->message }}
                                                </div>
                                            </div>

                                            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                                <div class="text-xs uppercase tracking-wider text-gray-500 mb-3">Lead Details</div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Product:</span>
                                                        <span class="text-white">{{ $lead->product_name ?: 'General Inquiry' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">SKU:</span>
                                                        <span class="text-white">{{ $lead->product_sku ?: '—' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Source:</span>
                                                        <span class="text-white">{{ $sourceLabel }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Button:</span>
                                                        <span class="text-white">{{ $lead->button_label ?: '—' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">IP Address:</span>
                                                        <span class="text-white">{{ $lead->ip_address ?: '—' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Created:</span>
                                                        <span class="text-white">{{ optional($lead->created_at)->format('d M Y, h:i A') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-4">
                                            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                                <div class="text-xs uppercase tracking-wider text-gray-500 mb-3">Update Status</div>

                                                <form method="POST" action="{{ route('admin.chatbot-leads.update-status', $lead) }}" class="space-y-3">
                                                    @csrf
                                                    @method('PATCH')

                                                    <select
                                                        name="status"
                                                        class="w-full px-3 py-3 rounded-xl bg-[#12141b] border border-white/10 text-sm text-white focus:outline-none focus:border-admin-primary">
                                                        @foreach(['new', 'reviewed', 'contacted', 'converted', 'closed'] as $status)
                                                        <option value="{{ $status }}" {{ $lead->status === $status ? 'selected' : '' }}>
                                                            {{ ucfirst($status) }}
                                                        </option>
                                                        @endforeach
                                                    </select>

                                                    <button
                                                        type="submit"
                                                        class="w-full px-4 py-3 rounded-xl bg-admin-primary text-white font-medium hover:bg-admin-primary/80 transition">
                                                        Save Changes
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                                <div class="text-xs uppercase tracking-wider text-gray-500 mb-2">Status</div>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-{{ $statusColor }}/20 text-{{ $statusColor }}">
                                                    {{ ucfirst($lead->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-6 py-16 text-center text-gray-500">
                                    No chatbot leads found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($leads, 'links'))
                <div class="px-4 lg:px-6 py-4 border-t border-white/10">
                    {{ $leads->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function toggleLeadDetail(rowId, arrowId) {
        const allRows = document.querySelectorAll('[id^="lead-detail-"]');
        const allArrows = document.querySelectorAll('[id^="arrow-"]');

        // Close all other rows
        allRows.forEach(row => {
            if (row.id !== rowId) {
                row.classList.add('hidden');
            }
        });

        // Reset all arrows
        allArrows.forEach(arrow => {
            arrow.classList.remove('rotate-180');
        });

        const row = document.getElementById(rowId);
        const arrow = document.getElementById(arrowId);

        if (!row) return;

        const isOpen = !row.classList.contains('hidden');

        if (isOpen) {
            row.classList.add('hidden');
            arrow?.classList.remove('rotate-180');
        } else {
            row.classList.remove('hidden');
            arrow?.classList.add('rotate-180');
        }
    }
</script>