$(document).ready(function () {
    // Event listeners for states and symbols input
    $('#states, #symbols').on('input', function () {
        updateTransitionTable();
    });

    // Function to update transition table based on states and symbols input
    function updateTransitionTable() {
        const states = $('#states').val().split(',').map(s => s.trim()).filter(s => s);
        const symbols = $('#symbols').val().split(',').map(s => s.trim()).filter(s => s);

        // Clear the transition table
        $('#transition-table thead tr').empty();
        $('#transition-table tbody').empty();

        // Add headers to the transition table
        $('#transition-table thead tr').append('<th>State</th>');
        symbols.forEach(symbol => {
            $('#transition-table thead tr').append(`<th>${symbol}</th>`);
        });

        // Add rows to the transition table
        states.forEach(state => {
            const row = $('<tr></tr>');
            row.append(`<td>${state}</td>`);
            symbols.forEach(symbol => {
                row.append(`<td><select class="form-control transition-select" data-state="${state}" data-symbol="${symbol}">
                    <option value="">Select state</option>
                    ${states.map(s => `<option value="${s}">${s}</option>`).join('')}
                </select></td>`);
            });
            $('#transition-table tbody').append(row);
        });

        // Update start state and final states options
        updateStateOptions(states);
    }

    // Function to update start state and final states options
    function updateStateOptions(states) {
        $('#start-state, .final-state-select').empty();
        $('#start-state').append('<option value="">Select start state</option>');
        $('.final-state-select').append('<option value="">Select final states</option>');

        states.forEach(state => {
            $('#start-state').append(`<option value="${state}">${state}</option>`);
            $('.final-state-select').append(`<option value="${state}">${state}</option>`);
        });
    }

    // Initial call to populate the transition table and state options
    updateTransitionTable();
});
