<?php
class Graph
{
    public $startState;
    public $finalStates;
    public $alphabet;
    public $transitionTable;

    public $name;

    public function __construct()
    {
        $this->name = null;
        $this->startState = null;
        $this->finalStates = [];
        $this->alphabet = [];
        $this->transitionTable = [];
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    public function setStartState($state)
    {
        $this->startState = $state;
    }
    public function setFinalState(array $states)
    {
        $this->finalStates = $states;
    }
    public function setAlphabet(array $symbols)
    {
        $this->alphabet = $symbols;
    }
    public function setTransition(array $transitionTable)
    {
        $this->transitionTable = $transitionTable;
    }
}
?>