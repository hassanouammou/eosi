document.addEventListener("DOMContentLoaded", () => {
    var cardsSubContent = document.querySelectorAll(".card .sub-content");
    cardsSubContent.forEach((subContent, index) => {
        if (index !== 0) {
            subContent.style.cssText = `
            display: none;
        `;
        } else {
            var firstButton = document.querySelector(".card .expand-card");
            firstButton.parentElement.nextElementSibling.style.cssText = `
                display: bloc;
            `;
            firstButton.closest(".expand").style.borderBottom = "none";
            firstButton.firstChild.classList.replace("fa-chevron-right", "fa-chevron-down");
            firstButton.previousElementSibling.style.cssText = `
                padding-top: 30px;
                font-size: 1.4em;
            `;
            firstButton.style.paddingTop = "30px";
        }
    });
    var expandCardsButtons = document.querySelectorAll(".card .expand-card");
    expandCardsButtons.forEach((button, index) => {
        button.onclick = () => {
            if (button.parentElement.nextElementSibling.style.display == "none") {
                button.parentElement.nextElementSibling.style.cssText = `
                    display: bloc;
                `;
                button.closest(".expand").style.borderBottom = "none";
                button.firstChild.classList.replace("fa-chevron-right", "fa-chevron-down");
                button.previousElementSibling.style.cssText = `
                    padding-top: 30px;
                    font-size: 1.4em;
                `;
                button.style.paddingTop = "30px";
            } else {
                button.parentElement.nextElementSibling.style.cssText = `
                    display: none;
                `;
                button.firstChild.classList.replace("fa-chevron-down", "fa-chevron-right");
                button.closest(".expand").style.borderBottom = "2px solid #051937";
                button.previousElementSibling.style.cssText = `
                    padding-top: 0;
                    font-size: 1.2em;
                `;
                button.style.paddingTop = "0";
            }
        }
    });
});

