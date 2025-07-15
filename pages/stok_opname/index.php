<?php
include_once(__DIR__ . '/../../includes/header.php');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Stok Opname (Penyesuaian Stok)</h1>
</div>

<div class="card">
            <div class="card-body">
                        <p class="card-text">Isi kolom "Stok Fisik" dengan hasil hitungan manual di lapangan. Selisih akan terhitung otomatis. Klik "Sesuaikan" pada setiap baris untuk memperbarui stok sistem.</p>
                        <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                                <thead class="table-dark">
                                                            <tr>
                                                                        <th style="width: 30%;">Nama Produk</th>
                                                                        <th class="text-center">Stok Sistem</th>
                                                                        <th class="text-center" style="width: 15%;">Stok Fisik</th>
                                                                        <th class="text-center">Selisih</th>
                                                                        <th class="text-center" style="width: 15%;">Aksi</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            $queryProduk = $pdo->query("SELECT id_produk, nama_produk, stok_saat_ini FROM produk ORDER BY nama_produk ASC");
                                                            while ($produk = $queryProduk->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                        <tr class="align-middle">
                                                                                    <td><?php echo htmlspecialchars($produk['nama_produk']); ?></td>
                                                                                    <td class="text-center fs-5 fw-bold stok-sistem"><?php echo $produk['stok_saat_ini']; ?></td>
                                                                                    <td>
                                                                                                <form action="proses.php" method="POST" class="form-opname">
                                                                                                            <input type="number" class="form-control form-control-lg text-center stok-fisik" name="stok_fisik" min="0" required>
                                                                                                            <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">
                                                                                                            <input type="hidden" class="selisih-hidden" name="selisih" value="0">
                                                                                    </td>
                                                                                    <td class="text-center fs-5 fw-bold selisih">0</td>
                                                                                    <td class="text-center">
                                                                                                <button type="submit" name="action" value="sesuaikan" class="btn btn-primary">
                                                                                                            <i class="fas fa-sync-alt"></i> Sesuaikan
                                                                                                </button>
                                                                                                </form>
                                                                                    </td>
                                                                        </tr>
                                                            <?php } ?>
                                                </tbody>
                                    </table>
                        </div>
            </div>
</div>

<script>
            document.addEventListener('DOMContentLoaded', function() {
                        const rows = document.querySelectorAll('tbody tr');
                        rows.forEach(row => {
                                    const stokSistemEl = row.querySelector('.stok-sistem');
                                    const stokFisikEl = row.querySelector('.stok-fisik');
                                    const selisihEl = row.querySelector('.selisih');
                                    const selisihHiddenEl = row.querySelector('.selisih-hidden');

                                    stokFisikEl.addEventListener('input', function() {
                                                const stokSistem = parseInt(stokSistemEl.textContent, 10) || 0;
                                                const stokFisik = parseInt(this.value, 10) || 0;
                                                const selisih = stokFisik - stokSistem;

                                                selisihEl.textContent = selisih;
                                                selisihHiddenEl.value = selisih;

                                                if (selisih < 0) {
                                                            selisihEl.classList.add('text-danger');
                                                            selisihEl.classList.remove('text-success');
                                                } else if (selisih > 0) {
                                                            selisihEl.classList.add('text-success');
                                                            selisihEl.classList.remove('text-danger');
                                                            selisihEl.textContent = '+' + selisih;
                                                } else {
                                                            selisihEl.classList.remove('text-danger', 'text-success');
                                                }
                                    });
                        });
            });
</script>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>