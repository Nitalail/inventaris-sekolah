@extends('layouts.master')

@section('title', isset($title) ? $title . ' - SchoolLend' : 'SchoolLend - Sistem Peminjaman Sekolah')

@section('body-class', 'bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen')

@section('body-attributes', 'x-data="userApp()"')

@section('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .skeleton {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        background-color: #e2e8f0;
        border-radius: 0.5rem;
    }
    
    .hero-stats .animate-counter {
        animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    * {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }
</style>
@endsection

@section('content')
    <!-- User Header/Navigation -->
    @include('partials.user.header')
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        <!-- Page Header -->
        @if(isset($pageHeader))
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $pageHeader['title'] ?? 'Dashboard' }}</h1>
                @if(isset($pageHeader['description']))
                    <p class="text-gray-600">{{ $pageHeader['description'] }}</p>
                @endif
                @if(isset($pageHeader['breadcrumb']))
                    <nav class="flex mt-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            @foreach($pageHeader['breadcrumb'] as $item)
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
        @endif
        
        <!-- Page Content -->
        @yield('page-content')
    </main>
    
    <!-- User Footer -->
    @include('partials.user.footer')
    
    <!-- User Modals -->
    @include('partials.user.modals')
@endsection

@section('scripts')
<script>
    // Global state management
    let isPageReady = false;
    
    function userApp() {
        return {
            init() {
                // Add delay to ensure page is fully loaded
                setTimeout(() => {
                    isPageReady = true;
                    this.setupInteractions();
                }, 200);
            },
            
            setupInteractions() {
                // Setup date constraints for date inputs
                this.setupDateConstraints();
                
                // Setup modal interactions
                this.setupModals();
                
                // Setup smooth scrolling
                this.setupSmoothScrolling();
            },
            
            setupDateConstraints() {
                const today = new Date().toISOString().split('T')[0];
                const startDateInputs = document.querySelectorAll('#borrowStartDate');
                
                startDateInputs.forEach(input => {
                    input.min = today;
                    input.addEventListener('change', function() {
                        const correspondingEndDate = document.getElementById('borrowEndDate');
                        
                        if (correspondingEndDate) {
                            const nextDay = new Date(this.value);
                            nextDay.setDate(nextDay.getDate() + 1);
                            correspondingEndDate.min = nextDay.toISOString().split('T')[0];
                            
                            if (correspondingEndDate.value && new Date(correspondingEndDate.value) < nextDay) {
                                correspondingEndDate.value = nextDay.toISOString().split('T')[0];
                            }
                        }
                    });
                });
            },
            
            setupModals() {
                // Modal close on outside click
                document.addEventListener('click', (event) => {
                    const modals = ['notificationModal', 'borrowModal'];
                    
                    modals.forEach(modalId => {
                        const modal = document.getElementById(modalId);
                        if (modal && event.target === modal) {
                            modal.style.display = 'none';
                        }
                    });
                });

                // Modal close on escape key
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        const modals = ['notificationModal', 'borrowModal'];
                        
                        modals.forEach(modalId => {
                            const modal = document.getElementById(modalId);
                            if (modal && modal.style.display === 'flex') {
                                modal.style.display = 'none';
                            }
                        });
                    }
                });
            },
            
            setupSmoothScrolling() {
                // Setup smooth scrolling for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });
            }
        }
    }
    
    // Global functions for user interactions
    window.showToast = function(type, message) {
        const toastContainer = document.getElementById('toastContainer') || document.body;
        const toast = document.createElement('div');
        
        const toastClasses = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            info: 'bg-blue-500 text-white',
            warning: 'bg-yellow-500 text-white'
        };
        
        toast.className = `fixed top-4 right-4 z-50 flex items-center gap-3 p-4 rounded-xl shadow-lg max-w-xs transition-all transform translate-x-full opacity-0 ${toastClasses[type]}`;
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                    type === 'error' ? 'fa-exclamation-circle' : 
                    type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
        
        toast.innerHTML = `
            <i class="fas ${icon} text-xl"></i>
            <div class="flex-1 text-sm">${message}</div>
            <button class="toast-close text-lg" onclick="this.parentElement.remove()">&times;</button>
        `;
        
        toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 100);
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    };
</script>
@endsection 