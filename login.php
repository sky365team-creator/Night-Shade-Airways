<?php
require_once 'db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = $_POST['email']; $pw = $_POST['password'];
  $db = getDB();
  $stmt = $db->prepare('SELECT id,password,name,role FROM users WHERE email = ?');
  $stmt->execute([$email]);
  $u = $stmt->fetch(PDO::FETCH_ASSOC);
  if($u && password_verify($pw, $u['password'])){
    $_SESSION['user_id'] = $u['id'];
    header('Location: index.php'); exit;
  }else{ $err = 'Email atau password salah.'; }
}
include 'header.php';
?>
<div class="card">
  <h3>Login</h3>
  <?php if(!empty($err)) echo '<p style="color:red">'.htmlspecialchars($err).'</p>'; ?>
  <form method="post">
    <label>Email</label>
    <input name="email" type="email" required>
    <label>Password</label>
    <input name="password" type="password" required>
    <button>Login</button>
  </form>
</div>
<?php include 'footer.php'; ?>
