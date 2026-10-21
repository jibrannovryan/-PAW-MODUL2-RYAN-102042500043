<?php
require 'koneksi.php'; require 'fungsi.php';
$courses=get_courses($conn); $pesan=''; $mkid=''; $judul=''; $isi=''; $tautan='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $mkid=valid_id($_POST['mata_kuliah_id']??null); $judul=trim($_POST['judul']??''); $isi=trim($_POST['isi']??''); $tautan=trim($_POST['tautan']??'');
 if(!csrf_valid()) $pesan='Token formulir tidak valid.';
 elseif(!$mkid || !course_exists($conn,$mkid)) $pesan='Pilih mata kuliah yang tersedia.';
 elseif(!valid_text($judul,3,150)) $pesan='Judul harus 3–150 karakter.';
 elseif(!valid_text($isi,3,2000)) $pesan='Isi materi harus 3–2000 karakter.';
 elseif(!valid_url($tautan)) $pesan='Tautan harus berupa URL valid yang diawali http:// atau https://.';
 else {$st=$conn->prepare("INSERT INTO materi (mata_kuliah_id,judul,isi,tautan) VALUES (?,?,?,?)");$st->bind_param("isss",$mkid,$judul,$isi,$tautan);if($st->execute())redirect_to('daftar_materi.php');$pesan='Materi gagal disimpan.';$st->close();}
}
header_html('Tambah Materi'); if(!$courses) echo '<p class="msg">Buat mata kuliah terlebih dahulu.</p>'; if($pesan!=='')echo '<p class="msg">'.e($pesan).'</p>';
?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label>Mata Kuliah</label><select name="mata_kuliah_id" required><option value="">Pilih mata kuliah</option><?php foreach($courses as $c): ?><option value="<?= (int)$c['id'] ?>" <?= (string)$mkid===(string)$c['id']?'selected':'' ?>><?= e($c['kode'].' - '.$c['nama']) ?></option><?php endforeach; ?></select>
<label>Judul (3–150 karakter)</label><input name="judul" minlength="3" maxlength="150" value="<?= e($judul) ?>" required>
<label>Isi materi (3–2000 karakter)</label><textarea name="isi" minlength="3" maxlength="2000" required><?= e($isi) ?></textarea>
<label>Tautan (http/https)</label><input type="url" name="tautan" maxlength="255" value="<?= e($tautan) ?>" required>
<p><button class="btn primary" type="submit" <?= !$courses?'disabled':'' ?>>Simpan</button><a class="btn" href="daftar_materi.php">Batal</a></p></form>
<?php footer_html(); ?>
