<?php
// require_once 'FiniteStateMachine.php'; 
// $fml = new FiniteStateMachine();
// $dfa = new DeterministicFiniteAutomaton();
// $nfa = new NonDeterministicFiniteAutomaton();
// $graph = new Graph();

// $transitions = [
//     'q0' => ['a' => ['q1'], 'b' => ['q0']],
//     'q1' => ['a' => ['q2']],
//     'q2' => ['a' => ['q2'], 'b' => ['q0']]
// ];

// $graph->setAlphabet(['a', 'b']);
// $graph->setTransition($transitions);
// $graph->setStartState('q0');
// $graph->setFinalState(['q2']);
// $graph->name = 'test3'; // Set a name for the FSM

// $outputFilePath = $fml->generateGraph($graph);

// set dfa
// $dfa->setStartState('q0');
// $dfa->setFinalState(['q2']);
// $dfa->setAlphabet(['a', 'b']);
// $dfa->setTransition([
//     'q0' => ['a' => 'q1', 'b' => 'q0'],
//     'q1' => ['a' => 'q1', 'b' => 'q2'],
//     'q2' => ['a' => 'q2', 'b' => 'q0']
// ]);

// set nfa
// $nfa->setStartState('q0');
// $nfa->setFinalState(['q2']);
// $nfa->setAlphabet(['a', 'b']);
// $nfa->setTransition([
//     'q0' => ['a' => ['q1'], 'b' => ['q0']],
//     'q1' => ['a' => ['q1', 'q2'], 'b' => []],
//     'q2' => ['a' => ['q2'], 'b' => ['q0']]
// ]);
// $nfa->setStartState('q0');
// $nfa->setFinalState(['q1']);
// $nfa->setAlphabet(['a', 'b']);
// $nfa->setTransition([
//     'q0' => ['a' => ['q0'], 'b' => ['q0','q1']],
//     'q1' => ['a' => ['q1'], 'b' => []],
// ]);
// $nfa->setStartState('q0');
// $nfa->setFinalState(['q2']);
// $nfa->setAlphabet(['a', 'b']);
// $nfa->setTransition([
//     'q0' => ['a' => ['q0','q1'], 'b' => ['q0']],
//     'q1' => ['a' => [], 'b' => ['q2']],
// ]);

// initialize FiniteStateMachine


//test DFA acceptance
// $input1 = 'ababa';
// $input2 = 'baaba';

// echo "Testing DFA acceptance:"clea;
// echo  $dfa->startState ."\n";
// echo "Testing Final State: ";
// foreach($dfa->finalStates as $final ){
//     echo $final . " ";
// }echo PHP_EOL;
// echo "Testing Alphabet:";
// foreach($dfa->alphabet as $char ){
//     echo $char . " ";
// }echo PHP_EOL;
// echo "Testing State:";
// foreach($dfa->transitionTable as $state => $trasition){
//     echo $state . " ";
// }echo PHP_EOL;
// foreach($dfa->transitionTable as $state => $trasition){
//     echo $state . " " . PHP_EOL;
//     foreach($trasition as $input => $nextState){
//         echo "[" . $input . " -> " . $nextState . "]". PHP_EOL;
//     }
// }
// echo PHP_EOL;
// echo "Input '$input1': " . ($graph->accepts($input1, $dfa) ? "Accepted\n" : "Rejected\n");
// echo "Input '$input2': " . ($graph->accepts($input2, $dfa) ? "Accepted\n" : "Rejected\n");
// echo "\n";

// Convert NFA to DFA
// $convertedDFA = $graph->nfaToDfa($nfa);

// echo "Converted DFA transition table:\n";
// echo "Testing State:";
// foreach($convertedDFA->transitionTable as $state => $trasition){
//     echo $state . " ";
// }
// echo "\n";
// echo "Testing NFA acceptance:";
// echo  $nfa->startState ."\n";
// echo "Testing Final State: ";
// foreach($nfa->finalStates as $final ){
//     echo $final . " ";
// }echo PHP_EOL;
// echo "Testing Alphabet:";
// foreach($nfa->alphabet as $char ){
//     echo $char . " ";
// }echo PHP_EOL;
// foreach($convertedDFA->transitionTable as $state => $trasition){
//     echo $state . " " . PHP_EOL;
//     foreach($trasition as $input => $nextState){
//         echo "[" . $input . " -> " . $nextState . "]". PHP_EOL;
//     }
// }
//print_r($convertedDFA);

// Minimize DFA
// $minimizedDFA = $graph->minimizeDFA($dfa);

// echo "Minimized DFA transition table:\n";
// foreach($minimizedDFA->transitionTable as $state => $trasition){
//     echo $state . " " . PHP_EOL;
//     foreach($trasition as $input => $nextState){
//         echo "[" . $input . " -> " . $nextState . "]". PHP_EOL;
//     }
// }

// echo "Minimized DFA final states: ";
// foreach($minimizedDFA->finalStates as $final ){
//     echo $final . " ";
// }echo PHP_EOL;