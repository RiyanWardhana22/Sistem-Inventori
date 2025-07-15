<?php
include_once(__DIR__ . '/../../includes/header.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
            redirect(BASE_URL . 'pages/laporan_stok/');
}

$id_produk = $_GET['id'];

$stmtProduk = $pdo->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmtProduk->execute([$id_produk]);
$produk = $stmtProduk->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
            redirect(BASE_URL . 'pages/laporan_stok/');
}

$stmtRiwayat = $pdo->prepare("SELECT * FROM riwayat_stok WHERE id_produk = ? ORDER BY tanggal_transaksi DESC, id_riwayat DESC");
$stmtRiwayat->execute([$id_produk]);
$riwayat_list = $stmtRiwayat->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Kembali ke Laporan Stok</a>
<h1 class="h3 mb-1">Kartu Stok: <?php echo htmlspecialchars($produk['nama_produk']); ?></h1>
<p class="mb-3">Kode SKU: <?php echo htmlspecialchars($produk['kode_sku']); ?></p>

<div class="card">
            <div class="card-header">
                        Riwayat Detail Transaksi (Stok Saat Ini: <strong class="fs-5"><?php echo $produk['stok_saat_ini']; ?> pcs</strong>)
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-bordered">
                                                <thead class="table-light">
                                                            <tr>
                                                                        <th>Tanggal</th>
                                                                        <th>Tipe Transaksi</th>
                                                                        <th class="text-center">Jumlah</th>
                                                                        <th>Keterangan</th>
                                                                        <th class="text-center">Stok Akhir</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            $stok_berjalan = $produk['stok_saat_ini'];

                                                            if (count($riwayat_list) > 0) {
                                                                        foreach ($riwayat_list as $riwayat) {
                                                                                    $jumlah = (int)$riwayat['jumlah'];
                                                                                    $tipe = $riwayat['tipe_transaksi'];

                                                                                    $stok_akhir_saat_itu = $stok_berjalan;
                                                            ?>
                                                                                    <tr>
                                                                                                <td><?php echo date('d M Y, H:i', strtotime($riwayat['tanggal_transaksi'])); ?></td>
                                                                                                <td>
                                                                                                            <?php
                                                                                                            $badge_class = 'bg-secondary';
                                                                                                            if ($tipe == 'masuk') $badge_class = 'bg-success';
                                                                                                            if ($tipe == 'keluar') $badge_class = 'bg-danger';
                                                                                                            if ($tipe == 'bs') $badge_class = 'bg-warning text-dark';
                                                                                                            if ($tipe == 'penyesuaian') $badge_class = 'bg-info text-dark';
                                                                                                            echo "<span class='badge {$badge_class}'>" . ucfirst($tipe) . "</span>";
                                                                                                            ?>
                                                                                                </td>
                                                                                                <td class="text-center fw-bold">
                                                                                                            <?php
                                                                                                            if ($tipe == 'masuk' || ($tipe == 'penyesuaian' && $jumlah > 0)) {
                                                                                                                        echo "<span class='text-success'>+{$jumlah}</span>";
                                                                                                            } else {
                                                                                                                        echo "<span class='text-danger'>-{$jumlah}</span>";
                                                                                                            }
                                                                                                            ?>
                                                                                                </td>
                                                                                                <td><?php echo htmlspecialchars($riwayat['keterangan']); ?></td>
                                                                                                <td class="text-center fw-bold"><?php echo $stok_akhir_saat_itu; ?></td>
                                                                                    </tr>
                                                            <?php
                                                                                    if ($tipe == 'masuk' || ($tipe == 'penyesuaian' && $jumlah > 0)) {
                                                                                                $stok_berjalan -= $jumlah;
                                                                                    } else {
                                                                                                $stok_berjalan += $jumlah;
                                                                                    }
                                                                        }
                                                            } else {
                                                                        echo "<tr><td colspan='5' class='text-center'>Tidak ada riwayat transaksi untuk produk ini.</td></tr>";
                                                            }
                                                            ?>
                                                </tbody>
                                    </table>
                        </div>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>