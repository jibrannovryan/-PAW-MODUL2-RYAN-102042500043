<?php
require 'koneksi.php';require 'fungsi.php';$id=valid_id($_GET['id']??null);if(!$id){http_response_code(400);exit('ID tidak valid.');}
$st=$conn->prepare("SELECT t.judul,t.instruksi,t.tenggat,t.status,mk.nama AS nama_mk FROM tugas t JOIN mata_kuliah mk ON mk.id=t.mata_kuliah_id WHERE t.id=?");$st->bind_param("i",$id);$st->execute();$d=$st->get_result()->fetch_assoc();$st->close();if(!$d){http_response_code(404);exit('Tugas tidak ditemukan.');}
header_html('Detail Tugas');?>
<table><tr><th>Mata Kuliah</th><td><?= e($d['nama_mk']) ?></td></tr><tr><th>Judul</th><td><?= e($d['judul']) ?></td></tr><tr><th>Instruksi</th><td><?= nl2br(e($d['instruksi'])) ?></td></tr><tr><th>Tenggat</th><td><?= e($d['tenggat']) ?></td></tr><tr><th>Status</th><td><?= e($d['status']) ?></td></tr></table>
<a class="btn" href="edit_tugas.php?id=<?= $id ?>">Edit</a><a class="btn" href="daftar_tugas.php">Kembali</a><?php footer_html(); ?>
