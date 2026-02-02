<header class="bg-white border-b border-luxury-border sticky top-0 z-40">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Page Title / Breadcrumb -->
        <div>
            <h2 class="text-2xl font-serif font-semibold text-primary">
                @yield('page-title', 'Dashboard')
            </h2>
            <nav class="flex mt-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm text-gray-500">
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="hidden md:block relative" x-data="{ open: false }">
                <input type="text" 
                       @focus="open = true" 
                       @blur="setTimeout(() => open = false, 200)"
                       placeholder="Search..." 
                       class="w-64 px-4 py-2 pl-10 bg-luxury-bg border border-luxury-border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent/50 focus:border-accent">
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-accent transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- Notification Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-cloak
                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-luxury-border overflow-hidden">
                    <div class="p-4 border-b border-luxury-border">
                        <h3 class="font-semibold text-gray-800">Notifications</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <a href="#" class="block px-4 py-3 hover:bg-luxury-bg transition-colors border-b border-luxury-border">
                            <p class="text-sm font-medium text-gray-800">New reservation for Room 302</p>
                            <p class="text-xs text-gray-500 mt-1">2 minutes ago</p>
                        </a>
                        <a href="#" class="block px-4 py-3 hover:bg-luxury-bg transition-colors border-b border-luxury-border">
                            <p class="text-sm font-medium text-gray-800">Payment received - $450.00</p>
                            <p class="text-xs text-gray-500 mt-1">1 hour ago</p>
                        </a>
                        <a href="#" class="block px-4 py-3 hover:bg-luxury-bg transition-colors">
                            <p class="text-sm font-medium text-gray-800">Room 105 needs maintenance</p>
                            <p class="text-xs text-gray-500 mt-1">3 hours ago</p>
                        </a>
                    </div>
                    <div class="p-3 bg-luxury-bg text-center">
                        <a href="#" class="text-sm text-accent hover:text-accent-light font-medium">View all notifications</a>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-luxury-bg transition-colors">
                    <div class="w-10 h-10 bg-gradient-to-br from-accent to-accent-light rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr(auth()->user()->full_name ?? 'AD', 0, 2) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->full_name ?? 'Admin User' }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">{{ auth()->user()->role ?? 'Administrator' }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- User Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-cloak
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-luxury-border overflow-hidden">
                    <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-luxury-bg transition-colors">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Profile</span>
                        </div>
                    </a>
                    <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-luxury-bg transition-colors">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Settings</span>
                        </div>
                    </a>
                    <hr class="border-luxury-border">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Logout</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>