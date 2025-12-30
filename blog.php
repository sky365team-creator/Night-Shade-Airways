<?php
require_once 'db.php';
$db = getDB();
$posts = $db->query('SELECT id,title,excerpt,slug,image,created_at FROM blog ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>
<div class="card">
  <h3>Blog</h3>
  <div class="grid">
  <?php foreach($posts as $p): ?>
    <div class="card">
      <img src="<?php echo htmlspecialchars($p['image']); ?>" style="height:140px;object-fit:cover;width:100%">
      <h4><?php echo htmlspecialchars($p['title']); ?></h4>
      <p class="small"><?php echo htmlspecialchars($p['excerpt']); ?></p>
      <a href="blog_view.php?slug=<?php echo urlencode($p['slug']); ?>"><button>Baca Selengkapnya →</button></a>
    </div>
  <?php endforeach; ?>
  </div>
</div>
<?php include 'footer.php'; ?>
