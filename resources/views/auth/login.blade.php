<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SchoolLend</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#8B5CF6',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        pulse: {
                            '0%, 100%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.05)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        
        input:focus + label, 
        input:not(:placeholder-shown) + label {
            transform: translateY(-10px) scale(0.9);
            @apply text-primary;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Floating decorative elements -->
    <div class="absolute -top-20 -left-20 w-64 h-64 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full filter blur-xl animate-float"></div>
    <div class="absolute bottom-10 right-10 w-40 h-40 bg-gradient-to-br from-yellow-100/30 to-pink-100/30 rounded-full filter blur-lg animate-[float_6s_ease-in-out_inverse]"></div>
    
    <!-- Main login card -->
    <div class="w-full max-w-md bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-100 overflow-hidden transform transition-all duration-500 animate-slide-up relative z-10 hover:shadow-2xl">
        <!-- Header section with gradient -->
        <div class="bg-gradient-to-r from-primary to-secondary p-6 text-center relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-5 -left-5 w-20 h-20 bg-white/10 rounded-full"></div>
            
            <div class="relative z-10">
                <div class="flex justify-center mb-4 transform hover:scale-110 transition duration-300">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-primary shadow-lg animate-pulse-slow">
                        <i class="fas fa-graduation-cap text-xl"></i>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-white animate-fade-in">Masuk ke SchoolLend</h1>
                <p class="text-white/90 text-sm mt-1 animate-[fade-in_1s_ease]">SMAN 1 Cikalong Wetan</p>
            </div>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 px-6 pt-4 animate-[fade-in_0.5s_ease]" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="p-6">
            @csrf

            <!-- Email Input -->
            <div class="mb-6 relative animate-[fade-in_0.6s_ease]">
                <x-input-label for="email" :value="__('Email')" class="block text-gray-700 font-medium mb-2" />
                <x-text-input id="email" class="block mt-1 w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/30 transition shadow-sm hover:shadow-md" 
                            type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
            </div>

            <!-- Password Input -->
            <div class="mb-6 relative animate-[fade-in_0.8s_ease]">
                <x-input-label for="password" :value="__('Password')" class="block text-gray-700 font-medium mb-2" />
                <x-text-input id="password" class="block mt-1 w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/30 transition shadow-sm hover:shadow-md"
                            type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
            </div>

            <!-- Remember Me & Forgot Password -->
            {{-- <div class="flex items-center justify-between mb-6 animate-[fade-in_1s_ease]">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary/50 shadow-sm hover:shadow transition" name="remember">
                    <span class="ms-2 text-sm text-gray-600 hover:text-gray-800 transition">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-primary/80 transition transform hover:-translate-y-0.5">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div> --}}

            <!-- Login Button -->
            <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary text-white py-3 px-4 rounded-xl font-medium hover:from-primary/90 hover:to-secondary/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 animate-[fade-in_1.2s_ease]">
                {{ __('Log in') }} <i class="fas fa-sign-in-alt ml-2"></i>
            </button>

            <!-- Register Link -->
            {{-- @if (Route::has('register'))
                <div class="mt-6 text-center text-sm text-gray-600 animate-[fade-in_1.4s_ease]">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-primary font-medium hover:text-primary/80 transition border-b border-transparent hover:border-primary/50">
                        Daftar sekarang <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
            @endif --}}
        </form>
    </div>

    <!-- Back to home link -->
    <a href="/" class="absolute top-6 left-6 text-gray-600 hover:text-primary transition transform hover:scale-105 animate-[fade-in_1s_ease] flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
    </a>
</body>
</html>