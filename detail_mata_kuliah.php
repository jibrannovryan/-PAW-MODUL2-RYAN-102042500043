<?php
require 'koneksi.php'; require 'fungsi.php';
$id = valid_id($_GET['id'] ?? null);
if ($id === null) { http_response_code(400); exit('ID tidak valid.'); }
$stmt=$conn->prepare("SELECT kode,nama,dosen,semester FROM mata_kuliah WHERE id=?");
$stmt->bind_param("i",$id); $stmt->execute(); $d=$stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$d) { http_response_code(404); exit('Mata kuliah tidak ditemukan.'); }
header_html('Detail Mata Kuliah');
?>
<table>
<tr><th>Kode</th><td><?= e($d['kode']) ?></td></tr>
<tr><th>Nama</th><td><?= e($d['nama']) ?></td></tr>
<tr><th>Dosen</th><td><?= e($d['dosen']) ?></td></tr>
<tr><th>Semester</th><td><?= e($d['semester']) ?></td></tr>
</table>
<a class="btn" href="edit_mata_kuliah.php?id=<?= $id ?>">Edit</a>
<a class="btn" href="daftar_mata_kuliah.php">Kembali</a>
<?php footer_html(); ?>
