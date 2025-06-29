<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CSRF Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">CSRF Token Test</h1>
        
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">Current CSRF Token</h2>
            <p class="font-mono text-sm bg-gray-100 p-2 rounded">{{ csrf_token() }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">Form Test</h2>
            <form id="testForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2">Test Input</label>
                    <input type="text" name="test_input" class="w-full border rounded px-3 py-2" placeholder="Enter some text">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Submit Form
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">AJAX Test</h2>
            <button id="ajaxTest" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Test AJAX Request
            </button>
        </div>

        <div id="results" class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Results</h2>
            <div id="resultContent" class="text-gray-600">No tests run yet</div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    
    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/test-csrf-endpoint', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('resultContent').innerHTML = 
                    `<div class="text-green-600">Form test successful: ${JSON.stringify(data)}</div>`;
            })
            .catch(error => {
                document.getElementById('resultContent').innerHTML = 
                    `<div class="text-red-600">Form test failed: ${error.message}</div>`;
            });
        });

        document.getElementById('ajaxTest').addEventListener('click', function() {
            fetch('/admin/dashboard/data')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('resultContent').innerHTML = 
                    `<div class="text-green-600">AJAX test successful! Loaded ${Object.keys(data.stats || {}).length} stats</div>`;
            })
            .catch(error => {
                document.getElementById('resultContent').innerHTML = 
                    `<div class="text-red-600">AJAX test failed: ${error.message}</div>`;
            });
        });
    </script>
</body>
</html> 