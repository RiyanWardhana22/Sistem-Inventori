<?php
include_once(__DIR__ . '/../../includes/header.php');
if ($_SESSION['level'] != 'admin') redirect(BASE_URL);
?>

<h1 class="h3 mb-3">Tambah Pengguna Baru</h1>

<div class="card">
            <div class="card-body">
                        <form action="proses.php" method="POST">
                                    <input type="hidden" name="action" value="tambah">
                                    <div class="mb-3">
                                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                                    </div>
                                    <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="mb-3">
                                                <label for="level" class="form-label">Role</label>
                                                <select class="form-select" id="level" name="level" required>
                                                            <option value="pegawai">Pegawai</option>
                                                            <option value="admin">Admin</option>
                                                </select>
                                    </div>
                                    <a href="index.php" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
                        </form>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>