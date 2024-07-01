<?php
require_once(__DIR__ . "/../Controller/dbconfig.php");
require_once(__DIR__ . "/../Controller/DatabaseHandler.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    $graphName = $_POST['graph-name'];
    $states = $_POST['states'];
    $symbols = $_POST['symbols'];
    $startState = $_POST['start-state'];
    $finalStates = $_POST['final-states']; // Assuming final states are submitted as an array

    // Initialize DatabaseHandler
    $dbHandler = new DatabaseHandler($dbh);

    try {
        $lastInsertId = $dbHandler->insertAutomata($graphName, $states, $symbols, $startState, $finalStates);
        
        if ($lastInsertId) {
            echo "<script>alert('Record inserted successfully');</script>";
            echo "<script>window.location.href='index.php'</script>";
        } else {
            echo "<script>alert('Failed to insert record');</script>";
            echo "<script>window.location.href='index.php'</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error inserting record: " . addslashes($e->getMessage()) . "');</script>";
    }
} else {
    echo "<script>alert('Please fill out all required fields.');</script>";
}
?>
