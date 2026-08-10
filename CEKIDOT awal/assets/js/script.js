// assets/js/script.js
document.addEventListener('DOMContentLoaded', function() {

    // ===== NAVIGASI MOBILE =====
    var navToggle = document.getElementById('navToggle');
    var navMenu = document.getElementById('navMenu');

    if (navToggle) {
        navToggle.addEventListener('click', function() {
            if (navMenu) {
                navMenu.classList.toggle('open');
            }
        });
    }

    // ===== SLIDER =====
    var slides = document.querySelectorAll('.slide');
    var dots = document.querySelectorAll('.dot');
    var prevBtn = document.getElementById('prevBtn');
    var nextBtn = document.getElementById('nextBtn');
    var currentIndex = 0;
    var slideInterval;
    var totalSlides = slides.length;

    if (totalSlides > 0) {
        function showSlide(index) {
            if (index < 0) index = totalSlides - 1;
            if (index >= totalSlides) index = 0;

            for (var i = 0; i < totalSlides; i++) {
                slides[i].classList.remove('active');
                if (dots[i]) dots[i].classList.remove('active');
            }

            slides[index].classList.add('active');
            if (dots[index]) dots[index].classList.add('active');
            currentIndex = index;
        }

        function nextSlide() {
            var newIndex = currentIndex + 1;
            if (newIndex >= totalSlides) newIndex = 0;
            showSlide(newIndex);
        }

        function prevSlide() {
            var newIndex = currentIndex - 1;
            if (newIndex < 0) newIndex = totalSlides - 1;
            showSlide(newIndex);
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                nextSlide();
                resetInterval();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                prevSlide();
                resetInterval();
            });
        }

        for (var i = 0; i < dots.length; i++) {
            if (dots[i]) {
                dots[i].addEventListener('click', function() {
                    var index = parseInt(this.getAttribute('data-index'));
                    showSlide(index);
                    resetInterval();
                });
            }
        }

        function startInterval() {
            if (slideInterval) clearInterval(slideInterval);
            slideInterval = setInterval(function() {
                nextSlide();
            }, 10000);
        }

        function resetInterval() {
            clearInterval(slideInterval);
            startInterval();
        }

        startInterval();
    }

});