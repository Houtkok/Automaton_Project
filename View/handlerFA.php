<?php

require_once '../Controller/FiniteStateMachine.php';
require_once '../Controller/DeterministicFiniteAutomaton.php';
require_once '../Controller/NonDeterministicFiniteAutomaton.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["graph-name"];
    $states = explode(',', $_POST["states"]);
    $symbols = explode(',', $_POST["symbols"]);
    $start_state = $_POST["start-state"];
    $final_states = $_POST["final-states"];
    $transition_table = [];

    // Example processing with basic validation
    if (!empty($states) && !empty($symbols) && !empty($start_state)) {
        // Process the form data as needed
        //echo "Name: " . $name . ", States: " . implode(',', $states) . ", Symbols: " . implode(',', $symbols) . ", Start State: $start_state, Final States: " . implode(',', $final_states);

        // Proceed with further processing, e.g., constructing automaton, etc.
    } else {
        // Handle case where required fields are not filled
        echo "Please fill out all required fields.";
    }

    foreach ($states as $state) {
        foreach ($symbols as $symbol) {
            $transition_value = "transition_{$state}_{$symbol}";
            if (isset($_POST[$transition_value])) {
                // Ensure we handle multiple values correctly
                $transition_table[$state][$symbol] = $_POST[$transition_value];
            }
        }
    }

    // Create FiniteStateMachine instance
    $fsm = new FiniteStateMachine($symbols, $start_state, $final_states, $transition_table);
    
    // Determine if DFA or NFA
    if ($fsm->isDFA()) {
        $fa = new DeterministicFiniteAutomaton();
    } else {
        $fa = new NonDeterministicFiniteAutomaton();
    }

    // Set up FA properties
    $fa->setStartState($start_state);
    $fa->setFinalState($final_states);
    $fa->setAlphabet($symbols);
    $fa->setTransition($transition_table);

    // Handle different actions based on form submission
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'test_deterministic':
            $result = $fsm->isDFA() ? "True" : "False";
            break;
        case 'convert_nfa':
            $result = $fsm->nfaToDfa($fa);
            break;
        case 'test_string':
            $input_string = isset($_POST['test_string_input']) ? $_POST['test_string_input'] : '';
            $result = $fsm->isAccepted($input_string);
            break;
        case 'minimize':
            $result = $fsm->isDFA() ? $fsm->minimizeDFA($fa) : "This is not a DFA, cannot be minimized.";
            break;
        default:
            $result = "No action specified.";
            break;
    }

    // Output result
    print_r($result);

} else {
    // Handle case where form is not submitted
    echo "Form submission method not supported.";
}

?>
