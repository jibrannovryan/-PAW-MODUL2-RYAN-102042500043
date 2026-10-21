<?php
require 'koneksi.php';
<<<<<<< HEAD
require 'fungsi.php';

// Menghitung jumlah total data dari masing-masing tabel secara dinamis
$jml_mk = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM mata_kuliah"))[0];
$jml_materi = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM materi"))[0];
$jml_tugas = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM tugas"))[0];

// Mengambil daftar mata kuliah yang tersedia
$query_mk = mysqli_query($conn, "SELECT kode, nama FROM mata_kuliah ORDER BY nama");

header_html("LMS Sederhana - Beranda");
?>
<main>
    <h1>Learning Management System</h1>
    <p>Pengelolaan mata kuliah, materi pembelajaran, dan tugas.</p>
    
    <p>
        <strong>Mata kuliah:</strong> <?= $jml_mk ?> &nbsp;&nbsp; 
        <strong>Materi:</strong> <?= $jml_materi ?> &nbsp;&nbsp; 
        <strong>Tugas:</strong> <?= $jml_tugas ?>
    </p>

    <!-- Tombol navigasi untuk mengelola masing-masing menu -->
    <p>
        <a href="mata_kuliah.php" class="btn" style="display:inline-block; padding:8px 12px; background:#1d4ed8; color:#fff; text-decoration:none; border-radius:4px; margin-right:6px;">Kelola Mata Kuliah</a>
        <a href="materi.php" class="btn" style="display:inline-block; padding:8px 12px; background:#1d4ed8; color:#fff; text-decoration:none; border-radius:4px; margin-right:6px;">Kelola Materi</a>
        <a href="tugas.php" class="btn" style="display:inline-block; padding:8px 12px; background:#1d4ed8; color:#fff; text-decoration:none; border-radius:4px;">Kelola Tugas</a>
    </p>

    <h2>Mata kuliah tersedia</h2>
    <ul>
        <?php while ($row = mysqli_fetch_assoc($query_mk)): ?>
            <li><?= e($row['kode']) ?> - <?= e($row['nama']) ?></li>
        <?php endwhile; ?>
    </ul>
</main>
<?php footer_html(); ?>
=======
$cari = '';
if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
}
$hasil = mysqli_query($conn, 
    "SELECT * FROM showroom_mobil 
    WHERE nama_mobil LIKE '%$cari%' OR brand_mobil LIKE '%$cari%' 
    ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>ShowRoom EAD</title>
<link rel="stylesheet" href="style.css">
</head>
<body><main>
<p class="label">PRAKTIKUM PAW / MODUL 2</p>
<h1>ShowRoom EAD</h1>
<p>Kelola data mobil yang tersedia di showroom.</p>

<?php if (isset($_GET['pesan'])): ?>
<p class="pesan">Perubahan data berhasil disimpan.</p>
<?php endif ?>

<p><a class="tombol" href="tambah.php">Tambah mobil</a></p>

<form method="get" class="pencarian">
<label for="cari">Cari nama atau brand</label>
<input id="cari" name="cari" value="<?= $_GET['cari'] ?? '' ?>">
<button>Cari</button> <a href="index.php">Reset</a>
</form>

<p>Jumlah hasil: <?= mysqli_num_rows($hasil) ?></p>

<?php if (mysqli_num_rows($hasil) == 0): ?>
<p>Data tidak ditemukan. Coba kata kunci lain.</p>
<?php endif ?>

<div class="daftar">
<?php while ($mobil = mysqli_fetch_assoc($hasil)): ?>
<article>
<p class="label"><?= $mobil['kode_mobil'] ?></p>
<h2><?= $mobil['nama_mobil'] ?></h2>
<p><?= $mobil['brand_mobil'] ?> &bull; <?= $mobil['tipe_mobil'] ?></p>
<p>Warna: <?= $mobil['warna_mobil'] ?></p>
<p>Rp <?= number_format($mobil['harga_mobil'], 0, ',', '.') ?></p>
<a href="detail.php?id=<?= $mobil['id'] ?>">Detail</a>
<a href="edit.php?id=<?= $mobil['id'] ?>">Edit</a>
<a href="hapus.php?id=<?= $mobil['id'] ?>">Hapus</a>
</article>
<?php endwhile; ?>
</div>
</main></body>
</html>
>>>>>>> 06701a8a15f16d083bf42b7e6edc71bc6b9bdbd8
