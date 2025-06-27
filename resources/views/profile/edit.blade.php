<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight bg-gradient-to-r from-primary to-secondary px-4 py-3 rounded-lg shadow-md">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Back to Dashboard Buttons -->
            <div class="p-4 sm:p-8 bg-white/80 backdrop-blur-sm shadow-lg sm:rounded-lg border border-gray-200">
                <div class="max-w-xl flex space-x-4">
                    @if(auth()->user()->hasRole('admin'))
                        <x-primary-button class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 focus:ring-primary/20 shadow-md">
                            <a href="{{ route('admin.dashboard') }}" class="text-white flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Back to Owner Dashboard
                            </a>
                        </x-primary-button>
                    @elseif(auth()->user()->hasRole('pengguna'))
                        <x-primary-button class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 focus:ring-primary/20 shadow-md">
                            <a href="{{ route('user.dashboard') }}" class="text-white flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Back to Customer Dashboard
                            </a>
                        </x-primary-button>
                    @endif
                </div>
            </div>

            <!-- Profile Information Form Only -->
            <div class="p-4 sm:p-8 bg-white/80 backdrop-blur-sm shadow-lg sm:rounded-lg border border-gray-200">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>