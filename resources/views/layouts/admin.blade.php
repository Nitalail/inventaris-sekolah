@extends('layouts.master')

@section('title', isset($title) ? $title . ' - Admin Dashboard' : 'Admin Dashboard')

@section('body-class', 'font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100')

@section('body-attributes', 'x-data="adminApp()"')

@section('styles')
<style>
    [x-cloak] { display: none !important; }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 6px;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 6px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Glassmorphism effect */
    .glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    
    /* Table row hover */
    .table-row-hover:hover {
        @apply bg-gray-50/50;
    }
    
    /* Smooth transitions */
    .transition-slow {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
@endsection

@section('content')
    <!-- Admin Sidebar -->
    @include('partials.admin.sidebar')
    
    <!-- Admin Navbar -->
    @include('partials.admin.navbar')
    
    <!-- Main Content -->
    <main class="pt-16 lg:pl-64 min-h-screen transition-all duration-300 ease-in-out">
        <div class="p-6">
            <!-- Page Header -->
            @if(isset($header) || isset($breadcrumb))
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                    <div>
                        @if(isset($header))
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $header }}</h1>
                        @endif
                        @if(isset($description))
                            <p class="text-gray-600">{{ $description }}</p>
                        @endif
                        @if(isset($breadcrumb))
                            <nav class="flex mt-2" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                    @foreach($breadcrumb as $item)
                                        <li class="inline-flex items-center">
                                            @if(!$loop->last)
                                                <a href="{{ $item['url'] }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary">
                                                    @if($loop->first)
                                                        <i class="fas fa-home mr-2"></i>
                                                    @endif
                                                    {{ $item['title'] }}
                                                </a>
                                                <i class="fas fa-chevron-right mx-2 text-gray-400 text-xs"></i>
                                            @else
                                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $item['title'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </nav>
                        @endif
                    </div>
                    @if(isset($headerActions))
                        <div class="mt-4 md:mt-0">
                            {{ $headerActions }}
                        </div>
                    @endif
                </div>
            @endif
            
            <!-- Page Content -->
            @yield('page-content')
        </div>
    </main>
    
    <!-- Admin Notifications -->
    @include('partials.notification-system')
@endsection

@section('scripts')
<script>
    function adminApp() {
        return {
            sidebarOpen: false,
            init() {
                // Initialize admin app
                this.setupSidebar();
            },
            
            setupSidebar() {
                // Mobile sidebar toggle
                const sidebarToggle = document.getElementById('sidebar-toggle');
                const sidebar = document.getElementById('sidebar');
                
                if (sidebarToggle && sidebar) {
                    sidebarToggle.addEventListener('click', () => {
                        this.sidebarOpen = !this.sidebarOpen;
                        sidebar.classList.toggle('-translate-x-full');
                    });
                }
                
                // Close sidebar on outside click (mobile)
                document.addEventListener('click', (e) => {
                    if (window.innerWidth < 1024 && this.sidebarOpen && 
                        !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        this.sidebarOpen = false;
                        sidebar.classList.add('-translate-x-full');
                    }
                });
            }
        }
    }
</script>
@endsection 