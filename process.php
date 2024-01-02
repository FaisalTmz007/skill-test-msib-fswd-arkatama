<?php
$saveData = false;
$angka = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
$tahunKeywords = ["TAHUN", "THN", "TH"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = $_POST["userData"];
    $userData = explode(' ', $userInput);

    if (count($userData) >= 1) {
        $nama = transformUppercase(implode(' ', array_slice($userData, 0, -1)));
        $usia = transformAge($userInput);
        $kota = transformUppercase(end($userData));

        if (!$saveData) {
            simpanKeDatabase($nama, $usia, $kota);
            $saveData = true;

            session_start();
            $_SESSION['success_message'] = "Data berhasil disimpan ke database.";

            header("Location: index.php");
            exit();
        }
    } else {
        echo "Format input tidak sesuai.\n";
    }
} else {
    $userInput = readline("Masukkan data pengguna (NAMA USIA KOTA): ");
    $userData = explode(' ', $userInput);

    if (count($userData) >= 1) {
        $nama = transformUppercase(implode(' ', array_slice($userData, 0, -1)));
        $usia = transformAge($userInput);
        $kota = transformUppercase(end($userData));

        if (!$saveData) {
            simpanKeDatabase($nama, $usia, $kota);
            $saveData = true;

            session_start();
            $_SESSION['success_message'] = "Data berhasil disimpan ke database.";

            header("Location: index.php");
            exit();
        }
    } else {
        echo "Format input tidak sesuai.\n";
    }
}

function transformAge($userInput) {
    global $angka, $tahunKeywords;

    foreach ($angka as $digit) {
        $containsAngka = strpos($userInput, $digit) !== false;

        if ($containsAngka) {
            return (int) preg_replace('/\D/', '', $userInput);
        }
    }

    return null;
}

function transformUppercase($string) {
    global $angka, $tahunKeywords;

    $nameWithoutNum = str_replace($angka, '', $string);
    $nameWithoutTahun = str_ireplace($tahunKeywords, '', $nameWithoutNum);

    return strtoupper($nameWithoutTahun);
}

function simpanKeDatabase($nama, $usia, $kota) {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "arkatama_test";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $insertQuery = "INSERT INTO user_data (name, age, city) VALUES (?, ?, ?)";
    $saveToDB = $conn->prepare($insertQuery);

    $saveToDB->bind_param("sis", $nama, $usia, $kota);

    $saveToDB->execute();

    $saveToDB->close();
    $conn->close();
}
?>
