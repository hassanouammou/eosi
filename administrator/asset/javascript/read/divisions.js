window.addEventListener("DOMContentLoaded", () => {
    var updateButton     = document.querySelector("#update-button");
    var deleteButton     = document.querySelector("#delete-button");
    var checkedDivisions = document.querySelectorAll(".checked_divisions");

    deleteButton.addEventListener("mouseover", () => {
        var checkedDivisionsLength = 0;
        checkedDivisions.forEach(mail => {
            if (mail.checked) {
                checkedDivisionsLength++;
            }
        });
        if (checkedDivisionsLength === 0) {
            deleteButton.disabled = true;
            deleteButton.style.cursor = "not-allowed";
            deleteButton.setAttribute("title", "Veuillez Séléctionner au Minimum Une Division");
        } else {
            deleteButton.style.cursor = "pointer";
            deleteButton.disabled = false;  
            var newTitle = checkedDivisionsLength === 1 ? "Supprimer Cette Division" : "Supprimer Ces Divisions";
            deleteButton.setAttribute("title", newTitle);
        }
    });

    updateButton.addEventListener("mouseover", () => {
        var checkedDivisionsLength = 0;
        checkedDivisions.forEach(mail => {
            if (mail.checked) {
                checkedDivisionsLength++;
            }
        });
        if (checkedDivisionsLength > 1 || checkedDivisionsLength === 0) {
            updateButton.disabled = true;
            updateButton.style.cursor = "not-allowed";
            updateButton.setAttribute("title", "Veuillez Sélectionner Uniquement Une Division");
        } else {
            updateButton.style.cursor = "pointer";
            updateButton.disabled = false;
            updateButton.setAttribute("title", "Modifier Cette Division");
        }
    });

    var searchButton = document.querySelector("#search-button");
    var searchForm   = document.querySelector("#om-search-form");
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



    var searchReset = document.querySelector("#search-reset-di");
    var omSearchFormInputs = document.querySelectorAll("#om-search-form input");
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
    

    function throwException(header, body, timeout = null) {
        var exceptionPopUp    = document.querySelector("#exception-pop-up");
        exceptionPopUp.querySelector("#exception-header").innerHTML = header;
        exceptionPopUp.querySelector("#exception-body").innerHTML   = body;
        document.querySelectorAll(".attention--pop-up").forEach(popUp => {popUp.style.visibility = "hidden"; });
        closePopUpButtons.forEach(closeButton => {
            closeButton.addEventListener("click", () => {
                closeButton.closest(".attention--pop-up").style.visibility = "hidden"; 
            });
        });
        exceptionPopUp.style.visibility = "visible";
        if (timeout !== null) {
            setTimeout(() => {
                exceptionPopUp.style.visibility = "hidden";
            }, timeout);
        } 
    }

    function make_vars_in_url(baseurl, values) {
        var url = `${baseurl}?`;
        let arguments = new Array();
        values.forEach((value, index) => {
            arguments.push(`user_id${index}=${value}`);
        });
        return url.concat(arguments.join("&"));
    }

    function checkedDivisionsIds() {
        var divisions = document.querySelectorAll(".checked_divisions");
        let arguments = new Array();
        divisions.forEach(division => {
            if (division.checked) {
                // Passing the Value which is the USER_ID
                arguments.push(division.value);
            }
        });
        return arguments;
    }

    document.querySelectorAll(".attention--pop-up").forEach(popUp => {popUp.style.visibility = "hidden"; });
    var closePopUpButtons = document.querySelectorAll(".closePopUp");
    closePopUpButtons.forEach(closeButton => {
        closeButton.addEventListener("click", () => {
            closeButton.closest(".attention--pop-up").style.visibility = "hidden"; 
        });
    });

    var deletePopUp       = document.querySelector("#delete-pop-up");
    deleteButton.onclick = () => {
        let url = make_vars_in_url("/eosi/administrator/exception/delete/divisions.php", checkedDivisionsIds());
        var xhr = new XMLHttpRequest();
        xhr.open("get", url);
        xhr.send();
        xhr.onload = () => {
            if (xhr.responseText.trim() == "so far so good") {
                if (deletePopUp.style.visibility === "hidden") {
                    deletePopUp.style.visibility = "visible";
                    var checkedDivisionsLength = 0;
                    checkedDivisions.forEach(mail => {
                        if (mail.checked) {
                            checkedDivisionsLength++;
                        }
                    });
                    if (checkedDivisionsLength == 1) {
                        deletePopUp.querySelector("p").innerHTML = "Voulez Vous Vraiment Supprimer Cette Division ?!";
                    } else {
                        deletePopUp.querySelector("p").innerHTML = "Voulez Vous Vraiment Supprimer Ces Divisions ?!";
                    }
                } else {
                    deletePopUp.style.visibility = "hidden";
                }
            } else {
                throwException(
                `<i class="fa-solid fa-circle-exclamation"></i>Attention</strong>`, 
                    xhr.responseText
                );
            }
        }
    };


});