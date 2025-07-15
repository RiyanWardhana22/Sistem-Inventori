<?php
$current_page = $_SERVER['REQUEST_URI'];
?>
<div class="sidebar">
            <h3 class="text-white text-center mt-3 mb-4">Silmarils</h3>

            <a href="<?php echo BASE_URL; ?>" class="<?php echo (strpos($current_page, '/pages/') === false) ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>

            <?php if ($_SESSION['level'] == 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>pages/produk/" class="<?php echo (strpos($current_page, 'pages/produk') !== false) ? 'active' : ''; ?>">
                                    <i class="fas fa-box-open me-2"></i> Manajemen Produk
                        </a>
            <?php endif; ?>

            <?php
            $is_transaksi_active = strpos($current_page, 'stok_masuk') !== false ||
                        strpos($current_page, 'stok_keluar') !== false ||
                        strpos($current_page, 'stok_bs') !== false;
            ?>
            <a href="#stokSubmenu" data-bs-toggle="collapse" class="dropdown-toggle <?php echo $is_transaksi_active ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $is_transaksi_active ? 'true' : 'false'; ?>">
                        <i class="fas fa-exchange-alt me-2"></i> Transaksi Stok
            </a>
            <ul class="collapse list-unstyled <?php echo $is_transaksi_active ? 'show' : ''; ?>" id="stokSubmenu">
                        <li>
                                    <a href="<?php echo BASE_URL; ?>pages/stok_masuk/" class="<?php echo (strpos($current_page, 'stok_masuk') !== false) ? 'active-child' : ''; ?>">Stok Masuk (Produksi)</a>
                        </li>
                        <li>
                                    <a href="<?php echo BASE_URL; ?>pages/stok_keluar/" class="<?php echo (strpos($current_page, 'stok_keluar') !== false) ? 'active-child' : ''; ?>">Stok Keluar (Penjualan)</a>
                        </li>
                        <li>
                                    <a href="<?php echo BASE_URL; ?>pages/stok_bs/" class="<?php echo (strpos($current_page, 'stok_bs') !== false) ? 'active-child' : ''; ?>">Stok BS (Rusak)</a>
                        </li>
            </ul>

            <a href="<?php echo BASE_URL; ?>pages/stok_opname/" class="<?php echo (strpos($current_page, 'pages/stok_opname') !== false) ? 'active' : ''; ?>">
                        <i class="fas fa-clipboard-check me-2"></i> Stok Opname
            </a>

            <?php if ($_SESSION['level'] == 'admin'): ?>
                        <?php
                        $is_laporan_active = strpos($current_page, 'laporan_stok') !== false ||
                                    strpos($current_page, 'laporan_penjualan') !== false ||
                                    strpos($current_page, 'laporan_bs') !== false;
                        ?>
                        <a href="#laporanSubmenu" data-bs-toggle="collapse" class="dropdown-toggle <?php echo $is_laporan_active ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $is_laporan_active ? 'true' : 'false'; ?>">
                                    <i class="fas fa-chart-line me-2"></i> Laporan & Analisis
                        </a>
                        <ul class="collapse list-unstyled <?php echo $is_laporan_active ? 'show' : ''; ?>" id="laporanSubmenu">
                                    <li>
                                                <a href="<?php echo BASE_URL; ?>pages/laporan_stok/" class="<?php echo (strpos($current_page, 'laporan_stok') !== false) ? 'active-child' : ''; ?>">Laporan Stok</a>
                                    </li>
                                    <li>
                                                <a href="<?php echo BASE_URL; ?>pages/laporan_penjualan/" class="<?php echo (strpos($current_page, 'laporan_penjualan') !== false) ? 'active-child' : ''; ?>">Laporan Penjualan</a>
                                    </li>
                                    <li>
                                                <a href="<?php echo BASE_URL; ?>pages/laporan_bs/" class="<?php echo (strpos($current_page, 'laporan_bs') !== false) ? 'active-child' : ''; ?>">Laporan Produk BS</a>
                                    </li>
                        </ul>
            <?php endif; ?>
</div>