<?php
include_once(__DIR__ . '/../../includes/header.php');
if ($_SESSION['level'] != 'admin') redirect(BASE_URL);
?>

<div class="card">
            <div class="card-header">
                        <h5 class="card-title my-2">Form Pengaturan</h5>
            </div>
            <div class="card-body">
                        <form action="proses.php" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                                <label for="nama_website" class="form-label">Nama Website</label>
                                                <input type="text" class="form-control" id="nama_website" name="nama_website" value="<?php echo htmlspecialchars(NAMA_WEBSITE); ?>" required>
                                    </div>
                                    <div class="mb-4">
                                                <label for="favicon" class="form-label">Upload Favicon Baru</label>
                                                <div class="d-flex align-items-center">
                                                            <img src="<?php echo BASE_URL . PATH_FAVICON; ?>?t=<?php echo time(); ?>" alt="Current Favicon" class="me-3 border p-1" style="width:32px; height:32px;">
                                                            <input class="form-control" type="file" id="favicon" name="favicon" accept="image/png, image/jpeg, image/x-icon">
                                                </div>
                                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah favicon. Tipe file: .ico, .png, .jpg</small>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                        </form>
            </div>
</div>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>