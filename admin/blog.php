<?php
require_once __DIR__ . '/../db.php';
require_admin();
$db = getDB();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $title = $_POST['title']; $slug = $_POST['slug']; $excerpt = $_POST['excerpt']; $body = $_POST['body']; $image = $_POST['image'];
  $db->prepare('INSERT INTO blog (title,slug,excerpt,body,image,created_at) VALUES (?,?,?,?,?,datetime("now"))')
    ->execute([$title,$slug,$excerpt,$body,$image]);
  header('Location: blog.php'); exit;
}
if(isset($_GET['delete'])){ $db->prepare('DELETE FROM blog WHERE id=?')->execute([intval($_GET['delete'])]); header('Location: blog.php'); exit; }
$posts = $db->query('SELECT * FROM blog ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/../header.php';
?>
<div class="card">
  <h3>📝 Manajemen Blog</h3>

  <div style="background: rgba(34,197,94,0.08); padding: 16px; border-radius: 8px; border-left: 4px solid #22c55e; margin-bottom: 20px;">
    <h4 style="margin-top: 0;">Tulis Artikel Baru</h4>
    <form method="post">
      <label>Judul Artikel</label>
      <input name="title" required placeholder="Judul menarik untuk artikel">
      
      <label>Slug (URL Friendly)</label>
      <input name="slug" required placeholder="judul-menarik-untuk-artikel">
      
      <label>Ringkasan Singkat</label>
      <input name="excerpt" placeholder="Deskripsi singkat artikel">
      
      <label>Gambar (Path)</label>
      <input name="image" value="assets/gambar1.jpeg" placeholder="assets/gambar1.jpeg">
      
      <label>Isi Artikel</label>
      <textarea name="body" style="height:180px" required placeholder="Tulis konten artikel di sini..."></textarea>
      
      <button style="width: 100%; margin-top: 12px;">📤 Publikasikan Artikel</button>
    </form>
  </div>

  <h4 style="margin-top: 24px; margin-bottom: 12px;">Artikel Terbitan (<?php echo count($posts); ?>)</h4>
  <div style="overflow-x: auto;">
    <table class="admin-table">
      <thead>
      <tr><th>ID</th><th>Judul</th><th>Slug</th><th>Dibuat</th><th>Aksi</th></tr>
      </thead>
      <tbody>
      <?php foreach($posts as $p): ?>
      <tr>
        <td><?php echo $p['id']; ?></td>
        <td><strong><?php echo htmlspecialchars(substr($p['title'], 0, 40)); ?></strong></td>
        <td><code style="background: var(--border-color); padding: 2px 4px; border-radius: 4px;"><?php echo htmlspecialchars($p['slug']); ?></code></td>
        <td><?php echo substr($p['created_at'], 0, 10); ?></td>
        <td>
          <a class="btn btn-secondary" href="../blog_view.php?slug=<?php echo urlencode($p['slug']); ?>">👁️ Lihat</a>
          <a class="btn btn-danger" href="blog.php?delete=<?php echo $p['id']; ?>" onclick="return confirm('Hapus artikel ini? Tindakan tidak bisa dibatalkan.')">🗑️ Hapus</a>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p style="margin-top: 16px; text-align: right;"><a class="btn btn-secondary" href="dashboard.php">← Kembali ke Dashboard</a></p>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
