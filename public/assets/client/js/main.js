document.addEventListener('DOMContentLoaded', function() {
    
    // =========================================
    // 1. XỬ LÝ MENU MOBILE (DRAWER)
    // =========================================
    const menuBtn = document.querySelector('.mobile-menu-btn');       // Nút 3 gạch
    const menuDrawer = document.querySelector('.mobile-nav-drawer');  // Khung menu
    const menuOverlay = document.querySelector('.mobile-nav-overlay');// Lớp phủ đen
    const closeBtn = document.querySelector('.btn-close-menu');       // Nút đóng X

    // Hàm mở menu
    function openMenu() {
        if(menuDrawer && menuOverlay) {
            menuDrawer.classList.add('active');
            menuOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Khóa cuộn trang web khi mở menu
        }
    }

    // Hàm đóng menu
    function closeMenu() {
        if(menuDrawer && menuOverlay) {
            menuDrawer.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = ''; // Mở lại cuộn trang
        }
    }

    // Gán sự kiện click
    if (menuBtn) {
        menuBtn.addEventListener('click', openMenu);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeMenu);
    }

    if (menuOverlay) {
        menuOverlay.addEventListener('click', closeMenu); // Bấm ra ngoài vùng đen cũng đóng
    }

    // =========================================
    // 2. CÁC XỬ LÝ KHÁC (Slider, Cart...) - Để dành cho sau này
    // =========================================
    
});