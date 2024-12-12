<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['id'])) {
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
    
    $sql = "DELETE FROM student WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?delete_success=1");
    } else {
        header("Location: index.php?delete_error=1");
    }
    
    $stmt->close();
    $conn->close();
}
?>
