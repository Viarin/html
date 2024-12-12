<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";     // змініть на ваш username в MySQL
    $password = "alabarda123.";    // оновлений пароль
    $dbname = "bazatest";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $_POST['name'];
    $nazwisko = $_POST['nazwisko'];
    $wjek = $_POST['wjek'];
    
    $sql = "INSERT INTO student (Name, nazwisko, wjek) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $nazwisko, $wjek);
    
    if ($stmt->execute()) {
        header("Location: index.php?success=1");
    } else {
        header("Location: index.php?error=1");
    }
    
    $stmt->close();
    $conn->close();
}
?>
