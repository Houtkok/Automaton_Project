<?php

require_once 'FiniteStateMachine.php';
// require_once '/Controller/DeterministicFiniteAutomata.php';
// require_once '/Controller/NonDeterministicFiniteAutomata.php';

if($_SERVER["REQUEST"] == "POST"){
    $states = isset($_POST['states']) ? explode(',',$_POST['states']):[];
    $symbols = isset($_POST['symbols']) ? explode(',',$_POST['symbols']):[];
    $start_state = isset($_POST['start_state']) ? $_POST['states']:'';
    $final_states = isset($_POST['final_states']) ? explode(',',$_POST['final_states']):[];
    $transition_table = [];

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

    $action = isset($_POST['action']) ? $POST['action']:'';

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