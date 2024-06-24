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
        $('#start-state, #final-states').empty();
        $('#start-state').append('<option value="">Select start state</option>');
        $('#final-states').append('<option value="">Select final states</option>');

        states.forEach(state => {
            $('#start-state').append(`<option value="${state}">${state}</option>`);
            $('#final-states').append(`<option value="${state}">${state}</option>`);
        });
    }

    // Initial call to populate the transition table and state options
    updateTransitionTable();

    // Event listener for final states select
    $('#final-states').on('change', function () {
        const selectedState = $(this).val();
        if (selectedState) {
            addSelectedState(selectedState);
        }
        // Reset select value to default after adding
        $(this).val('');
    });

    // Function to add selected state to container
    function addSelectedState(state) {
        const selectedStatesContainer = $('#selected-state');
        const existingStates = selectedStatesContainer.find('.state');

        // Check if state already exists
        if (existingStates.length === 0 || !existingStates.toArray().some(el => $(el).text().trim() === state)) {
            selectedStatesContainer.append(`<div class="state">${state} <span class="remove-btn">x</span></div>`);
            // Add remove functionality
            selectedStatesContainer.find('.remove-btn').off('click').on('click', function () {
                $(this).parent().remove();
            });
        }
    }

    // Handle form submission
    $('#automata-form').on('submit', function (event) {
        // Collect final states from the container
        const finalStates = [];
        $('#selected-state .state').each(function () {
            finalStates.push($(this).text().trim().replace(' x', ''));
        });

        // Append final states as hidden inputs to the form
        finalStates.forEach(state => {
            $(this).append(`<input type="hidden" name="final-states[]" value="${state}">`);
        });

        // Collect transition table data
        const states = $('#states').val().split(',').map(s => s.trim()).filter(s => s);
        const symbols = $('#symbols').val().split(',').map(s => s.trim()).filter(s => s);
        states.forEach(state => {
            symbols.forEach(symbol => {
                const transitionValue = $(`select[data-state="${state}"][data-symbol="${symbol}"]`).val();
                if (transitionValue) {
                    $(this).append(`<input type="hidden" name="transition_${state}_${symbol}" value="${transitionValue}">`);
                }
            });
        });

        // Allow the form to be submitted
    });
});
