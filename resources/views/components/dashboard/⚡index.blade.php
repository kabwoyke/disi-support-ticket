<?php

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TicketAssignment;

new class extends Component
{
    // Mock data - replace with Eloquent queries
    public function with(): array
    {
        return [
            'stats' => [
                'open'     => 12,
                'assigned' => 5,
                'resolved' => 0,
                'closed'   => 0,
            ],
            'openTickets' => [
                [
                    'id' => 'TK-1001',
                    'user' => 'Sarah Jenkins',
                    'ext' => '2401',
                    'location' => 'Desk A-08',
                    'issue' => 'Printer drivers failing to connect over LAN',
                    'priority' => 'High',
                    'created_at' => '10 mins ago',
                ],
                [
                    'id' => 'TK-1002',
                    'user' => 'Michael Chen',
                    'ext' => '1105',
                    'location' => 'Desk C-14',
                    'issue' => 'Monitor flickers when connected to dock',
                    'priority' => 'Medium',
                    'created_at' => '35 mins ago',
                ],
                [
                    'id' => 'TK-1003',
                    'user' => 'David K.',
                    'ext' => '3302',
                    'location' => 'Desk B-02',
                    'issue' => 'Outlook asking for password repeatedly',
                    'priority' => 'Low',
                    'created_at' => '1 hour ago',
                ],
            ]
        ];
    }

    public function resolveTicket($ticketId): void
    {
        // Handle resolution logic
    }

     public function render()
    {
        $supportId = auth()->guard('support')->user()->id;
        $numberOfOpenTickets = count(Ticket::where('status' , 'OPEN')->get());
        $numberOfAssignedTickets = count(TicketAssignment::where('teamId' , $supportId)->get());

        $openTickets = Ticket::where('status' , 'OPEN')->get();

        return view('components.dashboard.⚡index' , ['numberOfOpenTickets' => $numberOfOpenTickets , 'numberOfAssignedTickets' => $numberOfAssignedTickets , 'open_Tickets' =>$openTickets]);
    }


};
?>

<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Welcome {{ auth()->guard('support')->user()->first_name ." ". auth()->guard('support')->user()->last_name }}</h1>
            <p class="text-sm text-base-content/70">Overview of current ticket dispatches and system activity.</p>
        </div>

    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Open Tickets -->
        <div class="bg-base-100 border border-primary-200/50 rounded-box p-5 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-wider text-base-content/60">Open Tickets</p>
                <p class="text-3xl font-extrabold text-error">{{ $numberOfOpenTickets }}</p>
            </div>
            <div class="bg-error/10 text-error p-3 rounded-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>

        <!-- Assigned Tickets -->
        <div class="bg-base-100 border border-primary-200/50 rounded-box p-5 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-wider text-base-content/60">Assigned To Me</p>
                <p class="text-3xl font-extrabold text-primary">{{ $numberOfAssignedTickets }}</p>
            </div>
            <div class="bg-primary/10 text-primary p-3 rounded-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"/></svg>
            </div>
        </div>

        <!-- Resolved Tickets -->
        <div class="bg-base-100 border border-primary-200/50 rounded-box p-5 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-wider text-base-content/60">Resolved Today</p>
                <p class="text-3xl font-extrabold text-warning">{{ $stats['resolved'] }}</p>
            </div>
            <div class="bg-warning/10 text-warning p-3 rounded-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Closed Tickets -->
        <div class="bg-base-100 border border-primary-200/50 rounded-box p-5 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-wider text-base-content/60">Total Closed</p>
                <p class="text-3xl font-extrabold text-success">{{ $stats['closed'] }}</p>
            </div>
            <div class="bg-success/10 text-success p-3 rounded-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>

    </div>

    <!-- Open Tickets Table Card -->
    <div class="bg-base-100 border border-primary-200/50 rounded-box shadow-sm overflow-hidden">
        <div class="p-4 border-b border-base-200 flex items-center justify-between bg-base-100">
            <div>
                <h2 class="font-bold text-lg">Active Open Tickets</h2>
                <p class="text-xs text-base-content/60">List of pending dispatches requiring ICT attention</p>
            </div>
            <span class="badge badge-primary font-medium">{{ $numberOfOpenTickets }} Open</span>
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full text-left">
                <thead class="bg-base-200/50 text-xs text-base-content/70 uppercase">
                    <tr>
                        <th>Ticket ID</th>
                        <th>Subject</th>
                        <th>Issue Description</th>
                        <th>Priority</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200 text-sm">
                    @forelse($open_Tickets as $ticket)
                        <tr class="hover:bg-base-200/30 transition-colors">
                            <td class="font-bold text-primary">{{ $ticket['id'] }}</td>
                            <td>
                                <div class="font-medium">{{ $ticket['subject'] }}</div>
                                <div class="text-xs text-base-content/60">Ext: {{ $ticket['ext'] }}</div>
                            </td>
                            <td>
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-btn bg-primary-100 text-primary font-semibold text-xs">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $ticket['description'] }}
                                </div>
                            </td>

                            <td>
                                @if($ticket['priority'] === 'HIGH')
                                    <span class="badge badge-error text-xs font-semibold text-white">High</span>
                                @elseif($ticket['priority'] === 'MODERATE')
                                    <span class="badge badge-warning text-xs font-semibold">Medium</span>
                                @else
                                    <span class="badge badge-ghost text-xs font-semibold">Low</span>
                                @endif
                            </td>

                            <td class="text-right whitespace-nowrap">
                                <button
                                    wire:click="resolveTicket('{{ $ticket['id'] }}')"
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm btn-success text-white gap-1"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Resolve
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-base-content/60">
                                No active tickets found in queue.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
