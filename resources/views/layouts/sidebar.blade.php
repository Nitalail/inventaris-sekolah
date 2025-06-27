<!-- resources/views/layouts/sidebar.blade.php -->
<aside class="w-64 h-screen bg-white shadow-lg fixed">
    <div class="p-6">
        <h1 class="text-2xl font-bold text-pastel-blue">📦 Inventaris</h1>
    </div>
    <nav class="mt-8">
        <ul class="space-y-2 px-4">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-pastel-light transition {{ request()->routeIs('dashboard') ? 'bg-pastel-light font-bold text-pastel-blue' : 'text-gray-600' }}">
                    🏠 <span class="ml-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('barang.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-pastel-light transition {{ request()->routeIs('barang.*') ? 'bg-pastel-light font-bold text-pastel-blue' : 'text-gray-600' }}">
                    📁 <span class="ml-3">Data Barang</span>
                </a>
            </li>
            <li>
                <a href="{{ route('peminjaman.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-pastel-light transition {{ request()->routeIs('peminjaman.*') ? 'bg-pastel-light font-bold text-pastel-blue' : 'text-gray-600' }}">
                    📋 <span class="ml-3">Data Peminjaman</span>
                </a>
            </li>
            <li>
                <a href="{{ route('pengguna.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-pastel-light transition {{ request()->routeIs('pengguna.*') ? 'bg-pastel-light font-bold text-pastel-blue' : 'text-gray-600' }}">
                    👥 <span class="ml-3">Data Pengguna</span>
                </a>
            </li>
            <li>
                <a href="{{ route('ruangan.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-pastel-light transition {{ request()->routeIs('ruangan.*') ? 'bg-pastel-light font-bold text-pastel-blue' : 'text-gray-600' }}">
                    🏫 <span class="ml-3">Data Ruangan</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
