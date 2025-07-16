<?php
include_once(__DIR__ . '/../../includes/header.php');

$tanggal_filter = $_GET['tanggal'] ?? date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT bs.*, p.nama_produk, p.kode_sku 
    FROM stok_bs bs 
    JOIN produk p ON bs.id_produk = p.id_produk 
    WHERE bs.tanggal_bs = ? 
    ORDER BY p.nama_produk ASC
");
$stmt->execute([$tanggal_filter]);
?>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center py-3 gy-3">
            <div class="col-lg-4 col-md-12">
                <h4 class="mb-0">Stok Produk BS</h4>
            </div>

            <div class="col-lg-8 col-md-12">
                <div class="d-flex align-items-center justify-content-lg-end">
                    <form method="GET" action="" class="d-flex me-2">
                        <input type="date" class="form-control" name="tanggal" value="<?php echo $tanggal_filter; ?>">
                        <button type="submit" class="btn btn-primary ms-2">Lihat</button>
                    </form>
                    <a href="tambah.php" class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-header border-top">
        Menampilkan Data untuk Tanggal: <strong><?php echo format_hari_tanggal($tanggal_filter); ?></strong>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kode Produk</th>
                        <th class="text-center">Jumlah</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                                <td><?php echo htmlspecialchars($row['kode_sku']); ?></td>
                                <td class="text-center"><?php echo $row['jumlah']; ?></td>
                                <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                                <td class="d-flex justify-content-center gap-2">
                                    <a href="edit.php?id=<?php echo $row['id_bs']; ?>" class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pencil"></i></a>
                                    <a href="proses.php?action=hapus&id=<?php echo $row['id_bs']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus data ini?');"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4'>Tidak ada data Stok BS untuk tanggal yang dipilih.</td></tr>";
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