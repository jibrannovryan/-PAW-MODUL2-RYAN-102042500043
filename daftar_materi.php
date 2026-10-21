<?php
require 'koneksi.php'; require 'fungsi.php';
$q=trim($_GET['q']??''); $course=valid_id($_GET['mata_kuliah_id']??null);
$courses=get_courses($conn);
$sql="SELECT m.id,m.judul,m.isi,m.tautan,m.mata_kuliah_id,mk.nama AS nama_mk FROM materi m JOIN mata_kuliah mk ON mk.id=m.mata_kuliah_id WHERE 1=1";
if($q!=='' && $course!==null){
 $st=$conn->prepare($sql." AND m.judul LIKE CONCAT('%', ?, '%') AND m.mata_kuliah_id=? ORDER BY m.judul"); $st->bind_param("si",$q,$course); $st->execute(); $rows=$st->get_result();
}elseif($q!==''){
 $st=$conn->prepare($sql." AND m.judul LIKE CONCAT('%', ?, '%') ORDER BY m.judul"); $st->bind_param("s",$q); $st->execute(); $rows=$st->get_result();
}elseif($course!==null){
 $st=$conn->prepare($sql." AND m.mata_kuliah_id=? ORDER BY m.judul"); $st->bind_param("i",$course); $st->execute(); $rows=$st->get_result();
}else $rows=$conn->query($sql." ORDER BY m.judul");
header_html('Daftar Materi');
?>
<p><a class="btn primary" href="tambah_materi.php">+ Tambah Materi</a></p>
<form method="get"><label>Cari judul materi</label><input name="q" value="<?= e($q) ?>"><label>Filter mata kuliah</label><select name="mata_kuliah_id"><option value="">Semua mata kuliah</option><?php foreach($courses as $c): ?><option value="<?= (int)$c['id'] ?>" <?= $course===(int)$c['id']?'selected':'' ?>><?= e($c['kode'].' - '.$c['nama']) ?></option><?php endforeach; ?></select><button class="btn" type="submit">Cari / Filter</button><a class="btn" href="daftar_materi.php">Reset</a></form>
<?php if($rows && $rows->num_rows): ?><table><tr><th>Judul</th><th>Mata Kuliah</th><th>Aksi</th></tr><?php while($r=$rows->fetch_assoc()): ?><tr><td><?= e($r['judul']) ?></td><td><?= e($r['nama_mk']) ?></td><td><a class="btn" href="detail_materi.php?id=<?= (int)$r['id'] ?>">Detail</a> <a class="btn" href="edit_materi.php?id=<?= (int)$r['id'] ?>">Edit</a> <a class="btn danger" href="hapus_materi.php?id=<?= (int)$r['id'] ?>">Hapus</a></td></tr><?php endwhile; ?></table><?php else: ?><p>Tidak ada data materi yang cocok.</p><?php endif; ?>
<?php footer_html(); ?>
