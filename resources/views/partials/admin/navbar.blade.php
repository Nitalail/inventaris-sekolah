<!-- Admin Navbar -->
<nav class="fixed top-0 right-0 left-0 lg:left-64 h-16 glass border-b border-gray-200/70 shadow-sm z-20 px-6 flex items-center justify-between transition-all duration-300 ease-in-out">
    <!-- Mobile Sidebar Toggle -->
    <button class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none transition-slow" 
            id="sidebar-toggle"
            aria-label="Toggle Sidebar">
        <i class="fas fa-bars text-xl"></i>
    </button>
    
    <!-- Search Bar (Desktop) -->
    <div class="hidden lg:block relative w-96">
        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" 
               class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow" 
               placeholder="Cari barang, transaksi, atau kategori..."
               x-data="{ value: '' }"
               x-model="value"
               @keydown.enter="performSearch(value)">
    </div>
    
    <!-- Right Side Actions -->
    <div class="flex items-center space-x-4">
        <!-- Quick Actions (Desktop) -->
        <div class="hidden md:flex items-center space-x-2">
            @if(request()->routeIs('admin.barang.*'))
                <a href="{{ route('admin.barang.create') }}" 
                   class="inline-flex items-center px-3 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary/90 transition-slow">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Barang
                </a>
            @elseif(request()->routeIs('admin.transaksi.*'))
                <a href="{{ route('admin.transaksi.create') }}" 
                   class="inline-flex items-center px-3 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary/90 transition-slow">
                    <i class="fas fa-plus mr-2"></i>
                    Transaksi Baru
                </a>
            @elseif(request()->routeIs('admin.laporan.*'))
                <button onclick="exportReport()" 
                        class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-slow">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </button>
            @endif
        </div>
        
        <!-- Notifications Bell -->
        @include('partials.admin-notifications')
        
        <!-- Mobile Search Toggle -->
        <button class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none transition-slow" 
                @click="$refs.mobileSearch.classList.toggle('hidden')"
                aria-label="Toggle Search">
            <i class="fas fa-search text-xl"></i>
        </button>
    </div>
    
    <!-- Mobile Search Bar -->
    <div x-ref="mobileSearch" class="hidden lg:hidden absolute top-16 left-0 right-0 bg-white border-b border-gray-200 p-4">
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" 
                   class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow" 
                   placeholder="Cari barang, transaksi, atau kategori..."
                   x-data="{ value: '' }"
                   x-model="value"
                   @keydown.enter="performSearch(value)">
        </div>
    </div>
</nav>

@push('scripts')
<script>
    function performSearch(query) {
        if (!query.trim()) return;
        
        // Get current route to determine search context
        const currentPath = window.location.pathname;
        let searchUrl = '';
        
        if (currentPath.includes('/admin/barang')) {
            searchUrl = '{{ route("admin.barang.index") }}';
        } else if (currentPath.includes('/admin/transaksi')) {
            searchUrl = '{{ route("admin.transaksi.index") }}';
        } else if (currentPath.includes('/admin/pengguna')) {
            searchUrl = '{{ route("admin.pengguna.index") }}';
        } else {
            // Default to global search or dashboard
            searchUrl = '{{ route("dashboard") }}';
        }
        
        // Append search parameter
        const url = new URL(searchUrl, window.location.origin);
        url.searchParams.set('search', query);
        
        window.location.href = url.toString();
    }
    
    // Export function for reports
    function exportReport() {
        if (typeof window.exportCurrentReport === 'function') {
            window.exportCurrentReport();
        } else {
            // Default export action
            window.location.href = '{{ route("admin.laporan.export") }}';
        }
    }
</script>
@endpush 