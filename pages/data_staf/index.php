<?php
include_once(__DIR__ . '/../../includes/header.php');

if ($_SESSION['level'] != 'admin') {
            redirect(BASE_URL);
}

$query = $pdo->query("SELECT * FROM pengguna ORDER BY nama_lengkap ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Daftar Pengguna (Staf)</h1>
            <a href="tambah.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah
            </a>
</div>

<div class="card">
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                                <thead class="table-dark">
                                                            <tr>
                                                                        <th>No</th>
                                                                        <th>Nama Lengkap</th>
                                                                        <th>Username</th>
                                                                        <th class="text-center">Role</th>
                                                                        <th class="text-center">Aksi</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            $no = 1;
                                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                        <tr>
                                                                                    <td><?php echo $no++; ?></td>
                                                                                    <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                                                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                                                                    <td class="text-center">
                                                                                                <?php
                                                                                                $badge_class = ($row['level'] == 'admin') ? 'bg-primary' : 'bg-secondary';
                                                                                                echo "<span class='badge {$badge_class}'>" . ucfirst($row['level']) . "</span>";
                                                                                                ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                                <a href="edit.php?id=<?php echo $row['id_pengguna']; ?>" class="btn btn-sm btn-warning">
                                                                                                            <i class="fas fa-edit"></i>
                                                                                                </a>
                                                                                                <?php if ($_SESSION['user_id'] != $row['id_pengguna']): ?>
                                                                                                            <a href="proses.php?action=hapus&id=<?php echo $row['id_pengguna']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                                                                                                        <i class="fas fa-trash"></i>
                                                                                                            </a>
                                                                                                <?php endif; ?>
                                                                                    </td>
                                                                        </tr>
                                                            <?php } ?>
                                                </tbody>
                                    </table>
                        </div>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>