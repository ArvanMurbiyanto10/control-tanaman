/**
 * COTA — Responsive JavaScript
 * Sidebar toggle, hamburger menu, mobile interactions
 */
document.addEventListener('DOMContentLoaded', function () {
    createMenuButton();
    createOverlay();
    setupEventListeners();
});

function createMenuButton() {
    if (document.querySelector('.menu-toggle')) return;
    const btn = document.createElement('button');
    btn.className = 'menu-toggle';
    btn.setAttribute('aria-label', 'Toggle Menu');
    btn.innerHTML = '<i class="fa-solid fa-bars text-lg"></i>';
    document.body.appendChild(btn);
}

function createOverlay() {
    if (document.querySelector('.sidebar-overlay')) return;
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
}

function setupEventListeners() {
    const btn = document.querySelector('.menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    if (btn) btn.addEventListener('click', (e) => { e.stopPropagation(); toggleSidebar(); });
    if (overlay) overlay.addEventListener('click', closeSidebar);

    document.querySelectorAll('#sidebar a').forEach(link => {
        link.addEventListener('click', () => { if (window.innerWidth <= 1024) closeSidebar(); });
    });

    window.addEventListener('resize', () => { if (window.innerWidth > 1024) closeSidebar(); });
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    if (!sidebar || !overlay) return;
    const isOpen = sidebar.classList.contains('sidebar-active');
    if (isOpen) { closeSidebar(); } else { openSidebar(); }
}

function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const btn = document.querySelector('.menu-toggle');
    if (!sidebar || !overlay) return;
    sidebar.classList.add('sidebar-active');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    if (btn) btn.innerHTML = '<i class="fa-solid fa-xmark text-lg"></i>';
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const btn = document.querySelector('.menu-toggle');
    if (!sidebar || !overlay) return;
    sidebar.classList.remove('sidebar-active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
    if (btn) btn.innerHTML = '<i class="fa-solid fa-bars text-lg"></i>';
}

window.toggleSidebar = toggleSidebar;
window.closeSidebar = closeSidebar;
window.openSidebar = openSidebar;