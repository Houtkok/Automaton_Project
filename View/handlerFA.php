<?php

require_once '../Controller/FiniteStateMachine.php';
require_once '../Controller/DeterministicFiniteAutomaton.php';
require_once '../Controller/NonDeterministicFiniteAutomaton.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $states = $_POST["states"];
    $symbols = $_POST["symbols"];
    $start_state = $_POST["start_state"];
    $final_states = isset($_POST["final_states"]) ? $_POST["final_states"] : [];
    $transition_table = [];
    echo "States: $states, Symbols: $symbols, Start State: $start_state, Final State: $final_states";
    // Example processing with basic validation
    if (!empty($states) && !empty($symbols) && !empty($start_state)) {
        // Process the form data as needed
        echo "States: $states, Symbols: $symbols, Start State: $start_state";
        
        // Proceed with further processing, e.g., constructing automaton, etc.
    } else {
        // Handle case where required fields are not filled
        echo "Please fill out all required fields.";
    }

    foreach($states as $state){
        foreach($symbols as $symbol){
            $transition_value = "transition_{$state}_{$symbol}";
            if(isset($_POST[$transition_value])){
                $transition_table[$state][$symbol] = explode(',',$_POST[$transition_value]);
            }
        }
    }

    $fsm = new FiniteStateMachine();
    if($fsm -> isDFA((object)['trasitions' => $transition_table, 'alphabet' => $symbols])){
        $fa = new DeterministicFiniteAutomaton($states, $symbols, $transition_table, $start_state, $final_states);
    }
    else{
        $fa = new NonDeterministicFiniteAutomaton($states, $symbols, $transition_table, $start_state, $final_states);
    }

    $action = isset($_POST['action']) ? $_POST['action']:'';

    switch($action){
        case 'test_derterministic':
            $result = $fsm -> isDFA($fa) ? "True" : "False";
            break;
        case 'convert_nfa':
            $result = $fsm -> nfaToDfa($fa);
            break;
            case 'test_string':
            $input_string = isset($_POST['test_string']) ? $_POST['test_string']:'';
            break;
        case 'minimize':
            $result = $fsm -> isDFA($fa) ? $fsm -> minimizeDFA($fa) : "This is not a DFA cannot be minimized.";
            break;
        default:
            $result = "no action";
            break;
    }
    print_r($result);

}
else {
    // Handle case where form is not submitted
    echo "Form submission method not supported.";
}