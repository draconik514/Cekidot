@extends('layouts.app')

@section('title', 'Home - CEKIDOT')

@section('styles')
<style>
    html, body { margin: 0; padding: 0; width: 100%; overflow-x: hidden; }
    .slider-section {
        position: relative;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        background: #0f3b5e;
        margin: 0;
        padding: 0;
    }
    .slider-container { width: 100%; height: 100%; position: relative; }
    .slide {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0; transition: opacity 0.8s ease-in-out; z-index: 1;
    }
    .slide.active { opacity: 1; z-index: 2; }
    .slide img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .slider-btn {
        position: absolute; top: 50%; transform: translateY(-50%);
        background: rgba(0,0,0,0.4); color: #fff; border: none;
        padding: 16px 20px; cursor: pointer; font-size: 20px;
        border-radius: 50%; transition: all 0.3s; z-index: 10;
        backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    }
    .slider-btn:hover { background: rgba(234,179,8,0.8); transform: translateY(-50%) scale(1.05); }
    .slider-btn.prev { left: 20px; }
    .slider-btn.next { right: 20px; }
    .slider-dots {
        position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%);
        display: flex; gap: 10px; z-index: 10;
    }
    .slider-dots .dot {
        width: 12px; height: 12px; border-radius: 50%;
        background: rgba(255,255,255,0.4); cursor: pointer; transition: all 0.3s;
    }
    .slider-dots .dot.active { background: #eab308; transform: scale(1.2); }
    .slider-dots .dot:hover { background: rgba(255,255,255,0.8); }

    .layanan-section {
        padding: 80px 0 90px;
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(160deg, #eef2f7 0%, #dce3ed 30%, #e8edf5 60%, #d5dee8 100%);
    }
    .layanan-section::after {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(15,59,94,0.06) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(234,179,8,0.08) 0%, transparent 35%),
            radial-gradient(circle at 50% 50%, rgba(15,59,94,0.03) 0%, transparent 50%),
            repeating-linear-gradient(45deg, transparent, transparent 30px, rgba(15,59,94,0.035) 30px, rgba(15,59,94,0.035) 31px),
            repeating-linear-gradient(-45deg, transparent, transparent 30px, rgba(234,179,8,0.025) 30px, rgba(234,179,8,0.025) 31px);
        background-size: auto, auto, auto, 60px 60px, 60px 60px;
        pointer-events: none; z-index: 0;
    }
    .layanan-section::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(1px);
        -webkit-backdrop-filter: blur(1px);
        z-index: 0;
    }
    .layanan-section .container { position: relative; z-index: 1; width: 100%; }
    .section-header { text-align: center; margin-bottom: 50px; position: relative; }
    .section-header .header-line {
        width: 80px; height: 4px;
        background: linear-gradient(90deg, #eab308, #f59e0b);
        border-radius: 4px; margin: 0 auto 18px; position: relative;
    }
    .section-header .header-line::after {
        content: '';
        position: absolute; top: -3px; left: 50%; transform: translateX(-50%);
        width: 20px; height: 10px; background: rgba(234,179,8,0.2);
        border-radius: 50%; filter: blur(6px);
    }
    .section-header .header-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 70px; height: 70px;
        background: linear-gradient(135deg, #0f3b5e, #1a5a7a);
        border-radius: 50%; margin-bottom: 12px;
        box-shadow: 0 8px 30px rgba(15,59,94,0.2);
        position: relative;
    }
    .section-header .header-icon i { font-size: 30px; color: #eab308; }
    .section-header .header-icon::after {
        content: '';
        position: absolute; top: -4px; left: -4px; right: -4px; bottom: -4px;
        border-radius: 50%; border: 2px solid rgba(234,179,8,0.15);
        animation: pulse-ring 2s ease-in-out infinite;
    }
    @keyframes pulse-ring {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.08); opacity: 0.5; }
        100% { transform: scale(1); opacity: 1; }
    }
    .section-header h2 { font-size: 36px; font-weight: 800; color: #0f3b5e; letter-spacing: -0.5px; margin-bottom: 4px; }
    .section-header h2 span { color: #eab308; }
    .section-header .subtitle { font-size: 16px; color: #64748b; font-weight: 400; letter-spacing: 0.3px; }

    .layanan-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 24px; max-width: 1100px; margin: 0 auto;
    }
    .layanan-card {
        background: rgba(255,255,255,0.88);
        backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        border-radius: 16px; padding: 30px 24px 24px;
        text-align: center;
        box-shadow: 0 4px 25px rgba(0,0,0,0.06);
        border: 1px solid rgba(255,255,255,0.6);
        transition: all 0.4s cubic-bezier(0.34,1.56,0.64,1);
        cursor: pointer; position: relative; overflow: hidden;
        display: flex; flex-direction: column; align-items: center;
        min-height: 260px;
    }
    .layanan-card::after {
        content: '';
        position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle at center, rgba(234,179,8,0.08), transparent 60%);
        opacity: 0; transition: opacity 0.6s ease; pointer-events: none;
    }
    .layanan-card:hover::after { opacity: 1; }
    .layanan-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 45px rgba(15,59,94,0.12);
        border-color: rgba(15,59,94,0.15);
        background: rgba(255,255,255,0.95);
    }
    .layanan-card .layanan-icon {
        width: 64px; height: 64px; border-radius: 50%;
        background: linear-gradient(135deg, #0f3b5e, #1a5a7a);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px; font-size: 26px; color: #fff;
        transition: all 0.4s ease; flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(15,59,94,0.15);
    }
    .layanan-card:hover .layanan-icon {
        background: #eab308; transform: scale(1.05) rotate(-5deg);
        box-shadow: 0 6px 25px rgba(234,179,8,0.3);
    }
    .layanan-card h3 { font-size: 17px; font-weight: 700; color: #0f3b5e; margin-bottom: 2px; }
    .layanan-card .layanan-sub { font-size: 11px; color: #94a3b8; font-weight: 500; margin-bottom: 6px; letter-spacing: 0.5px; text-transform: uppercase; }
    .layanan-card .layanan-desc { font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 16px; flex: 1; }
    .btn-layanan {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 22px; background: #0f3b5e; color: #fff;
        border-radius: 8px; font-weight: 600; font-size: 13px;
        text-decoration: none; transition: all 0.3s; margin-top: auto;
    }
    .btn-layanan:hover { background: #eab308; color: #0f3b5e; transform: scale(1.02); }

    @media (max-width: 992px) {
        .slider-section { height: 100vh; }
        .layanan-section { padding: 60px 0 70px; min-height: auto; }
        .section-header h2 { font-size: 30px; }
        .layanan-grid { grid-template-columns: repeat(3, 1fr); gap: 18px; padding: 0 16px; }
        .layanan-card { min-height: 240px; padding: 24px 18px 20px; }
    }
    @media (max-width: 768px) {
        .slider-section { height: 100vh; }
        .slider-btn { padding: 12px 16px; font-size: 16px; }
        .slider-btn.prev { left: 12px; }
        .slider-btn.next { right: 12px; }
        .slider-dots .dot { width: 10px; height: 10px; }
        .slider-dots { bottom: 20px; gap: 8px; }
        .layanan-section { padding: 50px 0 60px; min-height: auto; }
        .section-header { margin-bottom: 36px; }
        .section-header .header-icon { width: 56px; height: 56px; }
        .section-header .header-icon i { font-size: 24px; }
        .section-header h2 { font-size: 26px; }
        .section-header .subtitle { font-size: 14px; }
        .layanan-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; padding: 0 12px; }
        .layanan-card { min-height: 200px; padding: 20px 14px 16px; }
        .layanan-card .layanan-icon { width: 52px; height: 52px; font-size: 20px; margin-bottom: 10px; }
        .layanan-card h3 { font-size: 15px; }
        .layanan-card .layanan-desc { font-size: 12px; min-height: 30px; margin-bottom: 12px; }
        .btn-layanan { font-size: 12px; padding: 6px 16px; }
    }
    @media (max-width: 480px) {
        .slider-section { height: 100vh; }
        .slider-btn { padding: 8px 12px; font-size: 14px; }
        .slider-dots .dot { width: 8px; height: 8px; }
        .slider-dots { bottom: 15px; gap: 6px; }
        .layanan-section { padding: 40px 0 50px; min-height: auto; }
        .section-header { margin-bottom: 28px; }
        .section-header .header-icon { width: 48px; height: 48px; }
        .section-header .header-icon i { font-size: 20px; }
        .section-header .header-line { width: 60px; height: 3px; }
        .section-header h2 { font-size: 22px; }
        .section-header .subtitle { font-size: 13px; }
        .layanan-grid { grid-template-columns: 1fr 1fr; gap: 10px; padding: 0 8px; }
        .layanan-card { min-height: 180px; padding: 16px 10px 14px; border-radius: 12px; }
        .layanan-card .layanan-icon { width: 44px; height: 44px; font-size: 18px; margin-bottom: 8px; }
        .layanan-card h3 { font-size: 14px; }
        .layanan-card .layanan-sub { font-size: 9px; margin-bottom: 4px; }
        .layanan-card .layanan-desc { font-size: 11px; min-height: 24px; margin-bottom: 10px; }
        .btn-layanan { font-size: 10px; padding: 4px 12px; gap: 4px; }
    }
</style>
@endsection

@section('content')
<!-- SLIDER -->
<section class="slider-section" id="homeSlider">
    <div class="slider-container" id="sliderContainer">
        @if($slides->isEmpty())
        <div class="slide active">
            <img src="{{ asset('assets/img/slide-1.jpg') }}" alt="Default Slide">
        </div>
        <div class="slide">
            <img src="{{ asset('assets/img/slide-2.jpg') }}" alt="Default Slide 2">
        </div>
        <div class="slide">
            <img src="{{ asset('assets/img/slide-3.jpg') }}" alt="Default Slide 3">
        </div>
        @else
        @foreach($slides as $index => $slide)
        <div class="slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
            <img src="{{ asset('assets/img/slider/' . $slide->gambar) }}" alt="{{ $slide->judul }}">
        </div>
        @endforeach
        @endif
    </div>

    <button class="slider-btn prev" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-btn next" id="nextBtn"><i class="fas fa-chevron-right"></i></button>

    <div class="slider-dots" id="sliderDots">
        @for($i = 0; $i < max(3, $slides->count()); $i++)
        <span class="dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></span>
        @endfor
    </div>
</section>

<!-- LAYANAN -->
<section class="layanan-section">
    <div class="container">
        <div class="section-header">
            <div class="header-icon">
                <i class="fas fa-th-large"></i>
            </div>
            <div class="header-line"></div>
            <h2>Layanan <span>Kami</span></h2>
            <p class="subtitle">Akses cepat berbagai layanan informasi dan monitoring kinerja</p>
        </div>

        <div class="layanan-grid">
            <div class="layanan-card" onclick="window.location.href='{{ route('surat.create') }}'">
                <div class="layanan-icon"><i class="fas fa-envelope"></i></div>
                <h3>Persuratan WFH</h3>
                <p class="layanan-sub">Work From Home</p>
                <p class="layanan-desc">Kirim surat dan masukan secara digital</p>
                <a href="{{ route('surat.create') }}" class="btn-layanan"><i class="fas fa-paper-plane"></i> Kirim</a>
            </div>

            <div class="layanan-card" onclick="window.location.href='{{ route('iku.public') }}'">
                <div class="layanan-icon"><i class="fas fa-chart-line"></i></div>
                <h3>IKU</h3>
                <p class="layanan-sub">Indikator Kinerja Utama</p>
                <p class="layanan-desc">Monitoring capaian kinerja utama dinas</p>
                <a href="{{ route('iku.public') }}" class="btn-layanan"><i class="fas fa-eye"></i> Lihat</a>
            </div>

            <div class="layanan-card" onclick="window.location.href='{{ route('akip.public') }}'">
                <div class="layanan-icon"><i class="fas fa-clipboard-check"></i></div>
                <h3>AKIP</h3>
                <p class="layanan-sub">Akuntabilitas Kinerja</p>
                <p class="layanan-desc">Laporan akuntabilitas kinerja instansi</p>
                <a href="{{ route('akip.public') }}" class="btn-layanan"><i class="fas fa-eye"></i> Lihat</a>
            </div>

            <div class="layanan-card" onclick="window.location.href='{{ route('iki.public') }}'">
                <div class="layanan-icon"><i class="fas fa-user-check"></i></div>
                <h3>IKI</h3>
                <p class="layanan-sub">Indikator Kinerja Individu</p>
                <p class="layanan-desc">Penilaian kinerja pegawai individu</p>
                <a href="{{ route('iki.public') }}" class="btn-layanan"><i class="fas fa-eye"></i> Lihat</a>
            </div>

            <div class="layanan-card" onclick="window.location.href='{{ route('capaian.public') }}'">
                <div class="layanan-icon"><i class="fas fa-flag-checkered"></i></div>
                <h3>Capaian Program</h3>
                <p class="layanan-sub">Evaluasi Program</p>
                <p class="layanan-desc">Laporan capaian kinerja program dinas</p>
                <a href="{{ route('capaian.public') }}" class="btn-layanan"><i class="fas fa-eye"></i> Lihat</a>
            </div>

            <div class="layanan-card" onclick="window.location.href='{{ route('monev.public') }}'">
                <div class="layanan-icon"><i class="fas fa-chart-pie"></i></div>
                <h3>Monev</h3>
                <p class="layanan-sub">Monitoring & Evaluasi</p>
                <p class="layanan-desc">Monitoring dan evaluasi kinerja program</p>
                <a href="{{ route('monev.public') }}" class="btn-layanan"><i class="fas fa-eye"></i> Lihat</a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var slides = document.querySelectorAll('.slide');
    var dots = document.querySelectorAll('.dot');
    var prevBtn = document.getElementById('prevBtn');
    var nextBtn = document.getElementById('nextBtn');
    var currentIndex = 0;
    var totalSlides = slides.length;
    var intervalTime = 15000;
    var timerId = null;

    function goToSlide(index) {
        slides.forEach(function(slide, i) {
            slide.classList.remove('active');
        });
        dots.forEach(function(dot, i) {
            dot.classList.remove('active');
        });

        slides[index].classList.add('active');
        dots[index].classList.add('active');
        currentIndex = index;
    }

    function nextSlide() {
        var nextIndex = (currentIndex + 1) % totalSlides;
        goToSlide(nextIndex);
    }

    function prevSlide() {
        var prevIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        goToSlide(prevIndex);
    }

    function startAutoPlay() {
        if (timerId) clearInterval(timerId);
        timerId = setInterval(nextSlide, intervalTime);
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            clearInterval(timerId);
            nextSlide();
            startAutoPlay();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            clearInterval(timerId);
            prevSlide();
            startAutoPlay();
        });
    }

    dots.forEach(function(dot, index) {
        dot.addEventListener('click', function() {
            clearInterval(timerId);
            goToSlide(index);
            startAutoPlay();
        });
    });

    startAutoPlay();
});
</script>
@endsection