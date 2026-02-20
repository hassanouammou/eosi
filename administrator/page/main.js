window.addEventListener("DOMContentLoaded", () => {
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    // Notifications Dropdown 
    var notificationsBell     = document.querySelector("#notifications button");
    var notificationsDropdown = document.querySelector("#notifications-dropdown");
    var notificationsBellIcon = document.querySelector("#notifications-icon");
    notificationsDropdown.style.visibility = "hidden";

    notificationsBell.addEventListener("click", () => {
        if (notificationsDropdown.style.visibility == "hidden") {
            notificationsDropdown.style.visibility = "visible";
            notificationsBellIcon.classList.replace("fa-regular", "fa-solid");
        } else {
            notificationsDropdown.style.visibility = "hidden";
            notificationsBellIcon.classList.replace("fa-solid", "fa-regular");
        }
    });

    // Profile and Logout Dropdown 
    var profileAndLogoutArrow     = document.querySelector("#profile-and-logout button");
    var profileAndLogoutArrowIcon = document.querySelector("#profile-and-logout button i");
    var profileAndLogoutDropdown  = document.querySelector("#profile-and-logout-dropdown");
    profileAndLogoutDropdown.style.visibility = "hidden";

    profileAndLogoutArrow.addEventListener("click", () => {
        if (profileAndLogoutDropdown.style.visibility == "visible") {
            profileAndLogoutDropdown.style.visibility = "hidden"; 
            profileAndLogoutArrowIcon.classList.replace("fa-xmark", "fa-bars");
        } else {
            profileAndLogoutDropdown.style.visibility = "visible";
            profileAndLogoutArrowIcon.classList.replace("fa-bars", "fa-xmark");
        }
    });

    document.onclick = (event) => {
        var target = event.target;
        if (notificationsDropdown.style.visibility == "visible") {
            if (!target.closest("#notifications-dropdown") & target != notificationsBellIcon) {
                notificationsDropdown.style.visibility = "hidden";
                notificationsBellIcon.classList.replace("fa-solid", "fa-regular");
            }
        }
        if (profileAndLogoutDropdown.style.visibility == "visible") {
            if (!target.closest("#profile-and-logout-dropdown") & target != profileAndLogoutArrowIcon) {
                profileAndLogoutDropdown.style.visibility = "hidden"; 
                profileAndLogoutArrowIcon.classList.replace("fa-xmark", "fa-bars");
            }
        }
    };

    // Aside  Dropdown (Courriers)
    var courriersArrowDropdown = document.querySelector("#first-navbar button");
    var courriersDropdown      = document.querySelector("#first-navbar ul");
    courriersArrowDropdown.addEventListener("click", () => {
        if (courriersDropdown.style.visibility == "visible") {
            courriersDropdown.style.cssText = `
                visibility: hidden;
            `;  
            courriersArrowDropdown.querySelector("i:nth-child(2)").classList.replace("fa-chevron-up", "fa-chevron-down");
            var i = 1; 
            document.querySelectorAll("#first-navbar > li").forEach(li => {
                if (i === 3) {
                    li.style.marginTop = "0";
                }
                i++;
            });
        } else {
            courriersDropdown.style.cssText = `
                visibility: visible;
            `; 
            courriersArrowDropdown.querySelector("i:nth-child(2)").classList.replace("fa-chevron-down", "fa-chevron-up");
            var i = 1; 
            document.querySelectorAll("#first-navbar > li").forEach(li => {
                if (i === 3) {
                    li.style.cssText = `
                        margin-top: 73px;
                        transition: all .5s;
                    `;
                }
                i++;
            });
        }
    });

    var href         = window.location.href.split("/");
    var currentLink  = href[href.length - 1];
    var links        = document.querySelectorAll(":is(#navbar, #profile-and-logout-dropdown ) li"); 
    
    function getFrenchName(link) {
        var frenchName = null;
        if (link.includes("dashboard.php")) {
            frenchName = "Tableau de Board"; 
        } else if (link.includes("outgoing-mails.php")|| link.includes("outgoing-mail.php")) {
            frenchName = "Courriers de Départ"; 
        } else if (link.includes("incoming-mails.php")|| link.includes("incoming-mail.php")) {
            frenchName = "Courriers à Arrivée"; 
        } else if (link.includes("employees.php") || link.includes("employee.php")) {
            frenchName = "Les Employés"; 
        } else if (link.includes("divisions.php")|| link.includes("division.php")) {
            frenchName = "Les Divisions"; 
        } else {
            frenchName = "Mon Profile"; 
        }
        return frenchName;
    }   

    links.forEach(link => {
        if (link.textContent.includes(getFrenchName(currentLink))) {
            link.classList.add("active");
        }
    });

    var searchListOptions = document.querySelector("#global-search #search-list-options");
    var globalSearchInput = document.querySelector("#global-search input");
    globalSearchInput.focus();
    setInterval(() => {
        var activeElement = document.activeElement;
        var nonActiveFields = 0;
        var fields = document.querySelectorAll(":is(input, textarea, select)");
        fields.forEach(field => {
            if (field !== activeElement) {
                nonActiveFields++;
            }
        });
        if (nonActiveFields === fields.length) {
            globalSearchInput.focus();
        }
    }, 2000);

    var options = [
        "Le Tableau de Board",
        "Les Courriers de Départ",
        "Les Courrier à Arrivées",
        "Les Employés",
        "Les Divisions",
        "Mon Profile",
        "Ajouter Un Courrier de Départ",
        "Ajouter Un Courrier à Arrivée",
        "Ajouter Un Employé",
        "Ajouter Une Division",
    ];

    var optionsLinks = [
        "/eosi/administrator/read/dashboard.php",
        "/eosi/administrator/read/outgoing-mails.php",
        "/eosi/administrator/read/incoming-mails.php",
        "/eosi/administrator/read/employees.php",
        "/eosi/administrator/read/divisions.php",
        "/eosi/administrator/read/profile.php",
        "/eosi/administrator/create/outgoing-mail.php",
        "/eosi/administrator/create/incoming-mail.php",
        "/eosi/administrator/create/employee.php",
        "/eosi/administrator/create/division.php",
    ];
    var apendedOption = [];
    globalSearchInput.onkeyup = (event) => {
        if (globalSearchInput.value.trim().length !== 0) {
            options.forEach(option => {
                if (!apendedOption.includes(option)) {
                    if (option.trim().includes(globalSearchInput.value.trim())) {
                        searchListOptions.innerHTML += `<option value="${option}"/>`;
                        apendedOption.push(option);
                    }
                }
            });
            if (event.key === "Enter") {
                options.forEach((option, index) => {
                    if(option.trim().includes(globalSearchInput.value.trim())) {
                        window.location.href = optionsLinks[index];
                    }
                });
            }
        } else {
            searchListOptions.innerHTML = ``;
            apendedOption.length = 0;
        }
    }
}); 