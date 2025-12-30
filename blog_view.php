<?php
require_once 'db.php';
$slug = $_GET['slug'] ?? '';
$db = getDB();
$stmt = $db->prepare('SELECT * FROM blog WHERE slug = ?');
$stmt->execute([$slug]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);
include 'header.php';
if(!$p){ echo '<div class="card"><p>Artikel tidak ditemukan.</p></div>'; include 'footer.php'; exit; }
?>
<div class="card">
  <h2><?php echo htmlspecialchars($p['title']); ?></h2>
  <p class="meta"><?php echo htmlspecialchars($p['created_at']); ?></p>
  <?php if(!empty($p['image'])): ?><img src="<?php echo htmlspecialchars($p['image']); ?>" style="width:100%;height:300px;object-fit:cover;border-radius:6px">
  <?php endif; ?>
  <div style="margin-top:12px"><?php echo nl2br(htmlspecialchars($p['body'])); ?></div>
</div>

<?php include 'footer.php'; ?>
