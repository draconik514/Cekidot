document.addEventListener('turbo:load', function () {
    var navbar = document.getElementById('mainNav');
    if (navbar) {
        function handleScroll() {
            if (window.scrollY > 80) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
        handleScroll();
        window.addEventListener('scroll', handleScroll);
    }

    var toggleBtn = document.getElementById('navToggle');
    var navMenu = document.getElementById('navMenu');
    var navOverlay = document.getElementById('navOverlay');

    function closeMenu() {
        if (navMenu) navMenu.classList.remove('open');
        if (navOverlay) navOverlay.classList.remove('show');
        if (toggleBtn) {
            var icon = toggleBtn.querySelector('i');
            if (icon) icon.className = 'fas fa-bars';
        }
    }

    function openMenu() {
        if (navMenu) navMenu.classList.add('open');
        if (navOverlay) navOverlay.classList.add('show');
        if (toggleBtn) {
            var icon = toggleBtn.querySelector('i');
            if (icon) icon.className = 'fas fa-times';
        }
    }

    if (toggleBtn && navMenu) {
        toggleBtn.addEventListener('click', function () {
            if (navMenu.classList.contains('open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        navMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                closeMenu();
            });
        });
    }

    if (navOverlay) {
        navOverlay.addEventListener('click', function () {
            closeMenu();
        });
    }
});
