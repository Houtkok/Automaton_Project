<?php
require_once "DeterministicFiniteAutomaton.php";
require_once "NonDeterministicFiniteAutomaton.php";
require_once "Graph.php";
class FiniteStateMachine
{
    //check if FA is DFA or NFA 
    public function isDFA($fa)
    {
        foreach ($fa->transitions as $transitionsForState) {
            foreach ($fa->alphabet as $input) {
                if (!isset($transitionsForState[$input]) || count($transitionsForState[$input]) != 1) {
                    return false;
                }
            }
        }
        return true;
        //implement this base on DFA : one current_state and input can only lead to one next_state 
    }

    // check if a string a is accepted or not 
    public function isAccepted($input,$fa)
    {
        //if dfa get check if accepted by dfa
        if ($this->isDFA($fa)) {
            return $this->isAcceptedByDFA($input,$fa);
        }
        //else get check if accepted by nfa
        else {
            return $this->isAcceptedByNFA($input,$fa);
        }
    }

    //check if accepted by dfa
    private function isAcceptedByDFA($input,$fa)
    {
        $currentState = $fa->startState;

        foreach (str_split($input) as $symbol) {
            if (!isset($fa->transitions[$currentState][$symbol])) {
                return false; // No transition defined for this symbol from the current state
            }
            $currentState = $fa->transitions[$currentState][$symbol][0]; // Move to the next state
        }

        return in_array($currentState, $fa->finalStates);
    }

    //check if accepted by NFA
    private function isAcceptedByNFA($input,$fa)
    {
        $currentStates = [$fa->startState];

        foreach (str_split($input) as $symbol) {
            $nextStates = [];
            foreach ($currentStates as $state) {
                if (isset($fa->transitions[$state][$symbol])) {
                    $nextStates = array_merge($nextStates, $fa->transitions[$state][$symbol]);
                }
            }
            $currentStates = array_unique($nextStates);
        }

        foreach ($currentStates as $state) {
            if (in_array($state, $fa->finalStates)) {
                return true;
            }
        }

        return false;
    }
    public function nfaToDfa(NonDeterministicFiniteAutomaton $nfa)
    {
        if ($this->isDFA($nfa)) {
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
        } else
            echo "The FA is aleady a DFA";
    }
    //minize by using Hopcroft algo
    public function minimizeDFA(DeterministicFiniteAutomaton $dfa)
    {
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
    //for generating graph
    private function transitionsToDot(Graph $graph)
    {
        $dot = "digraph G {\n";
        $dot .= "    rankdir=LR;\n";

        // Define start state
        $dot .= "    node [shape = point]; start;\n";
        $dot .= "    start -> \"$graph->startState\";\n";
        $dot .= "    node [shape = circle];\n";

        foreach ($graph->transitionTable as $state => $actions) {
            // Define node shapes based on final or non-final state
            if (in_array($state, $graph->finalStates)) {
                $dot .= "    \"$state\" [shape = doublecircle];\n";
            } else {
                $dot .= "    \"$state\" [shape = circle];\n";
            }

            // Add transitions for each action
            foreach ($actions as $action => $next_states) {
                foreach ($next_states as $next_state) {
                    $dot .= "    \"$state\" -> \"$next_state\" [label=\"$action\"];\n";
                }
            }
        }

        $dot .= "}";
        return $dot;
    }

    // Method to render the DOT representation as a PNG using Graphviz
    private function renderGraph($dotString, $outputFile)
    {
        // Ensure directory exists for output file
        $outputDir = dirname($outputFile);
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        // Write DOT string to a temporary file
        $tempDotFile = tempnam(sys_get_temp_dir(), 'graph') . '.dot';
        file_put_contents($tempDotFile, $dotString);

        // Generate PNG file using Graphviz
        $command = "dot -Tpng $tempDotFile -o $outputFile";
        shell_exec($command);

        // Clean up temporary DOT file
        unlink($tempDotFile);

        return $outputFile;
    }

    // Method to generate the graph and return the path to the generated PNG file
    public function generateGraph(Graph $graph)
    {
        $graphDot = $this->transitionsToDot($graph);
        $outputFile = __DIR__ . '/graphs/' . $graph->name . '.png';
        return $this->renderGraph($graphDot, $outputFile);
    }

}