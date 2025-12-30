-- migrations for Night Shade Airways minimal app
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  email TEXT UNIQUE NOT NULL,
  name TEXT,
  password TEXT,
  role TEXT DEFAULT 'user',
  created_at TEXT
);

CREATE TABLE services (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  code TEXT,
  title TEXT,
  origin TEXT,
  destination TEXT,
  depart_at TEXT,
  price REAL,
  seats INTEGER DEFAULT 100
);

CREATE TABLE bookings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER,
  service_id INTEGER,
  passengers INTEGER,
  contact TEXT,
  status TEXT DEFAULT 'booked',
  created_at TEXT,
  reference TEXT
);

CREATE TABLE cancellations (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  booking_id INTEGER,
  user_id INTEGER,
  reason TEXT,
  status TEXT DEFAULT 'pending',
  created_at TEXT
);

CREATE TABLE blog (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT,
  slug TEXT,
  excerpt TEXT,
  body TEXT,
  image TEXT,
  created_at TEXT
);

CREATE TABLE admin_logs (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  admin_id INTEGER,
  action TEXT,
  created_at TEXT
);

-- seed a few services and blog posts (these will show on first run)
INSERT INTO services (code, title, origin, destination, depart_at, price, seats) VALUES
('NS101','Night Shade Express','CGK','DPS','2026-01-05 08:30:00',1200000,150),
('NS202','Night Shade Rapid','CGK','SUB','2026-01-06 13:00:00',950000,150),
('NS303','Night Shade Premium','CGK','KNO','2026-01-07 15:45:00',1500000,100),
('NS404','Night Shade Comfort','SUB','DPS','2026-01-08 09:15:00',1100000,150),
('NS505','Night Shade Sunrise','DPS','CGK','2026-01-09 06:30:00',1300000,140),
('NS606','Night Shade Midday','KNO','SUB','2026-01-09 12:00:00',1050000,160),
('NS707','Night Shade Evening','CGK','MLG','2026-01-10 18:20:00',1400000,120),
('NS808','Night Shade Weekend','SUB','BPN','2026-01-11 07:50:00',1600000,130);

INSERT INTO blog (title, slug, excerpt, body, image, created_at) VALUES
('Panduan Pemesanan Mudah','panduan-pemesanan','Cara cepat dan mudah memesan tiket pesawat di Night Shade Airways dalam hitungan menit.','Night Shade Airways menyediakan sistem pemesanan online yang intuitif dan user-friendly. Cukup pilih rute, tanggal, dan jumlah penumpang, kemudian isi data kontak. Sistem kami yang otomatis akan memberikan kode referensi untuk setiap pemesanan. Anda dapat melacak status booking melalui dashboard profil atau halaman check-in kami.','assets/gambar1.jpeg',datetime('now')),
('Tips Aman Saat Bepergian','tips-aman','Pelajari tips keselamatan dan persiapan perjalanan udara yang wajib Anda ketahui.','Sebelum naik pesawat, pastikan Anda telah mempersiapkan dokumen dengan lengkap seperti KTP dan tiket boarding. Tiba di bandara minimal 2 jam sebelum keberangkatan. Ikuti semua instruksi crew penerbangan dan pahami prosedur keselamatan darurat. Selalu gunakan seatbelt dan ikuti tanda-tanda yang diberikan. Night Shade Airways berkomitmen menjaga keselamatan penumpang dengan standar internasional.','assets/gambar2.jpeg',datetime('now')),
('Kebijakan Pembatalan & Refund','kebijakan-pembatalan','Penjelasan lengkap tentang kebijakan pembatalan dan pengembalian dana di Night Shade Airways.','Night Shade Airways memahami bahwa rencana perjalanan kadang berubah. Anda dapat mengajukan pembatalan melalui halaman "Pengajuan Pembatalan" dengan memberikan alasan. Permintaan akan ditinjau oleh tim admin kami dalam waktu 24 jam. Jika disetujui, dana akan dikembalikan dalam 5-7 hari kerja. Semakin awal pembatalan, semakin besar persentase refund yang akan Anda terima.','assets/gambar3.webp',datetime('now')),
('Nikmati Kemudahan Check-in Online','kemudahan-checkin','Lakukan check-in secara online tanpa perlu antri di bandara.','Check-in online Night Shade Airways dapat dilakukan 24 jam sebelum keberangkatan. Cukup masukkan kode referensi booking dan nomor kontak Anda, sistem kami akan memverifikasi data secara otomatis. Setelah check-in berhasil, Anda dapat langsung menuju ke gate penerbangan di bandara. Fitur ini menghemat waktu Anda dan membuat proses penerbangan lebih efisien dan menyenangkan.','assets/gambar4.webp',datetime('now')),
('Promosi Spesial untuk Member Setia','promosi-member','Dapatkan diskon eksklusif dan berbagai keuntungan sebagai member Night Shade Airways.','Sebagai apresiasi kepada penumpang setia, Night Shade Airways menawarkan program membership dengan berbagai benefit menarik. Setiap pemesanan akan mengumpulkan poin loyalitas yang dapat ditukar dengan diskon tiket, upgrade seat, atau layanan ekstra. Member VIP mendapatkan akses prioritas ke rute-rute terbaru dan penawaran flash sale yang eksklusif. Daftarkan akun Anda hari ini dan mulai kumpulkan poin reward.','assets/gambar5.webp',datetime('now')),
('Destinasi Populer & Rekomendasi Liburan','destinasi-populer','Jelajahi destinasi-destinasi indah yang dapat ditempuh melalui penerbangan Night Shade Airways.','Indonesia memiliki banyak destinasi wisata menakjubkan yang dapat Anda jelajahi dengan Night Shade Airways. Dari Jakarta ke Bali untuk menikmati pantai eksotis, Surabaya untuk menjelajahi sejarah, hingga Medan untuk petualangan alam. Setiap tujuan menawarkan pengalaman unik dan tak terlupakan. Kami menyediakan berbagai jadwal penerbangan fleksibel untuk memudahkan perjalanan Anda ke destinasi impian.','assets/gambar6.jpg',datetime('now')),
('Pengalaman Penerbangan Kelas Premium','penerbangan-premium','Rasakan kenyamanan maksimal dengan layanan premium Night Shade Airways.','Night Shade Airways Premium menawarkan pengalaman terbang yang tak terlupakan dengan kursi ergonomis, catering berkualitas, dan pelayanan personal dari crew profesional kami. Penumpang premium mendapatkan akses ke lounge khusus sebelum penerbangan, prioritas boarding, dan bagasi gratis hingga 30kg. Nikmati hiburan multimedia dan pilihan makanan internasional selama penerbangan Anda.','assets/gambar7.webp',datetime('now')),
('Panduan Bagasi & Biaya Tambahan','panduan-bagasi','Informasi lengkap tentang allowance bagasi dan berbagai layanan tambahan yang tersedia.','Setiap tiket Night Shade Airways menyertakan bagasi kabin gratis 7kg dan bagasi checked 20kg. Bagasi tambahan dapat dibeli dengan harga terjangkau sesuai kebutuhan Anda. Kami juga menyediakan layanan excess baggage untuk penerbangan dengan banyak perlengkapan. Pastikan bagasi Anda telah ditandai dengan jelas dan melebihi batas dimensi yang ditentukan untuk keamanan proses handling.','assets/gambar8.webp',datetime('now'));
