window.addEventListener("DOMContentLoaded", () => {
    // Les Pop up de Validations 
    var addButton         = document.querySelector("#add-button");
    var closePopUpButtons = document.querySelectorAll(".closePopUp");
    var addPopUp          = document.querySelector("#add-pop-up");
    var fillPopUp         = document.querySelector("#fill-pop-up");
    closePopUpButtons.forEach(closeButton => {
        closeButton.addEventListener("click", () => {
            closeButton.closest(".attention--pop-up").style.visibility = "hidden"; 
        });
    });
    document.querySelectorAll(".attention--pop-up").forEach(popUp => {popUp.style.visibility = "hidden"; });
    addButton.addEventListener("click", () => {
        if (addPopUp.style.visibility === "hidden") {
            var inputs = document.querySelector("form").querySelectorAll("*:is(input, textarea)");
            var nonFilledInputsLength = 0;            
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].value.trim() === "") {
                    nonFilledInputsLength++;
                }
            }
            if (nonFilledInputsLength === 0) {
                addPopUp.style.visibility = "visible";
                
            } else {
                fillPopUp.style.visibility = "visible";
            }
        } else {
            addPopUp.style.visibility = "hidden";        
        }
    });
});