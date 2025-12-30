<?php
require_once 'db.php';
$db = getDB();
$msg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $ref = trim($_POST['reference']);
  $contact = trim($_POST['contact']);
  $reason = trim($_POST['reason']);
  $stmt = $db->prepare('SELECT * FROM bookings WHERE reference = ? AND contact = ?');
  $stmt->execute([$ref,$contact]);
  $b = $stmt->fetch(PDO::FETCH_ASSOC);
  if($b){
    $user = current_user();
    $db->prepare('INSERT INTO cancellations (booking_id,user_id,reason,status,created_at) VALUES (?,?,?,?,datetime("now"))')
      ->execute([$b['id'],$user?$user['id']:null,$reason,'pending']);
    $msg = 'Pengajuan pembatalan terkirim. Status: pending.';
  }else{ $msg = 'Booking tidak ditemukan.'; }
}
include 'header.php';
?>
<div class="card">
  <h3>Pengajuan Pembatalan</h3>
  <?php if($msg) echo '<p style="color:green">'.htmlspecialchars($msg).'</p>'; ?>
  <form method="post">
    <label>Kode Referensi</label>
    <input name="reference" required>
    <label>Kontak</label>
    <input name="contact" required>
    <label>Alasan Pembatalan</label>
    <textarea name="reason" required></textarea>
    <button>Ajukan</button>
  </form>
</div>
<?php include 'footer.php'; ?>
