<?php
require 'koneksi.php';

$cari = $_GET['cari'] ?? '';
$filter_mk = $_GET['mata_kuliah_id'] ?? '';

// Mengambil daftar mata kuliah untuk pilihan filter
$query_mk = mysqli_query($conn, "SELECT id, nama FROM mata_kuliah ORDER BY nama");

// Query pencarian materi dengan filter mata kuliah
$sql = "SELECT materi.*, mata_kuliah.nama AS nama_mk FROM materi 
        JOIN mata_kuliah ON materi.mata_kuliah_id = mata_kuliah.id 
        WHERE materi.judul LIKE ?";
$params = ["%$cari%"];
$types = "s";

if ($filter_mk !== '') {
    $sql .= " AND materi.mata_kuliah_id = ?";
    $params[] = $filter_mk;
    $types .= "i";
}
$sql .= " ORDER BY materi.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$hasil = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Materi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Materi Pembelajaran</h1>
    <p><a href="index.php">&larr; Beranda</a> | <a href="tambah_materi.php" class="tombol">Tambah Materi</a></p>

    <form method="get">
        <input type="text" name="cari" placeholder="Cari judul materi..." value="<?= htmlspecialchars($cari, ENT_QUOTES, 'UTF-8') ?>">
        
        <select name="mata_kuliah_id">
            <option value="">Semua mata kuliah</option>
            <?php while ($mk = mysqli_fetch_assoc($query_mk)): ?>
                <option value="<?= $mk['id'] ?>" <?= ($filter_mk == $mk['id']) ? 'selected' : '' ?>><?= htmlspecialchars($mk['nama'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Cari</button>
        <a href="materi.php">Reset</a>
    </form>

    <p>Menampilkan <?= $hasil->num_rows ?> data.</p>
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse;">
        <tr>
            <th>Judul Materi</th>
            <th>Mata Kuliah</th>
            <th>Aksi</th>
        </tr>
        <?php if ($hasil->num_rows == 0): ?>
            <tr><td colspan="3">Data tidak ditemukan.</td></tr>
        <?php else: ?>
            <?php while ($row = $hasil->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['nama_mk'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="detail_materi.php?id=<?= $row['id'] ?>">Detail</a>
                        <a href="edit_materi.php?id=<?= $row['id'] ?>">Edit</a>
                        <a href="hapus_materi.php?id=<?= $row['id'] ?>">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </table>
</main>
</body>
</html>