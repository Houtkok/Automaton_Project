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
    <button type="button" class="btn btn-success"><a href="index_database_crud.php">Create</a></button>
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
            // print_r($data);
                foreach ($data as $dt) {
                    echo "<tr>";
                    echo "<td>  {$dt['id']}               </td>";
                    echo "<td>  {$dt['StateName']}             </td>";
                    echo "<td>  {$dt['State']}      </td>";
                    echo "<td>  {$dt['Symbols']}        </td>";
                    echo "<td>  {$dt['Start_State']}              </td>";
                    echo "<td>  {$dt['Final_States']}         </td>";
                    echo "<td>  {$dt['Transition']}          </td>";
                    echo "<td>
                                <a class='btn btn-info'     href=''>View</a>
                                <a class='btn btn-danger'   href=''>Delete</a>
                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>