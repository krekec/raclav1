<?php
session_start();
include("povezava.php");

$error = ""; // Variable to hold error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM managers WHERE username = '$username'");

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) { 
            $_SESSION['username'] = $username; 
            header("Location: index.php");
            exit();
        } else {
            $error = "Neveljavno geslo.";
        }
    } else {
        $error = "Uporabniško ime  ni najdeno.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McDonald's Manager Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/3/36/McDonald%27s_Golden_Arches.svg" type="image/icon type">

    <style>
        body {
            background-color: #f8f9fa; /* Light background for better contrast */
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .loginsquare {
            border-radius: 10px;
            width: 400px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Softer shadow */
            text-align: center;
        }
  
        
        .gumb {
            background-color: #ffc107; /* Yellow button */
            border: none;
            color: #333333;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
        }
        .gumb:hover {
            background-color: #e0a800; /* Slightly darker yellow on hover */
        }
    </style>
</head>
<body>
    <div class="loginsquare">
        <h2>McDonald's Manager Prijava</h2>    
        <?php 
        if ($error != "") {
            echo "<div class='error-message'>$error</div>";
        }
        ?>
        <form method="POST" action="prijava.php">
            <div class="mb-3">
                <label for="username" class="form-label visually-hidden">Uporabniško iem</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Vnesi uporabnisko ime:" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label visually-hidden">Geslo</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Vnesi geslo:" required>
            </div>
            <button type="submit" class="gumb">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
