<?php
include_once(__DIR__ . '/../../../includes/header.php');

$records_per_page = 20;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;
$search_date = $_GET['search_date'] ?? '';

// Query untuk menghitung total data
$count_sql = "SELECT COUNT(*) FROM bs_bahan";
if (!empty($search_date)) {
    $count_sql .= " WHERE tanggal_bs = ?";
}
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute(!empty($search_date) ? [$search_date] : []);
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Query untuk mengambil data BS bahan
$sql = "SELECT 
            bs.id_bs,
            bs.nama_bahan,
            bs.tanggal_bs,
            bs.jumlah_bahan,
            bs.satuan_jumlah,
            bs.keterangan
        FROM bs_bahan bs";
if (!empty($search_date)) {
    $sql .= " WHERE bs.tanggal_bs = ?";
}
$sql .= " ORDER BY bs.tanggal_bs DESC, bs.nama_bahan ASC 
          LIMIT ? OFFSET ?";

$stmt = $pdo->prepare($sql);
if (!empty($search_date)) {
    $stmt->bindValue(1, $search_date, PDO::PARAM_STR);
    $stmt->bindValue(2, $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
} else {
    $stmt->bindValue(1, $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
}
$stmt->execute();
?>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center mb-4">
            <div class="col-sm-6 my-2">
                <h1 class="h3 mb-0">Manajemen Bad Stock Bahan</h1>
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
        <form method="GET" action="" class="row">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="date" class="form-control" name="search_date" value="<?php echo htmlspecialchars($search_date); ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calendar me-2"></i> Cari
                    </button>
                    <?php if (!empty($search_date)): ?>
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i> Reset
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal BS</th>
                        <th width="20%">Nama Bahan</th>
                        <th width="15%">Jumlah</th>
                        <th width="10%">Satuan</th>
                        <th width="25%">Keterangan</th>
                        <th width="10%" class="text-center">Aksi</th>
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
                                <td><?php echo date('d/m/Y', strtotime($row['tanggal_bs'])); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_bahan']); ?></td>
                                <td><?php echo is_numeric($row['jumlah_bahan']) ? number_format($row['jumlah_bahan'], 0, ',', '.') : '-'; ?></td>
                                <td><?php echo htmlspecialchars($row['satuan_jumlah'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="edit.php?id=<?php echo $row['id_bs']; ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <a href="proses.php?action=hapus&id=<?php echo $row['id_bs']; ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data BS bahan ini?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center py-4">Tidak ada data BS bahan ditemukan</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?search_date=<?php echo urlencode($search_date); ?>&page=<?php echo $current_page - 1; ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php 
                    $start_page = max(1, $current_page - 2);
                    $end_page = min($total_pages, $current_page + 2);
                    
                    if ($start_page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?search_date='.urlencode($search_date).'&page=1">1</a></li>';
                        if ($start_page > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }
                    
                    for ($i = $start_page; $i <= $end_page; $i++): ?>
                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?search_date=<?php echo urlencode($search_date); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; 
                    
                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="?search_date='.urlencode($search_date).'&page='.$total_pages.'">'.$total_pages.'</a></li>';
                    }
                    ?>
                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?search_date=<?php echo urlencode($search_date); ?>&page=<?php echo $current_page + 1; ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php
include_once(__DIR__ . '/../../../includes/footer.php');
?>