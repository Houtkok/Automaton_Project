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
    <title>Admin</title>
    <style>
        div{
            border: 1px solid black;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        a{
            display: inline;
            text-decoration: none;
            color: white;
        }
        a:hover{
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <button type="button" class="btn btn-success"><a href="homepage.php">Calculate</a></button>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>State Name</th>
                    <th>Symbol </th>
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
                    $unser = json_decode($dt['Transition'],true);
                    echo "<td>";
                    if (is_array($unser)) {
                        $db->printNestedArray($unser);
                    } else {
                        echo "Invalid transition data";
                    }
                    echo "</td>";
                    echo "<td>
                                <a class='btn btn-info'     href=''>View</a>
                                <a class='btn btn-danger'   href='handlerDelete.php?id=  {$dt['id']}'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
                ?>
                
            </tbody>
        </table>
    </div>
</body>
</html>