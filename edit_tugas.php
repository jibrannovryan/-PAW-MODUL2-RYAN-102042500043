<?php
require 'koneksi.php';require 'fungsi.php';$id=valid_id($_GET['id']??$_POST['id']??null);if(!$id){http_response_code(400);exit('ID tidak valid.');}
$st=$conn->prepare("SELECT mata_kuliah_id,judul,instruksi,tenggat,status FROM tugas WHERE id=?");$st->bind_param("i",$id);$st->execute();$d=$st->get_result()->fetch_assoc();$st->close();if(!$d){http_response_code(404);exit('Tugas tidak ditemukan.');}
$courses=get_courses($conn);$pesan='';$allowed=['Draf','Aktif','Selesai'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 $mk=valid_id($_POST['mata_kuliah_id']??null);$judul=trim($_POST['judul']??'');$instruksi=trim($_POST['instruksi']??'');$tenggat=trim($_POST['tenggat']??'');$status=$_POST['status']??'';$date=DateTime::createFromFormat('!Y-m-d',$tenggat);
 if(!csrf_valid())$pesan='Token tidak valid.';
 elseif(!$mk||!course_exists($conn,$mk))$pesan='Pilih mata kuliah yang tersedia.';
 elseif(!valid_text($judul,3,150))$pesan='Judul harus 3–150 karakter.';
 elseif(!valid_text($instruksi,3,2000))$pesan='Instruksi harus 3–2000 karakter.';
 elseif(!$date||$date->format('Y-m-d')!==$tenggat)$pesan='Tanggal tenggat tidak valid.';
 elseif(!in_array($status,$allowed,true))$pesan='Status harus Draf, Aktif, atau Selesai.';
 else{if($mk===(int)$d['mata_kuliah_id']&&$judul===$d['judul']&&$instruksi===$d['instruksi']&&$tenggat===$d['tenggat']&&$status===$d['status'])$pesan='Tidak ada perubahan pada data.';else{$up=$conn->prepare("UPDATE tugas SET mata_kuliah_id=?,judul=?,instruksi=?,tenggat=?,status=? WHERE id=?");$up->bind_param("issssi",$mk,$judul,$instruksi,$tenggat,$status,$id);if($up->execute()){if($up->affected_rows===0)$pesan='Data tidak berubah.';else redirect_to('daftar_tugas.php');}else$pesan='Tugas gagal diperbarui.';$up->close();}}
 $d=['mata_kuliah_id'=>$mk??'','judul'=>$judul,'instruksi'=>$instruksi,'tenggat'=>$tenggat,'status'=>$status];
}
header_html('Edit Tugas');if($pesan!=='')echo '<p class="msg">'.e($pesan).'</p>';?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= $id ?>">
<label>Mata Kuliah</label><select name="mata_kuliah_id" required><?php foreach($courses as $c): ?><option value="<?= (int)$c['id'] ?>" <?= (string)$d['mata_kuliah_id']===(string)$c['id']?'selected':'' ?>><?= e($c['kode'].' - '.$c['nama']) ?></option><?php endforeach; ?></select>
<label>Judul</label><input name="judul" minlength="3" maxlength="150" value="<?= e($d['judul']) ?>" required>
<label>Instruksi</label><textarea name="instruksi" minlength="3" maxlength="2000" required><?= e($d['instruksi']) ?></textarea>
<label>Tenggat</label><input type="date" name="tenggat" value="<?= e($d['tenggat']) ?>" required>
<label>Status</label><select name="status" required><?php foreach($allowed as $s): ?><option value="<?= e($s) ?>" <?= $d['status']===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select>
<p><button class="btn primary" type="submit">Simpan Perubahan</button><a class="btn" href="daftar_tugas.php">Batal</a></p></form><?php footer_html(); ?>
