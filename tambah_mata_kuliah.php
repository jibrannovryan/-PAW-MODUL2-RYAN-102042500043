<?php
require 'koneksi.php';
$pesan = '';
$kode = $nama = $dosen = $semester = '';

if (isset($_POST['simpan'])) {
    $kode = trim($_POST['kode']);
    $nama = trim($_POST['nama']);
    $dosen = trim($_POST['dosen']);
    $semester = trim($_POST['semester']);

    if ($kode == '' || $nama == '' || $dosen == '' || $semester == '') {
        $pesan = 'Semua kolom wajib diisi.';
    } elseif (strlen($kode) < 3 || strlen($kode) > 20) {
        $pesan = 'Kode harus 3-20 karakter.';
    } elseif (strlen($nama) < 3 || strlen($nama) > 100) {
        $pesan = 'Nama harus 3-100 karakter.';
    } elseif (strlen($dosen) < 3 || strlen($dosen) > 100) {
        $pesan = 'Dosen harus 3-100 karakter.';
    } elseif (!is_numeric($semester) || $semester < 1 || $semester > 14) {
        $pesan = 'Semester harus bilangan bulat 1-14.';
    } else {
        $stmt_cek = $conn->prepare("SELECT id FROM mata_kuliah WHERE kode = ?");
        $stmt_cek->bind_param("s", $kode);
        $stmt_cek->execute();
        if ($stmt_cek->get_result()->num_rows > 0) {
            $pesan = 'Kode mata kuliah sudah digunakan.';
        } else {
            $stmt = $conn->prepare("INSERT INTO mata_kuliah (kode, nama, dosen, semester) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $kode, $nama, $dosen, $semester);
            $stmt->execute();
            header('Location: mata_kuliah.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Tambah Mata Kuliah</h1>
    <p><a href="mata_kuliah.php">&larr; Kembali</a></p>

    <?php if ($pesan !== ''): ?>
        <div class="kesalahan"><?= $pesan ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Kode</label>
        <input type="text" name="kode" value="<?= htmlspecialchars($kode, ENT_QUOTES, 'UTF-8') ?>">
        
        <label>Nama</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') ?>">
        
        <label>Dosen</label>
        <input type="text" name="dosen" value="<?= htmlspecialchars($dosen, ENT_QUOTES, 'UTF-8') ?>">
        
        <label>Semester</label>
        <input type="number" name="semester" value="<?= htmlspecialchars($semester, ENT_QUOTES, 'UTF-8') ?>">
        
        <button type="submit" name="simpan">Simpan data</button>
        <a href="mata_kuliah.php">Batal</a>
    </form>
</main>
</body>
</html>