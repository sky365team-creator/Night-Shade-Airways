<?php
require_once 'db.php';
$db = getDB();
$msg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $ref = trim($_POST['reference']);
  $contact = trim($_POST['contact']);
  $stmt = $db->prepare('SELECT * FROM bookings WHERE reference = ? AND contact = ?');
  $stmt->execute([$ref,$contact]);
  $b = $stmt->fetch(PDO::FETCH_ASSOC);
  if($b){
    $u = current_user();
    $db->prepare('UPDATE bookings SET status = ? WHERE id = ?')->execute(['checked-in',$b['id']]);
    $msg = 'Check-in berhasil untuk reference '.$ref;
  }else{ $msg = 'Booking tidak ditemukan, periksa reference dan kontak.'; }
}
include 'header.php';
?>
<div class="card">
  <h3>Check-in Online</h3>
  <?php if($msg) echo '<p>'.htmlspecialchars($msg).'</p>'; ?>
  <form method="post">
    <label>Kode Referensi</label>
    <input name="reference" required>
    <label>Kontak (email/telepon)</label>
    <input name="contact" required>
    <button>Check-in</button>
  </form>
</div>
<?php include 'footer.php'; ?>
