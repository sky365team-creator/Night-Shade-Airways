<?php
require_once __DIR__ . '/../db.php';
require_admin();
$db = getDB();

// Handle new user creation
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = $_POST['email'];
  $name = $_POST['name'];
  $password = $_POST['password'];
  $role = $_POST['role'] === 'admin' ? 'admin' : 'user';
  
  // Check if email already exists
  $check = $db->prepare('SELECT id FROM users WHERE email = ?')->execute([$email]);
  if($check->rowCount() === 0){
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $db->prepare('INSERT INTO users (email, name, password, role, created_at) VALUES (?, ?, ?, ?, datetime("now"))')
      ->execute([$email, $name, $hashedPassword, $role]);
    header('Location: users.php'); exit;
  } else {
    $error = "Email sudah terdaftar!";
  }
}

if(isset($_GET['delete'])){
  $db->prepare('DELETE FROM users WHERE id = ?')->execute([intval($_GET['delete'])]);
  header('Location: users.php'); exit;
}
$users = $db->query('SELECT id,email,name,role,created_at FROM users ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/../header.php';
?>
<div class="card">
  <h3>👥 Manajemen User</h3>
  
  <div style="background: rgba(59,130,246,0.08); padding: 16px; border-radius: 8px; border-left: 4px solid #3b82f6; margin-bottom: 20px;">
    <h4 style="margin-top: 0;">Tambah User Baru</h4>
    <?php if(isset($error)): ?>
      <div style="background: rgba(239,68,68,0.1); color: #dc2626; padding: 12px; border-radius: 6px; margin-bottom: 12px;">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
          <label>Email</label><input name="email" type="email" required placeholder="user@example.com">
        </div>
        <div>
          <label>Nama Lengkap</label><input name="name" required placeholder="Nama User">
        </div>
        <div>
          <label>Password</label><input name="password" type="password" required placeholder="Minimal 6 karakter">
        </div>
        <div>
          <label>Role</label>
          <select name="role" required style="width:100%;padding:10px;margin:8px 0;border:1px solid var(--border-color);border-radius:4px;background:var(--card-bg);color:var(--text-primary);font-size:1em">
            <option value="user">👤 User Biasa</option>
            <option value="admin">👑 Admin</option>
          </select>
        </div>
      </div>
      <button style="margin-top: 12px; width: 100%;">➕ Tambah User</button>
    </form>
  </div>

  <h4 style="margin-top: 24px; margin-bottom: 12px;">Daftar User (<?php echo count($users); ?>)</h4>
  <p style="color: var(--text-secondary); margin-bottom: 12px;">Total user: <strong><?php echo count($users); ?></strong></p>
  <table class="admin-table">
    <thead>
    <tr><th>ID</th><th>Email</th><th>Nama</th><th>Role</th><th>Dibuat</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php foreach($users as $u): ?>
    <tr>
      <td><?php echo $u['id']; ?></td>
      <td><?php echo htmlspecialchars($u['email']); ?></td>
      <td><?php echo htmlspecialchars($u['name']); ?></td>
      <td><span style="background: <?php echo $u['role']==='admin'?'#ef4444':'#3b82f6'; ?>; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.85em;"><?php echo htmlspecialchars($u['role']); ?></span></td>
      <td><?php echo substr($u['created_at'], 0, 10); ?></td>
      <td><?php if($u['role']!=='admin'): ?><a class="btn btn-danger" href="users.php?delete=<?php echo $u['id']; ?>" onclick="return confirm('Hapus user ini? Tindakan tidak bisa dibatalkan.')">🗑️ Hapus</a><?php else: ?><span style="color: var(--text-secondary);">Admin</span><?php endif; ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p style="margin-top: 16px; text-align: right;"><a class="btn btn-secondary" href="dashboard.php">← Kembali ke Dashboard</a></p>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
