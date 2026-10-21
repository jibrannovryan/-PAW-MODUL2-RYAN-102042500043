<?php
require 'koneksi.php';
session_start();

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$cari = '';
if (isset($_GET['cari'])) {
    $cari = trim($_GET['cari']);
}

$stmt = $conn->prepare("SELECT * FROM mata_kuliah WHERE kode LIKE ? OR nama LIKE ? ORDER BY id DESC");
$keyword = "%$cari%";
$stmt->bind_param("ss", $keyword, $keyword);
$stmt->execute();
$hasil = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Mata Kuliah</h1>
    <p><a href="index.php">&larr; Beranda</a> | <a href="tambah_mata_kuliah.php" class="tombol">Tambah Mata Kuliah</a></p>

    <form method="get">
        <input type="text" name="cari" placeholder="Cari kode atau nama..." value="<?= htmlspecialchars($cari, ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit">Cari</button>
        <a href="mata_kuliah.php">Reset</a>
    </form>

    <p>Menampilkan <?= $hasil->num_rows ?> data.</p>
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse;">
        <tr>
            <th>Mata Kuliah</th>
            <th>Dosen / Semester</th>
            <th>Aksi</th>
        </tr>
        <?php if ($hasil->num_rows == 0): ?>
            <tr><td colspan="3">Data tidak ditemukan.</td></tr>
        <?php else: ?>
            <?php while ($row = $hasil->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['kode'] . ' - ' . $row['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['dosen'] . ' / Semester ' . $row['semester'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="edit_mata_kuliah.php?id=<?= $row['id'] ?>">Edit</a>
                        <a href="hapus_mata_kuliah.php?id=<?= $row['id'] ?>">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </table>
</main>
</body>
</html>