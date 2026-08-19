<?php

use Livewire\Component;
use App\Events\UserChat;
use Livewire\Attributes\Validate;


new class extends Component
{
    //

    #[Validate('required|string')]
    public $userChat = '';

    public function send() {
        $this->validate();

        Log::info("message" , ['msg' => $this->userChat]);

        broadcast(new UserChat($this->userChat));

        $this->reset('userChat');
    }

public function render()
    {
        return view('pages::users.⚡chat')
            ->layout('layouts::user');
    }
};
?>

<div>
    <div class="h-[calc(100vh-5rem)] max-w-4xl mx-auto p-4 flex flex-col">
    <!-- Main Chat Card -->
    <div class="flex-1 bg-base-100 rounded-box border border-base-200 shadow-sm flex flex-col overflow-hidden">

        <!-- Header: Ticket Overview -->
        <div class="p-4 border-b border-base-200 bg-base-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="#" class="btn btn-sm btn-ghost btn-circle" title="Back to Tickets">
                    <svg class="w-5 h-5 text-base-content/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-xs font-bold text-primary">#T-00104</span>
                        <h1 class="text-sm font-bold text-base-content">Network printer on Desk 04 unreachable</h1>
                    </div>
                    <p class="text-xs text-base-content/60 mt-0.5">Assigned to: <span class="font-semibold text-base-content">ICT Support Team</span></p>
                </div>
            </div>

            <!-- Status & Priority Badges -->
            <div class="flex items-center gap-2">
                <span class="badge badge-warning text-white font-semibold text-xs">HIGH PRIORITY</span>
                <span class="badge badge-info badge-outline text-xs">IN PROGRESS</span>
            </div>
        </div>

        <!-- Chat Conversation Body -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-base-200/20">
            <!-- Timestamp Divider -->
            <div class="divider text-[10px] text-base-content/40 font-semibold uppercase">Today</div>

            <!-- User Message (Outgoing) -->
            <div class="chat chat-end">
                <div class="chat-header text-xs text-base-content/60 mb-1">
                    You
                    <time class="text-[10px] opacity-50 ml-1">10:35 AM</time>
                </div>
                <div class="chat-bubble chat-bubble-primary text-white text-xs leading-relaxed">
                    Hello IT Team, my network printer on Desk 04 suddenly stopped responding. Can someone assist?
                </div>
                <div class="chat-footer text-[10px] opacity-50 mt-1">Sent</div>
            </div>

            <!-- User Message with Attachment (Outgoing) -->

        </div>

        <!-- Chat Input Area -->
        <div class="p-3 bg-base-100 border-t border-base-200">
            <form class="flex items-center gap-2" wire:submit='send'>
                @csrf
                <!-- Attachment Button -->
                <button type="button" class="btn btn-ghost btn-circle btn-sm text-base-content/60 hover:text-primary" title="Attach file">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </button>

                <!-- Input Textarea/Field -->
                <input
                    type="text"
                    wire:model='userChat'
                    placeholder="Type your reply here..."
                    class="input input-sm input-bordered flex-1 text-xs focus:outline-none focus:border-primary"
                />

                <!-- Submit Button -->
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
