<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CSRF Test - Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">CSRF Token Test</h1>
        
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">CSRF Token Value:</h2>
            <p class="text-sm text-gray-600 break-all">{{ csrf_token() }}</p>
        </div>
        
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Session ID:</h2>
            <p class="text-sm text-gray-600">{{ session()->getId() }}</p>
        </div>
        
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">User Authenticated:</h2>
            <p class="text-sm text-gray-600">{{ auth()->check() ? 'Yes' : 'No' }}</p>
        </div>
        
        @if(auth()->check())
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">User Info:</h2>
            <p class="text-sm text-gray-600">Name: {{ auth()->user()->name }}</p>
            <p class="text-sm text-gray-600">Email: {{ auth()->user()->email }}</p>
        </div>
        @endif
        
        <!-- Test Form -->
        <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ auth()->user()->name ?? 'Test User' }}" 
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ auth()->user()->email ?? 'test@example.com' }}" 
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                Test CSRF Token
            </button>
        </form>
        
        @if(session('success'))
        <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
        @endif
        
        @if($errors->any())
        <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</body>
</html> 