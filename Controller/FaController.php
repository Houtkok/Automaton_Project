<?php
class FaController {
    private $numStates;
    private $alphabet;
    private $transitions;
    private $startState;
    private $finalStates;
    
    //constructor
    public function __construct($numStates, $alphabet, $startState, $finalStates, $transitions) {
        $this->numStates = $numStates;
        $this->alphabet = $alphabet;
        $this->startState = $startState;
        $this->finalStates = $finalStates;
        $this->transitions = $transitions;
    }
    
    //check if FA is DFA or NFA 
    public function isDFA() {
        foreach ($this->transitions as $transitionsForState) {
            foreach ($this->alphabet as $input) {
                if (!isset($transitionsForState[$input]) || count($transitionsForState[$input]) != 1) {
                    return false;
                }
            }
        }
        return true;
        //implement this base on DFA : one current_state and input can only lead to one next_state 
    }
    
    // check if a string a is accepted or not 
    public function isAccepted($input) {
        //if dfa get check if accepted by dfa
        if ($this->isDFA()) {
            return $this->isAcceptedByDFA($input);
        } 
        //else get check if accepted by nfa
        else {
            return $this->isAcceptedByNFA($input);
        }
    }

    //check if accepted by dfa
    private function isAcceptedByDFA($input) {
        $currentState = $this->startState;
        
        foreach (str_split($input) as $symbol) {
            if (!isset($this->transitions[$currentState][$symbol])) {
                return false; // No transition defined for this symbol from the current state
            }
            $currentState = $this->transitions[$currentState][$symbol][0]; // Move to the next state
        }
        
        return in_array($currentState, $this->finalStates);
    }

    //check if accepted by NFA
    private function isAcceptedByNFA($input) {
        $currentStates = [$this->startState];
        
        foreach (str_split($input) as $symbol) {
            $nextStates = [];
            foreach ($currentStates as $state) {
                if (isset($this->transitions[$state][$symbol])) {
                    $nextStates = array_merge($nextStates, $this->transitions[$state][$symbol]);
                }
            }
            $currentStates = array_unique($nextStates);
        }
        
        foreach ($currentStates as $state) {
            if (in_array($state, $this->finalStates)) {
                return true;
            }
        }
        
        return false;
    }
}
// Example usage:
// $numStates = 3;
// $alphabet = [0, 1];
// $startState = 'A';
// $finalStates = ['C'];
// $transitions = [
//     'A' => ['0' => ['B'], '1' => ['A']],
//     'B' => ['0' => ['B'], '1' => ['C']],
//     'C' => ['0' => ['C'], '1' => ['A']]
// ];

// $fa = new FA($numStates, $alphabet, $startState, $finalStates, $transitions);
// echo "FA is " . ($fa->isDFA() ? "DFA" : "NFA") . "<br>"; 
// $input = "0100";
// echo "Is Accepted: " . ($fa->isAccepted($input) ? "Yes" : "No");


?>