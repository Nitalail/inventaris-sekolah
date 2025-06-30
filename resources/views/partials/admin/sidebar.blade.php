<!-- Admin Sidebar -->
<aside class="fixed inset-y-0 left-0 z-30 w-64 glass shadow-lg border-r border-gray-200/70 transition-all duration-300 ease-in-out transform -translate-x-full lg:translate-x-0" id="sidebar">
    <div class="flex flex-col h-full">
        <!-- Sidebar Header -->
        <div class="p-6 pb-4 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg">
                IS
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Inventaris Sekolah</h2>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 overflow-y-auto">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-slow {{ request()->routeIs('dashboard') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-100/50' }}">
                        <i class="fas fa-th-large w-5 mr-3 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.barang.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-slow {{ request()->routeIs('admin.barang.*') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-100/50' }}">
                        <i class="fas fa-box w-5 mr-3 text-center"></i>
                        <span>Barang</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.transaksi.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-slow {{ request()->routeIs('admin.transaksi.*') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-100/50' }}">
                        <i class="fas fa-exchange-alt w-5 mr-3 text-center"></i>
                        <span>Transaksi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ruangan.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-slow {{ request()->routeIs('admin.ruangan.*') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-100/50' }}">
                        <i class="fas fa-warehouse w-5 mr-3 text-center"></i>
                        <span>Ruangan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.kategori.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-slow {{ request()->routeIs('admin.kategori.*') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-100/50' }}">
                        <i class="fas fa-tags w-5 mr-3 text-center"></i>
                        <span>Kategori</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pengguna.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-slow {{ request()->routeIs('admin.pengguna.*') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-100/50' }}">
                        <i class="fas fa-users w-5 mr-3 text-center"></i>
                        <span>Pengguna</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.laporan.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-slow {{ request()->routeIs('admin.laporan.*') ? 'text-white bg-gradient-to-r from-primary to-secondary shadow-sm' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-100/50' }}">
                        <i class="fas fa-file-alt w-5 mr-3 text-center"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- User Profile Section -->
        <div class="p-4 border-t border-gray-200/70" x-data="{ open: false }">
            <div class="flex items-center space-x-3 cursor-pointer group" @click="open = !open">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center text-primary-600 font-semibold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 group-hover:text-gray-600" 
                   :class="{ 'transform rotate-180': open }"></i>
            </div>

            <div x-show="open" x-transition class="mt-2 space-y-1 pl-13">
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center w-full px-3 py-2 text-sm text-gray-600 hover:text-primary hover:bg-primary/5 rounded-lg transition-slow">
                    <i class="fas fa-user w-4 mr-2 text-center"></i>
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center w-full px-3 py-2 text-sm text-rose-500 hover:text-rose-600 hover:bg-rose-50/30 rounded-lg transition-slow">
                        <i class="fas fa-sign-out-alt w-4 mr-2 text-center text-rose-500"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside> 