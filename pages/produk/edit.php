<?php
include_once(__DIR__ . '/../../includes/header.php');

if (!isset($_GET['id'])) {
            redirect(BASE_URL . 'pages/produk/');
}

$id_produk = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmt->execute([$id_produk]);
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
            redirect(BASE_URL . 'pages/produk/');
}
?>

<h1 class="h3 mb-3">Edit Produk</h1>

<div class="card">
            <div class="card-body">
                        <form action="proses.php" method="POST">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">

                                    <div class="mb-3">
                                                <label for="nama_produk" class="form-label">Nama Produk</label>
                                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($produk['nama_produk']); ?>" required>
                                    </div>

                                    <a href="index.php" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">Update Produk</button>
                        </form>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>