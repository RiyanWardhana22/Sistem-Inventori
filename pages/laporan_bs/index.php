<?php
include_once(__DIR__ . '/../../includes/header.php');

$tanggal_akhir = $_GET['tanggal_akhir'] ?? date('Y-m-d');
$tanggal_mulai = $_GET['tanggal_mulai'] ?? date('Y-m-d', strtotime('-29 days', strtotime($tanggal_akhir)));

$stmt = $pdo->prepare("
    SELECT 
        p.nama_produk, 
        SUM(bs.jumlah) as total_kuantitas,
        COUNT(bs.id_bs) as frekuensi
    FROM stok_bs bs
    JOIN produk p ON bs.id_produk = p.id_produk
    WHERE bs.tanggal_bs BETWEEN ? AND ?
    GROUP BY p.id_produk, p.nama_produk
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

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3">Analisis Produk BS (Rusak/Sisa)</h1>
</div>

<div class="card mb-4">
  <div class="card-body">
    <form method="GET" action="" class="row g-3 align-items-end">
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
  <div class="col-xl-5">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary">Komposisi Produk BS</h6>
      </div>
      <div class="card-body">
        <div class="chart-pie pt-4">
          <canvas id="produkBsPieChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-7">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary">Rincian Data BS Periode <?php echo date('d M Y', strtotime($tanggal_mulai)); ?> - <?php echo date('d M Y', strtotime($tanggal_akhir)); ?></h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Nama Produk</th>
                <th class="text-center">Total BS</th>
                <th class="text-center">Frekuensi Kejadian</th>
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
                  <td colspan="3" class="text-center">Tidak ada data BS pada periode ini.</td>
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
        type: 'pie',
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
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
          },
          legend: {
            display: true,
            position: 'bottom'
          },
          cutoutPercentage: 0,
        },
      });
    }
  });
</script>

<?php
include_once(__DIR__ . '/../../includes/footer.php');
?>