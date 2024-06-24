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

    // echo "States: " . implode(',', $states) . ", Symbols: " . implode(',', $symbols) . ", Start State: $start_state, Final States: " . implode(',', $final_states);

    // Example processing with basic validation
    if (!empty($states) && !empty($symbols) && !empty($start_state)) {
        // Process the form data as needed
        echo "Name:" .$name. "States: " . implode(',', $states) . ", Symbols: " . implode(',', $symbols) . ", Start State: $start_state". "Final States:" . implode(',', $final_states);
        
        // Proceed with further processing, e.g., constructing automaton, etc.
    } else {
        // Handle case where required fields are not filled
        echo "Please fill out all required fields.";
    }

    foreach ($states as $state) {
        foreach ($symbols as $symbol) {
            $transition_value = "transition_{$state}_{$symbol}";
            if (isset($_POST[$transition_value])) {
                $transition_table[$state][$symbol] = explode(',', $_POST[$transition_value]);
            }
        }
    }
    var_dump($transition_table);

    $fsm = new FiniteStateMachine();
    if ($fsm->isDFA((object)['transitions' => $transition_table, 'alphabet' => $symbols])) {
        $fa = new DeterministicFiniteAutomaton();
    } else {
        $fa = new NonDeterministicFiniteAutomaton();
    }

    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'test_deterministic':
            $result = $fsm->isDFA($fa) ? "True" : "False";
            break;
        case 'convert_nfa':
            $result = $fsm->nfaToDfa($fa);
            break;
        case 'test_string':
            $input_string = isset($_POST['test_string_input']) ? $_POST['test_string_input'] : '';
            $result = $fml->isAccepted($input_string,$fml);
            break;
        case 'minimize':
            $result = $fsm->isDFA($fa) ? $fsm->minimizeDFA($fa) : "This is not a DFA, cannot be minimized.";
            break;
        default:
            $result = "No action specified.";
            break;
    }
    print_r($result);

} else {
    // Handle case where form is not submitted
    echo "Form submission method not supported.";
}
?>
