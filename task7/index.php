<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "alabarda123.";
$dbname = "bazatest";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM student";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Завдання 7</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            color: white;
        }
        .btn-edit {
            background-color: #2196F3;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .form-container {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            width: 70%;
            max-width: 500px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Список студентів</h1>
        
        <?php
        if (isset($_GET['success'])) {
            echo '<div class="message success">Студента успішно додано!</div>';
        }
        if (isset($_GET['error'])) {
            echo '<div class="message error">Помилка при додаванні студента!</div>';
        }
        if (isset($_GET['delete_success'])) {
            echo '<div class="message success">Студента успішно видалено!</div>';
        }
        if (isset($_GET['delete_error'])) {
            echo '<div class="message error">Помилка при видаленні студента!</div>';
        }
        if (isset($_GET['update_success'])) {
            echo '<div class="message success">Дані студента успішно оновлено!</div>';
        }
        if (isset($_GET['update_error'])) {
            echo '<div class="message error">Помилка при оновленні даних студента!</div>';
        }
        ?>

        <div class="form-container">
            <h2>Додати нового студента</h2>
            <form action="add_student.php" method="POST">
                <div class="form-group">
                    <label for="name">Ім'я:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="nazwisko">Прізвище:</label>
                    <input type="text" id="nazwisko" name="nazwisko" required>
                </div>
                <div class="form-group">
                    <label for="wjek">Вік:</label>
                    <input type="number" id="wjek" name="wjek" required min="1" max="120">
                </div>
                <button type="submit" class="btn-submit">Додати студента</button>
            </form>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Ім'я</th>
                <th>Прізвище</th>
                <th>Вік</th>
                <th>Дії</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["Name"] . "</td>";
                    echo "<td>" . $row["nazwisko"] . "</td>";
                    echo "<td>" . $row["wjek"] . "</td>";
                    echo "<td class='action-buttons'>";
                    echo "<button onclick='openEditModal(" . $row["id"] . ", \"" . $row["Name"] . "\", \"" . $row["nazwisko"] . "\", " . $row["wjek"] . ")' class='btn btn-edit'>Редагувати</button>";
                    echo "<form action='delete_student.php' method='POST' style='display: inline;'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<button type='submit' class='btn btn-delete' onclick='return confirm(\"Ви впевнені?\")'>Видалити</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Немає даних</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Модальне вікно для редагування -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2>Редагувати дані студента</h2>
            <form action="update_student.php" method="POST">
                <input type="hidden" id="edit_id" name="id">
                <div class="form-group">
                    <label for="edit_name">Ім'я:</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="edit_nazwisko">Прізвище:</label>
                    <input type="text" id="edit_nazwisko" name="nazwisko" required>
                </div>
                <div class="form-group">
                    <label for="edit_wjek">Вік:</label>
                    <input type="number" id="edit_wjek" name="wjek" required min="1" max="120">
                </div>
                <button type="submit" class="btn-submit">Зберегти зміни</button>
                <button type="button" class="btn-submit" style="background-color: #f44336;" onclick="closeEditModal()">Скасувати</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name, nazwisko, wjek) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_nazwisko').value = nazwisko;
            document.getElementById('edit_wjek').value = wjek;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Закрити модальне вікно при кліку поза ним
        window.onclick = function(event) {
            var modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
