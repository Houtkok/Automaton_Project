<?php
class DeterministicFiniteAutomaton{
    public $startState;
    public $finalStates;
    public $alphabet;
    public $transitionTable;

    public function __construct(){
        $this->startState = null;
        $this->finalStates = [];
        $this->alphabet = [];
        $this->transitionTable = [];
    }
    
    public function setStartState($state){
        $this -> startState = $state;
    }
    public function setFinalState(array $states){
        $this -> finalStates = $states;
    }
    public function setAlphabet(array $symbols){
        $this -> alphabet = $symbols;
    }
    public function setTransition(array $transitionTable){
        $this -> transitionTable = $transitionTable;
    }
}