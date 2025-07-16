<?php
function is_active($path_segment)
{
            return strpos($_SERVER['REQUEST_URI'], $path_segment) !== false;
}
?>
<div class="sidebar">
            <a href="<?php echo BASE_URL; ?>" class="sidebar-brand">
                        <span><?php echo defined('NAMA_WEBSITE') ? NAMA_WEBSITE : 'Sistem Inventori'; ?></span>
            </a>
            <hr>

            <a href="<?php echo BASE_URL; ?>" class="<?php echo (strpos($_SERVER['REQUEST_URI'], '/pages/') === false) ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt fa-fw me-2"></i> <span>Dashboard</span>
            </a>

            <?php if ($_SESSION['level'] == 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>pages/produk/" class="<?php echo is_active('pages/produk') ? 'active' : ''; ?>">
                                    <i class="fas fa-box-open fa-fw me-2"></i> <span>Manajemen Produk</span>
                        </a>
            <?php endif; ?>

            <a href="<?php echo BASE_URL; ?>pages/stok_opname/" class="<?php echo is_active('pages/stok_opname') ? 'active' : ''; ?>">
                        <i class="fas fa-clipboard-check fa-fw me-2"></i> <span>Stok Opname</span>
            </a>

            <a href="<?php echo BASE_URL; ?>pages/stok_bs/" class="<?php echo is_active('pages/stok_bs') ? 'active' : ''; ?>">
                        <i class="fas fa-trash-alt fa-fw me-2"></i> <span>Stok Produk BS</span>
            </a>

            <?php if ($_SESSION['level'] == 'admin'): ?>
                        <?php
                        $is_laporan_active = is_active('pages/laporan_opname') || is_active('pages/laporan_bs');
                        ?>
                        <a href="#laporanSubmenu" data-bs-toggle="collapse" class="dropdown-toggle <?php echo $is_laporan_active ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $is_laporan_active ? 'true' : 'false'; ?>">
                                    <i class="fas fa-chart-line fa-fw me-2"></i> <span>Laporan & Analisis</span>
                        </a>
                        <ul class="collapse list-unstyled <?php echo $is_laporan_active ? 'show' : ''; ?>" id="laporanSubmenu">
                                    <li>
                                                <a href="<?php echo BASE_URL; ?>pages/laporan_opname/" class="<?php echo is_active('pages/laporan_opname') ? 'active' : ''; ?>">Laporan Opname</a>
                                    </li>
                                    <li>
                                                <a href="<?php echo BASE_URL; ?>pages/laporan_bs/" class="<?php echo is_active('pages/laporan_bs') ? 'active' : ''; ?>">Analisis Produk BS</a>
                                    </li>
                        </ul>

                        <?php
                        $is_settings_active = is_active('pages/data_staf') || is_active('pages/pengaturan_web');
                        ?>
                        <a href="#settingsSubmenu" data-bs-toggle="collapse" class="dropdown-toggle <?php echo $is_settings_active ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $is_settings_active ? 'true' : 'false'; ?>">
                                    <i class="fas fa-cog fa-fw me-2"></i> <span>Settings</span>
                        </a>
                        <ul class="collapse list-unstyled <?php echo $is_settings_active ? 'show' : ''; ?>" id="settingsSubmenu">
                                    <li>
                                                <a href="<?php echo BASE_URL; ?>pages/data_staf/" class="<?php echo is_active('pages/data_staf') ? 'active' : ''; ?>">Data Staf</a>
                                    </li>
                                    <li>
                                                <a href="<?php echo BASE_URL; ?>pages/pengaturan_web/" class="<?php echo is_active('pages/pengaturan_web') ? 'active' : ''; ?>">Pengaturan Web</a>
                                    </li>
                        </ul>
            <?php endif; ?>
</div>