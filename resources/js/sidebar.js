// Toggle sidebar pada tampilan mobile
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
    }

    // Ambil semua menu link
    const menuLinks = document.querySelectorAll('.sidebar-menu-link');
    
    // Dapatkan nama halaman dari URL saat ini
    const currentPath = window.location.pathname;
    const currentPage = currentPath.split('/').pop().replace('.html', '') || 'dashboard';
    
    // Tambahkan event listener untuk setiap menu
    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Hapus kelas active dari semua menu
            menuLinks.forEach(item => item.classList.remove('active'));
            
            // Tambahkan kelas active ke menu yang diklik
            this.classList.add('active');
            
            // Simpan halaman aktif di localStorage
            localStorage.setItem('activePage', this.getAttribute('data-page'));
        });
        
        // Tandai menu yang sesuai dengan halaman saat ini
        const linkPage = link.getAttribute('data-page');
        if (linkPage === currentPage) {
            menuLinks.forEach(item => item.classList.remove('active'));
            link.classList.add('active');
        }
    });
    
    // Cek jika ada halaman tersimpan di localStorage dan gunakan jika ada
    const activePage = localStorage.getItem('activePage');
    if (activePage) {
        // Jika URL tidak menunjukkan halaman tertentu (misalnya home/index), 
        // gunakan halaman yang tersimpan di localStorage
        if (currentPage === 'index' || currentPage === '' || currentPage === 'dashboard') {
            const activeMenu = document.querySelector(`.sidebar-menu-link[data-page="${activePage}"]`);
            if (activeMenu) {
                menuLinks.forEach(item => item.classList.remove('active'));
                activeMenu.classList.add('active');
            }
        }
    }
});