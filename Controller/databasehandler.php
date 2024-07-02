<?php

class DatabaseHandler {
    private $dbh;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function insertAutomata($graphName, $states, $symbols, $startState, $finalStates, $transition_table) {
        // Convert final states array to a string for database storage
        $finalStatesStr = json_encode($finalStates);
        $statesJson = json_encode($states);
        $symbolsJson = json_encode($symbols);
        $transitionJson = serialize($transition_table);

        try {
            $sql = "INSERT INTO automata (StateName, State, Symbols, Start_State, Final_States, Transition) 
                    VALUES (:graphName, :states, :symbols, :startState, :finalStates, :transition)";
            $query = $this->dbh->prepare($sql);

            $query->bindParam(':graphName', $graphName, PDO::PARAM_STR);
            $query->bindParam(':states', $statesJson, PDO::PARAM_STR);
            $query->bindParam(':symbols', $symbolsJson, PDO::PARAM_STR);
            $query->bindParam(':startState', $startState, PDO::PARAM_STR);
            $query->bindParam(':finalStates', $finalStatesStr, PDO::PARAM_STR);
            $query->bindParam(':transition', $transitionJson, PDO::PARAM_STR);

            if ($query->execute()) {
                return $this->dbh->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Error inserting record: " . $e->getMessage());
        }
    }
    public function read(){
        try{
            $query = $this->dbh->prepare("SELECT * FROM `automata`");
            $query -> execute();
            $result = $query -> fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e){
            throw new Exception("Error ".$e->getMessage());
        }
    }
}

?>