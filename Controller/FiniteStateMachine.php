<?php
require_once "DeterministicFiniteAutomaton.php";
require_once "NonDeterministicFiniteAutomaton.php";
require_once "Graph.php";
class FiniteStateMachine
{
    private $alphabet;
    private $transitions;
    private $startState;
    private $finalStates;

    public function __construct($alphabet, $startState, $finalStates, $transitions)
    {
        $this->alphabet = $alphabet;
        $this->startState = $startState;
        $this->finalStates = $finalStates;
        $this->transitions = $transitions;
    }
    //check if FA is DFA or NFA 
    public function isDFA()
    {
        try {
            foreach ($this->transitions as $transitionsForState) {
                foreach ($this->alphabet as $input) {
                    if (!isset($transitionsForState[$input]) || count($transitionsForState[$input]) != 1) {
                        return false;
                    }
                }
            }
            return true;
            //implement this base on DFA : one current_state and input can only lead to one next_state 
        } catch (Exception $e) {
            throw new Exception("Error DFA mathod: " . $e->getMessage());
        }
    }

    // check if a string a is accepted or not 
    public function isAccepted($input)
    {
        try {
            //if dfa get check if accepted by dfa
            if ($this->isDFA()) {
                return $this->isAcceptedByDFA($input);
            }
            //else get check if accepted by nfa
            else {
                return $this->isAcceptedByNFA($input);
            }
        } catch (Exception $e) {
            throw new Exception("Error in isAccepted method: " . $e->getMessage());
        }
    }

    //check if accepted by dfa
    private function isAcceptedByDFA($input)
    {
        try {
            $currentState = $this->startState;

            foreach (str_split($input) as $symbol) {
                if (!isset($this->transitions[$currentState][$symbol])) {
                    return false; // No transition defined for this symbol from the current state
                }
                $currentState = $this->transitions[$currentState][$symbol][0]; // Move to the next state
            }
            return in_array($currentState, $this->finalStates);
        } catch (Exception $e) {
            throw new Exception("Error in isAccepted method: " . $e->getMessage());
        }
    }

    //check if accepted by NFA
    private function isAcceptedByNFA($input)
    {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Error in isAccepted method: " . $e->getMessage());
        }
    }
    public function nfaToDfa(NonDeterministicFiniteAutomaton $nfa)
    {
        try {
            if (!$this->isDFA()) {
                //Intialize DFA
                $dfa = new DeterministicFiniteAutomaton();
                $dfa->startState = $nfa->startState;
                $dfa->alphabet = $nfa->alphabet;
                //create unmarked states group of NFA wrapped Array is use to keep track of state groups of nfa that need to be proceed
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
        } catch (Exception $e) {
            throw new Exception("Error in isAccepted method: " . $e->getMessage());
        }
    }
    //minize by using Hopcroft algo
    public function minimizeDFA(DeterministicFiniteAutomaton $dfa)
    {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Error in isAccepted method: " . $e->getMessage());
        }
    }
    //for generating graph
    private function transitionsToDot(Graph $graph)
    {
        try {
            $dot = "digraph G {\n";
            $dot .= "    rankdir=LR;\n";

            // Define start state
            $dot .= "    node [shape = point]; start;\n";
            $dot .= "    start -> \"$graph->startState\";\n";
            $dot .= "    node [shape = circle];\n";
            // Define start state color
            $dot .= "\"$graph->startState\" [label=<<font color=\"black\">$graph->startState</font>>, shape = circle, style = filled, color=black, fillcolor=green, fontcolor=black];\n";
            // Track transitions already added to avoid duplicates
            $addedTransitions = [];

            foreach ($graph->transitionTable as $state => $actions) {
                foreach ($graph->finalStates as $finalstates => $final) {
                    $dot .= "\"$final\" [label=<<font color=\"black\">$final</font>>, shape = doublecircle, style = filled, color=black, fillcolor=red, fontcolor=black];\n";
                    if ($state == $final) {
                        //$dot .= "\"$state\" [label=<<font color=\"black\">$state</font>>, shape = doublecircle, style = filled, color=black, fillcolor=red, fontcolor=black];\n";
                    } else {
                        $dot .= "    \"$state\" [shape = circle];\n";
                    }
                }

                // Add transitions for each action
                foreach ($actions as $action => $next_states) {
                    // Ensure $next_states is always an array
                    $nextStatesArray = is_array($next_states) ? $next_states : [$next_states];

                    foreach ($nextStatesArray as $next_state) {
                        // Format the action and next_state for uniqueness
                        $transitionKey = "$state-$action-$next_state";

                        // Add the transition if it hasn't been added already
                        if (!isset($addedTransitions[$transitionKey])) {
                            $dot .= "    \"$state\" -> \"$next_state\" [label=\"$action\"];\n";
                            $addedTransitions[$transitionKey] = true;
                        }
                    }
                }
            }

            $dot .= "}";
            return $dot;
        } catch (Exception $e) {
            throw new Exception("Error in transitionsToDot method: " . $e->getMessage());
        }
    }

    // Method to render the DOT representation as a PNG using Graphviz
    private function renderGraph($dotString, $outputFile)
    {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Error in isAccepted method: " . $e->getMessage());
        }
    }

    // Method to generate the graph and return the path to the generated PNG file
    public function generateGraph(Graph $graph)
    {
        try {
            $graphDot = $this->transitionsToDot($graph);
            $baseDir = realpath($_SERVER['DOCUMENT_ROOT']);
            $outputFile = __DIR__ . '/graphs/' . $graph->name . '.png';
            $relativePath = str_replace($baseDir, '', $outputFile);

            return $this->renderGraph($graphDot, ltrim($relativePath, DIRECTORY_SEPARATOR));
        } catch (Exception $e) {
            throw new Exception("Error in isAccepted method: " . $e->getMessage());
        }
    }

}