<!-- User Header -->
<header class="bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between py-4">
            <!-- Logo -->
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 text-primary font-bold text-2xl">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-graduation-cap text-lg"></i>
                </div>
                <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">SchoolLend</span>
            </a>
            
            <!-- Navigation Menu -->
            <nav class="hidden md:flex items-center gap-2">
                <a href="{{ route('user.dashboard') }}" 
                   class="px-4 py-2 font-semibold rounded-xl transition-all {{ request()->routeIs('user.dashboard') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-lg' : 'text-gray-600 hover:text-primary hover:bg-primary/10' }}">
                    <i class="fas fa-home mr-2"></i>Beranda
                </a>
                <a href="{{ route('user.pinjaman-saya') }}" 
                   class="px-4 py-2 font-semibold rounded-xl transition-all {{ request()->routeIs('user.pinjaman-saya') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-lg' : 'text-gray-600 hover:text-primary hover:bg-primary/10' }}">
                    <i class="fas fa-book-open mr-2"></i>Pinjaman Saya
                </a>
                <a href="{{ route('user.riwayat') }}" 
                   class="px-4 py-2 font-semibold rounded-xl transition-all {{ request()->routeIs('user.riwayat') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-lg' : 'text-gray-600 hover:text-primary hover:bg-primary/10' }}">
                    <i class="fas fa-history mr-2"></i>Riwayat
                </a>
                <a href="{{ route('user.profile') }}" 
                   class="px-4 py-2 font-semibold rounded-xl transition-all {{ request()->routeIs('user.profile') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-lg' : 'text-gray-600 hover:text-primary hover:bg-primary/10' }}">
                    <i class="fas fa-user mr-2"></i>Profile
                </a>
            </nav>
            
            <!-- User Profile & Mobile Menu -->
            <div class="flex items-center gap-4">
                <!-- Notifications (if any) -->
                <button class="relative p-2 text-gray-500 hover:text-primary hover:bg-primary/10 rounded-full transition-all" 
                        onclick="openNotificationModal()"
                        aria-label="Notifications">
                    <i class="fas fa-bell text-xl"></i>
                    <span id="notificationBadge" 
                          class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full items-center justify-center text-[10px] font-medium" 
                          style="display: none;">0</span>
                </button>
                
                <!-- User Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <div class="flex items-center gap-3 cursor-pointer" @click="open = !open">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-semibold shadow-lg">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1) . (strpos(Auth::user()->name, ' ') ? substr(Auth::user()->name, strpos(Auth::user()->name, ' ') + 1, 1) : '')) }}
                        </div>
                        <div class="hidden lg:block">
                            <h3 class="font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-gray-600">SMAN 1 Cikalong Wetan</p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-sm transition-transform" 
                           :class="{ 'transform rotate-180': open }"></i>
                    </div>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition
                         @click.away="open = false"
                         class="absolute right-0 top-16 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <a href="{{ route('user.profile') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                            <i class="fas fa-user mr-3 w-4 text-center"></i>
                            Profile Saya
                        </a>
                        
                        <a href="{{ route('user.pinjaman-saya') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                            <i class="fas fa-book mr-3 w-4 text-center"></i>
                            Pinjaman Aktif
                        </a>
                        
                        <a href="{{ route('user.riwayat') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                            <i class="fas fa-history mr-3 w-4 text-center"></i>
                            Riwayat Peminjaman
                        </a>
                        
                        <div class="border-t border-gray-100 mt-2 pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-800 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3 w-4 text-center"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Menu Toggle -->
                <button class="md:hidden p-2 text-gray-500 hover:text-primary hover:bg-primary/10 rounded-lg transition-all" 
                        @click="$refs.mobileMenu.classList.toggle('hidden')"
                        aria-label="Toggle Mobile Menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Navigation Menu -->
        <div x-ref="mobileMenu" class="hidden md:hidden border-t border-gray-200 py-4">
            <div class="flex flex-col space-y-2">
                <a href="{{ route('user.dashboard') }}" 
                   class="flex items-center px-3 py-2 rounded-lg transition-all {{ request()->routeIs('user.dashboard') ? 'text-primary bg-primary/10 font-semibold' : 'text-gray-600 hover:text-primary hover:bg-gray-50' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i>Beranda
                </a>
                <a href="{{ route('user.pinjaman-saya') }}" 
                   class="flex items-center px-3 py-2 rounded-lg transition-all {{ request()->routeIs('user.pinjaman-saya') ? 'text-primary bg-primary/10 font-semibold' : 'text-gray-600 hover:text-primary hover:bg-gray-50' }}">
                    <i class="fas fa-book-open mr-3 w-5 text-center"></i>Pinjaman Saya
                </a>
                <a href="{{ route('user.riwayat') }}" 
                   class="flex items-center px-3 py-2 rounded-lg transition-all {{ request()->routeIs('user.riwayat') ? 'text-primary bg-primary/10 font-semibold' : 'text-gray-600 hover:text-primary hover:bg-gray-50' }}">
                    <i class="fas fa-history mr-3 w-5 text-center"></i>Riwayat
                </a>
                <a href="{{ route('user.profile') }}" 
                   class="flex items-center px-3 py-2 rounded-lg transition-all {{ request()->routeIs('user.profile') ? 'text-primary bg-primary/10 font-semibold' : 'text-gray-600 hover:text-primary hover:bg-gray-50' }}">
                    <i class="fas fa-user mr-3 w-5 text-center"></i>Profile
                </a>
            </div>
        </div>
    </div>
</header> 