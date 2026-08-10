<?php
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <img src="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) ? 'img/logo-sulteng.png' : '../admin/img/logo-sulteng.png' ?>" alt="Logo" onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:28px;font-weight:900;color:#eab308;\'>S</span>'">
        </div>
        <div class="brand-text">
            <h2>CEK<span>IDOT</span></h2>
            <small>Admin Panel</small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="slider.php" class="<?= basename($_SERVER['PHP_SELF']) == 'slider.php' ? 'active' : '' ?>"><i class="fas fa-images"></i><span>Slider</span></a></li>
            <li>
                <a href="surat-masuk.php" class="<?= basename($_SERVER['PHP_SELF']) == 'surat-masuk.php' ? 'active' : '' ?>">
                    <i class="fas fa-inbox"></i>
                    <span>Surat Masuk</span>
                    <?php if (isset($total_baru) && $total_baru > 0): ?>
                    <span class="badge" style="background:#ef4444; color:#fff; font-size:10px; font-weight:600; padding:0 8px; min-width:18px; height:18px; border-radius:999px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; line-height:1;"><?= $total_baru ?></span>
                    <?php else: ?>
                    <span class="badge zero" style="background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.35); font-size:9px; font-weight:500; padding:0 8px; min-width:18px; height:18px; border-radius:999px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; line-height:1;">0</span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="akip.php" class="<?= basename($_SERVER['PHP_SELF']) == 'akip.php' ? 'active' : '' ?>"><i class="fas fa-clipboard-check"></i><span>Dokumen AKIP</span></a></li>
            <li><a href="iki.php" class="<?= basename($_SERVER['PHP_SELF']) == 'iki.php' ? 'active' : '' ?>"><i class="fas fa-user-check"></i><span>Dokumen IKI</span></a></li>
            <li><a href="iku.php" class="<?= basename($_SERVER['PHP_SELF']) == 'iku.php' ? 'active' : '' ?>"><i class="fas fa-chart-line"></i><span>IKU</span></a></li>
            <li><a href="capaian.php" class="<?= basename($_SERVER['PHP_SELF']) == 'capaian.php' ? 'active' : '' ?>"><i class="fas fa-flag-checkered"></i><span>Capaian Program</span></a></li>
            <li><a href="monev.php" class="<?= basename($_SERVER['PHP_SELF']) == 'monev.php' ? 'active' : '' ?>"><i class="fas fa-chart-pie"></i><span>Monev Renaksi</span></a></li>
            <li class="nav-divider"></li>
            <li class="nav-logout"><a href="../logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="datetime">
            <div class="date"><i class="fas fa-calendar-alt"></i> <span id="sidebarDate"><?= date('d F Y') ?></span></div>
            <div class="time"><i class="fas fa-clock"></i> <span id="sidebarClock">00:00:00</span></div>
        </div>
    </div>
</aside>