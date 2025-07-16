<?php
include_once(__DIR__ . '/../../includes/header.php');

$records_per_page = 20;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

$search_term = $_GET['search'] ?? '';
$search_query = "%" . $search_term . "%";

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE nama_produk LIKE ?");
$count_stmt->execute([$search_query]);
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

$stmt = $pdo->prepare("SELECT * FROM produk WHERE nama_produk LIKE ? ORDER BY nama_produk ASC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $search_query, PDO::PARAM_STR);
$stmt->bindValue(2, $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Manajemen Produk</h1>
            <a href="tambah.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Produk
            </a>
</div>

<div class="card mb-4">
            <div class="card-body">
                        <form method="GET" action="" class="d-flex">
                                    <input type="text" class="form-control me-2" name="search" placeholder="Cari nama produk..." value="<?php echo htmlspecialchars($search_term); ?>">
                                    <button type="submit" class="btn btn-primary">Cari</button>
                        </form>
            </div>
</div>


<div class="card">
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                                <thead class="table-dark">
                                                            <tr>
                                                                        <th>No</th>
                                                                        <th>Nama Produk</th>
                                                                        <th class="text-center">Aksi</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            $no = $offset + 1;
                                                            if ($stmt->rowCount() > 0) {
                                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                                    <tr>
                                                                                                <td><?php echo $no++; ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                                                                                                <td class="text-center">
                                                                                                            <a href="edit.php?id=<?php echo $row['id_produk']; ?>" class="btn btn-sm btn-warning">
                                                                                                                        <i class="fas fa-edit"></i> Edit
                                                                                                            </a>
                                                                                                            <a href="proses.php?action=hapus&id=<?php echo $row['id_produk']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                                                                                                        <i class="fas fa-trash"></i> Hapus
                                                                                                            </a>
                                                                                                </td>
                                                                                    </tr>
                                                            <?php
                                                                        }
                                                            } else {
                                                                        echo "<tr><td colspan='3' class='text-center'>Produk tidak ditemukan.</td></tr>";
                                                            }
                                                            ?>
                                                </tbody>
                                    </table>
                        </div>

                        <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                                <?php if ($current_page > 1): ?>
                                                            <li class="page-item">
                                                                        <a class="page-link" href="?search=<?php echo urlencode($search_term); ?>&page=<?php echo $current_page - 1; ?>">Previous</a>
                                                            </li>
                                                <?php endif; ?>

                                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                                                        <a class="page-link" href="?search=<?php echo urlencode($search_term); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                            </li>
                                                <?php endfor; ?>

                                                <?php if ($current_page < $total_pages): ?>
                                                            <li class="page-item">
                                                                        <a class="page-link" href="?search=<?php echo urlencode($search_term); ?>&page=<?php echo $current_page + 1; ?>">Next</a>
                                                            </li>
                                                <?php endif; ?>
                                    </ul>
                        </nav>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>