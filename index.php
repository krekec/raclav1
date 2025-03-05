<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: prijava.php");
    exit();
}

include('povezava.php'); 

$activeTab = isset($_GET['view']) ? $_GET['view'] : 'usluzbenci';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_worker'])) {
    $ime = $_POST['ime'];
    $primek = $_POST['primek'];
    $sql = "INSERT INTO usluzbenci (ime, primek) VALUES ('$ime', '$primek')";
    $conn->query($sql);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_inventory'])) {
    $artikel = $_POST['artikel'];
    $zaloga = $_POST['zaloga'];
    $mesecna_poraba = $_POST['mesecna_poraba'];
    $sql = "INSERT INTO skladisce (artikel, zaloga, mesecna_poraba) VALUES ('$artikel', $zaloga, $mesecna_poraba)";
    $conn->query($sql);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_finance_day'])) {
    $zasluzek = $_POST['zasluzek'];
    $sql = "INSERT INTO finance (zasluzek) VALUES ($zasluzek)";
    $conn->query($sql);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_poraba'])) {
    $artikel_id = (int)$_POST['artikel_id'];
    $change = (int)$_POST['change']; /
    $sql = "UPDATE skladisce SET mesecna_poraba = mesecna_poraba + ($change) WHERE artikel_id = $artikel_id";
    $conn->query($sql);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_zaloga'])) {
    $artikel_id = (int)$_POST['artikel_id'];
    $change = (int)$_POST['change'];
    $sql = "UPDATE skladisce SET zaloga = zaloga + ($change) WHERE artikel_id = $artikel_id";
    $conn->query($sql);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_artikel'])) {
    $artikel_id = (int)$_POST['artikel_id'];
    $sql = "DELETE FROM skladisce WHERE artikel_id = $artikel_id";
    $conn->query($sql);
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
        .adjust-buttons form {
            display: inline;
        }
        .adjust-buttons form button {
            display: inline-block;
            margin: 0 5px;
            padding: 2px 6px;
        }
        .delete-button form {
            display: inline;
        }
    </style>
</head>
<body>

    <div class="pozdrav">
        <div class="logo-and-greeting">
            <img src="https://upload.wikimedia.org/wikipedia/commons/3/36/McDonald%27s_Golden_Arches.svg" alt="McDonald's Logo">
            <p class="greeting">Pozdravljen, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        </div>
        <a href="prijava.php" class="gumb">ODJAVA</a>
    </div>

    <div class="nav-buttons">
        <button onclick="showTable('usluzbenci')">Uslužbenci</button>
        <button onclick="showTable('skladisce')">Skladišče</button>
        <button onclick="showTable('finance')">Finance</button>
    </div>

    <div class="container">
        <div id="usluzbenci" class="data-table">
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
                    <?php 
                    $usluzbenci_result->data_seek(0);
                    while ($row = $usluzbenci_result->fetch_assoc()): ?>
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
                <form method="POST" action="?view=usluzbenci">
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
                        <th>Izbriši</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $skladisce_result = $conn->query($skladisce_query);
                    while ($row = $skladisce_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['artikel_id']; ?></td>
                            <td><?php echo $row['artikel']; ?></td>
                            <td class="adjust-buttons">
                                <form method="POST" action="?view=skladisce" style="display:inline;">
                                    <input type="hidden" name="artikel_id" value="<?php echo $row['artikel_id']; ?>">
                                    <input type="hidden" name="change" value="-1">
                                    <button type="submit" name="update_zaloga" class="btn btn-sm btn-danger">-</button>
                                </form>
                                <?php echo $row['zaloga']; ?>
                                <form method="POST" action="?view=skladisce" style="display:inline;">
                                    <input type="hidden" name="artikel_id" value="<?php echo $row['artikel_id']; ?>">
                                    <input type="hidden" name="change" value="1">
                                    <button type="submit" name="update_zaloga" class="btn btn-sm btn-success">+</button>
                                </form>
                            </td>
                            <td class="adjust-buttons">
                                <form method="POST" action="?view=skladisce" style="display:inline;">
                                    <input type="hidden" name="artikel_id" value="<?php echo $row['artikel_id']; ?>">
                                    <input type="hidden" name="change" value="-1">
                                    <button type="submit" name="update_poraba" class="btn btn-sm btn-danger">-</button>
                                </form>
                                <?php echo $row['mesecna_poraba']; ?>
                                <form method="POST" action="?view=skladisce" style="display:inline;">
                                    <input type="hidden" name="artikel_id" value="<?php echo $row['artikel_id']; ?>">
                                    <input type="hidden" name="change" value="1">
                                    <button type="submit" name="update_poraba" class="btn btn-sm btn-success">+</button>
                                </form>
                            </td>
                            <td class="delete-button">
                                <form method="POST" action="?view=skladisce" style="display:inline;">
                                    <input type="hidden" name="artikel_id" value="<?php echo $row['artikel_id']; ?>">
                                    <button type="submit" name="delete_artikel" class="btn btn-sm btn-danger">Izbriši</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="form-container">
                <h4>Dodaj Inventar</h4>
                <form method="POST" action="?view=skladisce">
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
                    <?php 
                    $finance_result->data_seek(0);
                    while ($row = $finance_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['dan_id']; ?></td>
                            <td><?php echo $row['zasluzek']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="form-container">
                <h4>Dodaj Dan v Finance</h4>
                <form method="POST" action="?view=finance">
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

        document.addEventListener("DOMContentLoaded", function() {
            showTable('<?php echo $activeTab; ?>');
        });
    </script>
</body>
</html>
