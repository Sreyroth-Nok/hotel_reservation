<aside class="fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-primary to-primary-dark text-white transform transition-transform duration-300 lg:translate-x-0 -translate-x-full" 
       x-data="{ open: false }" 
       :class="{ '-translate-x-full lg:translate-x-0': !open }">
    
    <!-- Logo Section -->
    <div class="p-6 border-b border-accent/20">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center text-2xl">
                🏨
            </div>
            <div>
                <h1 class="text-2xl font-serif font-semibold text-accent tracking-wide">Luxe Stay</h1>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Hotel Management</p>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-120px)]">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-accent/10 text-accent border-l-4 border-accent' : 'text-gray-300 hover:bg-white/5 hover:text-accent' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="{{ route('reservations.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('reservations.*') ? 'bg-accent/10 text-accent border-l-4 border-accent' : 'text-gray-300 hover:bg-white/5 hover:text-accent' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="font-medium">Reservations</span>
        </a>

        <a href="{{ route('rooms.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('rooms.*') ? 'bg-accent/10 text-accent border-l-4 border-accent' : 'text-gray-300 hover:bg-white/5 hover:text-accent' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span class="font-medium">Rooms</span>
        </a>

        <a href="{{ route('payments.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('payments.*') ? 'bg-accent/10 text-accent border-l-4 border-accent' : 'text-gray-300 hover:bg-white/5 hover:text-accent' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            <span class="font-medium">Payments</span>
        </a>

        {{-- @if(auth()->user()->role === 'admin')
        <a href="{{ route('users.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-accent/10 text-accent border-l-4 border-accent' : 'text-gray-300 hover:bg-white/5 hover:text-accent' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span class="font-medium">Users</span>
        </a>
        @endif --}}

        <div class="pt-4 mt-4 border-t border-accent/20">
            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/5 hover:text-accent transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">Settings</span>
            </a>
        </div>
    </nav>
</aside>

<!-- Mobile Menu Button -->
<button @click="open = !open" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-primary text-white rounded-lg">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>