<!-- User Modals -->

<!-- Borrow Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm" 
     id="borrowModal" 
     style="display: none !important;">
    <div class="modal-content bg-white rounded-xl p-8 max-w-md w-full max-h-[80vh] overflow-y-auto animate-[slideUp_0.3s_ease]">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Pinjam Barang</h2>
            <button class="text-gray-600 text-2xl hover:text-primary transition transform hover:rotate-90" 
                    onclick="closeBorrowModal()"
                    aria-label="Close Modal">
                &times;
            </button>
        </div>

        <div id="borrowModalContent">
            <!-- Modal content will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Notification Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm" 
     id="notificationModal" 
     style="display: none !important;">
    <div class="modal-content bg-white rounded-xl p-6 max-w-2xl w-full max-h-[80vh] overflow-y-auto animate-[slideUp_0.3s_ease]">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <i class="fas fa-bell text-primary text-2xl"></i>
                <h2 class="text-2xl font-bold text-gray-900">Notifikasi</h2>
            </div>
            <div class="flex gap-3">
                <button class="bg-primary/10 text-primary px-4 py-2 rounded-lg font-medium flex items-center gap-2 hover:bg-primary/20 transition" 
                        onclick="markAllAsRead()">
                    <i class="fas fa-check-double"></i>
                    Tandai Semua Dibaca
                </button>
                <button class="text-gray-600 text-2xl hover:text-primary transition" 
                        onclick="closeNotificationModal()"
                        aria-label="Close Notifications">
                    &times;
                </button>
            </div>
        </div>
        
        <div class="space-y-3 pr-2 max-h-[60vh] overflow-y-auto" id="notificationsList">
            <!-- Notifications will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-5 right-5 z-50 space-y-3"></div>

