<?php
include_once(__DIR__ . '/../../../includes/header.php');

$tanggal_akhir = $_GET['tanggal_akhir'] ?? date('Y-m-d');
$tanggal_mulai = $_GET['tanggal_mulai'] ?? date('Y-m-d', strtotime('-30 days', strtotime($tanggal_akhir)));
$stmt = $pdo->prepare("
    SELECT 
        p.nama_produk, 
        SUM(bs.jumlah) as total_kuantitas,
        COUNT(bs.id_bs) as frekuensi
    FROM stok_bs bs
    JOIN produk p ON bs.kode_produk = p.kode_produk
    WHERE bs.tanggal_bs BETWEEN ? AND ?
    GROUP BY p.kode_produk, p.nama_produk
    ORDER BY total_kuantitas DESC
");
$stmt->execute([$tanggal_mulai, $tanggal_akhir]);
$data_bs = $stmt->fetchAll(PDO::FETCH_ASSOC);
$labels = [];
$chart_data = [];
foreach ($data_bs as $item) {
  $labels[] = $item['nama_produk'];
  $chart_data[] = $item['total_kuantitas'];
}
?>

<div class="card mb-4">
  <div class="card-header bg-white border-0 pb-0">
    <div class="my-2">
      <h1 class="h3 mb-0">Analisis Produk BS</h1>
    </div>
    <hr>
    <h5 class="card-title mb-0">Filter Laporan</h5>
  </div>
  <div class="card-body">
    <form method="GET" action="" class="row gx-2 gy-2 align-items-end">
      <div class="col-md-5">
        <label for="tanggal_mulai" class="form-label">Dari Tanggal</label>
        <input type="date" class="form-control" name="tanggal_mulai" value="<?php echo $tanggal_mulai; ?>">
      </div>
      <div class="col-md-5">
        <label for="tanggal_akhir" class="form-label">Sampai Tanggal</label>
        <input type="date" class="form-control" name="tanggal_akhir" value="<?php echo $tanggal_akhir; ?>">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
      </div>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-xl-5 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h6 class="m-0 fw-bold">Analisis Produk BS</h6>
      </div>
      <div class="card-body d-flex justify-content-center align-items-center">
        <?php if (!empty($chart_data)): ?>
          <div class="chart-pie" style="position: relative; height:300px; width:300px">
            <canvas id="produkBsPieChart"></canvas>
          </div>
        <?php else: ?>
          <p class="text-muted">Tidak ada data untuk ditampilkan di grafik.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-xl-7 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h6 class="m-0 fw-bold">Rincian Data BS Periode <?php echo date('d M Y', strtotime($tanggal_mulai)); ?> - <?php echo date('d M Y', strtotime($tanggal_akhir)); ?></h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-modern">
            <thead>
              <tr>
                <th>Nama Produk</th>
                <th class="text-center">Total Kuantitas BS</th>
                <th class="text-center">Total Kejadian</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($data_bs) > 0): ?>
                <?php foreach ($data_bs as $item): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                    <td class="text-center"><?php echo $item['total_kuantitas']; ?></td>
                    <td class="text-center"><?php echo $item['frekuensi']; ?> kali</td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="3" class="text-center py-4">Tidak ada data BS pada periode ini.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById("produkBsPieChart");
    if (ctx) {
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: <?php echo json_encode($labels); ?>,
          datasets: [{
            data: <?php echo json_encode($chart_data); ?>,
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617', '#60616f', '#37383e'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
          }],
        },
        options: {
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: 'bottom'
            }
          },
          cutout: '80%',
        },
      });
    }
  });
</script>

<?php
include_once(__DIR__ . '/../../../includes/footer.php');
?>