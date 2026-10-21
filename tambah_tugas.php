<?php
require 'koneksi.php';
require 'fungsi.php';

$pesan = '';
$judul = $instruksi = $tenggat = $status = $mata_kuliah_id = '';

$query_mk = mysqli_query($conn, "SELECT id, nama FROM mata_kuliah ORDER BY nama");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mata_kuliah_id = (int) ($_POST['mata_kuliah_id'] ?? 0);
    $judul = trim($_POST['judul'] ?? '');
    $instruksi = trim($_POST['instruksi'] ?? '');
    $tenggat = trim($_POST['tenggat'] ?? '');
    $status = trim($_POST['status'] ?? '');

    $status_valid = ['Draf', 'Aktif', 'Selesai'];

    if ($mata_kuliah_id == 0 || $judul == '' || $instruksi == '' || $tenggat == '' || $status == '') {
        $pesan = 'Semua kolom wajib diisi.';
    } elseif (!in_array($status, $status_valid)) {
        $pesan = 'Status hanya boleh Draf, Aktif, atau Selesai.';
    } else {
        $stmt = $conn->prepare("INSERT INTO tugas (mata_kuliah_id, judul, instruksi, tenggat, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $mata_kuliah_id, $judul, $instruksi, $tenggat, $status);
        $stmt->execute();
        redirect_to('daftar_tugas.php');
    }
}

header_html("Tambah Tugas");
?>
<main>
    <h1>Tambah Tugas</h1>
    <p><a href="daftar_tugas.php">&larr; Kembali</a></p>

    <?php if ($pesan !== ''): ?>
        <div class="kesalahan"><?= e($pesan) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Mata Kuliah</label>
        <select name="mata_kuliah_id">
            <option value="">Pilih Mata Kuliah</option>
            <?php while ($mk = mysqli_fetch_assoc($query_mk)): ?>
                <option value="<?= $mk['id'] ?>" <?= ($mata_kuliah_id == $mk['id']) ? 'selected' : '' ?>><?= e($mk['nama']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Judul Tugas</label>
        <input type="text" name="judul" value="<?= e($judul) ?>">

        <label>Instruksi Tugas</label>
        <textarea name="instruksi" rows="5"><?= e($instruksi) ?></textarea>

        <label>Tenggat Waktu</label>
        <input type="date" name="tenggat" value="<?= e($tenggat) ?>">

        <label>Status</label>
        <select name="status">
            <option value="Draf" <?= ($status == 'Draf') ? 'selected' : '' ?>>Draf</option>
            <option value="Aktif" <?= ($status == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
            <option value="Selesai" <?= ($status == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
        </select>

        <button type="submit">Simpan data</button>
        <a href="daftar_tugas.php">Batal</a>
    </form>
</main>
<?php footer_html(); ?>