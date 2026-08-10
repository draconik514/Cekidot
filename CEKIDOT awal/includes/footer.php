<!-- includes/footer.php -->
</main>

<!-- ============================================================
   FOOTER
   ============================================================ -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand -->
            <div class="footer-brand">
                <div class="footer-logo">
                    <div class="logo-icon">
                        <img src="assets/img/logo-sulteng.png" alt="Logo Sulawesi Tengah" onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:18px;font-weight:900;color:#eab308;\'>S</span>'">
                    </div>
                    <span class="logo-text">CEK<span>IDOT</span></span>
                </div>
                <p class="footer-desc">
                    CEK IKU DAN DOKUMEN TERPADU - Dinas Pariwisata Provinsi Sulawesi Tengah.
                    Monitoring dan evaluasi capaian kinerja utama secara transparan dan akuntabel.
                </p>
                <div class="footer-social">
                    <a href="https://www.facebook.com/share/1XYixEkxXT/?mibextid=wwXIfr" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.tiktok.com/@dispar.sulteng?_r=1&_t=ZS-988tKvc8ZOu" target="_blank" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.instagram.com/dinaspariwisatasulteng?igsh=MXVobWZ2aWIxdzlyYQ==" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.youtube.com/@DinasPariwisataSulteng" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Informasi -->
            <div class="footer-links">
                <h4>Informasi</h4>
                <ul>
                    <li><a href="https://pariwisata.sultengprov.go.id/profil1.html">Tentang</a></li>
                    <li><a href="https://pariwisata.sultengprov.go.id/profil1/contacts.html">Hubungi Kami</a></li>
                    <li><a href="https://pariwisata.sultengprov.go.id/berita.html">Berita</a></li>
                    <li><a href="https://pariwisata.sultengprov.go.id/informasi-publik/ppid.html">PPID</a></li>
                </ul>
            </div>

            <!-- Layanan -->
            <div class="footer-links">
                <h4>Layanan</h4>
                <ul>
                    <li><a href="iku.php">IKU</a></li>
                    <li><a href="akip.php">AKIP</a></li>
                    <li><a href="iki.php">IKI</a></li>
                    <li><a href="monev.php">Monev</a></li>
                    <li><a href="kirim-surat.php">Persuratan</a></li>
                    <li><a href="capaian.php">Capaian Program</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div class="footer-contact">
                <h4>Kontak</h4>
                <ul>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Jl. Dewi Sartika No.45 Palu, Kode Pos 94121</span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <span>(0451) 483942</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>dispar@sulteng.go.id</span>
                    </li>
                    <li>
                        <i class="fas fa-globe"></i>
                        <span>pariwisata.sultengprov.go.id</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom -->
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> CEKIDOT - Dinas Pariwisata Provinsi Sulawesi Tengah</p>
            <p class="footer-credit">Dibangun dengan <i class="fas fa-heart" style="color:#eab308;"></i> untuk Sulawesi Tengah</p>
        </div>
    </div>
</footer>

<!-- ============================================================
   STYLE FOOTER
   ============================================================ -->
<style>
    .footer {
        background: #0f3b5e;
        color: rgba(255, 255, 255, 0.8);
        padding: 30px 0 0;
        border-top: 3px solid #eab308;
    }

    .footer .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.5fr;
        gap: 40px;
        padding-bottom: 30px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .footer-brand .footer-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .footer-brand .footer-logo .logo-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        padding: 4px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .footer-brand .footer-logo .logo-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .footer-brand .footer-logo .logo-text {
        font-size: 20px;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.3px;
    }

    .footer-brand .footer-logo .logo-text span {
        color: #eab308;
    }

    .footer-brand .footer-desc {
        font-size: 13px;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.6);
        max-width: 320px;
        margin-bottom: 16px;
    }

    .footer-social {
        display: flex;
        gap: 10px;
    }

    .footer-social a {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 14px;
        border: 1px solid rgba(255, 255, 255, 0.04);
    }

    .footer-social a:hover {
        background: #eab308;
        color: #0f3b5e;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(234, 179, 8, 0.30);
        border-color: #eab308;
    }

    .footer-links h4,
    .footer-contact h4 {
        font-size: 14px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 14px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-links h4::after,
    .footer-contact h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 30px;
        height: 2px;
        background: #eab308;
        border-radius: 2px;
    }

    .footer-links ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links ul li {
        margin-bottom: 8px;
    }

    .footer-links ul li a {
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        font-size: 13px;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-links ul li a:hover {
        color: #eab308;
        transform: translateX(4px);
    }

    .footer-contact ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-contact ul li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 10px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.6);
        line-height: 1.5;
    }

    .footer-contact ul li i {
        color: #eab308;
        font-size: 14px;
        margin-top: 2px;
        min-width: 18px;
        text-align: center;
    }

    .footer-contact ul li span {
        flex: 1;
    }

    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding: 16px 0;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.4);
    }

    .footer-bottom .footer-credit {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.3);
    }

    .footer-bottom .footer-credit i {
        color: #eab308;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
    }

    @media (max-width: 768px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .footer-brand .footer-desc {
            max-width: 100%;
        }

        .footer-bottom {
            flex-direction: column;
            text-align: center;
            gap: 4px;
        }

        .footer {
            padding: 30px 0 0;
        }

        .footer .container {
            padding: 0 16px;
        }

        .footer-links h4::after,
        .footer-contact h4::after {
            width: 24px;
        }
    }

    @media (max-width: 480px) {
        .footer {
            padding: 24px 0 0;
        }

        .footer-brand .footer-logo .logo-icon {
            width: 30px;
            height: 30px;
            padding: 3px;
        }

        .footer-brand .footer-logo .logo-text {
            font-size: 17px;
        }

        .footer-brand .footer-desc {
            font-size: 12px;
        }

        .footer-links ul li a,
        .footer-contact ul li {
            font-size: 12px;
        }

        .footer-bottom p {
            font-size: 10px;
        }

        .footer-social a {
            width: 30px;
            height: 30px;
            font-size: 12px;
        }
    }
</style>

<!-- ============================================================
   JAVASCRIPT
   ============================================================ -->
<script src="assets/js/script.js"></script>
</body>
</html>