<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include('povezava.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_worker'])) {
    $ime = $_POST['ime'];
    $primek = $_POST['primek'];
    $stmt = $conn->prepare("INSERT INTO usluzbenci (ime, primek) VALUES (?, ?)");
    $stmt->bind_param("ss", $ime, $primek);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_inventory'])) {
    $artikel = $_POST['artikel'];
    $zaloga = $_POST['zaloga'];
    $mesecna_poraba = $_POST['mesecna_poraba'];
    $stmt = $conn->prepare("INSERT INTO skladisce (artikel, zaloga, mesecna_poraba) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $artikel, $zaloga, $mesecna_poraba);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_finance_day'])) {
    $zasluzek = $_POST['zasluzek'];
    $stmt = $conn->prepare("INSERT INTO finance (zasluzek) VALUES (?)");
    $stmt->bind_param("i", $zasluzek);
    $stmt->execute();
    $stmt->close();
}

$usluzbenci_query = "SELECT * FROM usluzbenci";
$usluzbenci_result = $conn->query($usluzbenci_query);

$skladisce_query = "SELECT * FROM skladisce";
$skladisce_result = $conn->query($skladisce_query);

$finance_query = "SELECT * FROM finance";
$finance_result = $conn->query($finance_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McManager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/3/36/McDonald%27s_Golden_Arches.svg" type="image/icon type">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .pozdrav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 30px;
            background-color: #ffffff;
            border-bottom: 2px solid #FFC72C;
        }
        .pozdrav img {
            height: 50px;
        }
        .greeting {
            font-size: 20px;
            font-weight: bold;
            color: #333333;
        }
        .gumb {
            background-color: #ffc107;
            color: #333333;
            font-weight: bold;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .gumb:hover {
            background-color: #e0a800;
        }
        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }
        .nav-buttons button {
            background-color: #ffc107;
            border: none;
            color: #333333;
            font-size: 14px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .nav-buttons button:hover {
            background-color: #e0a800;
        }
        .data-table {
            margin-top: 20px;
            display: none;
        }
        .data-table.active {
            display: block;
        }
        .form-container {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }
        .form-container h4 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="pozdrav">
        <div class="logo-and-greeting">
            <img src="https://upload.wikimedia.org/wikipedia/commons/3/36/McDonald%27s_Golden_Arches.svg" alt="McDonald's Logo">
            <p class="greeting">Pozdravljen, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        </div>
        <a href="logout.php" class="gumb">ODJAVA</a>
    </div>

    <div class="nav-buttons">
        <button onclick="showTable('usluzbenci')">Uslužbenci</button>
        <button onclick="showTable('skladisce')">Skladišče</button>
        <button onclick="showTable('finance')">Finance</button>
    </div>

    <div class="container">
        <div id="usluzbenci" class="data-table active">
            <h2>Uslužbenci</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ime</th>
                        <th>Primek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $usluzbenci_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['ime']; ?></td>
                            <td><?php echo $row['primek']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="form-container">
                <h4>Dodaj Uslužbenca</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label for="ime" class="form-label">Ime</label>
                        <input type="text" name="ime" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="primek" class="form-label">Primek</label>
                        <input type="text" name="primek" class="form-control" required>
                    </div>
                    <button type="submit" name="add_worker" class="btn btn-primary">Dodaj</button>
                </form>
            </div>
        </div>

        <div id="skladisce" class="data-table">
            <h2>Skladišče</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Artikel ID</th>
                        <th>Artikel</th>
                        <th>Zaloga</th>
                        <th>Mesečna Poraba</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $skladisce_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['artikel_id']; ?></td>
                            <td><?php echo $row['artikel']; ?></td>
                            <td><?php echo $row['zaloga']; ?></td>
                            <td><?php echo $row['mesecna_poraba']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="form-container">
                <h4>Dodaj Inventar</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label for="artikel" class="form-label">Artikel</label>
                        <input type="text" name="artikel" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="zaloga" class="form-label">Zaloga</label>
                        <input type="number" name="zaloga" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="mesecna_poraba" class="form-label">Mesečna Poraba</label>
                        <input type="number" name="mesecna_poraba" class="form-control" required>
                    </div>
                    <button type="submit" name="add_inventory" class="btn btn-primary">Dodaj</button>
                </form>
            </div>
        </div>

        <div id="finance" class="data-table">
            <h2>Finance</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Dan ID</th>
                        <th>Zaslužek (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $finance_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['dan_id']; ?></td>
                            <td><?php echo $row['zasluzek']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="form-container">
                <h4>Dodaj Dan v Finance</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label for="zasluzek" class="form-label">Zaslužek (€)</label>
                        <input type="number" name="zasluzek" class="form-control" required>
                    </div>
                    <button type="submit" name="add_finance_day" class="btn btn-primary">Dodaj</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showTable(tableId) {
            document.querySelectorAll('.data-table').forEach(table => table.classList.remove('active'));
            document.getElementById(tableId).classList.add('active');
        }
    </script>
</body>
</html>
