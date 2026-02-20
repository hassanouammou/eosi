window.addEventListener("DOMContentLoaded", () => {
    var fakeFileButton = document.querySelector("#fake-file-button");
    var fakeFileSpan   = document.querySelector(".fake-file span");
    var realFileButton = document.querySelector("#electronic-mail");
    var realLabel      = document.querySelector("#file-label");
    var arr = [fakeFileButton, realLabel];
    arr.forEach(element => {
        element.onclick = (event) => {
            event.preventDefault();
            realFileButton.click();
            realFileButton.onchange = () => {
                if (realFileButton.value) {
                    var value = realFileButton.value;
                    value = value.split("\\");
                    fakeFileSpan.innerHTML = value[value.length - 1];
                } else {
                    fakeFileSpan.innerHTML = "Aucun Courrier Éléctronique Séléctionné";
                }
            };
        }
    });

    // Les Pop up de Validations 
    var updateButton      = document.querySelector("#update-button");
    var closePopUpButtons = document.querySelectorAll(".closePopUp");
    var updatePopUp       = document.querySelector("#update-pop-up");
    var fillPopUp         = document.querySelector("#fill-pop-up");
    closePopUpButtons.forEach(closeButton => {
        closeButton.addEventListener("click", () => {
            closeButton.closest(".attention--pop-up").style.visibility = "hidden"; 
        });
    });
    document.querySelectorAll(".attention--pop-up").forEach(popUp => {popUp.style.visibility = "hidden"; });
    updateButton.addEventListener("click", () => {
        if (updatePopUp.style.visibility == "hidden") {
            var inputs = document.querySelector("form").querySelectorAll("form .input :is(input, textarea, select)");
            var nonFilledFieldsExceptFile = 0;
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].value == "") {
                    nonFilledFieldsExceptFile++;
                }
            }
            if (nonFilledFieldsExceptFile === 1 || nonFilledFieldsExceptFile === 0) {
                updatePopUp.style.visibility = "visible";
            } else {
                fillPopUp.style.visibility = "visible";
            }
        } else {
            updatePopUp.style.visibility = "hidden";        
        }
    });
});