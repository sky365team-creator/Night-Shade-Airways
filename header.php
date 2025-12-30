<?php
require_once __DIR__ . '/db.php';
$user = current_user();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Night Shade Airways</title>
  <link rel="stylesheet" href="/UAS/style.css">
  <script>
    // Restore theme SEBELUM DOM render (synchronous)
    (function(){
      const savedTheme = localStorage.getItem('theme');
      if(savedTheme === 'dark'){
        document.documentElement.classList.add('dark-mode');
      }
    })();
  </script>
</head>
<body>
<header class="site-header">
  <div class="brand">
    <img src="/UAS/assets/gambar7.webp" alt="logo" class="logo">
    <h1>Night Shade Airways</h1>
  </div>
  <nav>
    <a href="/UAS/index.php">Home</a>
    <a href="/UAS/reserve.php">Reservasi</a>
    <a href="/UAS/blog.php">Blog</a>
    <a href="/UAS/checkin.php">Check-in</a>
    <a href="/UAS/cancel.php">Pembatalan</a>
    <a href="/UAS/infographic.php">Infografis</a>
    <?php if($user): ?>
      <a href="/UAS/profile.php">Halo, <?php echo htmlspecialchars($user['name']); ?></a>
      <?php if($user['role']==='admin'): ?>
        <a href="/UAS/admin/dashboard.php">Admin</a>
      <?php endif; ?>
      <a href="/UAS/logout.php">Logout</a>
    <?php else: ?>
      <a href="/UAS/login.php">Login</a>
      <a href="/UAS/register.php">Register</a>
    <?php endif; ?>
    <button class="theme-toggle" id="themeBtn">🌙</button>
  </nav>
</header>
<main class="container">
<script>
  document.addEventListener('DOMContentLoaded', function(){
    const themeBtn = document.getElementById('themeBtn');
    const isDark = document.documentElement.classList.contains('dark-mode');
    themeBtn.textContent = isDark ? '☀️' : '🌙';
    themeBtn.addEventListener('click', function(){
      document.documentElement.classList.toggle('dark-mode');
      const newTheme = document.documentElement.classList.contains('dark-mode') ? 'dark' : 'light';
      localStorage.setItem('theme', newTheme);
      themeBtn.textContent = newTheme === 'dark' ? '☀️' : '🌙';
    });

    // underline animation: use classes to trigger clip-path keyframes so show/hide both run left->right
    const navLinks = document.querySelectorAll('nav a');
    function addHoverAnim(el){
      el.addEventListener('mouseenter', ()=>{
        el.classList.remove('anim-hide');
        // reflow to reset animation
        void el.offsetWidth;
        el.classList.add('anim-reveal');
      });
      el.addEventListener('mouseleave', ()=>{
        el.classList.remove('anim-reveal');
        void el.offsetWidth;
        el.classList.add('anim-hide');
      });
    }
    navLinks.forEach(addHoverAnim);
    // theme toggle underline too
    addHoverAnim(themeBtn);
  });
</script>
