<?php
require_once __DIR__ . '/../db.php';
require_admin();
$db = getDB();
$today = date('Y-m-d');
$stmt = $db->prepare("SELECT COUNT(*) FROM bookings WHERE date(created_at)=?"); 
$stmt->execute([$today]); 
$todayBookings = $stmt->fetchColumn();
$pendingCanc = $db->query("SELECT COUNT(*) FROM cancellations WHERE status='pending'")->fetchColumn();
try{
  $revenue = $db->query("SELECT COALESCE(SUM(s.price * b.passengers), 0) FROM bookings b JOIN services s ON s.id=b.service_id WHERE b.status!='cancelled'")->fetchColumn();
} catch(Exception $e){
  $revenue = 0;
}
include __DIR__ . '/../header.php';
?>
<div class="card" style="margin-bottom: 20px;">
  <h3>Admin Dashboard</h3>
  <div class="stats-grid">
    <div class="stat-card">
      <h4>Reservasi Hari Ini</h4>
      <p><?php echo $todayBookings; ?></p>
    </div>
    <div class="stat-card">
      <h4>Pembatalan Menunggu</h4>
      <p><?php echo $pendingCanc; ?></p>
    </div>
    <div class="stat-card">
      <h4>Estimasi Pendapatan</h4>
      <p>Rp <?php echo number_format($revenue ?: 0); ?></p>
    </div>
  </div>
  <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
    <h4>Manajemen</h4>
    <div class="grid">
      <a href="users.php" class="card" style="text-decoration: none; color: inherit; text-align: center;">
        <h4>👥 Manajemen User</h4>
        <p style="font-size: 0.9em; margin: 0;">Kelola akun pengguna</p>
      </a>
      <a href="services.php" class="card" style="text-decoration: none; color: inherit; text-align: center;">
        <h4>✈️ Manajemen Layanan</h4>
        <p style="font-size: 0.9em; margin: 0;">Kelola penerbangan</p>
      </a>
      <a href="blog.php" class="card" style="text-decoration: none; color: inherit; text-align: center;">
        <h4>📝 Manajemen Blog</h4>
        <p style="font-size: 0.9em; margin: 0;">Kelola artikel</p>
      </a>
      <a href="cancellations.php" class="card" style="text-decoration: none; color: inherit; text-align: center;">
        <h4>❌ Manajemen Pembatalan</h4>
        <p style="font-size: 0.9em; margin: 0;">Kelola pembatalan tiket</p>
      </a>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
