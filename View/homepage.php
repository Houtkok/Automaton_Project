<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automata</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .state {
            display: inline-block;
            background-color: #f0f0f0;
            padding: 5px 10px;
            margin: 5px;
            border-radius: 5px;
        }

        .remove-btn {
            cursor: pointer;
            color: red;
            margin-left: 5px;
        }
    </style>
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
                            <label for="graph-name">States</label>
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
                            <select class="form-control" id="final-states">
                                <!-- Options will be populated dynamically -->
                            </select>
                            <br>
                            <div id="selected-state" name="selected-final" type="text">
                                <!-- Selected final states will be displayed dynamically -->
                            </div>
                        </div>
                    </section>
                </form>
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
            </div>
            <button type="submit" class="btn btn-primary" form="automata-form" name="action">Confirm</button>
        </div>
        <h3>Features</h3>
        <section class="features">
            <div class="row">
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Test if this FA is deterministic or non-deterministic</h5>
                            <button type="submit" class="btn btn-secondary" form="automata-form" name="action"
                                value="test_deterministic">TEST</button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">NFA to DFA</h5>
                            <button type="submit" class="btn btn-secondary" form="automata-form" name="action"
                                value="convert_nfa">Convert</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Test if a string is accepted</h5>
                            <input type="text" class="form-control mb-2" name="test_string_input"
                                placeholder="Input a string">
                            <button type="submit" class="btn btn-secondary" form="automata-form" name="action"
                                value="test_string">TEST</button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Minimize DFA</h5>
                            <button type="submit" class="btn btn-secondary" form="automata-form" name="action"
                                value="minimize">Minimize</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
