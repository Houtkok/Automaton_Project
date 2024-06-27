<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automata</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header class="bg-#365679 p-2 d-flex justify-content-between align-items-center">
        <h3 style="color: white;">Automata</h3>
    </header>
    <main class="container">
        <div class="row">
            <div class="col-6">
                <form id="automata-form" action="handlerFA.php" method="POST">
                    <section class="main-input">
                        <div class="form-group">
                            <label for="graph-name">Name</label>
                            <input type="text" class="form-control" name="graph-name" id="graph-name"
                                placeholder="Enter FA name">
                        </div>
                        <div class="form-group">
                            <label for="states">States</label>
                            <input type="text" class="form-control" name="states" id="states"
                                placeholder="Enter states (Separated by ,)">
                        </div>
                        <div class="form-group">
                            <label for="symbols">Symbols</label>
                            <input type="text" class="form-control" name="symbols" id="symbols"
                                placeholder="Enter symbols (Separated by ,)">
                        </div>
                        <div class="form-group">
                            <label for="start-state">Start State</label>
                            <select class="form-control" name="start-state" id="start-state">
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="final-states">Final States</label>
                            <select class="form-control" id="final-states" name="final-states[]">
                                <!-- Options will be populated dynamically -->
                            </select>
                            <br>
                            <div id="selected-state" name="selected-final" type="text">
                                <!-- Selected final states will be displayed dynamically -->
                            </div>
                        </div>
                    </section>
            </div>
            <div class="col-6">
                <h3>Transition Table</h3>
                <section class="transitions">
                    <table class="table table-bordered" id="transition-table">
                        <thead>
                            <tr>
                                <th>State</th>
                                <!-- Symbols headers will be dynamically added here -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Transition rows will be dynamically added here -->
                        </tbody>
                    </table>
                </section>
                <p style="margin-left: 230px;"><b style="color: red;">Note</b>: Please confirm before using feature!</p>
                <div style="display: flex; justify-content: right;">
                    <button type="submit" class="btn btn-secondary" name="submit" style="background-color: #365679;" >confirm</button>
                </div>
            </div>
        </div>
        <h3>Features</h3>
        <section class="features">
            <div class="row">
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Test if this FA is deterministic or non-deterministic</h5>
                            <button type="button" class="btn btn-secondary btn-feature" data-action="test_deterministic"
                                data-target="#test-deterministic-modal" data-toggle="modal">
                                TEST
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">NFA to DFA</h5>
                            <button type="button" class="btn btn-secondary btn-feature" data-action="convert_nfa"
                                data-target="#nfa-to-dfa-modal" data-toggle="modal">
                                Convert
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Test if a string is accepted</h5>
                            <input type="text" class="form-control mb-2" name="test_string_input" id="test-string-input"
                                placeholder="Input a string">
                            <button type="button" class="btn btn-secondary btn-feature" data-action="test_string"
                                data-target="#test-string-modal" data-toggle="modal">
                                TEST
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Minimize DFA</h5>
                            <button type="button" class="btn btn-secondary btn-feature" data-action="minimize"
                                data-target="#minimize-dfa-modal" data-toggle="modal">
                                Minimize
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </form>

        <!-- Modals -->
        <div class="modal fade" id="test-deterministic-modal" tabindex="-1" role="dialog"
            aria-labelledby="test-deterministic-modal-label" aria-hidden="true" >
            <div class="modal-dialog" role="document" style="max-width: 80%; height: 80vh; margin: auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="test-deterministic-modal-label">Test Deterministic Finite Automaton
                            (DFA)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="test-fa-result" style="display: flex; justify-content: center; align-items: center; height: 80vh;">
                        <!-- Result of deterministic test shown here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for NFA to DFA conversion -->
        <div class="modal fade" id="nfa-to-dfa-modal" tabindex="-1" role="dialog"
            aria-labelledby="nfa-to-dfa-modal-label" aria-hidden="true" >
            <div class="modal-dialog" role="document" style="max-width: 80%; height: 80vh; margin: auto;">
                <div class="modal-content" style="height: 100%;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nfa-to-dfa-modal-label">Convert NFA to DFA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="nfa-to-dfa-result" style="display: flex; justify-content: center; align-items: center; height: 80vh;">
                        <!-- Result of NFA to DFA conversion shown here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Test accepted string -->
        <div class="modal fade" id="test-string-modal" tabindex="-1" role="dialog"
            aria-labelledby="test-string-modal-label" aria-hidden="true" >
            <div class="modal-dialog" role="document" style="max-width: 80%; height: 80vh; margin: auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="test-string-modal-label">Test Accepted String</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="test-string-result" style="display: flex; justify-content: center; align-items: center; height: 80vh;">
                        <!-- Result of string acceptance test shown here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for minimize DFA conversion -->
        <div class="modal fade" id="minimize-dfa-modal" tabindex="-1" role="dialog"
            aria-labelledby="minimize-dfa-modal-label" aria-hidden="true" >
            <div class="modal-dialog" role="document" style="max-width: 80%; height: 80vh; margin: auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="minimize-dfa-modal-label">Minimize DFA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="minimize-dfa-result" style="display: flex; justify-content: center; align-items: center; height: 80vh;">
                        <!-- Result of DFA minimization shown here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>