<?php
require 'koneksi.php'; require 'fungsi.php';
$id=valid_id($_GET['id']??null); if(!$id){http_response_code(400);exit('ID tidak valid.');}
$st=$conn->prepare("SELECT m.judul,m.isi,m.tautan,mk.nama AS nama_mk FROM materi m JOIN mata_kuliah mk ON mk.id=m.mata_kuliah_id WHERE m.id=?");$st->bind_param("i",$id);$st->execute();$d=$st->get_result()->fetch_assoc();$st->close();if(!$d){http_response_code(404);exit('Materi tidak ditemukan.');}
header_html('Detail Materi'); ?>
<table><tr><th>Mata Kuliah</th><td><?= e($d['nama_mk']) ?></td></tr><tr><th>Judul</th><td><?= e($d['judul']) ?></td></tr><tr><th>Isi</th><td><?= nl2br(e($d['isi'])) ?></td></tr><tr><th>Tautan</th><td><a href="<?= e($d['tautan']) ?>" target="_blank" rel="noopener noreferrer"><?= e($d['tautan']) ?></a></td></tr></table>
<a class="btn" href="edit_materi.php?id=<?= $id ?>">Edit</a><a class="btn" href="daftar_materi.php">Kembali</a><?php footer_html(); ?>
