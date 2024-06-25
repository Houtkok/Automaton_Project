<?php
class Graph
{
    public $startState;
    public $finalStates;
    public $alphabet;
    public $transitionTable;

    public $name;
    public function __construct($name,$alphabet, $startState, $finalStates, $transitions) {
        $this->name=$name;
        $this->alphabet = $alphabet;
        $this->startState = $startState;
        $this->finalStates = $finalStates;
        $this->transitions = $transitions;
    }
}
?>