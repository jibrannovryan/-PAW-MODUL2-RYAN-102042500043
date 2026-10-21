<?php
require 'koneksi.php'; require 'fungsi.php';
$q = trim($_GET['q'] ?? '');
$sql = "SELECT id, kode, nama, dosen, semester FROM mata_kuliah";
if ($q !== '') {
    $stmt = $conn->prepare("SELECT id, kode, nama, dosen, semester FROM mata_kuliah WHERE kode LIKE CONCAT('%', ?, '%') OR nama LIKE CONCAT('%', ?, '%') ORDER BY nama");
    $stmt->bind_param("ss", $q, $q); $stmt->execute(); $hasil = $stmt->get_result();
} else { $hasil = $conn->query($sql . " ORDER BY nama"); }
header_html('Daftar Mata Kuliah');
?>
<p><a class="btn primary" href="tambah_mata_kuliah.php">+ Tambah Mata Kuliah</a></p>
<form method="get"><label for="q">Cari berdasarkan kode atau nama</label><input id="q" name="q" value="<?= e($q) ?>"><button class="btn" type="submit">Cari</button><a class="btn" href="daftar_mata_kuliah.php">Reset</a></form>
<?php if ($hasil && $hasil->num_rows): ?>
<table><tr><th>Kode</th><th>Nama</th><th>Dosen</th><th>Semester</th><th>Aksi</th></tr>
<?php while ($r = $hasil->fetch_assoc()): ?>
<tr><td><?= e($r['kode']) ?></td><td><?= e($r['nama']) ?></td><td><?= e($r['dosen']) ?></td><td><?= e($r['semester']) ?></td><td>
<a class="btn" href="detail_mata_kuliah.php?id=<?= (int)$r['id'] ?>">Detail</a>
<a class="btn" href="edit_mata_kuliah.php?id=<?= (int)$r['id'] ?>">Edit</a>
<a class="btn danger" href="hapus_mata_kuliah.php?id=<?= (int)$r['id'] ?>">Hapus</a>
</td></tr><?php endwhile; ?></table>
<?php else: ?><p>Tidak ada data mata kuliah yang cocok.</p><?php endif; ?>
<?php footer_html(); ?>
