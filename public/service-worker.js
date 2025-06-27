self.addEventListener('install', event => {
  console.log('Service Worker installed');
  // Tambahkan caching jika perlu
});

self.addEventListener('activate', event => {
  console.log('Service Worker activated');
});

self.addEventListener('fetch', event => {
  event.respondWith(fetch(event.request));
});
