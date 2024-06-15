<?php
class FiniteStateMachine {
    // private $numStates;
    // private $alphabet;
    // private $transitions;
    // private $startState;
    // private $finalStates;
    
    // //constructor
    // public function __construct($numStates, $alphabet, $startState, $finalStates, $transitions) {
    //     $this->numStates = $numStates;
    //     $this->alphabet = $alphabet;
    //     $this->startState = $startState;
    //     $this->finalStates = $finalStates;
    //     $this->transitions = $transitions;
    // }
    
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
    public function nfaToDfa(NonDeterministicFiniteAutomaton $nfa) {
        //Intialize DFA
        $dfa = new DeterministicFiniteAutomaton();
        $dfa->startState = $nfa->startState;
        $dfa->alphabet = $nfa->alphabet;
        //create unmarked states group of NFA wrapped Array
        $unmarkedStates = [[$nfa->startState]];
        //emptry transion and final for dfa 
        $dfa->transitionTable = [];
        $dfa->finalStates = [];

        while (!empty($unmarkedStates)) {
            //pop an unmarked state group from stack 
            $current = array_pop($unmarkedStates);
            $currentStateName = implode(",", $current);
            //ensure that transition table entry exist for current state group
            if (!isset($dfa->transitionTable[$currentStateName])) {
                $dfa->transitionTable[$currentStateName] = [];
            }
            //collect all the possible states nfa to current state
            foreach ($nfa->alphabet as $symbol) {
                $nextStates = [];
                foreach ($current as $state) {
                    //merge the dupplicates
                    if (isset($nfa->transitionTable[$state][$symbol])) {
                        $nextStates = array_merge($nextStates, $nfa->transitionTable[$state][$symbol]);
                    }
                }
                $nextStates = array_unique($nextStates);
                //convert all the nextStates to nextStateName
                if (!empty($nextStates)) {
                    $nextStateName = implode(",", $nextStates);
                    //check if nextStateName is not mark in the dfa, then add it to stack of unmark
                    if (!isset($dfa->transitionTable[$currentStateName][$symbol])) {
                        $dfa->transitionTable[$currentStateName][$symbol] = $nextStateName;
                        $unmarkedStates[] = $nextStates;
                    }
                }
            }
            //check final states of the NFA
            foreach ($current as $state) {
                if (in_array($state, $nfa->finalStates)) {
                    $dfa->finalStates[] = $currentStateName;
                    break;
                }
            }
        }
        return $dfa;
    }
    //minize by using Hopcroft algo
    public function minimizeDFA(DeterministicFiniteAutomaton $dfa) {
        //Initialize Partition P = F, Q \ F. 
        //F final state, array_key(...) = Q set of all transition, Array_diff(Q) = set are not final
        $P = [$dfa->finalStates, array_diff(array_keys($dfa->transitionTable), $dfa->finalStates)];
        //Initial W as set of final state
        $W = [$dfa->finalStates];
        while (!empty($W)) {
            //Initize set A to pop from W
            $A = array_pop($W);
            foreach ($dfa->alphabet as $c) {
                //let X be the set of stated for which a transition on C lead to state in A
                $X = [];
                foreach ($dfa->transitionTable as $q => $transitions) {
                    if (isset($transitions[$c]) && in_array($transitions[$c], $A)) {
                        $X[] = $q;
                    }
                }
                //remove unwanted partition in P 
                foreach ($P as $i => $Y) {
                    // X intersect Y is non-empty
                    $intersect = array_intersect($X, $Y);
                    // X union Y
                    $diff = array_diff($Y, $X);
                    //if both intersect and diff are nonempty
                    if (!empty($intersect) && !empty($diff)) {
                        unset($P[$i]);
                        $P[] = $intersect;
                        $P[] = $diff;
                        if (in_array($Y, $W)) {
                            unset($W[array_search($Y, $W)]);
                            $W[] = $intersect;
                            $W[] = $diff;
                        } else {
                            //include intersect and diff if they are not in W
                            if (count($intersect) <= count($diff)) {
                                $W[] = $intersect;
                            } else {
                                $W[] = $diff;
                            }
                        }
                    }
                }
            }
        }
        // a new DFA minimize result
        $minDFA = new DeterministicFiniteAutomaton();
        $minDFA->alphabet = $dfa->alphabet;
        $minDFA->transitionTable = [];
        //map original states to new minimized state base on P
        $stateMap = [];
        foreach ($P as $i => $stateGroup) {
            $name = implode(',', $stateGroup);
            if (array_intersect($stateGroup, $dfa->finalStates)) {
                $minDFA->finalStates[] = $name;
            }
            foreach ($stateGroup as $s) {
                $stateMap[$s] = $name;
            }
        }
        //set minimized start state
        $minDFA->startState = $stateMap[$dfa->startState];
        //add minimized transition table
        foreach ($dfa->transitionTable as $oldState => $transitions) {
            foreach ($transitions as $symbol => $nextState) {
                $minDFA->transitionTable[$stateMap[$oldState]][$symbol] = $stateMap[$nextState];
            }
        }

        return $minDFA;
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