<?php
require_once 'db.php';
$user = current_user();
$db = getDB();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $service_id = intval($_POST['service_id']);
  $passengers = max(1,intval($_POST['passengers']));
  $contact = trim($_POST['contact']);
  $user_id = $user ? $user['id'] : null;
  $ref = strtoupper(substr(md5(time().rand()),0,8));
  $stmt = $db->prepare('INSERT INTO bookings (user_id,service_id,passengers,contact,status,created_at,reference) VALUES (?,?,?,?,?,datetime("now"),?)');
  $stmt->execute([$user_id,$service_id,$passengers,$contact,'booked',$ref]);
  $msg = "Reservasi berhasil! Kode referensi: <strong>$ref</strong><br>Simpan kode ini untuk check-in dan pembatalan.";
}
$services = $db->query('SELECT * FROM services ORDER BY depart_at')->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>
<div class="card">
  <h2>Reservasi Tiket Pesawat</h2>
  <?php if(!empty($msg)) echo '<p style="color:green;background:#d1fae5;padding:10px;border-radius:6px">'.htmlspecialchars($msg,ENT_QUOTES,'UTF-8',false).'</p>'; ?>
  
  <div style="margin-top:16px">
    <h4>Pilih Penerbangan</h4>
    <div class="grid">
    <?php 
    $services_list = $db->query('SELECT * FROM services ORDER BY depart_at')->fetchAll(PDO::FETCH_ASSOC);
    $images = ['assets/gambar1.jpeg','assets/gambar2.jpeg','assets/gambar3.webp','assets/gambar4.webp'];
    $img_idx = 0;
    foreach($services_list as $idx => $s): 
    ?>
      <div class="card" style="cursor:pointer;transition:0.3s" onclick="selectService(<?php echo $s['id']; ?>,'<?php echo htmlspecialchars($s['code'].' '.$s['origin'].'→'.$s['destination'],ENT_QUOTES); ?>')">
        <img src="<?php echo htmlspecialchars($images[$idx % count($images)]); ?>" style="height:140px;width:100%;object-fit:cover;border-radius:6px">
        <h4><?php echo htmlspecialchars($s['code']); ?></h4>
        <p class="small"><strong><?php echo htmlspecialchars($s['origin'].' → '.$s['destination']); ?></strong></p>
        <p class="small">Berangkat: <?php echo date('d M Y, H:i',strtotime($s['depart_at'])); ?></p>
        <p style="color:#3b82f6;font-weight:bold">Rp <?php echo number_format($s['price']); ?></p>
        <p class="small">Kursi tersedia: <?php echo $s['seats']; ?></p>
      </div>
    <?php endforeach; ?>
    </div>
  </div>

  <form method="post" style="margin-top:20px;background:var(--card-bg);padding:16px;border:1px solid var(--border-color);border-radius:8px">
    <label>Penerbangan yang Dipilih</label>
    <div id="selected-service" style="padding:10px;background:#f0f4fa;border-radius:6px;margin-bottom:12px">
      <p class="small">Pilih penerbangan dari daftar di atas</p>
    </div>
    <input type="hidden" id="service_id" name="service_id" required>
    
    <label>Jumlah Penumpang</label>
    <input type="number" name="passengers" value="1" min="1" max="6">
    
    <label>Kontak (Email / Telepon)</label>
    <input name="contact" placeholder="email@example.com atau 081234567890" required>
    
    <button type="submit" style="width:100%;margin-top:12px">Pesan Sekarang</button>
  </form>
</div>

<script>
function selectService(id, name){
  document.getElementById('service_id').value = id;
  document.getElementById('selected-service').innerHTML = '<p><strong>' + name + '</strong></p>';
}
</script>

<?php include 'footer.php'; ?>
