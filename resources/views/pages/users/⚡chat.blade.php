<?php

use Livewire\Component;
use App\Events\UserChat;
use App\Models\ChatMessage;
use App\Models\Chat;
use App\Models\Ticket;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;

new class extends Component
{
    #[Validate('required|string|min:1')]
    public string $userChat = '';

    #[Url]
    public $ticket = '';

    public array $messages = [];

    public function mount(): void
    {
        if (!$this->ticket) {
            $this->ticket = request()->query('ticket', '');
        }

        $this->loadMessages();
    }

    public function loadMessages(): void
    {
        if (!$this->ticket) {
            return;
        }

        $this->messages = ChatMessage::where('chat_id', $this->ticket)
            ->oldest()
            ->get()
            ->map(fn ($msg) => [
                'id' => 'msg_' . $msg->id,
                'sender' => $msg->sender_type === 'App\Models\SupportTeam' ? 'admin' : 'user',
                'text' => $msg->message,
                'time' => $msg->created_at->timezone('Africa/Nairobi')->format('g:i A'),
            ])
            ->toArray();
    }

    public function getListeners(): array
    {
        if (!$this->ticket) {
            return [];
        }

        return [
            "echo-private:admin-chat.{$this->ticket},.AdminChat" => 'receiveAdminMessage',
        ];
    }

    public function receiveAdminMessage(array $event): void
    {
        if (!empty($event['adminText'])) {
            $this->messages[] = [
                'id' => uniqid('msg_'),
                'sender' => 'admin',
                'text' => $event['adminText'],
                'time' => now()->timezone('Africa/Nairobi')->format('g:i A'),
            ];
        }
    }

    public function send(): void
{
    $this->validate();

    if (!$this->ticket) {
        return;
    }

    // Retrieve the active Chat record (or create one if it doesn't exist)
    $chat = Chat::firstOrCreate([
        'id' => $this->ticket,
    ], [
        'user_id' => auth()->id(),
    ]);

    // 1. Save message using the verified Chat ID
    $savedMessage = ChatMessage::create([
        'chat_id'     => $chat->id,
        'sender_type' => 'App\Models\User',
        'sender_id'   => auth()->id(),
        'message'     => $this->userChat,
    ]);

    // 2. Broadcast to admin
    broadcast(new UserChat($this->userChat, (int) $chat->id));

    // 3. Update UI state
    $this->messages[] = [
        'id'     => 'msg_' . $savedMessage->id,
        'sender' => 'user',
        'text'   => $this->userChat,
        'time'   => $savedMessage->created_at->timezone('Africa/Nairobi')->format('g:i A'),
    ];

    $this->reset('userChat');
}

    public function render()
    {
        $ticketDetail = Ticket::find($this->ticket);

        return view('pages::users.⚡chat', ['ticketDetail' => $ticketDetail])
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
                            <span class="font-mono text-xs font-bold text-primary">#T-00{{ $ticketDetail->id }}</span>
                            <h1 class="text-sm font-bold text-base-content">{{ $ticketDetail->description }}</h1>
                        </div>
                        {{-- <p class="text-xs text-base-content/60 mt-0.5">Assigned to: <span class="font-semibold text-base-content">{{  }}</span></p> --}}
                    </div>
                </div>

                <!-- Status & Priority Badges -->
                <div class="flex items-center gap-2">
                    <span class="badge badge-warning text-white font-semibold text-xs">{{ strtoupper($ticketDetail->priority) }}</span>
                    <span class="badge badge-info badge-outline text-xs">{{ $ticketDetail->status }}</span>
                </div>
            </div>

            <!-- Chat Conversation Body -->
            <div
                x-data="{ scrollToBottom() { $el.scrollTop = $el.scrollHeight } }"
                x-init="scrollToBottom()"
                x-effect="scrollToBottom()"
                class="flex-1 overflow-y-auto p-4 space-y-4 bg-base-200/20"
            >
                <!-- Timestamp Divider -->
                <div class="divider text-[10px] text-base-content/40 font-semibold uppercase">Today</div>

                @forelse ($messages as $msg)
                    @if ($msg['sender'] === 'user')
                        <!-- User Outgoing Bubble -->
                        <div wire:key="{{ $msg['id'] }}" class="chat chat-end transition-all duration-300">
                            <div class="chat-header text-xs text-base-content/60 mb-1">
                                You
                                <time class="text-[10px] opacity-50 ml-1">{{ $msg['time'] }}</time>
                            </div>
                            <div class="chat-bubble chat-bubble-primary text-white text-xs leading-relaxed">
                                {{ $msg['text'] }}
                            </div>
                            <div class="chat-footer text-[10px] opacity-50 mt-1">Sent</div>
                        </div>
                    @else
                        <!-- Admin Incoming Bubble -->
                        <div wire:key="{{ $msg['id'] }}" class="chat chat-start transition-all duration-300">
                            <div class="chat-image avatar">
                                <div class="w-8 rounded-full">
                                    <img src="https://media.licdn.com/dms/image/v2/D4E03AQGWlFXHRfXxDw/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1674839788606?e=2147483647&v=beta&t=fartG9Fh-rvivnWKxgT4xKADm9jgjdLXxyK3BUjHiOI" alt="Support Admin" />
                                </div>
                            </div>
                            <div class="chat-header text-xs text-base-content/60 mb-1">
                                ICT Support Admin
                                <time class="text-[10px] opacity-50 ml-1">{{ $msg['time'] }}</time>
                            </div>
                            <div class="chat-bubble chat-bubble-neutral text-xs leading-relaxed">
                                {{ $msg['text'] }}
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center text-xs text-base-content/40 py-8">
                        Type your message below to send a update to the support team.
                    </div>
                @endforelse
            </div>

            <!-- Chat Input Area -->
            <div class="p-3 bg-base-100 border-t border-base-200">
                <form class="flex items-center gap-2" wire:submit="send">
                    <!-- Attachment Button -->
                    <button type="button" class="btn btn-ghost btn-circle btn-sm text-base-content/60 hover:text-primary" title="Attach file">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>

                    <!-- Input Field -->
                    <input
                        type="text"
                        wire:model="userChat"
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
