<?php
include_once(__DIR__ . '/../../../includes/header.php');

$tanggal_filter = $_GET['tanggal'] ?? date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT op.*, p.nama_produk 
    FROM opname_produk op 
    JOIN produk p ON op.kode_produk = p.kode_produk 
    WHERE op.tanggal_opname = ? 
    ORDER BY p.nama_produk ASC
");
$stmt->execute([$tanggal_filter]);
?>


<div class="card">
    <div class="card-header">
        <div class="row align-items-center py-3 gy-3">
            <div class="col-lg-4 col-md-12">
                <h4 class="mb-0">Data Opname Produk</h4>
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
                        <th>Kode</th>
                        <th>Stok Awal</th>
                        <th>Stok Akhir</th>
                        <th>Penjualan</th>
                        <th>BS</th>
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
                                <td><?php echo htmlspecialchars($row['kode']); ?></td>
                                <td><?php echo htmlspecialchars($row['stok_awal']); ?></td>
                                <td><?php echo htmlspecialchars($row['stok_akhir']); ?></td>
                                <td><?php echo htmlspecialchars($row['penjualan']); ?></td>
                                <td><?php echo htmlspecialchars($row['bs']); ?></td>
                                <td class="d-flex justify-content-center gap-2">
                                    <a href="edit.php?id=<?php echo $row['id_opname']; ?>" class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pencil"></i></a>
                                    <a href="proses.php?action=hapus&id=<?php echo $row['id_opname']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus data ini?');"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-4'>Tidak ada data opname untuk tanggal yang dipilih.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include_once(__DIR__ . '/../../../includes/footer.php');
?>