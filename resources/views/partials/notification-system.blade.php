<!-- Notification Component -->
<div class="notification-container fixed top-4 right-4 z-50 space-y-2" 
     x-data="{ 
        notifications: [],
        isReady: false,
        addNotification(type, message, clickable = false, url = null, actionText = '') {
            // Only add notifications if component is ready
            if (!this.isReady) return;
            
            this.notifications.push({
                id: Date.now(),
                type: type,
                message: message,
                show: true,
                clickable: clickable,
                url: url,
                actionText: actionText
            });
            setTimeout(() => {
                let index = this.notifications.findIndex(n => n.id === this.notifications[0].id);
                if (index !== -1) this.notifications[index].show = false;
                setTimeout(() => this.notifications.shift(), 300);
            }, 8000);
        },
        handleNotificationClick(notification) {
            if (notification.clickable && notification.url) {
                window.location.href = notification.url;
            }
        }
     }" 
     x-init="
                 // Wait for DOM to be fully ready
         $nextTick(() => {
             setTimeout(() => {
                 this.isReady = true;
                 this.$el.setAttribute('data-alpine-ready', 'true');
                 
                 // Add session-based notifications only after component is ready
                 @if(session('success'))
                     this.addNotification('success', {{ json_encode(session('success')) }}, true, {{ json_encode($clickUrl ?? '/admin/transaksi') }}, {{ json_encode($actionText ?? 'ke halaman transaksi') }});
                 @endif
                 @if(session('error'))
                     this.addNotification('error', {{ json_encode(session('error')) }}, false, null, '');
                 @endif
                 @if(session('info'))
                     this.addNotification('info', {{ json_encode(session('info')) }}, true, {{ json_encode($clickUrl ?? '/admin/laporan') }}, {{ json_encode($actionText ?? 'lihat laporan') }});
                 @endif
                 @if(session('warning'))
                     this.addNotification('warning', {{ json_encode(session('warning')) }}, false, null, '');
                 @endif
             }, 100);
         });
     ">
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="notification.show && isReady"
             x-transition:enter="transform transition ease-out duration-300"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transform transition ease-in duration-300"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             @click="handleNotificationClick(notification)"
             class="max-w-sm w-full glass rounded-lg shadow-lg border transition-all duration-200"
             style="display: none;"
             x-bind:style="notification.show && isReady ? '' : 'display: none;'"
             :class="{
                'border-green-200 bg-green-50 hover:bg-green-100 cursor-pointer': notification.type === 'success',
                'border-red-200 bg-red-50': notification.type === 'error',
                'border-blue-200 bg-blue-50 hover:bg-blue-100 cursor-pointer': notification.type === 'info',
                'border-yellow-200 bg-yellow-50 hover:bg-yellow-100 cursor-pointer': notification.type === 'warning',
                'hover:shadow-xl transform hover:scale-[1.02]': notification.clickable
             }">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                        <i :class="{
                            'fas fa-check-circle text-green-500': notification.type === 'success',
                            'fas fa-times-circle text-red-500': notification.type === 'error',
                            'fas fa-info-circle text-blue-500': notification.type === 'info',
                            'fas fa-exclamation-triangle text-yellow-500': notification.type === 'warning'
                        }"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p x-text="notification.message" 
                           :class="{
                            'text-green-800': notification.type === 'success',
                            'text-red-800': notification.type === 'error',
                            'text-blue-800': notification.type === 'info',
                            'text-yellow-800': notification.type === 'warning'
                           }"
                           class="text-sm font-medium"></p>
                        <p x-show="notification.clickable" 
                           :class="{
                            'text-green-600': notification.type === 'success',
                            'text-blue-600': notification.type === 'info',
                            'text-yellow-600': notification.type === 'warning'
                           }"
                           class="text-xs mt-1 flex items-center">
                            <i class="fas fa-mouse-pointer mr-1"></i>
                            <span x-text="'Klik untuk ' + notification.actionText"></span>
                        </p>
                    </div>
                    <button @click.stop="notification.show = false; setTimeout(() => notifications.splice(notifications.indexOf(notification), 1), 300)" 
                            class="flex-shrink-0 ml-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<!-- Global notification function for JavaScript -->
<script>
    // Ensure DOM is ready and add proper null checks
    (function() {
        'use strict';
        
        // Global notification function with improved ready state checking
        window.showNotification = function(type, message, clickable = false, url = null, actionText = '') {
            const notificationComponent = document.querySelector('.notification-container');
            if (notificationComponent && notificationComponent.__x && notificationComponent.__x.$data && notificationComponent.__x.$data.isReady) {
                notificationComponent.__x.$data.addNotification(type, message, clickable, url, actionText);
            } else {
                console.warn('🔔 Notification component not ready, queuing notification');
                // Queue the notification for later with a maximum retry limit
                let retries = 0;
                const maxRetries = 10;
                const retryInterval = setInterval(() => {
                    const component = document.querySelector('.notification-container');
                    if ((component && component.__x && component.__x.$data && component.__x.$data.isReady) || retries >= maxRetries) {
                        clearInterval(retryInterval);
                        if (retries < maxRetries && component && component.__x && component.__x.$data) {
                            component.__x.$data.addNotification(type, message, clickable, url, actionText);
                        }
                    }
                    retries++;
                }, 100);
            }
        };


    })();
</script> 