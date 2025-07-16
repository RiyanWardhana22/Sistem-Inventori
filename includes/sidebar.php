<?php
$current_page = $_SERVER['REQUEST_URI'];
?>
<div class="sidebar">
            <h3 class="text-white text-center mt-3 mb-4"><?php echo defined('NAMA_WEBSITE') ? NAMA_WEBSITE : 'Sistem Inventori'; ?></h3>

            <a href="<?php echo BASE_URL; ?>" class="<?php echo (strpos($current_page, '/pages/') === false) ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>

            <?php if ($_SESSION['level'] == 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>pages/produk/" class="<?php echo (strpos($current_page, 'pages/produk') !== false) ? 'active' : ''; ?>">
                                    <i class="fas fa-box-open me-2"></i> Manajemen Produk
                        </a>
            <?php endif; ?>

            <a href="<?php echo BASE_URL; ?>pages/stok_opname/" class="<?php echo (strpos($current_page, 'pages/stok_opname') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-clipboard-check me-2"></i> Stok Opname
            </a>

            <a href="<?php echo BASE_URL; ?>pages/stok_bs/" class="<?php echo (strpos($current_page, 'pages/stok_bs') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-trash-alt me-2"></i> Stok Produk BS
            </a>


            <?php if ($_SESSION['level'] == 'admin'): ?>
                        <?php
                        $is_laporan_active = strpos($current_page, 'laporan_opname') !== false ||
                                    strpos($current_page, 'laporan_bs') !== false;
                        ?>
                        <a href="#laporanSubmenu" data-bs-toggle="collapse" class="dropdown-toggle <?php echo $is_laporan_active ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $is_laporan_active ? 'true' : 'false'; ?>">
                                    <i class="fas fa-chart-line me-2"></i> Laporan & Analisis
                        </a>
                        <ul class="collapse list-unstyled <?php echo $is_laporan_active ? 'show' : ''; ?>" id="laporanSubmenu">
                                    <li>
                                                <a href="<?php echo BASE_URL; ?>pages/laporan_opname/" class="<?php echo (strpos($current_page, 'laporan_opname') !== false) ? 'active-child' : ''; ?>">Laporan Opname</a>
                                    </li>
                                    <li>
                                                <a href="<?php echo BASE_URL; ?>pages/laporan_bs/" class="<?php echo (strpos($current_page, 'laporan_bs') !== false) ? 'active-child' : ''; ?>">Analisis Produk BS</a>
                                    </li>
                        </ul>
            <?php endif; ?>

            <?php if ($_SESSION['level'] == 'admin'): ?>
                        <a href="#settingsSubmenu" data-bs-toggle="collapse" class="dropdown-toggle collapsed" aria-expanded="false">
                                    <i class="fas fa-cog me-2"></i> Settings
                        </a>
                        <ul class="collapse list-unstyled" id="settingsSubmenu">
                                    <li>
                                                <a href="<?php echo BASE_URL; ?>pages/data_staf/">Data Staf</a>
                                    </li>
                                    <li>
                                                <a href="<?php echo BASE_URL; ?>pages/pengaturan_web/">Pengaturan Web</a>
                                    </li>
                        </ul>
            <?php endif; ?>
</div>