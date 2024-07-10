<?php
require_once(__DIR__ . "/../Controller/dbconfig.php");
require_once(__DIR__ . "/../Controller/DatabaseHandler.php");
$db = new DatabaseHandler($dbh);
$data = $db->read();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Admin</title>
    <style>
        body {
            padding-top: 70px; /* Adjust based on header height */
        }
        .button-container {
            text-align: center;
            margin-bottom: 20px;
        }
        a {
            text-decoration: none;
            color: white;
        }
        a:hover {
            color: white;
            text-decoration: none;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
        }
        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header class="bg p-2 d-flex justify-content-between align-items-center">
        <h3 style="color: white;">Automata</h3>
        <div style="display: flex; justify-content: right;">
            <button type="button" class="btn btn-success">
                <a href="homepage.php">Back</a>
            </button>
        </div>
    </header>
    <div class="container">
        <div class="table-container">
            <table class="table table-striped table-responsive">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>State Name</th>
                        <th>Symbol</th>
                        <th>Symbols</th>
                        <th>Start State</th>
                        <th>Final State</th>
                        <th>Transition</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($data as $dt) {
                        echo "<tr>";
                        echo "<td>"  .$dt['id']                            .      "</td>";
                        echo "<td>"  .$db->cleanStr($dt['StateName'])      .      "</td>";
                        echo "<td>"  .$db->cleanStr($dt['State'])          .      "</td>";
                        echo "<td>"  .$db->cleanStr($dt['Symbols'])        .      "</td>";
                        echo "<td>"  .$db->cleanStr($dt['Start_State'])    .      "</td>";
                        echo "<td>"  .$db->cleanStr($dt['Final_States'])   .      "</td>";
                        $unser = json_decode($dt['Transition'], true);
                        echo "<td>";
                        if (is_array($unser)) {
                            $db->printNestedArray($unser);
                        } else {
                            echo "Invalid transition data";
                        }
                        echo "</td>";
                        echo "<td>
                            <a class='btn btn-info btn-sm' href=''>View</a>
                            <a class='btn btn-danger btn-sm' href='handlerDelete.php?id={$dt['id']}'>Delete</a>
                        </td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
