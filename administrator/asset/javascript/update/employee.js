window.addEventListener("DOMContentLoaded", () => {
    var updateProfileBtn  = document.querySelector("#update-profile-btn");
    var profileFormFields = document.querySelectorAll("#main-form-employee :is(input:not(#user-picture-file), select)");
    updateProfileBtn.hidden = true;
    profileFormFields.forEach(field => {
        var initialValue = field.value;
        field.onkeyup = () => {
            if (field.value != initialValue) {
                updateProfileBtn.hidden = false;
            } else {
                updateProfileBtn.hidden = true;
            }
        }
    });

    var userPicture = document.querySelector("#user-picture img");
    var targetInputFile = document.querySelector("#user-picture #user-picture-file");
    userPicture.onclick = () => {
        targetInputFile.click();
    }
    targetInputFile.onchange = (event) => {
        userPicture.src = URL.createObjectURL(event.target.files[0]); 
        updateProfileBtn.hidden = false;
    }
    
    var initialGender= gender.value;
    gender.onchange = (event) => {
        if (gender.value != initialGender) {
            updateProfileBtn.hidden = false;
        } else {
            updateProfileBtn.hidden = true;
        }
    }
});