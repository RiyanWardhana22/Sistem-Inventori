<?php
function is_active($path_segment)
{
            return strpos($_SERVER['REQUEST_URI'], $path_segment) !== false;
}
?>
<div class="sidebar">
            <a href="<?php echo BASE_URL; ?>" class="sidebar-brand">
                        <?php echo defined('NAMA_WEBSITE') ? NAMA_WEBSITE : 'Sistem Inventori'; ?>
            </a>

            <a href="<?php echo BASE_URL; ?>" class="<?php echo (strpos($_SERVER['REQUEST_URI'], '/pages/') === false) ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt fa-fw me-2"></i> Dashboard
            </a>

            <?php if ($_SESSION['level'] == 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>pages/produk/" class="<?php echo is_active('pages/produk') ? 'active' : ''; ?>">
                                    <i class="fas fa-box-open fa-fw me-2"></i> Manajemen Produk
                        </a>
            <?php endif; ?>

            <a href="<?php echo BASE_URL; ?>pages/stok_opname/" class="<?php echo is_active('pages/stok_opname') ? 'active' : ''; ?>">
                        <i class="fas fa-clipboard-check fa-fw me-2"></i> Stok Opname
            </a>

            <a href="<?php echo BASE_URL; ?>pages/stok_bs/" class="<?php echo is_active('pages/stok_bs') ? 'active' : ''; ?>">
                        <i class="fas fa-trash-alt fa-fw me-2"></i> Stok Produk BS
            </a>

            <?php if ($_SESSION['level'] == 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>pages/laporan_opname/" class="<?php echo is_active('pages/laporan_opname') ? 'active' : ''; ?>">
                                    <i class="fas fa-chart-line fa-fw me-2"></i> Laporan Opname
                        </a>
                        <a href="<?php echo BASE_URL; ?>pages/laporan_bs_analisis/" class="<?php echo is_active('pages/laporan_bs_analisis') ? 'active' : ''; ?>">
                                    <i class="fas fa-chart-pie fa-fw me-2"></i> Analisis Produk BS
                        </a>
                        <a href="<?php echo BASE_URL; ?>pages/data_staf/" class="<?php echo is_active('pages/data_staf') ? 'active' : ''; ?>">
                                    <i class="fas fa-users fa-fw me-2"></i> Data Staf
                        </a>
                        <a href="<?php echo BASE_URL; ?>pages/pengaturan_web/" class="<?php echo is_active('pages/pengaturan_web') ? 'active' : ''; ?>">
                                    <i class="fas fa-cog fa-fw me-2"></i> Pengaturan Web
                        </a>
            <?php endif; ?>
</div>