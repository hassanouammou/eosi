document.addEventListener("DOMContentLoaded", () => {
    var loginSection = document.querySelector("#login-section");
    var loginForm = document.querySelector("#login-form");

    /* window.onscroll = () => {
        if (Math.floor(window.scrollY) === loginSection.scrollHeight) {
            loginForm.querySelector("input[type='email']").focus();
        } else {
            loginForm.querySelector("input[type='email']").blur();
        }
    }; */

    loginForm.onsubmit = (event) => {
        var password = document.querySelector("input[type='password']");
        if (password.value.replace(/\s+/g, '').length == 0) {
            event.preventDefault();
            var loginMessage = document.querySelector("#login-message");
            loginMessage.innerHTML = 
            `<i class="fa-solid fa-circle-exclamation"></i>
            <strong class="capitalize">Veuillez Saisir Un Mot de Passe Valide !!</strong></div>`;
            loginMessage.style.display = "block";
            password.value = "";
            password.onclick = () => {
                loginMessage.style.display = "none";
            }
            password.onkeyup = () => {
                loginMessage.style.display = "none";
            };
            setTimeout(() => {
                loginMessage.style.display = "none";
            }, 5000);
        }
    };
});