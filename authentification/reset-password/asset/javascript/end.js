var endResetForm    = document.querySelector("#end-reset-form");
var endResetButton  = document.querySelector("#end-reset-button");
var newPassword     = document.querySelector("#new-password");
var confirmPassword = document.querySelector("#confirm-password");
var requestMessage  = document.querySelector("#request-message");
var inPasswords = [newPassword, confirmPassword];



endResetForm.onsubmit = (event) => {
    var motherCondition = 
    newPassword.value.replace(/\s/g, '').length === 0  && confirmPassword.value.replace(/\s/g, '').length === 0 || 
    newPassword.value.replace(/\s/g, '').length !== 0  && confirmPassword.value.replace(/\s/g, '').length === 0 ||
    newPassword.value.replace(/\s/g, '').length === 0  && confirmPassword.value.replace(/\s/g, '').length !== 0 ||
    newPassword.value.replace(/\s/g, '').length  < 5   && confirmPassword.value.replace(/\s/g, '').length  <  5 ||
    newPassword.value !== confirmPassword.value;
    if (motherCondition) {
        event.preventDefault();
        if (newPassword.value.replace(/\s/g, '').length === 0 && confirmPassword.value.replace(/\s/g, '').length === 0) {
            requestMessage.innerHTML = 
            `<i class="fa-solid fa-circle-exclamation"></i>
            <strong class="capitalize">Veuillez Saisir Un Mot de Passe Valide !!</strong>`;
        } else if(newPassword.value.replace(/\s/g, '').length < 5 && confirmPassword.value.replace(/\s/g, '').length  <  5) {
            requestMessage.innerHTML = 
            `<i class="fa-solid fa-circle-exclamation"></i>
            <strong class="capitalize">Le Mot de Passe doit Contenir Au Minimum 5 Caractères !!</strong>`;
        } else {
            requestMessage.innerHTML = 
            `<i class="fa-solid fa-circle-exclamation"></i>
            <strong class="capitalize">Veuillez Saisir Le Même Mot de Passe !!</strong>`;
        }
        requestMessage.style.display = "block";
        inPasswords.forEach(inPassword => {
            inPassword.value = "";
            inPassword.onclick = () => {
                requestMessage.style.display = "none";
            }
        });
        setTimeout(() => {
            requestMessage.style.display = "none";
        }, 5000);
    }
};
