<div class="sidebar">
            <h3 class="text-white text-center mt-3 mb-4">Silmarils</h3>
            <a href="<?php echo BASE_URL; ?>" class="active">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>pages/produk/">
                        <i class="fas fa-box-open me-2"></i> Manajemen Produk
            </a>

            <a href="#stokSubmenu" data-bs-toggle="collapse" class="dropdown-toggle">
                        <i class="fas fa-exchange-alt me-2"></i> Transaksi Stok
            </a>
            <ul class="collapse list-unstyled" id="stokSubmenu">
                        <li>
                                    <a href="<?php echo BASE_URL; ?>pages/stok_masuk/">Stok Masuk (Produksi)</a>
                        </li>
                        <li>
                                    <a href="<?php echo BASE_URL; ?>pages/stok_keluar/">Stok Keluar (Penjualan)</a>
                        </li>
                        <li>
                                    <a href="<?php echo BASE_URL; ?>pages/stok_bs/">Stok BS (Rusak)</a>
                        </li>
            </ul>

            <a href="<?php echo BASE_URL; ?>pages/stok_opname/">
                        <i class="fas fa-clipboard-check me-2"></i> Stok Opname
            </a>
            <a href="#laporanSubmenu" data-bs-toggle="collapse" class="dropdown-toggle">
                        <i class="fas fa-chart-line me-2"></i> Laporan & Analisis
            </a>
            <ul class="collapse list-unstyled" id="laporanSubmenu">
                        <li>
                                    <a href="<?php echo BASE_URL; ?>pages/laporan_stok/">Laporan Stok</a>
                        </li>
                        <li>
                                    <a href="#">Laporan Penjualan</a>
                        </li>
                        <li>
                                    <a href="#">Laporan Produk BS</a>
                        </li>
            </ul>
            <a href="#">
                        <i class="fas fa-cog me-2"></i> Settings
            </a>
</div>