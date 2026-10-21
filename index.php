<?php
require 'koneksi.php';
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