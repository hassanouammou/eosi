window.addEventListener("DOMContentLoaded", () => {
    var updateButton = document.querySelector("#update-button");
    var deleteButton = document.querySelector("#delete-button");
    var checkedIncomingMails = document.querySelectorAll(".checked_incoming_mails");

    deleteButton.addEventListener("mouseover", () => {
        var checkedIncomingMailsLength = 0;
        checkedIncomingMails.forEach(mail => {
            if (mail.checked) {
                checkedIncomingMailsLength++;
            }
        });
        if (checkedIncomingMailsLength === 0) {
            deleteButton.disabled = true;
            deleteButton.style.cursor = "not-allowed";
            deleteButton.setAttribute("title", "Veuillez Séléctionner Au Minimum Un Courrier");
        } else {
            deleteButton.style.cursor = "pointer";
            deleteButton.disabled = false;  
            var newTitle = checkedIncomingMailsLength === 1 ? "Supprimer Ce Courrier" : "Supprimer Ces Courriers";
            deleteButton.setAttribute("title", newTitle);
        }
    });

    updateButton.addEventListener("mouseover", () => {
        var checkedIncomingMailsLength = 0;
        checkedIncomingMails.forEach(mail => {
            if (mail.checked) {
                checkedIncomingMailsLength++;
            }
        });
        if (checkedIncomingMailsLength > 1 || checkedIncomingMailsLength === 0) {
            updateButton.disabled = true;
            updateButton.style.cursor = "not-allowed";
            updateButton.setAttribute("title", "Veuillez Sélectionner Uniquement Un Courrier");
        } else {
            updateButton.style.cursor = "pointer";
            updateButton.disabled = false;
            updateButton.setAttribute("title", "Modifier Ce Courrier");
        }
    });

    var deleteButton      = document.querySelector("#delete-button");
    var deletePopUp       = document.querySelector("#delete-pop-up");
    var closePopUpButtons = document.querySelectorAll(".closePopUp");
    document.querySelectorAll(".attention--pop-up").forEach(popUp => {popUp.style.visibility = "hidden"; });
    closePopUpButtons.forEach(closeButton => {
        closeButton.addEventListener("click", () => {
            closeButton.closest(".attention--pop-up").style.visibility = "hidden"; 
        });
    });
    deleteButton.addEventListener("click", () => {
        if (deletePopUp.style.visibility === "hidden") {
            var checkedIncomingMailsLength = 0;
            checkedIncomingMails.forEach(mail => {
                if (mail.checked) {
                    checkedIncomingMailsLength++;
                }
            });
            if (checkedIncomingMailsLength == 1) {
                deletePopUp.querySelector("p").innerHTML = "Voulez Vous Vraiment Supprimer Ce Courrier ?!";
            } else {
                deletePopUp.querySelector("p").innerHTML = "Voulez Vous Vraiment Supprimer Ces Courriers ?!";
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

    var searchReset = document.querySelector("#search-reset-im");
    var omSearchFormInputs = document.querySelectorAll("#om-search-form input");
    searchReset.onclick = () => {
        omSearchFormInputs.forEach(input => {
            input.setAttribute("value", "");
        });
    }

    var yearOption = document.querySelector("#year-option");
    yearOption.onchange = () => {
        document.querySelector("[name='search_incoming_mails']").click();
    };

    var subjects                  = document.querySelectorAll(".subject");
    var subjectsFisrtSightContent = [];
    subjects.forEach(subject => {
        var subjectTextContent = subject.textContent;
        var title = "";
        for (let i = 0; i < subjectTextContent.length; i++) {
            if (i % 50 == 0) {
                title += `\n${subjectTextContent[i]}`;
            } else {
                title += `${subjectTextContent[i]}`;
            }
        }
        subject.onmouseover = () => {
            subject.title = title;
        }
    });
    subjects.forEach(subject => {
        subjectsFisrtSightContent.push(subject.textContent.slice(0, 30));
    });
    for (let i = 0; i < subjects.length; i++) {
        subjects[i].innerHTML = subjectsFisrtSightContent[i] + "...";
    }

    console.log(subjects);
    console.log(subjectsFisrtSightContent);


    // KEEP IT LAST
    let table = new DataTable('#myTable', {
        ordering: false,
        info: false,
        ordering: false,
        searching: false,
        lengthChange: false,
        "iDisplayLength": 4,
        "language": {
            "emptyTable": "Aucun Résultats Trouvés"
        },
    });
});