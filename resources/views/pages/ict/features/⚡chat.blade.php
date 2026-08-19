<?php

use Livewire\Component;

new class extends Component
{
    //

    public $userMessage = '';

    protected $listeners = [
        'echo-private:user-chat,.UserChat' => 'recieveUserMessage',
    ];

    /**
     * Handle the incoming broadcast payload.
     */
    public function recieveUserMessage(array $event): void
    {

        // Extracts $userText from the UserChat event payload
        $this->userMessage = $event['userText'] ?? '';
    }

    public function render()
    {
        return view('pages::ict.features.⚡chat')
            ->layout('layouts::support');
    }
};
?>

<div>
    <div class="h-[calc(100vh-5rem)] max-w-7xl mx-auto p-4 flex gap-4">
    <!-- Sidebar: Conversation / Ticket List -->
    <div class="w-full md:w-80 lg:w-96 bg-base-100 rounded-box border border-base-200 shadow-sm flex flex-col shrink-0">
        <!-- Sidebar Header & Search -->
        <div class="p-4 border-b border-base-200 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-base-content">Support Chats</h2>
                <span class="badge badge-primary text-white font-semibold">4 Open</span>
            </div>
            <div class="relative">
                <input
                    type="text"
                    placeholder="Search conversation or user..."
                    class="input input-sm input-bordered w-full pl-9 text-xs"
                />
                <svg class="w-4 h-4 absolute left-3 top-2 text-base-content/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Conversations List -->
        <div class="flex-1 overflow-y-auto divide-y divide-base-200">
            <!-- Active Chat Item -->
            <button class="w-full p-3 text-left flex items-start gap-3 bg-base-200/60 border-l-4 border-primary transition-all">
                <div class="avatar online shrink-0">
                    <div class="w-10 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" alt="Avatar" />
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold text-base-content truncate">Jane Doe</h3>
                        <span class="text-[10px] text-base-content/50">10:42 AM</span>
                    </div>
                    <span class="font-mono text-[10px] font-semibold text-primary block">#T-00104</span>
                    <p class="text-xs text-base-content/70 truncate mt-0.5">My printer isn't responding to network pings...</p>
                </div>
            </button>

            <!-- Unread Chat Item -->
            {{-- <button class="w-full p-3 text-left flex items-start gap-3 hover:bg-base-200/40 transition-all">
                <div class="avatar online shrink-0">
                    <div class="w-10 rounded-full">
                        <img src="https://img.daisyui.com/images/stock/photo-1507003211169-0a1dd7228f2d.webp" alt="Avatar" />
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold text-base-content truncate">John Smith</h3>
                        <span class="text-[10px] text-primary font-bold">2m ago</span>
                    </div>
                    <span class="font-mono text-[10px] font-semibold text-base-content/50 block">#T-00102</span>
                    <p class="text-xs font-semibold text-base-content truncate mt-0.5">Uploaded the requested log files.</p>
                </div>
                <span class="badge badge-primary badge-xs mt-1">NEW</span>
            </button> --}}


        </div>
    </div>

    <!-- Main Chat Window -->
    <div class="flex-1 bg-base-100 rounded-box border border-base-200 shadow-sm flex flex-col overflow-hidden">
        <!-- Chat Top Bar / Header -->
        <div class="p-4 border-b border-base-200 flex items-center justify-between bg-base-100">
            <div class="flex items-center gap-3">
                <div class="avatar online">
                    <div class="w-10 rounded-full">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" alt="Avatar" />
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-bold text-base-content">Jane Doe</h2>
                        <span class="font-mono text-xs text-primary font-semibold">#T-00104</span>
                    </div>
                    <p class="text-xs text-base-content/60">Finance Dept &bull; Desk 04</p>
                </div>
            </div>

            <!-- Action Badges & Buttons -->
            <div class="flex items-center gap-2">
                <span class="badge badge-warning text-white font-semibold text-xs hidden sm:inline-flex">HIGH PRIORITY</span>
                <span class="badge badge-outline text-xs">IN PROGRESS</span>
                <button class="btn btn-sm btn-ghost btn-circle" title="View Ticket Details">
                    <svg class="w-5 h-5 text-base-content/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Body -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-base-200/20">
            <!-- Date Separator -->
            <div class="divider text-[10px] text-base-content/40 font-semibold uppercase">Today</div>

            <!-- User Chat Bubble (Incoming) -->
            <div class="chat chat-start">
                <div class="chat-image avatar">
                    <div class="w-8 rounded-full">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" alt="User" />
                    </div>
                </div>
                <div class="chat-header text-xs text-base-content/60 mb-1">
                    Jane Doe
                    <time class="text-[10px] opacity-50 ml-1">10:35 AM</time>
                </div>
                <div class="chat-bubble chat-bubble-neutral text-xs leading-relaxed">
                    {{ $userMessage }}
                </div>
            </div>

            <!-- Admin Chat Bubble (Outgoing) -->
            <div class="chat chat-end">
                <div class="chat-image avatar">
                    <div class="w-8 rounded-full">
                        <img src="https://img.daisyui.com/images/stock/photo-1535713875002-d1d0cf377fde.webp" alt="Admin" />
                    </div>
                </div>
                <div class="chat-header text-xs text-base-content/60 mb-1">
                    Support Admin (You)
                    <time class="text-[10px] opacity-50 ml-1">10:38 AM</time>
                </div>
                <div class="chat-bubble chat-bubble-primary text-white text-xs leading-relaxed">
                    Hi Jane! I'm checking the network logs for Desk 04 now. Have you tried restarting the printer unit?
                </div>
                <div class="chat-footer text-[10px] opacity-50 mt-1">Delivered</div>
            </div>

            <!-- User Chat Bubble with Attachment -->

        </div>

        <!-- Chat Input Footer -->
        <div class="p-3 bg-base-100 border-t border-base-200">
            <form class="flex items-center gap-2">
                <!-- Attach File Button -->
                <button type="button" class="btn btn-ghost btn-circle btn-sm text-base-content/60" title="Attach File">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </button>

                <!-- Input Field -->
                <input
                    type="text"
                    placeholder="Type your reply as support admin..."
                    class="input input-sm input-bordered flex-1 text-xs focus:outline-none focus:border-primary"
                />

                <!-- Send Button -->
                <button type="submit" class="btn btn-sm btn-primary text-white font-semibold gap-1">
                    <span>Send</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9-7-9-7-9 7 9 7zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
</div>
