<?php
require_once 'db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  $name = trim($_POST['name']);
  $pw = $_POST['password'];
  if($email && $pw){
    $db = getDB();
    $hash = password_hash($pw, PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT INTO users (email,name,password,role,created_at) VALUES(?,?,?,?,datetime("now"))');
    try{ $stmt->execute([$email,$name,$hash,'user']);
      header('Location: login.php'); exit;
    }catch(Exception $e){ $err = 'Email sudah terdaftar.'; }
  }else{ $err = 'Masukkan email yang valid dan password.'; }
}
include 'header.php';
?>
<div class="card">
  <h3>Register</h3>
  <?php if(!empty($err)) echo '<p style="color:red">'.htmlspecialchars($err).'</p>'; ?>
  <form method="post">
    <label>Name</label>
    <input name="name" required>
    <label>Email</label>
    <input name="email" type="email" required>
    <label>Password</label>
    <input name="password" type="password" required>
    <button>Register</button>
  </form>
</div>
<?php include 'footer.php'; ?>
