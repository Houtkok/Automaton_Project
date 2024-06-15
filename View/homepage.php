<!DOCTYPE html>
<html>
<head>
    <title>Finite Automaton Form</title>
</head>
<body>
    <h2>Finite Automaton Form</h2>
    <form method="post" action="">
        <label for="numStates">Number of States:</label>
        <input type="text" id="numStates" name="numStates" required><br><br>

        <label for="alphabet">Alphabet (comma-separated):</label>
        <input type="text" id="alphabet" name="alphabet" required><br><br>

        <label for="startState">Start State:</label>
        <input type="text" id="startState" name="startState" required><br><br>

        <label for="finalStates">Final States (comma-separated):</label>
        <input type="text" id="finalStates" name="finalStates" required><br><br>

        <label for="transitions">Transitions (format: fromState inputChar toState, separated by semicolons):</label><br>
        <textarea id="transitions" name="transitions" rows="6" cols="50" required></textarea><br><br>

        <label for="testString">Test String:</label>
        <input type="text" id="testString" name="testString" required><br><br>

        <input type="submit" value="Submit" name="submit">
    </form>

    <?php
    include "homepage.php";
    if (isset($_POST['submit'])) {
        $numStates = $_POST['numStates'];
        $alphabet = explode(',', $_POST['alphabet']);
        $startState = $_POST['startState'];
        $finalStates = explode(',', $_POST['finalStates']);
        $transitionsInput = $_POST['transitions'];

        $transitions = [];
        $transitionsArray = explode(';', $transitionsInput);

        // Process each transition
        foreach ($transitionsArray as $transition) {
            $transition = trim($transition);
            if (empty($transition)) {
                continue;
            }
            $transitionParts = explode(' ', $transition);
            $inputChar = $transitionParts[0];
            $state = $transitionParts[1];
            $nextState = $transitionParts[2];
            $transitions[$inputChar][$state][] = $nextState;
        }

        $fa = new FA($numStates, $alphabet, $startState, $finalStates, $transitions);
        echo "<br>FA is " . ($fa->isDFA() ? "DFA" : "NFA") . "<br>"; 
        $input = $_POST['testString'];
        echo "Is Accepted: " . ($fa->isAccepted($input) ? "Yes" : "No");
    }
    ?>
</body>
</html>
