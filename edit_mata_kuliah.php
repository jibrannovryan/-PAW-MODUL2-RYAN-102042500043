<?php
require 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM mata_kuliah WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mk = $stmt->get_result()->fetch_assoc();

if (!$mk) {
    exit('Mata kuliah tidak ditemukan.');
}

$pesan = '';
$kode = $mk['kode'];
$nama = $mk['nama'];
$dosen = $mk['dosen'];
$semester = $mk['semester'];

if (isset($_POST['simpan'])) {
    $kode = trim($_POST['kode']);
    $nama = trim($_POST['nama']);
    $dosen = trim($_POST['dosen']);
    $semester = trim($_POST['semester']);

    if ($kode == '' || $nama == '' || $dosen == '' || $semester == '') {
        $pesan = 'Semua kolom wajib diisi.';
    } else {
        $stmt_cek = $conn->prepare("SELECT id FROM mata_kuliah WHERE kode = ? AND id <> ?");
        $stmt_cek->bind_param("si", $kode, $id);
        $stmt_cek->execute();
        if ($stmt_cek->get_result()->num_rows > 0) {
            $pesan = 'Kode mata kuliah sudah digunakan.';
        } else {
            $stmt = $conn->prepare("UPDATE mata_kuliah SET kode = ?, nama = ?, dosen = ?, semester = ? WHERE id = ?");
            $stmt->bind_param("sssii", $kode, $nama, $dosen, $semester, $id);
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
    <title>Edit Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Edit Mata Kuliah</h1>
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
        
        <button type="submit" name="simpan">Simpan perubahan</button>
        <a href="mata_kuliah.php">Batal</a>
    </form>
</main>
</body>
</html>