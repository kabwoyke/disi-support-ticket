
    <x-layouts::app>
    <div class="bg-base-100 flex h-screen overflow-hidden text-base-content">

        <aside class="bg-primary-50 flex h-screen w-64 flex-col justify-between p-4 shrink-0 border-r border-primary-200/50" id="sidebar">
            <div class="space-y-6">

                <div class="flex items-center justify-center py-2" id="logo">
                    <img src="/disi-logo.avif" class="h-auto w-28 object-contain" alt="DISI Logo">
                </div>

                <nav id="sidebar-items-container">
                    <ul class="space-y-1">
                        <li>
                            <a wire:navigate href="{{ route('create-ticket') }}"
                               class="flex items-center gap-3 rounded-btn px-4 py-2.5 text-sm font-medium transition-all {{ request()->routeIs('create-ticket') ? 'bg-primary text-primary-content shadow-sm' : 'hover:bg-primary-100 text-base-content/80' }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Raise a Ticket</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>


            <div class="border-primary-200/60 border-t pt-4 space-y-1">
                <ul class="space-y-1 text-sm font-medium">
                    <li>
                        <a wire:navigate href="{{ route("help-page") }}"
                           class="flex items-center gap-3 rounded-btn px-4 py-2 transition-colors {{ request()->routeIs('help-page') ? 'bg-primary text-primary-content shadow-sm' : 'hover:bg-primary-100 text-base-content/80' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Help</span>
                        </a>
                    </li>
                    <li>
                        <a href=""
                           class="flex items-center gap-3 rounded-btn px-4 py-2 transition-colors {{ request()->routeIs('support') ? 'bg-primary text-primary-content shadow-sm' : 'hover:bg-primary-100 text-base-content/80' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Support</span>
                        </a>
                    </li>
                </ul>


                <form action="{{ route("logout") }}" method="POST">
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


        <main class="flex-1 overflow-y-auto bg-base-200/50 p-6 md:p-8" id="main-content">
            <div class="max-w-5xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
</x-layouts::app>


