<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "alabarda123.";
    $dbname = "bazatest";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_POST['id'];
    $name = $_POST['name'];
    $nazwisko = $_POST['nazwisko'];
    $wjek = $_POST['wjek'];
    
    $sql = "UPDATE student SET Name = ?, nazwisko = ?, wjek = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $name, $nazwisko, $wjek, $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?update_success=1");
    } else {
        header("Location: index.php?update_error=1");
    }
    
    $stmt->close();
    $conn->close();
}
?>
