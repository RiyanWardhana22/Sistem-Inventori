<?php
include_once(__DIR__ . '/../../../includes/header.php');

$id = $_GET['id'] ?? null;
if (!$id) redirect(BASE_URL . 'pages/stok_opname/produk');
$stmt = $pdo->prepare("SELECT * FROM opname_produk WHERE id_opname = ?");
$stmt->execute([$id]);
$opname = $stmt->fetch();
?>

<div class="page-header mb-4">
            <div class="row align-items-center">
                        <div class="col-sm-12">
                                    <h1 class="h3 mb-0">Edit Data Opname</h1>
                        </div>
            </div>
</div>

<div class="card">
            <div class="card-body">
                        <form action="proses.php" method="POST">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id_opname" value="<?php echo $opname['id_opname']; ?>">

                                    <div class="row">
                                                <div class="col-md-6 mb-3">
                                                            <label for="tanggal_opname" class="form-label">Tanggal</label>
                                                            <input type="date" class="form-control" id="tanggal_opname" name="tanggal_opname" value="<?php echo $opname['tanggal_opname']; ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                            <label for="kode_produk" class="form-label">Nama Produk</label>
                                                            <select class="form-select" id="kode_produk" name="kode_produk" required>
                                                                        <?php
                                                                        $queryProduk = $pdo->query("SELECT kode_produk, nama_produk FROM produk ORDER BY nama_produk ASC");
                                                                        while ($produk = $queryProduk->fetch(PDO::FETCH_ASSOC)) {
                                                                                    $selected = ($produk['kode_produk'] == $opname['kode_produk']) ? 'selected' : '';
                                                                                    echo "<option value='{$produk['kode_produk']}' {$selected}>{$produk['nama_produk']}</option>";
                                                                        }
                                                                        ?>
                                                            </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                            <label for="kode" class="form-label">Kode</label>
                                                            <input type="text" class="form-control" id="kode" name="kode" value="<?php echo htmlspecialchars($opname['kode']); ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                            <label for="stok_awal" class="form-label">Stok Awal</label>
                                                            <input type="text" class="form-control" id="stok_awal" name="stok_awal" value="<?php echo htmlspecialchars($opname['stok_awal']); ?>">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                            <label for="stok_akhir" class="form-label">Stok Akhir</label>
                                                            <input type="text" class="form-control" id="stok_akhir" name="stok_akhir" value="<?php echo htmlspecialchars($opname['stok_akhir']); ?>">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                            <label for="penjualan" class="form-label">Penjualan</label>
                                                            <input type="text" class="form-control" id="penjualan" name="penjualan" value="<?php echo htmlspecialchars($opname['penjualan']); ?>">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                            <label for="bs" class="form-label">BS</label>
                                                            <input type="text" class="form-control" id="bs" name="bs" value="<?php echo htmlspecialchars($opname['bs']); ?>">
                                                </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update Data</button>
                                    <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                        </form>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../../includes/footer.php');
?>