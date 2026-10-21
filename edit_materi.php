<?php
require 'koneksi.php'; require 'fungsi.php';
$id=valid_id($_GET['id']??$_POST['id']??null);if(!$id){http_response_code(400);exit('ID tidak valid.');}
$st=$conn->prepare("SELECT mata_kuliah_id,judul,isi,tautan FROM materi WHERE id=?");$st->bind_param("i",$id);$st->execute();$d=$st->get_result()->fetch_assoc();$st->close();if(!$d){http_response_code(404);exit('Materi tidak ditemukan.');}
$courses=get_courses($conn);$pesan='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $mk=valid_id($_POST['mata_kuliah_id']??null);$judul=trim($_POST['judul']??'');$isi=trim($_POST['isi']??'');$tautan=trim($_POST['tautan']??'');
 if(!csrf_valid())$pesan='Token tidak valid.';
 elseif(!$mk||!course_exists($conn,$mk))$pesan='Pilih mata kuliah yang tersedia.';
 elseif(!valid_text($judul,3,150))$pesan='Judul harus 3–150 karakter.';
 elseif(!valid_text($isi,3,2000))$pesan='Isi materi harus 3–2000 karakter.';
 elseif(!valid_url($tautan))$pesan='Tautan harus diawali http:// atau https:// dan maksimal 255 karakter.';
 else {if($mk===(int)$d['mata_kuliah_id']&&$judul===$d['judul']&&$isi===$d['isi']&&$tautan===$d['tautan'])$pesan='Tidak ada perubahan pada data.';else{$up=$conn->prepare("UPDATE materi SET mata_kuliah_id=?,judul=?,isi=?,tautan=? WHERE id=?");$up->bind_param("isssi",$mk,$judul,$isi,$tautan,$id);if($up->execute()){if($up->affected_rows===0)$pesan='Data tidak berubah.';else redirect_to('daftar_materi.php');}else$pesan='Data gagal diperbarui.';$up->close();}}
 $d=['mata_kuliah_id'=>$mk??'','judul'=>$judul,'isi'=>$isi,'tautan'=>$tautan];
}
header_html('Edit Materi');if($pesan!=='')echo '<p class="msg">'.e($pesan).'</p>';?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= $id ?>">
<label>Mata Kuliah</label><select name="mata_kuliah_id" required><?php foreach($courses as $c): ?><option value="<?= (int)$c['id'] ?>" <?= (string)$d['mata_kuliah_id']===(string)$c['id']?'selected':'' ?>><?= e($c['kode'].' - '.$c['nama']) ?></option><?php endforeach; ?></select>
<label>Judul</label><input name="judul" minlength="3" maxlength="150" value="<?= e($d['judul']) ?>" required>
<label>Isi materi</label><textarea name="isi" minlength="3" maxlength="2000" required><?= e($d['isi']) ?></textarea>
<label>Tautan</label><input type="url" name="tautan" maxlength="255" value="<?= e($d['tautan']) ?>" required>
<p><button class="btn primary" type="submit">Simpan Perubahan</button><a class="btn" href="daftar_materi.php">Batal</a></p></form><?php footer_html(); ?>
