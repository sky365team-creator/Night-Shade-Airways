<?php
require_once __DIR__ . '/../db.php';
require_admin();
$db = getDB();
if(isset($_GET['action']) && isset($_GET['id'])){
  $id = intval($_GET['id']);
  if($_GET['action']=='approve'){
    $db->prepare("UPDATE cancellations SET status='approved' WHERE id=?")->execute([$id]);
    $cid = $db->query("SELECT booking_id FROM cancellations WHERE id={$id}")->fetchColumn();
    $db->prepare('UPDATE bookings SET status = ? WHERE id = ?')->execute(['cancelled',$cid]);
  }elseif($_GET['action']=='reject'){
    $db->prepare("UPDATE cancellations SET status='rejected' WHERE id=?")->execute([$id]);
  }
  header('Location: cancellations.php'); exit;
}
$rows = $db->query('SELECT c.*, b.reference, u.email FROM cancellations c LEFT JOIN bookings b ON b.id=c.booking_id LEFT JOIN users u ON u.id=c.user_id ORDER BY c.created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/../header.php';
?>
<div class="card">
  <h3>❌ Manajemen Pembatalan</h3>
  
  <?php 
  $pending = array_filter($rows, function($r) { return $r['status'] == 'pending'; });
  if(count($pending) > 0): 
  ?>
  <div style="background: rgba(239,68,68,0.08); padding: 12px; border-radius: 8px; border-left: 4px solid #ef4444; margin-bottom: 16px;">
    <strong style="color: #dc2626;">⚠️ Ada <?php echo count($pending); ?> permintaan pembatalan menunggu persetujuan Anda!</strong>
  </div>
  <?php endif; ?>

  <div style="overflow-x: auto;">
    <table class="admin-table">
      <thead>
      <tr><th>ID</th><th>Ref</th><th>User</th><th>Alasan</th><th>Status</th><th>Dibuat</th><th>Aksi</th></tr>
      </thead>
      <tbody>
      <?php foreach($rows as $r): 
        $statusColor = $r['status'] == 'pending' ? 'orange' : ($r['status'] == 'approved' ? 'green' : 'gray');
        $statusBg = $r['status'] == 'pending' ? 'rgba(234,179,8,0.15)' : ($r['status'] == 'approved' ? 'rgba(34,197,94,0.15)' : 'rgba(107,114,128,0.15)');
      ?>
      <tr style="background: <?php echo $r['status'] == 'pending' ? 'rgba(239,68,68,0.05)' : ''; ?>">
        <td><strong><?php echo $r['id']; ?></strong></td>
        <td><code><?php echo htmlspecialchars($r['reference'] ?: 'N/A'); ?></code></td>
        <td><?php echo htmlspecialchars($r['email'] ?: 'Guest'); ?></td>
        <td><?php echo htmlspecialchars(substr($r['reason'], 0, 40)); ?></td>
        <td><span style="background: <?php echo $statusBg; ?>; padding: 4px 8px; border-radius: 4px; font-size: 0.85em; font-weight: 600;">
          <?php 
            if($r['status'] == 'pending') echo '⏳ Menunggu';
            elseif($r['status'] == 'approved') echo '✅ Disetujui';
            else echo '❌ Ditolak';
          ?>
        </span></td>
        <td><?php echo substr($r['created_at'], 0, 10); ?></td>
        <td>
          <?php if($r['status']=='pending'): ?>
            <a class="btn btn-primary" href="cancellations.php?action=approve&id=<?php echo $r['id']; ?>" onclick="return confirm('Setujui pembatalan ini?')">✅ Setujui</a>
            <a class="btn btn-danger" href="cancellations.php?action=reject&id=<?php echo $r['id']; ?>" onclick="return confirm('Tolak pembatalan ini?')">❌ Tolak</a>
          <?php else: ?>
            <span style="color: var(--text-secondary);">-</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if(count($rows) == 0): ?>
  <p style="text-align: center; color: var(--text-secondary); margin-top: 20px;">📭 Tidak ada data pembatalan</p>
  <?php endif; ?>

  <p style="margin-top: 16px; text-align: right;"><a class="btn btn-secondary" href="dashboard.php">← Kembali ke Dashboard</a></p>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
