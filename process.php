<?php
$saveData = false;
$angka = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
$tahunKeywords = ["TAHUN", "THN", "TH"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = $_POST["userData"];
    $userData = explode(' ', $userInput);

    if (count($userData) >= 1) {
        $name = '';
        $age = '';
        $city = '';
        $isAge = false;

        foreach ($userData as $element) {
            if (is_numeric($element)) {
                // Jika elemen berupa angka, set isAge menjadi true
                $isAge = true;
                // Masukkan elemen ke age
                $age .= $element . ' ';
            } elseif ($isAge) {
                // Jika isAge true, masukkan elemen ke city
                $city .= $element . ' ';
            } else {
                // Jika isAge false, masukkan elemen ke name
                $name .= $element . ' ';
            }
        }

        // Hapus spasi ekstra di akhir string
        $name = rtrim($name);
        $age = rtrim($age);
        $city = rtrim($city);

        if (!$saveData) {
            // Transformasi uppercase sesuai kebutuhan
            $name = transformUppercase($name);
            $age = transformAge($age);
            $city = transformUppercase($city);

            // Simpan ke database
            simpanKeDatabase($name, $age, $city);

            $saveData = true;

            // Set session untuk memberikan pesan sukses
            session_start();
            $_SESSION['success_message'] = "Data berhasil disimpan ke database.";

            // Kembalikan ke halaman index.php
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
        $name = '';
        $age = '';
        $city = '';
        $isAge = false;

        foreach ($userData as $element) {
            if (is_numeric($element)) {
                $isAge = true;
                $age .= $element . ' ';
            } elseif ($isAge) {
                $city .= $element . ' ';
            } else {
                $name .= $element . ' ';
            }
        }

        $name = rtrim($name);
        $age = rtrim($age);
        $city = rtrim($city);

        if (!$saveData) {
            $name = transformUppercase($name);
            $age = transformAge($age);
            $city = transformUppercase($city);

            simpanKeDatabase($name, $age, $city);

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

function transformAge($usiaString) {
    // Mengambil hanya angka dari string usia
    $usia = (int) preg_replace('/\D/', '', $usiaString);
    return $usia;
}

function transformUppercase($string) {
    return strtoupper($string);
}

function simpanKeDatabase($name, $age, $city) {
    global $tahunKeywords;

    // Hapus kata yang ada di tahunKeywords dari city
    $city = str_ireplace($tahunKeywords, '', $city);

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

    $saveToDB->bind_param("sis", $name, $age, $city);

    $saveToDB->execute();

    $saveToDB->close();
    $conn->close();
}

?>
