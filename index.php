<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pengguna</title>
</head>
<body>
    <h1>Input Pengguna</h1>

    <?php
    session_start();
    if (isset($_SESSION['success_message'])) {
        echo '<script>alert("' . $_SESSION['success_message'] . '");</script>';
        unset($_SESSION['success_message']);
    }
    ?>
    
    <form method="post" action="process.php">
        <label for="userData">Masukkan data pengguna (NAMA[spasi]USIA[spasi]KOTA): </label>
        <input type="text" id="userData" name="userData" required>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
