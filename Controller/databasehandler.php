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
        $transitionJson = json_encode($transition_table);

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
    //read data from database to view 
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
    // use for print out transition for view
    public function printNestedArray($array, $prefix = '') {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this -> printNestedArray($value, $prefix . "[$key]");
            } else {
                echo "{$prefix}[$key] => $value<br>";
            }
        }
    }
    //function use for clear unnecessary 
    public function cleanStr($string){
        $result = preg_replace('/[^A-Za-z0-9,]/','',$string);
        return $result;
    }
    //function for delect 
    public function delete($id){
        try{
            $query = $this->dbh->prepare("DELETE FROM `automata` WHERE `id`=:id");
            $query->bindParam(':id',$id);
            $query->execute();
            return true;
        }
        catch(PDOException $e){
            echo "Error : " . $e -> getMessage();
            return false;
        }
    }
}

?>