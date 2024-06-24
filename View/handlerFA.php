<?php

require_once '../Controller/FiniteStateMachine.php';
require_once '../Controller/DeterministicFiniteAutomaton.php';
require_once '../Controller/NonDeterministicFiniteAutomaton.php';

var_dump($_POST['final-states']);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $states = $_POST["states"];
    $symbols = $_POST["symbols"];
    $start_state = $_POST["start-state"];
    $final_states = $_POST["final-states"];
    $transition_table = [];
    var_dump($states.PHP_EOL,$symbols.PHP_EOL,$start_state.PHP_EOL,$final_states);
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
        $fa = new DeterministicFiniteAutomaton();
    }
    else{
        $fa = new NonDeterministicFiniteAutomaton();
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