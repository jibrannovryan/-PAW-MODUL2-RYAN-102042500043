<?php
require 'koneksi.php';
session_start();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM mata_kuliah WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mk = $stmt->get_result()->fetch_assoc();

if (!$mk) {
    exit('Mata kuliah tidak ditemukan.');
}

$error_pesan = '';
if (isset($_POST['hapus'])) {
    if (!hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
        exit('Token keamanan tidak valid.');
    }

    $stmt_del = $conn->prepare("DELETE FROM mata_kuliah WHERE id = ?");
    $stmt_del->bind_param("i", $id);
    
    try {
        if ($stmt_del->execute()) {
            header('Location: mata_kuliah.php');
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        $error_pesan = 'Data belum tersimpan. Mata kuliah masih memiliki materi atau tugas. Hapus data terkait terlebih dahulu.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Hapus Mata Kuliah?</h1>
    
    <?php if ($error_pesan !== ''): ?>
        <div class="kesalahan"><?= $error_pesan ?></div>
    <?php endif; ?>

    <p>Apakah Anda yakin ingin menghapus: <strong><?= htmlspecialchars($mk['nama'], ENT_QUOTES, 'UTF-8') ?></strong>?</p>
    <p>Data baru dihapus setelah tombol konfirmasi dikirim.</p>

    <form method="post">
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        <button type="submit" name="hapus" style="background: darkred; color: white;">Ya, hapus data</button>
        <a href="mata_kuliah.php">Batal</a>
    </form>
</main>
</body>
</html>