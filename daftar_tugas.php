<?php
require 'koneksi.php';
require 'fungsi.php';

$cari = $_GET['cari'] ?? '';
$filter_mk = $_GET['mata_kuliah_id'] ?? '';

$query_mk = mysqli_query($conn, "SELECT id, nama FROM mata_kuliah ORDER BY nama");

$sql = "SELECT tugas.*, mata_kuliah.nama AS nama_mk FROM tugas 
        JOIN mata_kuliah ON tugas.mata_kuliah_id = mata_kuliah.id 
        WHERE tugas.judul LIKE ?";
$params = ["%$cari%"];
$types = "s";

if ($filter_mk !== '') {
    $sql .= " AND tugas.mata_kuliah_id = ?";
    $params[] = $filter_mk;
    $types .= "i";
}
$sql .= " ORDER BY tugas.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$hasil = $stmt->get_result();

header_html("Daftar Tugas");
?>
<main>
    <h1>Daftar Tugas</h1>
    <p><a href="index.php">&larr; Beranda</a> | <a href="tambah_tugas.php" class="btn">Tambah Tugas</a></p>

    <form method="get">
        <input type="text" name="cari" placeholder="Cari judul tugas..." value="<?= e($cari) ?>">
        
        <select name="mata_kuliah_id">
            <option value="">Semua mata kuliah</option>
            <?php while ($mk = mysqli_fetch_assoc($query_mk)): ?>
                <option value="<?= $mk['id'] ?>" <?= ($filter_mk == $mk['id']) ? 'selected' : '' ?>><?= e($mk['nama']) ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Cari</button>
        <a href="daftar_tugas.php">Reset</a>
    </form>

    <p>Menampilkan <?= $hasil->num_rows ?> data.</p>
    <table>
        <tr>
            <th>Judul Tugas</th>
            <th>Mata Kuliah</th>
            <th>Tenggat</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php if ($hasil->num_rows == 0): ?>
            <tr><td colspan="5">Data tidak ditemukan.</td></tr>
        <?php else: ?>
            <?php while ($row = $hasil->fetch_assoc()): ?>
                <tr>
                    <td><?= e($row['judul']) ?></td>
                    <td><?= e($row['nama_mk']) ?></td>
                    <td><?= e($row['tenggat']) ?></td>
                    <td><?= e($row['status']) ?></td>
                    <td>
                        <a href="edit_tugas.php?id=<?= $row['id'] ?>">Edit</a>
                        <a href="hapus_tugas.php?id=<?= $row['id'] ?>">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </table>
</main>
<?php footer_html(); ?>