<?php
require_once 'db.php';
$db = getDB();
// bookings per day (last 14 days)
$rows = $db->query("SELECT date(created_at) as d, COUNT(*) as c FROM bookings GROUP BY date(created_at) ORDER BY d DESC LIMIT 14")->fetchAll(PDO::FETCH_ASSOC);
$labels = array_reverse(array_map(function($r){return $r['d'];}, $rows));
$data = array_reverse(array_map(function($r){return (int)$r['c'];}, $rows));

$cancelRows = $db->query("SELECT status, COUNT(*) as c FROM cancellations GROUP BY status")->fetchAll(PDO::FETCH_ASSOC);
$cancelLabels = array_map(function($r){return $r['status'];}, $cancelRows);
$cancelData = array_map(function($r){return (int)$r['c'];}, $cancelRows);

include 'header.php';
?>
<?php
// summary stats
$totalBookings = $db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$totalCanc = $db->query("SELECT COUNT(*) FROM cancellations")->fetchColumn();
$pendingCanc = $db->query("SELECT COUNT(*) FROM cancellations WHERE status='pending'")->fetchColumn();
$revenue = $db->query("SELECT SUM(s.price * b.passengers) FROM bookings b JOIN services s ON s.id=b.service_id WHERE b.status!='cancelled'")->fetchColumn();
?>
<div class="card">
  <h3>Infografis & Rekap Data</h3>
  <div class="stats-grid">
    <div class="stat-card">
      <h4>Total Reservasi</h4>
      <p><?php echo number_format($totalBookings ?: 0); ?></p>
    </div>
    <div class="stat-card">
      <h4>Total Pengajuan Pembatalan</h4>
      <p><?php echo number_format($totalCanc ?: 0); ?></p>
    </div>
    <div class="stat-card">
      <h4>Pembatalan Pending</h4>
      <p><?php echo number_format($pendingCanc ?: 0); ?></p>
    </div>
    <div class="stat-card">
      <h4>Estimasi Pendapatan</h4>
      <p>Rp <?php echo number_format($revenue ?: 0); ?></p>
    </div>
  </div>
  <div class="charts-wrap">
    <div class="chart-area">
      <canvas id="bookingsChart" style="width:100%;height:320px"></canvas>
    </div>
    <div class="side-area">
      <h4>Pembatalan</h4>
      <canvas id="cancelChart" style="width:100%;height:240px;margin-top:6px"></canvas>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
<script>
const labels = <?php echo json_encode($labels); ?>;
const data = <?php echo json_encode($data); ?>;
const ctx = document.getElementById('bookingsChart').getContext('2d');
const bookingsChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: labels,
    datasets: [{
      label: 'Reservasi per Hari',
      data: data,
      borderColor: '#1e3a8a',
      backgroundColor: 'rgba(59,130,246,0.08)',
      pointBackgroundColor: '#1e40af',
      fill: true,
      tension: 0.25,
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      x: { display: true, title: { display: true, text: 'Tanggal' } },
      y: { beginAtZero: true, title: { display: true, text: 'Jumlah Reservasi' } }
    },
    plugins: {
      legend: { display: false }
    }
  }
});

const clabels = <?php echo json_encode($cancelLabels); ?>;
const cdata = <?php echo json_encode($cancelData); ?>;
const ctx2 = document.getElementById('cancelChart').getContext('2d');
</script>
<script>
new Chart(ctx2,{type:'doughnut',data:{labels:clabels,datasets:[{data:cdata,backgroundColor:['#ef4444','#f59e0b','#10b981']}]} , options: {responsive:true,maintainAspectRatio:false}});
</script>
</script>
