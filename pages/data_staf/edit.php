<?php
include_once(__DIR__ . '/../../includes/header.php');
if ($_SESSION['level'] != 'admin') redirect(BASE_URL);

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM pengguna WHERE id_pengguna = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
?>

<div class="page-header mb-4">
            <div class="row align-items-center">
                        <div class="col-sm-12">
                                    <a href="index.php" class="btn btn-secondary btn-sm mb-3"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
                                    <h1 class="h3 mb-0">Edit Pengguna</h1>
                        </div>
            </div>
</div>

<div class="card">
            <div class="card-header">
                        <h5 class="card-title mb-0">Form Data Staf</h5>
            </div>
            <div class="card-body">
                        <form action="proses.php" method="POST">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id_pengguna" value="<?php echo $user['id_pengguna']; ?>">

                                    <div class="mb-3">
                                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password" name="password">
                                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                    </div>
                                    <div class="mb-3">
                                                <label for="level" class="form-label">Role</label>
                                                <select class="form-select" id="level" name="level" required>
                                                            <option value="pegawai" <?php echo ($user['level'] == 'pegawai') ? 'selected' : ''; ?>>Pegawai</option>
                                                            <option value="admin" <?php echo ($user['level'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                                </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Pengguna</button>
                                    <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                        </form>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>