@push('scripts')
<script>
    // User modal functions
    let notifications = [
        {
            id: 1,
            type: 'reminder',
            title: 'Pengingat Pengembalian',
            message: 'Buku "Matematika Dasar" harus dikembalikan besok',
            time: '2 jam yang lalu',
            read: false
        },
        {
            id: 2,
            type: 'success',
            title: 'Peminjaman Disetujui',
            message: 'Permintaan peminjaman kalkulator telah disetujui',
            time: '1 hari yang lalu',
            read: false
        }
    ];

    // Fungsi untuk modal pinjaman
    function openBorrowModal(id, name, description, condition) {
        if (!isPageReady) return;
        
        const modal = document.getElementById('borrowModal');
        const modalContent = document.getElementById('borrowModalContent');
        
        if (!modal || !modalContent) return;
        
        modalContent.innerHTML = `
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-900">${name}</h3>
                <p class="text-gray-600 mb-3">${description}</p>
            </div>
            
            <form id="borrowForm" onsubmit="submitBorrow(event, '${id}')">
                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-900">Pilih Item yang Dipinjam</label>
                    <div class="relative">
                        <div id="borrowItemsContainer" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus-within:ring-2 focus-within:ring-primary/20 focus-within:border-primary transition min-h-[120px] max-h-[200px] overflow-y-auto">
                            <p class="text-gray-500 text-center py-8">Memuat item tersedia...</p>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-gray-500">Pilih item yang ingin dipinjam</p>
                            <span id="selectedCount" class="text-xs font-medium text-primary">0 item dipilih</span>
                        </div>
                    </div>
                </div>
                
                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-900">Tanggal Mulai Pinjam</label>
                    <input type="date" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" id="borrowStartDate" required>
                </div>
                
                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-900">Tanggal Kembali</label>
                    <input type="date" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" id="borrowEndDate" required>
                </div>
                
                <div class="mb-5">
                    <label class="block font-semibold mb-2 text-gray-900">Keperluan</label>
                    <textarea class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition min-h-[100px]" id="borrowPurpose" placeholder="Jelaskan untuk apa barang ini digunakan..." required></textarea>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-900 px-6 py-3 rounded-xl font-semibold transition" onclick="closeBorrowModal()">Batal</button>
                    <button type="submit" class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-6 py-3 rounded-xl font-semibold transition hover:-translate-y-0.5 hover:shadow flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
                    </button>
                </div>
            </form>
        `;
        
        modal.setAttribute('data-modal-ready', 'true');
        modal.style.display = 'flex';
        loadAvailableSubBarang(id);
    }

    function closeBorrowModal() {
        const modal = document.getElementById('borrowModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Fungsi untuk notifikasi
    function openNotificationModal() {
        if (!isPageReady) return;
        
        const modal = document.getElementById('notificationModal');
        const notificationsList = document.getElementById('notificationsList');
        
        if (!modal || !notificationsList) return;
        
        notificationsList.innerHTML = notifications.map(notification => `
            <div class="flex gap-4 p-4 rounded-xl border border-gray-200 hover:border-primary hover:bg-primary/10 transition cursor-pointer relative ${notification.read ? 'bg-white' : 'bg-primary/10 border-l-4 border-primary'}" 
                 onclick="markAsRead(${notification.id})">
                <div class="flex-shrink-0 w-10 h-10 rounded-full ${getNotificationColor(notification.type)} flex items-center justify-center">
                    <i class="fas ${getNotificationIcon(notification.type)}"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold mb-1 text-gray-900">${notification.title}</h4>
                    <p class="text-xs text-gray-600 mb-2">${notification.message}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">${notification.time}</span>
                        ${!notification.read ? '<span class="absolute top-4 right-4 w-2 h-2 bg-primary rounded-full"></span>' : ''}
                    </div>
                </div>
            </div>
        `).join('');
        
        modal.setAttribute('data-modal-ready', 'true');
        modal.style.display = 'flex';
        updateNotificationBadge();
    }

    function closeNotificationModal() {
        const modal = document.getElementById('notificationModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function getNotificationIcon(type) {
        const icons = {
            reminder: 'fa-clock',
            success: 'fa-check-circle',
            info: 'fa-info-circle',
            warning: 'fa-exclamation-triangle',
            error: 'fa-times-circle'
        };
        return icons[type] || 'fa-bell';
    }

    function getNotificationColor(type) {
        const colors = {
            reminder: 'bg-blue-100 text-blue-600',
            success: 'bg-green-100 text-green-600',
            info: 'bg-blue-100 text-blue-600',
            warning: 'bg-amber-100 text-amber-600',
            error: 'bg-red-100 text-red-600'
        };
        return colors[type] || 'bg-blue-100 text-blue-600';
    }

    async function markAsRead(notificationId) {
        const notification = notifications.find(n => n.id === notificationId);
        if (notification && !notification.read) {
            try {
                // Get CSRF token safely
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }
                
                // Send API request to mark as read on server
                const response = await fetch(`/user/notifications/${notificationId}/read`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                if (response.ok) {
                    // Update local state only after successful server update
                    notification.read = true;
                    updateNotificationBadge();
                    
                    // Update UI
                    const notificationElement = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                    if (notificationElement) {
                        notificationElement.classList.remove('bg-primary/10', 'border-l-4', 'border-primary');
                        notificationElement.classList.add('bg-white');
                        
                        const unreadBadge = notificationElement.querySelector('.absolute');
                        if (unreadBadge) {
                            unreadBadge.remove();
                        }
                    }
                } else {
                    console.error('Failed to mark notification as read on server');
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }
    }

    async function markAllAsRead() {
        try {
            // Get CSRF token safely
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                console.error('CSRF token not found');
                return;
            }
            
            // Send API request to mark all as read on server
            const response = await fetch('/user/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            if (response.ok) {
                // Update local state only after successful server update
                notifications.forEach(notification => {
                    notification.read = true;
                });
                
                updateNotificationBadge();
                
                // Update all notification elements in the modal
                document.querySelectorAll('#notificationsList > div').forEach(element => {
                    element.classList.remove('bg-primary/10', 'border-l-4', 'border-primary');
                    element.classList.add('bg-white');
                    
                    const unreadBadge = element.querySelector('.absolute');
                    if (unreadBadge) {
                        unreadBadge.remove();
                    }
                });
            } else {
                console.error('Failed to mark all notifications as read on server');
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }

    function updateNotificationBadge() {
        if (!isPageReady) return;
        
        const unreadCount = notifications.filter(n => !n.read).length;
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            badge.textContent = unreadCount;
            badge.style.display = unreadCount > 0 ? 'flex' : 'none';
        }
    }

    // Additional functions for borrowing (will be implemented based on existing functionality)
    function loadAvailableSubBarang(barangId) {
        const container = document.getElementById('borrowItemsContainer');
        
        fetch(`/admin/sub-barang/available/${barangId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-8">Tidak ada item tersedia</p>';
                    return;
                }
                
                const checkboxes = data.map(item => {
                    const kondisiIcon = item.kondisi === 'baik' ? '✅' : '⚠️';
                    const kondisiLabel = item.kondisi === 'baik' ? 'Baik' : 'Rusak Ringan';
                    const kondisiClass = item.kondisi === 'baik' ? 'text-green-600' : 'text-amber-600';
                    
                    return `
                        <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition cursor-pointer border border-transparent hover:border-gray-200">
                            <input type="checkbox" class="sub-barang-checkbox" value="${item.id}" onchange="updateSelectedCount()">
                            <div class="flex flex-col flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900">${item.kode}</span>
                                    <span class="${kondisiClass} text-xs font-medium">${kondisiIcon} ${kondisiLabel}</span>
                                </div>
                                <span class="text-xs text-gray-500">Tahun Perolehan: ${item.tahun_perolehan}</span>
                            </div>
                        </label>
                    `;
                }).join('');
                
                container.innerHTML = checkboxes;
                updateSelectedCount();
            })
            .catch(error => {
                console.error('Error loading sub barang:', error);
                container.innerHTML = '<p class="text-red-500 text-center py-8">Gagal memuat item</p>';
            });
    }

    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.sub-barang-checkbox:checked');
        const count = checkboxes.length;
        const selectedCountElement = document.getElementById('selectedCount');
        if (selectedCountElement) {
            selectedCountElement.textContent = `${count} item dipilih`;
        }
    }

    function submitBorrow(event, itemId) {
        event.preventDefault();
        
        const checkboxes = document.querySelectorAll('.sub-barang-checkbox:checked');
        const selectedItems = Array.from(checkboxes).map(checkbox => checkbox.value);
        const startDate = document.getElementById('borrowStartDate').value;
        const endDate = document.getElementById('borrowEndDate').value;
        const purpose = document.getElementById('borrowPurpose').value;
        
        if (selectedItems.length === 0 || !startDate || !endDate || !purpose) {
            showToast('error', 'Semua field harus diisi! Pilih minimal satu item.');
            return;
        }
        
        if (new Date(startDate) >= new Date(endDate)) {
            showToast('error', 'Tanggal kembali harus setelah tanggal mulai pinjam!');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Show loading state
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner animate-spin"></i> Mengirim...';
        submitBtn.disabled = true;

        fetch('/user/peminjaman/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                barangId: itemId,
                subBarangIds: selectedItems.map(id => parseInt(id)),
                quantity: selectedItems.length,
                startDate: startDate,
                endDate: endDate,
                purpose: purpose
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            showToast('success', `Permintaan peminjaman berhasil diajukan untuk ${selectedItems.length} item!`);
            closeBorrowModal();
            
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        })
        .catch(error => {
            showToast('error', error.message || 'Terjadi kesalahan saat mengajukan peminjaman');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }
</script>
@endpush 