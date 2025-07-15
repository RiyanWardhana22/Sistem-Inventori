<?php
include_once(__DIR__ . '/../../includes/header.php');
if ($_SESSION['level'] != 'admin') redirect(BASE_URL);
?>

<h1 class="h3 mb-3">Pengaturan Website</h1>

<div class="card">
            <div class="card-body">
                        <form action="proses.php" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                                <label for="nama_website" class="form-label">Nama Website</label>
                                                <input type="text" class="form-control" id="nama_website" name="nama_website" value="<?php echo NAMA_WEBSITE; ?>" required>
                                    </div>
                                    <div class="mb-4">
                                                <label for="favicon" class="form-label">Upload Favicon Baru</label>
                                                <div class="d-flex align-items-center">
                                                            <img src="<?php echo BASE_URL . PATH_FAVICON; ?>" alt="Current Favicon" class="me-3" style="width:32px; height:32px;">
                                                            <input class="form-control" type="file" id="favicon" name="favicon">
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