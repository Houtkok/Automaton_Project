<?php

require_once '../Controller/FiniteStateMachine.php';
require_once '../Controller/DeterministicFiniteAutomaton.php';
require_once '../Controller/NonDeterministicFiniteAutomaton.php';
require_once '../Controller/databasehandler.php';
require_once '../Controller/dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["graph-name"];
    $states = explode(',', $_POST["states"]);
    $symbols = explode(',', $_POST["symbols"]);
    $start_state = $_POST["start-state"];
    $final_states = $_POST["final-states"];
    $transition_table = [];
    // iterate through each state and each symbol to build transition_table
    foreach ($states as $state) {
        foreach ($symbols as $symbol) {
            // Construct the POST key for transition function
            $post_key = "transition_" . $state . "_" . $symbol;
            // Check if the key exists in $_POST
            if (isset($_POST[$post_key])) {
                $transition_destinations = $_POST[$post_key];
                $transition_table[$state][$symbol] = $transition_destinations;
            } else {
                $transition_table[$state][$symbol] = [];
            }
        }
    }
    // Create FiniteStateMachine instance
    $fsm = new FiniteStateMachine($symbols, $start_state, $final_states, $transition_table);
    $dbh = new DatabaseHandler($dbh);
    
    // Determine if DFA or NFA
    if ($fsm->isDFA()) {
        $fa = new DeterministicFiniteAutomaton();
    } else {
        $fa = new NonDeterministicFiniteAutomaton();
    }
    $new_finalstates = [];
    foreach ($final_states as $item) {
        if ($item !== "") {
            $new_finalstates[] = $item;
        }
    }
    $fa->setStartState($start_state);
    $fa->setFinalState($new_finalstates);
    $fa->setAlphabet($symbols);
    $fa->setTransition($transition_table);
    // Set up FA properties
    // Handle different actions based on form submission
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    switch ($action) {
        case 'submit_insert':
            print_r($dbh);
            echo "Automata data inserted successfully!";
            break;
            case 'test_deterministic':
                if ($fsm->isDFA()) {
                    $result = "This FA is a DFA";
                } else {
                    $result = "This FA is a NFA";
                }
                $dbh->insertAutomata($name, $states, $symbols, $start_state, $new_finalstates, $transition_table);
            $graph = new Graph($name, $symbols, $start_state, $new_finalstates, $transition_table);
            $fullPath = $fsm->generateGraph($graph);
            echo '<img src="' . $fullPath . '" alt="FA graph" style="width:40% ; height: 40%;">';
            echo '<br>';
            echo $result;
            break;
        case 'convert_nfa':
            $graph = new Graph($name, $symbols, $start_state, $new_finalstates, $transition_table);
            $fullPath = $fsm->generateGraph($graph);
            echo '<img src="' . $fullPath . '" alt="FA graph" style="width:40% ; height: 40%;">';
            // print_r($transition_table);
            if (!$fsm->isDFA()) {
                $result = $fsm->nfaToDfa($fa);
                if ($result) {
                    echo 'TO';
                    $result_transition = $result->transitionTable;
                    $result_alphabet = $result->alphabet;
                    $result_start = $result->startState;
                    $result_final = $result->finalStates;
                    $graph = new Graph($name . 'dfa', $result_alphabet, $result_start, $result_final, $result_transition);
                    $fullPath = $fsm->generateGraph($graph);
                    echo '<img src="' . $fullPath . '" alt="FA graph" style="width:40% ; height: 40%;">';
                    // print_r($result_transition);
                }
            } else {
                echo " The FA is Already a DFA";
            }
            break;
        case 'test_string':
            $input_string = isset($_POST['test_string_input']) ? $_POST['test_string_input'] : '';
            $result = $fsm->isAccepted($input_string) ? "Accepted" : "Not Accepted";
            $graph = new Graph($name, $symbols, $start_state, $new_finalstates, $transition_table);
            $fullPath = $fsm->generateGraph($graph);
            echo '<img src="' . $fullPath . '" alt="FA graph" style="width:40% ; height: 40%;">';
            echo '<br>';
            echo 'The string ' . "'" . $input_string . "'" . ' is ' . $result . 'by the FA';
            break;
        case 'minimize':
            $graph = new Graph($name, $symbols, $start_state, $new_finalstates, $transition_table);
            $fullPath = $fsm->generateGraph($graph);
            echo '<img src="' . $fullPath . '" alt="FA graph" style="width:40% ; height: 40%;">';
            echo '<br>';

            if ($fsm->isDFA()) {
                echo 'TO';
                $converted_transition_table = [];
                foreach ($transition_table as $state => $transitions) {
                    foreach ($transitions as $symbol => $nextStates) {
                        $converted_transition_table[$state][$symbol] = $nextStates[0];
                    }
                }
                $fa->setTransition($converted_transition_table);
                $minimizedDFA = $fsm->minimizeDFA($fa);

                if ($minimizedDFA && $minimizedDFA->transitionTable !== $converted_transition_table) {
                    $result_transition = $minimizedDFA->transitionTable;
                    $result_alphabet = $minimizedDFA->alphabet;
                    $result_start = $minimizedDFA->startState;
                    $result_final = $minimizedDFA->finalStates;
                    $graph = new Graph($name . 'dfa', $result_alphabet, $result_start, $result_final, $result_transition);
                    $fullPath = $fsm->generateGraph($graph);
                    echo '<img src="' . $fullPath . '" alt="FA graph" style="width:40% ; height: 40%;">';
                } else {
                    $result = "Look like the DFA is minimized";
                    echo $result;
                }
            } else {
                // If the FA is an NFA, convert it to a DFA and then minimize it
                $dfa = $fsm->nfaToDfa($fa);
                $minimizedDFA = $fsm->minimizeDFA($dfa);

                if ($minimizedDFA) {
                    $result_transition = $minimizedDFA->transitionTable;
                    $result_alphabet = $minimizedDFA->alphabet;
                    $result_start = $minimizedDFA->startState;
                    $result_final = $minimizedDFA->finalStates;
                    $graph = new Graph($name . 'dfa', $result_alphabet, $result_start, $result_final, $result_transition);
                    $fullPath = $fsm->generateGraph($graph);
                    echo '<img src="' . $fullPath . '" alt="FA graph" style="width:40% ; height: 40%;">';
                } else {
                    $result = "Cannot minimize the DFA!";
                    echo $result;
                }
            }
            break;
            default:
                $result = "No action specified.";
                break;
    }
    } else {
        echo "Please fill out all required fields.";
    }

?>