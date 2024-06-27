$(document).ready(function () {

    // Debounce function to limit the frequency of update calls
    function debounce(func, wait) {
        let timeout;
        return function () {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // Event listeners for states and symbols input with debounce
    $('#states, #symbols').on('input', debounce(updateTransitionTable, 300));

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
                const select = $(`<select multiple class="form-control transition-select" data-state="${state}" data-symbol="${symbol}">
                    ${states.map(s => `<option value="${s}">${s}</option>`).join('')}
                </select>`);
                row.append($('<td></td>').append(select));
                select.select2();
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
        $(this).val('').trigger('change');
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
        event.preventDefault();

        // Clear previous hidden inputs
        $(this).find('input[type="hidden"]').remove();

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
                const transitionValues = $(`select[data-state="${state}"][data-symbol="${symbol}"]`).val();
                if (transitionValues && transitionValues.length) {
                    transitionValues.forEach(transitionValue => {
                        $(this).append(`<input type="hidden" name="transition_${state}_${symbol}[]" value="${transitionValue}">`);
                    });
                }
            });
        });

        // Submit the form
        submitForm();
    });

    // Define the submitForm function
    function submitForm() {
        $.ajax({
            url: 'handlerFA.php',
            type: 'POST',
            data: $('#automata-form').serialize(),
            success: function (response) {
                // Handle success - for example, displaying a success message
                alert('Form submitted successfully');
                console.log(response);
            },
            error: function () {
                // Handle error - for example, displaying an error message
                alert('Error submitting form.');
            }
        });
    }

    // Event listener for feature buttons
    $('.btn-feature').on('click', function () {
        const action = $(this).data('action');
        switch (action) {
            case 'test_deterministic':
                testDeterministic();
                break;
            case 'convert_nfa':
                convertNFAtoDFA();
                break;
            case 'test_string':
                testStringAcceptance();
                break;
            case 'minimize':
                minimizeDFA();
                break;
            default:
                // Handle unknown action
                break;
        }
    });

    // Function to test if FA is deterministic or non-deterministic
    function testDeterministic() {
        $.ajax({
            url: 'handlerFA.php',
            type: 'POST',
            data: $('#automata-form').serialize() + '&action=test_deterministic',
            success: function (response) {
                $('#test-fa-result').html(response);
                $('#test-deterministic-modal').modal('show');
            },
            error: function () {
                alert('Error testing FA type.');
            }
        });
    }

    // Function to convert NFA to DFA
    function convertNFAtoDFA() {
        $.ajax({
            url: 'handlerFA.php',
            type: 'POST',
            data: $('#automata-form').serialize() + '&action=convert_nfa',
            success: function (response) {
                $('#nfa-to-dfa-result').html(response);
                $('#nfa-to-dfa-modal').modal('show');
                // Display generated graph
            },
            error: function () {
                alert('Error converting NFA to DFA.');
            }
        });
    }

    // Function to test string acceptance
    function testStringAcceptance() {
        const testString = $('#test-string-input').val();
        
        if (!testString) {
            alert('Please enter a test string.');
            return;
        }

        $.ajax({
            url: 'handlerFA.php',
            type: 'POST',
            data: $('#automata-form').serialize() + '&action=test_string&test_string=' + testString,
            success: function (response) {
                $('#test-string-result').html(response);
                $('#test-string-modal').modal('show');
            },
            error: function () {
                alert('Error testing string acceptance.');
            }
        });
    }

    // Function to minimize DFA
    function minimizeDFA() {
        $.ajax({
            url: 'handlerFA.php',
            type: 'POST',
            data: $('#automata-form').serialize() + '&action=minimize',
            success: function (response) {
                $('#minimize-dfa-result').html(response);
                $('#minimize-dfa-modal').modal('show');
                // Display generated graph
            },
            error: function () {
                alert('Error minimizing DFA.');
            }
        });
    }

});
