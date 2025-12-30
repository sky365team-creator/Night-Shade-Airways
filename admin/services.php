<?php
require_once __DIR__ . '/../db.php';
require_admin();
$db = getDB();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $code = $_POST['code']; $title = $_POST['title']; $origin = $_POST['origin']; $destination = $_POST['destination'];
  $depart = $_POST['depart_at']; $price = floatval($_POST['price']); $seats = intval($_POST['seats']);
  $db->prepare('INSERT INTO services (code,title,origin,destination,depart_at,price,seats) VALUES (?,?,?,?,?,?,?)')
    ->execute([$code,$title,$origin,$destination,$depart,$price,$seats]);
  header('Location: services.php'); exit;
}
if(isset($_GET['delete'])){ $db->prepare('DELETE FROM services WHERE id=?')->execute([intval($_GET['delete'])]); header('Location: services.php'); exit; }
$services = $db->query('SELECT * FROM services ORDER BY depart_at DESC')->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/../header.php';
?>
<div class="card">
  <h3>✈️ Manajemen Layanan</h3>
  
  <div style="background: rgba(59,130,246,0.08); padding: 16px; border-radius: 8px; border-left: 4px solid #3b82f6; margin-bottom: 20px;">
    <h4 style="margin-top: 0;">Tambah Layanan Baru</h4>
    <form method="post">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
          <label>Code Penerbangan</label><input name="code" required placeholder="NS303">
        </div>
        <div>
          <label>Judul Layanan</label><input name="title" required placeholder="Night Shade Premium">
        </div>
        <div>
          <label>Asal</label><input name="origin" required placeholder="CGK">
        </div>
        <div>
          <label>Tujuan</label><input name="destination" required placeholder="KNO">
        </div>
        <div>
          <label>Keberangkatan (YYYY-MM-DD HH:MM:SS)</label><input name="depart_at" required placeholder="2026-01-07 15:45:00">
        </div>
        <div>
          <label>Harga (Rp)</label><input name="price" type="number" required placeholder="1500000">
        </div>
        <div>
          <label>Kursi Tersedia</label><input name="seats" type="number" required placeholder="100">
        </div>
      </div>
      <button style="margin-top: 12px; width: 100%;">➕ Tambah Layanan</button>
    </form>
  </div>

  <h4 style="margin-top: 24px; margin-bottom: 12px;">Daftar Layanan (<?php echo count($services); ?>)</h4>
  <div style="overflow-x: auto;">
    <table class="admin-table">
      <thead>
      <tr><th>ID</th><th>Code</th><th>Rute</th><th>Keberangkatan</th><th>Harga</th><th>Kursi</th><th>Aksi</th></tr>
      </thead>
      <tbody>
      <?php foreach($services as $s): ?>
      <tr>
        <td><?php echo $s['id']; ?></td>
        <td><strong><?php echo htmlspecialchars($s['code']); ?></strong></td>
        <td><?php echo htmlspecialchars($s['origin']); ?> → <?php echo htmlspecialchars($s['destination']); ?></td>
        <td><?php echo substr($s['depart_at'], 0, 16); ?></td>
        <td>Rp <?php echo number_format($s['price']); ?></td>
        <td><?php echo $s['seats']; ?> kursi</td>
        <td><a class="btn btn-danger" href="services.php?delete=<?php echo $s['id']; ?>" onclick="return confirm('Hapus layanan ini?')">🗑️ Hapus</a></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p style="margin-top: 16px; text-align: right;"><a class="btn btn-secondary" href="dashboard.php">← Kembali ke Dashboard</a></p>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
