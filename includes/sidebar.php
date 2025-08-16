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

    <div class="sidebar-scrollable">
        <a href="<?php echo BASE_URL; ?>" class="<?php echo (strpos($_SERVER['REQUEST_URI'], '/pages/') === false) ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt fa-fw me-2"></i> <span>Dashboard</span>
        </a>

        <?php
        $is_daftar_active = is_active('pages/produk') || is_active('pages/bahan');
        ?>
        <a href="#daftarSubmenu" data-bs-toggle="collapse" class="dropdown-toggle <?php echo $is_daftar_active ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $is_daftar_active ? 'true' : 'false'; ?>">
            <i class="fas fa-boxes fa-fw me-2"></i> <span>Daftar Produk & Bahan</span>
        </a>
        <ul class="collapse list-unstyled <?php echo $is_daftar_active ? 'show' : ''; ?>" id="daftarSubmenu">
            <li>
                <a href="<?php echo BASE_URL; ?>pages/produk/" class="<?php echo is_active('pages/produk') ? 'active' : ''; ?>">
                    <i class="fas fa-box-open fa-fw me-2"></i> Daftar Produk
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>pages/bahan/" class="<?php echo is_active('pages/bahan') ? 'active' : ''; ?>">
                    <i class="fas fa-box fa-fw me-2"></i> Daftar Bahan
                </a>
            </li>
        </ul>

        <?php
        $is_opname_active = is_active('pages/stok_opname/produk') || is_active('pages/stok_opname/bahan');
        ?>
        <a href="#opnameSubmenu" data-bs-toggle="collapse" class="dropdown-toggle <?php echo $is_opname_active ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $is_opname_active ? 'true' : 'false'; ?>">
            <i class="fas fa-clipboard-check fa-fw me-2"></i> <span>Stock Opname</span>
        </a>
        <ul class="collapse list-unstyled <?php echo $is_opname_active ? 'show' : ''; ?>" id="opnameSubmenu">
            <li>
                <a href="<?php echo BASE_URL; ?>pages/stok_opname/produk/" class="<?php echo is_active('pages/stok_opname/produk') ? 'active' : ''; ?>">Opname Produk</a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>pages/stok_opname/bahan/" class="<?php echo is_active('pages/stok_opname/bahan') ? 'active' : ''; ?>">Opname Bahan</a>
            </li>
        </ul>

        <?php
        $is_bs_active = is_active('pages/stok_bs/produk') || is_active('pages/stok_bs/bahan');
        ?>
        <a href="#bsSubmenu" data-bs-toggle="collapse" class="dropdown-toggle <?php echo $is_bs_active ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $is_bs_active ? 'true' : 'false'; ?>">
            <i class="fas fa-trash-alt fa-fw me-2"></i> <span>Stock BS</span>
        </a>
        <ul class="collapse list-unstyled <?php echo $is_bs_active ? 'show' : ''; ?>" id="bsSubmenu">
            <li>
                <a href="<?php echo BASE_URL; ?>pages/stok_bs/produk/" class="<?php echo is_active('pages/stok_bs/produk') ? 'active' : ''; ?>">BS Produk</a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>pages/stok_bs/bahan/" class="<?php echo is_active('pages/stok_bs/bahan') ? 'active' : ''; ?>">BS Bahan</a>
            </li>
        </ul>

        <?php if ($_SESSION['level'] == 'admin'): ?>
            <?php
            $is_laporan_active = is_active('pages/laporan_opname') || is_active('pages/laporan_bs') || 
                                is_active('pages/laporan_opname/produk') || is_active('pages/laporan_opname/bahan') || 
                                is_active('pages/laporan_bs/produk') || is_active('pages/laporan_bs/bahan');
            ?>
            <a href="#laporanSubmenu" data-bs-toggle="collapse" class="dropdown-toggle <?php echo $is_laporan_active ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $is_laporan_active ? 'true' : 'false'; ?>">
                <i class="fas fa-chart-line fa-fw me-2"></i> <span>Laporan & Analisis</span>
            </a>
            <ul class="collapse list-unstyled <?php echo $is_laporan_active ? 'show' : ''; ?>" id="laporanSubmenu">
                <li>
                    <a href="#laporanOpnameSubmenu" data-bs-toggle="collapse" class="dropdown-toggle <?php echo (is_active('pages/laporan_opname')) || is_active('pages/laporan_opname/produk') || is_active('pages/laporan_opname/bahan') ? '' : 'collapsed'; ?>">
                        Laporan Opname
                    </a>
                    <ul class="collapse list-unstyled <?php echo (is_active('pages/laporan_opname')) || is_active('pages/laporan_opname/produk') || is_active('pages/laporan_opname/bahan') ? 'show' : ''; ?>" id="laporanOpnameSubmenu">
                        <li>
                            <a href="<?php echo BASE_URL; ?>pages/laporan_opname/produk/" class="<?php echo is_active('pages/laporan_opname/produk') ? 'active' : ''; ?>">
                                <i class="fas fa-box-open fa-fw me-2"></i> Opname Produk
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>pages/laporan_opname/bahan/" class="<?php echo is_active('pages/laporan_opname/bahan') ? 'active' : ''; ?>">
                                <i class="fas fa-box fa-fw me-2"></i> Opname Bahan
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#analisisBsSubmenu" data-bs-toggle="collapse" class="dropdown-toggle <?php echo (is_active('pages/laporan_bs')) || is_active('pages/laporan_bs/produk') || is_active('pages/laporan_bs/bahan') ? '' : 'collapsed'; ?>">
                        Analisis BS
                    </a>
                    <ul class="collapse list-unstyled <?php echo (is_active('pages/laporan_bs')) || is_active('pages/laporan_bs/produk') || is_active('pages/laporan_bs/bahan') ? 'show' : ''; ?>" id="analisisBsSubmenu">
                        <li>
                            <a href="<?php echo BASE_URL; ?>pages/laporan_bs/produk/" class="<?php echo is_active('pages/laporan_bs/produk') ? 'active' : ''; ?>">
                                <i class="fas fa-box-open fa-fw me-2"></i> BS Produk
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>pages/laporan_bs/bahan/" class="<?php echo is_active('pages/laporan_bs/bahan') ? 'active' : ''; ?>">
                                <i class="fas fa-box fa-fw me-2"></i> BS Bahan
                            </a>
                        </li>
                    </ul>
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
</div>

<style>
    .sidebar {
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden; /* Menghilangkan scroll horizontal */
    }
    
    .sidebar-scrollable {
        flex: 1;
        overflow-y: auto; /* Hanya scroll vertikal */
        overflow-x: hidden; /* Menghilangkan scroll horizontal */
        padding-right: 5px;
    }
    
    /* Scrollbar styling vertikal saja */
    .sidebar-scrollable::-webkit-scrollbar {
        width: 6px; /* Lebar scrollbar vertikal */
    }
    
    .sidebar-scrollable::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .sidebar-scrollable::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .sidebar-scrollable::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Dropdown menu styling */
    .sidebar .collapse {
        margin-left: 15px;
    }
    
    .sidebar .list-unstyled li a {
        padding: 8px 15px;
        display: flex;
        align-items: center;
        white-space: nowrap; /* Mencegah text wrapping */
    }
    
    .sidebar .list-unstyled li a i {
        margin-right: 8px;
        width: 20px;
        text-align: center;
    }
    
    /* Nested submenu styling */
    .sidebar .list-unstyled ul {
        margin-left: 15px;
    }
</style>