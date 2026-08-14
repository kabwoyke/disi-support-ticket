<?php

use Livewire\Component;

new class extends Component
{
    public array $notifications = [];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = auth()->guard('support')->user();

        if ($user) {
            $this->notifications = $user->notifications()
                ->latest()
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'message' => $notification->data['message'] ?? 'New notification',
                        'read_at' => $notification->read_at,
                        'created_at' => $notification->created_at->diffForHumans(),
                    ];
                })
                ->toArray();
        }
    }

    public function getListeners()
    {
        $supportTeamId = auth()->guard('support')->id();

        return [
            "echo-private:App.Models.SupportTeam.{$supportTeamId},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'handleNewNotification',
            "echo-private:App.Models.SupportTeam.{$supportTeamId},NotificationSent" => 'handleNewNotification',
        ];
    }

    public function handleNewNotification($event)
    {
        $message = $event['message'] ?? $event['data']['message'] ?? 'New notification received';

        // Prepend new broadcasted notification directly to the array
        array_unshift($this->notifications, [
            'id' => $event['id'] ?? (string) Str::uuid(),
            'message' => $message,
            'read_at' => null,
            'created_at' => 'Just now',
        ]);
    }

    public function markAsRead(string $id)
    {
        $user = auth()->guard('support')->user();

        if ($user) {
            $notification = $user->notifications()->where('id', $id)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }

        // Update local state
        foreach ($this->notifications as &$item) {
            if ($item['id'] === $id) {
                $item['read_at'] = now();
                break;
            }
        }
    }

    public function markAllAsRead()
    {
        $user = auth()->guard('support')->user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        foreach ($this->notifications as &$item) {
            $item['read_at'] = now();
        }
    }

    public function render()
    {
        return view("pages::ict.features.⚡view-notification")->layout("layouts::support");
    }
};
?>

<div class="max-w-4xl mx-auto p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-base-200 pb-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Notifications</h1>
            <p class="text-xs text-base-content/60 mt-1">
                Real-time updates and ticket dispatches.
            </p>
        </div>

        @if(collect($notifications)->whereNull('read_at')->count() > 0)
            <button
                wire:click="markAllAsRead"
                class="btn btn-sm btn-outline btn-primary"
            >
                Mark all as read
            </button>
        @endif
    </div>

    <!-- Notification List -->
    <div class="space-y-3">
        @forelse($notifications as $notification)
            <div
                wire:key="notification-{{ $notification['id'] }}"
                class="p-4 rounded-box border transition-all flex items-start justify-between gap-4 {{ $notification['read_at'] ? 'bg-base-100 border-base-200 opacity-75' : 'bg-primary/5 border-primary/20 shadow-sm' }}"
            >
                <div class="flex items-start gap-3">
                    <!-- Status Icon -->
                    <div class="mt-0.5">
                        @if($notification['read_at'])
                            <svg class="w-5 h-5 text-base-content/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        @else
                            <span class="relative flex h-3 w-3 mt-1">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                            </span>
                        @endif
                    </div>

                    <!-- Message Body -->
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-base-content {{ $notification['read_at'] ? '' : 'font-semibold' }}">
                            {{ $notification['message'] }}
                        </p>
                        <span class="text-xs text-base-content/50 block">
                            {{ $notification['created_at'] }}
                        </span>
                    </div>
                </div>

                <!-- Mark single as read button -->
                @if(!$notification['read_at'])
                    <button
                        wire:click="markAsRead('{{ $notification['id'] }}')"
                        class="btn btn-xs btn-ghost text-primary hover:bg-primary/10"
                        title="Mark as read"
                    >
                        Mark as read
                    </button>
                @endif
            </div>
        @empty
            <div class="text-center py-12 border border-dashed border-base-300 rounded-box">
                <svg class="w-12 h-12 mx-auto text-base-content/30 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9" />
                </svg>
                <p class="text-sm font-medium text-base-content/60">No notifications found</p>
            </div>
        @endforelse
    </div>
</div>
