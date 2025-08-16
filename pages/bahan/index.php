<?php
include_once(__DIR__ . '/../../includes/header.php');

$records_per_page = 20;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;
$search_term = $_GET['search'] ?? '';
$search_query = "%" . $search_term . "%";

// Query untuk menghitung total data
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM bahan WHERE nama_bahan LIKE ?");
$count_stmt->execute([$search_query]);
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Query untuk mengambil data bahan
$stmt = $pdo->prepare("SELECT * FROM bahan 
                      WHERE nama_bahan LIKE ?
                      ORDER BY nama_bahan ASC 
                      LIMIT ? OFFSET ?");
$stmt->bindValue(1, $search_query, PDO::PARAM_STR);
$stmt->bindValue(2, $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
?>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center mb-4">
            <div class="col-sm-6 my-2">
                <h1 class="h3 mb-0">Manajemen Bahan</h1>
            </div>
            <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                <div class="d-flex justify-content-end gap-2">
                    <a href="tambah.php" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus me-2"></i> Tambah
                    </a>
                    <a href="export_excel.php" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-file-excel me-2"></i> Export
                    </a>
                </div>
            </div>
        </div>
        <form method="GET" action="">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Cari nama bahan..." value="<?php echo htmlspecialchars($search_term); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i> Cari
                </button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama Bahan</th>
                        <th width="15%">Jumlah</th>
                        <th width="10%">Satuan</th>
                        <th width="15%">Expired</th>
                        <th width="15%">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $offset + 1;
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Cek status expired jika ada tanggal expired
                            $status = $row['status'];
                            if ($row['tanggal_expired'] !== null) {
                                $today = new DateTime();
                                $expired_date = new DateTime($row['tanggal_expired']);
                                if ($expired_date < $today) {
                                    $status = 'Expired';
                                }
                            }
                            
                            // Tentukan warna badge berdasarkan status
                            $badge_color = '';
                            if ($status == 'Layak') {
                                $badge_color = 'success';
                            } elseif ($status == 'Rusak') {
                                $badge_color = 'warning';
                            } elseif ($status == 'Expired') {
                                $badge_color = 'danger';
                            }
                    ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_bahan']); ?></td>
                                <td><?php echo number_format($row['jumlah_bahan'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($row['satuan_jumlah']); ?></td>
                                <td class="<?php echo ($status == 'Expired') ? 'text-danger fw-bold' : ''; ?>">
                                    <?php echo $row['tanggal_expired'] ? date('d/m/Y', strtotime($row['tanggal_expired'])) : '-'; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $badge_color; ?>">
                                        <?php echo $status; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="edit.php?id=<?php echo urlencode($row['nama_bahan']); ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <a href="proses.php?action=hapus&id=<?php echo urlencode($row['nama_bahan']); ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus bahan ini?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center py-4">Tidak ada data bahan ditemukan</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?search=<?php echo urlencode($search_term); ?>&page=<?php echo $current_page - 1; ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php 
                    $start_page = max(1, $current_page - 2);
                    $end_page = min($total_pages, $current_page + 2);
                    
                    if ($start_page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?search='.urlencode($search_term).'&page=1">1</a></li>';
                        if ($start_page > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }
                    
                    for ($i = $start_page; $i <= $end_page; $i++): ?>
                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?search=<?php echo urlencode($search_term); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; 
                    
                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="?search='.urlencode($search_term).'&page='.$total_pages.'">'.$total_pages.'</a></li>';
                    }
                    ?>
                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?search=<?php echo urlencode($search_term); ?>&page=<?php echo $current_page + 1; ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>