<?php

class DatabaseHandler {
    private $dbh;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function insertAutomata($graphName, $states, $symbols, $startState, $finalStates) {
        // Convert final states array to a string for database storage
        $finalStatesStr = json_encode($finalStates);
        $statesJson = json_encode($states);
        $symbolsJson = json_encode($symbols);

        try {
            $sql = "INSERT INTO automata (StateName, State, Symbols, Start_State, Final_States) 
                    VALUES (:graphName, :states, :symbols, :startState, :finalStates)";
            $query = $this->dbh->prepare($sql);

            $query->bindParam(':graphName', $graphName, PDO::PARAM_STR);
            $query->bindParam(':states', $statesJson, PDO::PARAM_STR);
            $query->bindParam(':symbols', $symbolsJson, PDO::PARAM_STR);
            $query->bindParam(':startState', $startState, PDO::PARAM_STR);
            $query->bindParam(':finalStates', $finalStatesStr, PDO::PARAM_STR);

            if ($query->execute()) {
                return $this->dbh->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Error inserting record: " . $e->getMessage());
        }
    }
}

?>
