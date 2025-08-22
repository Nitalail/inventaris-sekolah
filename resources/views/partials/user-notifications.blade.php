<!-- User Notification Bell System -->
<div class="relative" x-data="userNotificationSystem" x-init="initializeComponent()">
    <button @click="toggleNotifications()" 
            class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 rounded-full transition-slow">
        <i class="fas fa-bell text-xl"></i>
        <span x-show="unreadCount > 0 && isReady" 
              x-text="unreadCount" 
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 scale-75"
              x-transition:enter-end="opacity-100 scale-100"
              class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
        </span>
    </button>

    <!-- Notification Dropdown -->
    <div x-show="showNotifications && isReady" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="showNotifications = false"
         style="display: none;"
         x-bind:style="showNotifications && isReady ? '' : 'display: none;'"
         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
            <button @click="markAllAsRead()" 
                    x-show="unreadCount > 0"
                    class="text-sm text-blue-600 hover:text-blue-800">
                Tandai Semua Dibaca
            </button>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            <template x-for="notification in notifications" :key="notification.id">
                <div @click="markAsRead(notification.id)" 
                     :class="notification.is_read ? 'bg-gray-50' : 'bg-blue-50'"
                     class="p-4 border-b border-gray-100 hover:bg-gray-100 cursor-pointer transition-colors">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <i :class="notification.icon + ' ' + notification.color"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                <span x-show="!notification.is_read" 
                                      class="w-2 h-2 bg-blue-600 rounded-full"></span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1" x-text="notification.message"></p>
                            <p class="text-xs text-gray-400 mt-1" x-text="notification.time_ago"></p>
                        </div>
                    </div>
                </div>
            </template>
            
            <!-- Empty state -->
            <div x-show="notifications.length === 0" 
                 class="p-8 text-center text-gray-500">
                <i class="fas fa-bell-slash text-4xl mb-4"></i>
                <p>Tidak ada notifikasi</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-3 border-t border-gray-200 bg-gray-50 rounded-b-lg">
            <button @click="loadNotifications()" 
                    class="w-full text-sm text-blue-600 hover:text-blue-800">
                <i class="fas fa-refresh mr-1"></i>
                Refresh
            </button>
        </div>
    </div>
</div>

<!-- User Notification System JavaScript -->
<script>
if (typeof userNotificationSystem === 'undefined') {
    function userNotificationSystem() {
        return {
            showNotifications: false,
            notifications: [],
            unreadCount: 0,
            loading: false,
            isReady: false,
            intervalId: null,

            initializeComponent() {
                // Wait for DOM to be fully ready
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.init();
                        this.$el.setAttribute('data-alpine-ready', 'true');
                    }, 150);
                });
            },

            init() {
                this.loadNotificationCount();
                this.loadNotifications();
                
                // Set ready state after initial load
                setTimeout(() => {
                    this.isReady = true;
                }, 200);
                
                // Auto refresh every 3 seconds with proper cleanup
                if (this.intervalId) {
                    clearInterval(this.intervalId);
                }
                this.intervalId = setInterval(() => {
                    if (this.isReady) {
                        this.loadNotificationCount();
                        if (this.showNotifications) {
                            this.loadNotifications();
                        }
                    }
                }, 3000);
            },

            async loadNotificationCount() {
                try {
                    const response = await fetch('/user/notifications/count');
                    const data = await response.json();
                    if (data.success) {
                        this.unreadCount = data.count;
                    }
                } catch (error) {
                    console.error('Error loading notification count:', error);
                }
            },

            async loadNotifications() {
                if (this.loading) return;
                
                this.loading = true;
                try {
                    const response = await fetch('/user/notifications?limit=10');
                    const data = await response.json();
                    if (data.success) {
                        this.notifications = data.data;
                    }
                } catch (error) {
                    console.error('Error loading notifications:', error);
                } finally {
                    this.loading = false;
                }
            },

            toggleNotifications() {
                if (!this.isReady) return;
                
                this.showNotifications = !this.showNotifications;
                if (this.showNotifications) {
                    this.loadNotifications();
                }
            },

            async markAsRead(notificationId) {
                try {
                    const response = await fetch(`/user/notifications/${notificationId}/read`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        // Update local state
                        const notification = this.notifications.find(n => n.id === notificationId);
                        if (notification && !notification.is_read) {
                            notification.is_read = true;
                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                        }
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            },

            async markAllAsRead() {
                try {
                    const response = await fetch('/user/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        // Update local state
                        this.notifications.forEach(notification => {
                            notification.is_read = true;
                        });
                        this.unreadCount = 0;
                        
                        // Show success message
                        this.showToast('Semua notifikasi telah ditandai sebagai dibaca', 'success');
                    }
                } catch (error) {
                    console.error('Error marking all notifications as read:', error);
                }
            },

            showToast(message, type = 'info') {
                // Simple toast notification
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
                    type === 'success' ? 'bg-green-500 text-white' : 
                    type === 'error' ? 'bg-red-500 text-white' : 
                    'bg-blue-500 text-white'
                }`;
                toast.textContent = message;
                
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        }
    }
}
</script>
