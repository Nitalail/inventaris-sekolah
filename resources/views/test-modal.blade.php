<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modal Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Modal styles */
        #testModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        #testModal.invisible {
            visibility: hidden;
        }
        
        #testModal.visible {
            visibility: visible;
        }
        
        #testModal .modal-content {
            transition: transform 0.3s ease;
        }
        
        #testModal .modal-content.scale-95 {
            transform: scale(0.95);
        }
        
        #testModal .modal-content.scale-100 {
            transform: scale(1);
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Modal Test</h1>
        
        <button onclick="openTestModal()" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 mb-4">
            Open Test Modal
        </button>
        
        <div class="text-sm text-gray-600">
            <p>Click the button above to test modal functionality.</p>
            <p>Check browser console for debug logs.</p>
        </div>
    </div>

    <!-- Test Modal -->
    <div id="testModal" class="invisible opacity-0">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTestModal()"></div>
        
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-200/70 w-full max-w-md scale-95 modal-content">
            <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Test Modal</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closeTestModal()">
                    <i class="fas fa-times">×</i>
                </button>
            </div>
            
            <div class="p-6">
                <p class="text-gray-700 mb-4">This is a test modal to verify functionality.</p>
                <div class="flex justify-end">
                    <button onclick="closeTestModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTestModal() {
            console.log('openTestModal called');
            const modal = document.getElementById('testModal');
            if (!modal) {
                console.error('Test modal element not found!');
                return;
            }
            console.log('Test modal found:', modal);
            
            modal.classList.remove('invisible', 'opacity-0');
            modal.classList.add('visible', 'opacity-100');
            
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                setTimeout(() => {
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }, 10);
            }
            
            console.log('Test modal opened successfully');
        }

        function closeTestModal() {
            console.log('closeTestModal called');
            const modal = document.getElementById('testModal');
            if (!modal) {
                console.error('Test modal element not found!');
                return;
            }
            
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
            }
            
            setTimeout(() => {
                modal.classList.remove('visible', 'opacity-100');
                modal.classList.add('invisible', 'opacity-0');
            }, 200);
            
            console.log('Test modal closed successfully');
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, test modal functionality initialized');
            
            const modal = document.getElementById('testModal');
            if (modal) {
                console.log('Test modal element found on DOM load');
            } else {
                console.error('Test modal element not found on DOM load');
            }
        });
    </script>
</body>
</html> 