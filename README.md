# Automata Simulator

## Description

The Automata Simulator is a comprehensive tool for designing, simulating, and visualizing various types of automata, including Deterministic Finite Automata (DFA), Non-Deterministic Finite Automata (NFA), and more. This project uses pure PHP for backend logic, JavaScript for frontend interactivity, and Graphviz for graph visualization.

## Table of Contents

1. [Installation](#installation)
2. [Usage](#usage)
3. [Features](#features)

## Installation

This project is a PHP-based Finite State Machine (FSM) simulator designed to model and analyze different types of automata including DFAs, NFAs, and their conversions.

### Prerequisites

- PHP 7.4 or higher
- Node.js and npm
- Graphviz

### Steps

1. Clone the repository:
    git clone https://github.com/Houtkok/Automaton_Project
2. Navigate to the project directory:
    ```sh
    cd automata-simulator
    ```
3. Install JavaScript dependencies:
    ```sh
    npm install
    ```
4. Install Graphviz:
    ```sh
    # For Ubuntu
    sudo apt-get install graphviz
    # For macOS
    brew install graphviz
    # For Window
    https://gitlab.com/api/v4/projects/4207231/packages/generic/graphviz-releases/12.0.0/windows_10_cmake_Release_graphviz-install-12.0.0-win32.exe
    ```
5. Configure your enviroment:
    Update `dbconfig.php` with your database credentials if using database features.

## Usage

1. Define your FSM by editing `index.php` and configuring states, symbols, transitions, etc.
2. Run `index.php` in your web browser to interact with the FSM simulator interface.
3. Use provided buttons for testing determinism, converting NFAs to DFAs, testing string acceptance, and minimizing DFAs.

### Running the Simulator

To start the local PHP server, run:
```sh
php -S localhost:8000
```
## Feature 

- Deterministic and Non-deterministic FSM detection.
- NFA to DFA conversion using the Subset Construction method.
- Minimization of DFAs using the Hopcroft algorithm.
- Graph visualization of FSMs using Graphviz.
