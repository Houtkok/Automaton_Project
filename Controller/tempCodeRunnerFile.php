<?php
// Define the transitions associative array
$transitions =
    [
        'q0' => ['a' => ['q1'], 'b' => ['q0']],
        'q1' => ['a' => ['q1,q2'], ],
        'q1,q2' => ['a' => ['q1,q2'], 'b' => ['q0']]
    ];

// Function to convert the transitions array to DOT format
function transitions_to_dot($transitions) {
    $dot = "digraph G {\n";
    foreach ($transitions as $state => $actions) {
        foreach ($actions as $action => $next_states) {
            foreach ($next_states as $next_state) {
                $dot .= "    \"$state\" -> \"$next_state\" [label=\"$action\"];\n";
            }
        }
    }
    $dot .= "}";
    return $dot;
}

// Function to render the DOT string with Graphviz
function render_graph($dot_string, $output_file) {
    // Write the DOT string to a temporary file
    $temp_dot_file = tempnam(sys_get_temp_dir(), 'graph') . '.dot';
    file_put_contents($temp_dot_file, $dot_string);

    // Define the output file path and the Graphviz command
    $output_file_path = __DIR__ . '/' . $output_file;
    $command = "dot -Tpng $temp_dot_file -o $output_file_path";

    // Execute the Graphviz command
    shell_exec($command);

    // Clean up the temporary DOT file
    unlink($temp_dot_file);

    return $output_file_path;
}

// Generate the DOT string from the transitions array
$graph_dot = transitions_to_dot($transitions);

// Render the graph and save it to a file
$output_file = 'result.png';
$output_file_path = render_graph($graph_dot, $output_file);

echo "Graph has been rendered to $output_file_path\n";
?>
