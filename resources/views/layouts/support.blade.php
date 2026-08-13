<x-layouts::app>
  <div class="bg-base-100 flex h-screen overflow-hidden text-base-content">

        <!-- ICT Sidebar (Uses base-200 for dark mode compatibility) -->
        <aside class="bg-base-200 flex h-screen w-64 flex-col justify-between p-4 shrink-0 border-r border-base-300" id="sidebar">
            <div class="space-y-6">

                <!-- Logo -->
                <div class="flex items-center justify-center py-2" id="logo">
                    <img src="/disi-logo.avif" class="h-auto w-28 object-contain" alt="DISI Logo">
                </div>

                <!-- Navigation -->
                <nav id="sidebar-items-container">
                    <div class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-base-content/60">ICT Operations</div>
                    <ul class="space-y-1">
                        <!-- Assigned / Active Queue -->
                        <li>
                            <a wire:navigate href="{{ route('ict-dashboard') }}"
                               class="flex items-center justify-between rounded-btn px-4 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('ict-dashboard') ? 'bg-primary text-primary-content shadow-sm' : 'hover:bg-base-300 text-base-content' }}">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                    </svg>

                                    <span>Home</span>
                                </div>

                            </a>
                        </li>

                        <!-- Floor Plan & Desk Map -->
                        <li>
                            <a wire:navigate href="{{ route('manage-desk') }}"
                               class="flex items-center gap-3 rounded-btn active:bg-primary-600 px-4 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('manage-desk') ? 'bg-primary text-primary-content shadow-sm' : 'hover:bg-base-300 text-base-content' }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                <span>Manage Desk</span>
                            </a>
                        </li>

                        <!-- Asset / Hardware Inventory -->
                        <li>
                            <a wire:navigate href="{{ route('manage-asset') }}"
                               class="flex items-center gap-3 rounded-btn px-4 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('manage-asset') ? 'bg-primary text-primary-content shadow-sm' : 'hover:bg-base-300 text-base-content' }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                                <span>Hardware & Assets</span>
                            </a>
                        </li>
                    </ul>

                </nav>
            </div>

            <!-- ICT Officer Info & Logout -->
            <div class="border-base-300 border-t pt-4 space-y-3">
                <div class="flex items-center gap-3 px-2 py-1.5 bg-base-300/50 rounded-btn">
                    <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content rounded-full w-8 h-8 flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'Tech', 0, 2)) }}
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold truncate text-base-content">{{ auth()->guard('support')->user()->first_name . " " . auth()->guard('support')->user()->last_name ?? 'ICT Support Officer' }}</p>
                        <p class="text-[10px] text-base-content/70 truncate">ICT On-Duty</p>
                    </div>
                </div>

                <form action="{{ route('support-logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="w-full hover:bg-error/10 text-error flex items-center gap-3 rounded-btn px-4 py-2 text-sm font-medium transition-colors text-left"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Livewire View Content Area -->
        <main class="flex-1 overflow-y-auto bg-base-300/30 p-6 md:p-8" id="main-content">
            <div class="max-w-6xl mx-auto space-y-6">
                {{ $slot }}
            </div>
        </main>

    </div>
</x-layouts::app>
