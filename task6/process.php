<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST['first-name'] ?? '';
    $lastName = $_POST['last-name'] ?? '';
    $street = $_POST['street'] ?? '';
    $houseNumber = $_POST['house-number'] ?? '';
    $city = $_POST['city'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    
    // You can process the data here (e.g., save to database)
    
    // Display the submitted data
    echo "<!DOCTYPE html>
    <html lang='pl'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Dane formularza</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 20px;
            }
            .data-container {
                max-width: 400px;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
                background-color: #f9f9f9;
            }
            .data-item {
                margin-bottom: 10px;
            }
            .label {
                font-weight: bold;
            }
            .back-button {
                margin-top: 20px;
                padding: 10px;
                background-color: #28a745;
                color: white;
                border: none;
                border-radius: 3px;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
            }
            .back-button:hover {
                background-color: #218838;
            }
        </style>
    </head>
    <body>
        <div class='data-container'>
            <h2>Wprowadzone dane:</h2>
            <div class='data-item'><span class='label'>Imię:</span> " . htmlspecialchars($firstName) . "</div>
            <div class='data-item'><span class='label'>Nazwisko:</span> " . htmlspecialchars($lastName) . "</div>
            <div class='data-item'><span class='label'>Ulica:</span> " . htmlspecialchars($street) . "</div>
            <div class='data-item'><span class='label'>Nr domu:</span> " . htmlspecialchars($houseNumber) . "</div>
            <div class='data-item'><span class='label'>Miasto:</span> " . htmlspecialchars($city) . "</div>
            <div class='data-item'><span class='label'>Data urodzenia:</span> " . htmlspecialchars($birthdate) . "</div>
            <div class='data-item'><span class='label'>Telefon:</span> " . htmlspecialchars($phone) . "</div>
            <div class='data-item'><span class='label'>E-mail:</span> " . htmlspecialchars($email) . "</div>
            
            <a href='form.html' class='back-button'>Powrót do formularza</a>
        </div>
    </body>
    </html>";
} else {
    // If someone tries to access this file directly without POST data
    header("Location: form.html");
    exit();
}
?>
