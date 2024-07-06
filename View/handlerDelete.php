<?php 

require_once '../Controller/databasehandler.php';
require_once '../Controller/dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    $db = new DatabaseHandler($dbh);
    $success = $db->delete($id);
    if ($success) {
        header("Location: index.php");
        exit();
    } else {
        echo "Delete failed";
    }
} else {
    echo "Invalid request!";
}