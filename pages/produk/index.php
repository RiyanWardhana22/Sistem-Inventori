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


<div class="card">
            <div class="card-header">
                        <div class="row align-items-center mb-4">
                                    <div class="col-sm-6">
                                                <h1 class="h3 mb-0">Manajemen Produk</h1>
                                    </div>
                                    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                                                <a href="tambah.php" class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-plus me-2"></i> Tambah
                                                </a>
                                    </div>
                        </div>
                        <form method="GET" action="">
                                    <div class="input-group">
                                                <input type="text" class="form-control" name="search" placeholder="Cari nama produk..." value="<?php echo htmlspecialchars($search_term); ?>">
                                                <button type="submit" class="btn btn-primary">Cari</button>
                                    </div>
                        </form>
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-modern">
                                                <thead>
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
                                                                                                <td class="d-flex justify-content-center gap-2">
                                                                                                            <a href="edit.php?id=<?php echo $row['id_produk']; ?>" class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pencil"></i></a>
                                                                                                            <a href="proses.php?action=hapus&id=<?php echo $row['id_produk']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin?');"><i class="fa-solid fa-trash"></i></a>
                                                                                                </td>
                                                                                    </tr>
                                                            <?php
                                                                        }
                                                            } else {
                                                                        echo "<tr><td colspan='3' class='text-center py-4'>Produk tidak ditemukan.</td></tr>";
                                                            }
                                                            ?>
                                                </tbody>
                                    </table>
                        </div>

                        <?php if ($total_pages > 1): ?>
                                    <nav aria-label="Page navigation" class="mt-4">
                                                <ul class="pagination justify-content-end">
                                                            <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                                                        <a class="page-link" href="?search=<?php echo urlencode($search_term); ?>&page=<?php echo $current_page - 1; ?>">Previous</a>
                                                            </li>
                                                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                                                                    <a class="page-link" href="?search=<?php echo urlencode($search_term); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                                        </li>
                                                            <?php endfor; ?>
                                                            <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                                                        <a class="page-link" href="?search=<?php echo urlencode($search_term); ?>&page=<?php echo $current_page + 1; ?>">Next</a>
                                                            </li>
                                                </ul>
                                    </nav>
                        <?php endif; ?>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>