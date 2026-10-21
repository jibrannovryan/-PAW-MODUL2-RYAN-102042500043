<?php
require 'koneksi.php'; require 'fungsi.php';
$id=valid_id($_GET['id']??$_POST['id']??null);if(!$id){http_response_code(400);exit('ID tidak valid.');}
$st=$conn->prepare("SELECT judul FROM materi WHERE id=?");$st->bind_param("i",$id);$st->execute();$d=$st->get_result()->fetch_assoc();$st->close();if(!$d){http_response_code(404);exit('Materi tidak ditemukan.');}
$pesan='';if($_SERVER['REQUEST_METHOD']==='POST'){if(!csrf_valid())$pesan='Token tidak valid.';else{$del=$conn->prepare("DELETE FROM materi WHERE id=?");$del->bind_param("i",$id);if($del->execute())redirect_to('daftar_materi.php');$pesan='Materi gagal dihapus.';$del->close();}}
header_html('Konfirmasi Hapus Materi');if($pesan!=='')echo '<p class="msg">'.e($pesan).'</p>';?>
<p>Yakin ingin menghapus materi <strong><?= e($d['judul']) ?></strong>?</p><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= $id ?>"><button class="btn danger" type="submit">Ya, Hapus</button><a class="btn" href="daftar_materi.php">Batal</a></form><?php footer_html(); ?>
