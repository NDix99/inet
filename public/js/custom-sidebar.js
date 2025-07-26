// Custom JavaScript untuk Sidebar yang tidak bisa di-collapse

$(document).ready(function() {
    // Menyembunyikan tombol collapse sidebar
    $('.sidebar-toggle').hide();
    
    // Memastikan sidebar selalu terbuka
    $('body').removeClass('sidebar-collapse sidebar-mini');
    $('body').addClass('sidebar-mini');
    
    // Mencegah collapse sidebar dengan keyboard shortcut
    $(document).keydown(function(e) {
        // Mencegah Ctrl+B (shortcut untuk collapse sidebar)
        if (e.ctrlKey && e.keyCode === 66) {
            e.preventDefault();
            return false;
        }
    });
    
    // Mencegah collapse sidebar dengan click event
    $(document).on('click', '.sidebar-toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    });
    
    // Memastikan sidebar tetap terbuka setelah resize window
    $(window).resize(function() {
        $('body').removeClass('sidebar-collapse');
        $('body').addClass('sidebar-mini');
    });
    
    // Menambahkan class untuk styling header menu
    $('.nav-sidebar .nav-header').addClass('menu-header');
    
    // Styling untuk menu items yang aktif
    $('.nav-sidebar .nav-link').each(function() {
        var href = $(this).attr('href');
        if (href && window.location.href.includes(href)) {
            $(this).addClass('active');
        }
    });
});

// Fungsi untuk memastikan sidebar tidak collapse
function preventSidebarCollapse() {
    $('body').removeClass('sidebar-collapse');
    $('body').addClass('sidebar-mini');
    $('.sidebar-toggle').hide();
}

// Panggil fungsi setiap 1 detik untuk memastikan sidebar tetap terbuka
setInterval(preventSidebarCollapse, 1000); 