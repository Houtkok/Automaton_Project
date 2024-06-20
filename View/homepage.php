<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automata</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
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

<body>
    <header class="bg-#365679; p-2 d-flex justify-content-between align-items-center">
        <h3 style="color: white;">Automata</h3>
    </header>
    <main class="container">
        <div class="row">
            <div class="col-6">
                <section class="main-input">
                    <div class="form-group">
                        <label for="states">States</label>
                        <input type="text" class="form-control" id="states" placeholder="Enter states">
                    </div>
                    <div class="form-group">
                        <label for="symbols">Symbols</label>
                        <input type="text" class="form-control" id="symbols" placeholder="Enter symbols">
                    </div>
                    <div class="form-group">
                        <label for="start-state">Start State</label>
                        <select class="form-control" id="start-state">
                            <option value="">Select start state</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="final-states">Final States</label>
                        <select class="form-control" id="final-states">
                            <option value="">Select final states</option>
                        </select>
                        <br>
                        <div id="selected-state">
                            <p>Selected Final States:</p>
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
            </div>
        </div>
        <h3>Feature</h3>
        <section class="features">
            <div class="row">
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Test if this FA is deterministic or non-deterministic</h5>
                            <button class="btn btn-secondary">TEST</button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">NFA to DFA</h5>
                            <button class="btn btn-secondary">Convert</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Test if a string is accepted</h5>
                            <input type="text" class="form-control mb-2" placeholder="Input a string">
                            <button class="btn btn-secondary">TEST</button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Minimize DFA</h5>
                            <button class="btn btn-secondary">Minimize</button>
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