window.addEventListener("DOMContentLoaded", () => {
    var fakeFileButton = document.querySelector(".fake-file button");
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
            var inputs = document.querySelector("form").querySelectorAll("*:is(input, textarea, select)");
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