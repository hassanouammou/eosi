window.addEventListener("DOMContentLoaded", () => {
    var updateProfileBtn  = document.querySelector("#update-profile-btn");
    var profileFormFields = document.querySelectorAll("#division-profile :is(input)");
    updateProfileBtn.style.visibility = "hidden";
    profileFormFields.forEach(field => {
        var initialValue = field.value;
        field.onkeyup = () => {
            if (field.value != initialValue) {
                updateProfileBtn.style.visibility = "visible";
            } else {
                updateProfileBtn.style.visibility = "hidden";
            }
        }
    });
});