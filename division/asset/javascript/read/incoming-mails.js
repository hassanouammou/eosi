window.addEventListener("DOMContentLoaded", () => {
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



    var subjects = document.querySelectorAll(".subject");
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