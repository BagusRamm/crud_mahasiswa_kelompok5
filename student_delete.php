<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include("connection.php");

if (isset($_GET["nim"])) {
    $nim = htmlentities(strip_tags(trim($_GET["nim"])));
    $nim = mysqli_real_escape_string($connection, $nim);

    // Ambil nama dulu untuk pesan konfirmasi
    $query = "SELECT name FROM student WHERE nim='$nim'";
    $result = mysqli_query($connection, $query);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        $message = "Data mahasiswa tidak ditemukan";
    } else {
        $name = $data["name"];
        $query = "DELETE FROM student WHERE nim='$nim'";
        $query_result = mysqli_query($connection, $query);

        if ($query_result) {
            $message = "Mahasiswa dengan nama = \"<b>$name</b>\" sudah berhasil dihapus";
        } else {
            die("Query gagal dijalankan: ".mysqli_errno($connection)." - ".mysqli_error($connection));
        }
    }
    mysqli_close($connection);

    header("Location: student_view.php?message=".urlencode($message));
    exit();
} else {
    mysqli_close($connection);
    header("Location: student_view.php");
    exit();
}
?>
