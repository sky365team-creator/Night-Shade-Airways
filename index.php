<?php include 'header.php'; ?>
<section class="hero">
  <div>
    <div class="card">
      <h2>Night Shade Airways — Terbang Nyaman, Aman, dan Tepat Waktu</h2>
      <p class="meta">Layanan pemesanan tiket pesawat online — cek jadwal, pesan, check-in, dan kelola pembatalan dengan mudah.</p>
      <p>Kami berkomitmen memberikan pengalaman terbang terbaik dengan harga yang kompetitif dan layanan pelanggan yang responsif.</p>
    </div>
  </div>
  <aside>
    <div class="card">
      <img src="assets/gambar7.webp" alt="hero" style="width:100%;height:200px;object-fit:cover;border-radius:6px">
      <h3 style="margin-top:12px">Reservasi Cepat</h3>
      <p>Pesan tiket pesawat dalam hitungan menit dengan sistem booking yang mudah dan aman.</p>
      <a href="reserve.php"><button>Pesan Sekarang →</button></a>
    </div>
  </aside>
</section>

<section style="margin-top:20px">
  <div class="card">
    <h3>Mengapa Pilih Night Shade Airways?</h3>
    <div class="grid services">
      <div class="card">
        <img src="assets/gambar4.webp" alt="check-in" style="height:140px;width:100%;object-fit:cover;border-radius:6px">
        <h4>Check-in Online Mudah</h4>
        <p class="small">Lakukan check-in 24 jam sebelum penerbangan tanpa perlu antri.</p>
      </div>
      <div class="card">
        <img src="assets/gambar5.webp" alt="member" style="height:140px;width:100%;object-fit:cover;border-radius:6px">
        <h4>Program Member Loyalitas</h4>
        <p class="small">Kumpulkan poin setiap pemesanan dan tukarkan dengan diskon.</p>
      </div>
      <div class="card">
        <img src="assets/gambar6.jpg" alt="destinasi" style="height:140px;width:100%;object-fit:cover;border-radius:6px">
        <h4>Destinasi Lengkap</h4>
        <p class="small">Rute penerbangan ke berbagai kota besar di Indonesia dan internasional.</p>
      </div>
    </div>
  </div>
</section>

<section style="margin-top:14px">
  <div class="card">
    <h3>Artikel Terbaru & Tips Bermanfaat</h3>
    <div class="grid" id="posts">
    <?php
    $db = getDB();
    $posts = $db->query('SELECT id,title,excerpt,image,slug FROM blog ORDER BY id DESC LIMIT 6')->fetchAll(PDO::FETCH_ASSOC);
    foreach($posts as $p): ?>
      <div class="card post-card">
        <img src="<?php echo htmlspecialchars($p['image']); ?>" style="height:160px;width:100%;object-fit:cover;border-radius:6px">
        <h4 style="margin-top:8px"><?php echo htmlspecialchars($p['title']); ?></h4>
        <p class="small"><?php echo htmlspecialchars($p['excerpt']); ?></p>
        <a href="blog_view.php?slug=<?php echo urlencode($p['slug']); ?>"><button>Baca Selengkapnya →</button></a>
      </div>
    <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:12px">
      <a href="blog.php"><button>Lihat Semua Artikel</button></a>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
