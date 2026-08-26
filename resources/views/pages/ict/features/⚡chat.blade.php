<?php

use Livewire\Component;
use App\Events\AdminChat;
use App\Models\ChatMessage;
use App\Models\Chat;
use Livewire\Attributes\Url;

new class extends Component
{
    #[Url]
    public $ticket = '';

    public array $messages = [];
    public string $adminReply = '';

    public function mount(): void
    {
        if (!$this->ticket) {
            $this->ticket = request()->query('ticket', '');
        }

        $this->loadMessages();
    }

    /**
     * Resolve the active Chat record, creating one if missing.
     */
    protected function getActiveChat(): ?Chat
    {
        if (!$this->ticket) {
            return null;
        }

        return Chat::firstOrCreate(
            ['id' => $this->ticket],
            ['support_id' => auth('support')->id()]
        );
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
            "echo-private:admin-chat.{$this->ticket},.UserChat" => 'recieveUserMessage',
        ];
    }

    public function recieveUserMessage(array $event): void
    {
        if (!empty($event['userText'])) {
            $this->messages[] = [
                'id' => uniqid('msg_'),
                'sender' => 'user',
                'text' => $event['userText'],
                'time' => now()->timezone('Africa/Nairobi')->format('g:i A'),
            ];
        }
    }

    public function sendAdminReply(): void
    {
        $this->validate([
            'adminReply' => 'required|string|min:1',
        ]);

        $chat = $this->getActiveChat();

        if (!$chat) {
            return;
        }

        // 1. Save directly to DB using guaranteed Chat ID
        $savedMessage = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'App\Models\SupportTeam',
            'sender_id' => auth('support')->id(),
            'message' => $this->adminReply,
        ]);

        // 2. Broadcast to user
        AdminChat::dispatch($this->adminReply, (int) $chat->id);

        // 3. Append to UI
        $this->messages[] = [
            'id' => 'msg_' . $savedMessage->id,
            'sender' => 'admin',
            'text' => $this->adminReply,
            'time' => $savedMessage->created_at->timezone('Africa/Nairobi')->format('g:i A'),
        ];

        $this->reset('adminReply');
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
                    {{-- <span class="badge badge-primary text-white font-semibold">4 Open</span> --}}
                </div>
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search conversation or user..."
                        class="input input-sm input-bordered w-full pl-9 text-xs"
                    />
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-base-content/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto divide-y divide-base-200">
               <!-- Sidebar Conversation Item -->
<button class="w-full p-3 text-left flex items-start gap-3 bg-base-200/60 border-l-4 border-primary transition-all">
    <div class="avatar online shrink-0">
        <div class="w-10 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRoeZOBBidNgNcGNpV6v4cwHkHPfhp98-J75q0kd2z1Qv9Da0Cimld3WA&s=10" alt="profile" />
        </div>
    </div>
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-bold text-base-content truncate">Mary Atieno</h3>
            <!-- Dynamic timestamp from the latest message or default fallback -->
            <span class="text-[10px] text-base-content/50">
                {{ end($messages)['time'] ?? '10:42 AM' }}
            </span>
        </div>
        <span class="font-mono text-[10px] font-semibold text-primary block">#T-00104</span>

        <!-- Dynamic message text preview -->
        <p class="text-xs text-base-content/70 truncate mt-0.5">
            {{ end($messages)['text'] ?? "Support Needed" }}
        </p>
    </div>
</button>
            </div>
        </div>

        <!-- Main Chat Window -->
        <div class="flex-1 bg-base-100 rounded-box border border-base-200 shadow-sm flex flex-col overflow-hidden">
            <!-- Chat Top Bar / Header -->
            <div class="p-4 border-b border-base-200 flex items-center justify-between bg-base-100">
                <div class="flex items-center gap-3">
                    <div class="avatar online">
                        <div class="w-10 rounded-full">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRoeZOBBidNgNcGNpV6v4cwHkHPfhp98-J75q0kd2z1Qv9Da0Cimld3WA&s=10" alt="Avatar" />
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold text-base-content">Mary Atieno</h2>
                            <span class="font-mono text-xs text-primary font-semibold">#T-00104</span>
                        </div>
                        <p class="text-xs text-base-content/60">Quality Control &bull; Desk 04</p>
                    </div>
                </div>

                <!-- Action Badges -->
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
            <div
                x-data="{ scrollToBottom() { $el.scrollTop = $el.scrollHeight } }"
                x-init="scrollToBottom()"
                x-effect="scrollToBottom()"
                class="flex-1 overflow-y-auto p-4 space-y-4 bg-base-200/20"
            >
                <div class="divider text-[10px] text-base-content/40 font-semibold uppercase">Today</div>

                @forelse ($messages as $msg)
                    @if ($msg['sender'] === 'user')
                        <!-- User Incoming Bubble -->
                        <div wire:key="{{ $msg['id'] }}" class="chat chat-start transition-all duration-300">
                            <div class="chat-image avatar">
                                <div class="w-8 rounded-full">
                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRoeZOBBidNgNcGNpV6v4cwHkHPfhp98-J75q0kd2z1Qv9Da0Cimld3WA&s=10" alt="User" />
                                </div>
                            </div>
                            <div class="chat-header text-xs text-base-content/60 mb-1">
                                Mary Atieno
                                <time class="text-[10px] opacity-50 ml-1">{{ $msg['time'] }}</time>
                            </div>
                            <div class="chat-bubble chat-bubble-neutral text-xs leading-relaxed">
                                {{ $msg['text'] }}
                            </div>
                        </div>
                    @else
                        <!-- Admin Outgoing Bubble -->
                        <div wire:key="{{ $msg['id'] }}" class="chat chat-end transition-all duration-300">
                            <div class="chat-image avatar">
                                <div class="w-8 rounded-full">
                                    <img src="https://media.licdn.com/dms/image/v2/D4E03AQGWlFXHRfXxDw/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1674839788606?e=2147483647&v=beta&t=fartG9Fh-rvivnWKxgT4xKADm9jgjdLXxyK3BUjHiOI" alt="profile" />
                                </div>
                            </div>
                            <div class="chat-header text-xs text-base-content/60 mb-1">
                                Support Admin (You)
                                <time class="text-[10px] opacity-50 ml-1">{{ $msg['time'] }}</time>
                            </div>
                            <div class="chat-bubble chat-bubble-primary text-white text-xs leading-relaxed">
                                {{ $msg['text'] }}
                            </div>
                            <div class="chat-footer text-[10px] opacity-50 mt-1">Delivered</div>
                        </div>
                    @endif
                @empty
                    <div class="text-center text-xs text-base-content/40 py-8">
                        No messages received yet. Broadcasted messages will appear here in real-time.
                    </div>
                @endforelse
            </div>

            <!-- Chat Input Footer -->
            <div class="p-3 bg-base-100 border-t border-base-200">
                <form wire:submit.prevent="sendAdminReply" class="flex items-center gap-2">
                    <button type="button" class="btn btn-ghost btn-circle btn-sm text-base-content/60" title="Attach File">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>

                    <input
                        wire:model="adminReply"
                        type="text"
                        placeholder="Type your reply as support admin..."
                        class="input input-sm input-bordered flex-1 text-xs focus:outline-none focus:border-primary"
                    />

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
