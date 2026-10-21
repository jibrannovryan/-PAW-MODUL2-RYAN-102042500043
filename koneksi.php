<?php
$conn = mysqli_connect('127.0.0.1', 'root', 'Ryan@071101', 'paw_jurnal2_lms');
mysqli_set_charset($conn, 'utf8mb4');
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
=======
// Sesuaikan host, user, password, dan nama database komputer Anda
$conn = mysqli_connect('127.0.0.1', 'root', 'Ryan@071101', 'paw_modul2_showroom');
mysqli_set_charset($conn, 'utf8mb4');
>>>>>>> 06701a8a15f16d083bf42b7e6edc71bc6b9bdbd8
