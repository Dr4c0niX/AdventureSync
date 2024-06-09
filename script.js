//NTEGRER L'API (instagram post ou drapeau) 
// ajouter le contact pour les voyages collaboratifs (maill ou/et réseaux sociaux)
//tri des voyages par pays

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