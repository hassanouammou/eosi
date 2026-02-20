window.addEventListener("DOMContentLoaded", () => {
    var deleteButton = document.querySelector("#delete-button");
    var checkedEmployees = document.querySelectorAll(".checked_employees");

    deleteButton.addEventListener("mouseover", () => {
        var checkedEmployeesLength = 0;
        checkedEmployees.forEach(mail => {
            if (mail.checked) {
                checkedEmployeesLength++;
            }
        });
        if (checkedEmployeesLength === 0) {
            deleteButton.disabled = true;
            deleteButton.style.cursor = "not-allowed";
            deleteButton.setAttribute("title", "Veuillez Séléctionner Au Minimum Un Employé");
        } else {
            deleteButton.style.cursor = "pointer";
            deleteButton.disabled = false;  
            var newTitle = checkedEmployeesLength === 1 ? "Supprimer Cet Employé" : "Supprimer Ces Employés";
            deleteButton.setAttribute("title", newTitle);
        }
    });

    // Les Pop up de Validations 
    var deleteButton      = document.querySelector("#delete-button");
    var closePopUpButtons = document.querySelectorAll(".closePopUp");
    var deletePopUp       = document.querySelector("#delete-pop-up");
    document.querySelectorAll(".attention--pop-up").forEach(popUp => {popUp.style.visibility = "hidden"; });
    closePopUpButtons.forEach(closeButton => {
        closeButton.addEventListener("click", () => {
            closeButton.closest(".attention--pop-up").style.visibility = "hidden"; 
        });
    });
    deleteButton.addEventListener("click", () => {
        if (deletePopUp.style.visibility === "hidden") {
            var checkedEmployeesLength = 0;
            checkedEmployees.forEach(mail => {
                if (mail.checked) {
                    checkedEmployeesLength++;
                }
            });
            if (checkedEmployeesLength == 1) {
                deletePopUp.querySelector("p").innerHTML = "Voulez Vous Vraiment Supprimer Cet Employé ?!";
            } else {
                deletePopUp.querySelector("p").innerHTML = "Voulez Vous Vraiment Supprimer Ces Employés ?!";
            }
            deletePopUp.style.visibility = "visible";
        } else {
            deletePopUp.style.visibility = "hidden";
        }
    });

    var searchButton      = document.querySelector("#search-button");
    var searchForm       = document.querySelector("#om-search-form");
    searchForm.style.visibility = "hidden";
    searchForm.style.position = "absolute";
    searchButton.addEventListener("click", () => {
        if (searchForm.style.visibility === "hidden") {
            searchForm.style.visibility = "visible";
            searchForm.style.position = "static";
            searchButton.style.cssText = `
                background-color: #ffffff;
                color: #051937;
            `; 
        } else {
            searchForm.style.visibility = "hidden";
            searchForm.style.position = "absolute";
            searchButton.style.cssText = `
                background-color: #051937;
                color: #ffffff;
            `; 
        }
    });

    var searchFormInputs = searchForm.querySelectorAll("input");
    var filledInputsLength = 0;
    searchFormInputs.forEach(input => {
        if (input.value !== "") {
            filledInputsLength++;
        } 
    });
    if (filledInputsLength !== 0) {
        searchForm.style.visibility = "visible";
        searchForm.style.position = "static";
        searchButton.style.cssText = `
            background-color: #ffffff;
            color: #051937;
        `; 
    }

    var searchReset = document.querySelector("#emp-search-reset");
    var omSearchFormInputs = document.querySelectorAll("#emp-search-form input");
    searchReset.onclick = () => {
        omSearchFormInputs.forEach(input => {
            input.setAttribute("value", "");
        });
    }

    // KEEP IT LAST
    let table = new DataTable('#myTable', {
        ordering: false,
        info: false,
        ordering: false,
        searching: false,
        lengthChange: false,
        "iDisplayLength": 9,
        "language": {
            "emptyTable": "Aucun Résultats Trouvés"
        },
    });
});