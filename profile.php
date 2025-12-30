<?php
require_once 'db.php';
require_login();
$user = current_user();
$db = getDB();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name = trim($_POST['name']);
  $stmt = $db->prepare('UPDATE users SET name = ? WHERE id = ?');
  $stmt->execute([$name, $user['id']]);
  $msg = 'Profil diperbarui.';
  $user = current_user();
}
$bookings = $db->prepare('SELECT b.*, s.code, s.origin, s.destination, s.depart_at FROM bookings b LEFT JOIN services s ON s.id = b.service_id WHERE b.user_id = ? ORDER BY b.created_at DESC');
$bookings->execute([$user['id']]);
$bookings = $bookings->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>
<div class="card">
  <h3>Profil Saya</h3>
  <?php if(!empty($msg)) echo '<p style="color:green">'.htmlspecialchars($msg).'</p>'; ?>
  <form method="post">
    <label>Name</label>
    <input name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
    <button>Simpan</button>
  </form>
</div>

<div class="card" style="margin-top:12px">
  <h3>Riwayat Reservasi</h3>
  <table class="admin-table">
    <tr><th>Ref</th><th>Layanan</th><th>Penumpang</th><th>Status</th><th>Dibuat</th></tr>
    <?php foreach($bookings as $b): ?>
    <tr>
      <td><?php echo htmlspecialchars($b['reference']); ?></td>
      <td><?php echo htmlspecialchars($b['code'].' '.$b['origin'].'→'.$b['destination'].' @ '.$b['depart_at']); ?></td>
      <td><?php echo $b['passengers']; ?></td>
      <td><?php echo htmlspecialchars($b['status']); ?></td>
      <td><?php echo htmlspecialchars($b['created_at']); ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>

<?php include 'footer.php'; ?>
