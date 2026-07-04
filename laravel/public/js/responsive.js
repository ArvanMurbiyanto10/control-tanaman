/**
 * RESPONSIVE JAVASCRIPT UNTUK MONITORING COTA
 * Fungsi: Toggle sidebar di mobile/tablet
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Buat tombol hamburger
    createMenuButton();
    
    // Buat overlay
    createOverlay();
    
    // Setup event listeners
    setupEventListeners();
});

/**
 * Fungsi untuk membuat tombol hamburger menu
 */
function createMenuButton() {
    // Cek apakah sudah ada
    if (document.querySelector('.menu-toggle')) return;
    
    const button = document.createElement('button');
    button.className = 'menu-toggle';
    button.setAttribute('aria-label', 'Toggle Menu');
    button.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    `;
    
    // Tempatkan di body
    document.body.appendChild(button);
}

/**
 * Fungsi untuk membuat overlay background
 */
function createOverlay() {
    // Cek apakah sudah ada
    if (document.querySelector('.sidebar-overlay')) return;
    
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
}

/**
 * Setup semua event listeners
 */
function setupEventListeners() {
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('aside.w-64');
    const overlay = document.querySelector('.sidebar-overlay');
    const navLinks = document.querySelectorAll('aside.w-64 a');
    
    // Toggle sidebar saat klik hamburger
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
    }
    
    // Tutup sidebar saat klik overlay
    if (overlay && sidebar) {
        overlay.addEventListener('click', function() {
            closeSidebar();
        });
    }
    
    // Tutup sidebar saat klik link navigasi (hanya di mobile/tablet)
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 1024) {
                closeSidebar();
            }
        });
    });
    
    // Tutup sidebar saat resize ke desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            closeSidebar();
        }
    });
    
    // Tutup sidebar saat klik di luar sidebar (mobile/tablet)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 1024 && sidebar && !sidebar.contains(e.target) && menuToggle && !menuToggle.contains(e.target)) {
            closeSidebar();
        }
    });
}

/**
 * Fungsi untuk toggle sidebar (buka/tutup)
 */
function toggleSidebar() {
    const sidebar = document.querySelector('aside.w-64');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.toggle('sidebar-active');
        overlay.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('sidebar-active') ? 'hidden' : '';
    }
}

/**
 * Fungsi untuk menutup sidebar
 */
function closeSidebar() {
    const sidebar = document.querySelector('aside.w-64');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.remove('sidebar-active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
}

/**
 * Fungsi untuk membuka sidebar
 */
function openSidebar() {
    const sidebar = document.querySelector('aside.w-64');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.add('sidebar-active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

// Export functions untuk akses global
window.toggleSidebar = toggleSidebar;
window.closeSidebar = closeSidebar;
window.openSidebar = openSidebar;