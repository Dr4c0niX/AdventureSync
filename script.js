window.onload = function() {
    var collaborativeCheckbox = document.getElementById('collaborative');
    var privateCheckbox = document.getElementById('private');

    collaborativeCheckbox.addEventListener('change', function() {
        if (this.checked) {
            privateCheckbox.checked = false;
        }
    });

    privateCheckbox.addEventListener('change', function() {
        if (this.checked) {
            collaborativeCheckbox.checked = false;
        }
    });
}

fetch('./API/countries.json')
    .then(response => response.json())
    .then(countries => {
        const select = document.getElementById('country');

        // Add default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Sélectionnez un pays';
        defaultOption.disabled = true;
        defaultOption.selected = true;
        select.appendChild(defaultOption);

        // Add countries
        countries.forEach(country => {
            const option = document.createElement('option');
            option.value = country;
            option.text = country;
            select.appendChild(option);
        });
    })
    .catch(error => console.error('Error:', error));