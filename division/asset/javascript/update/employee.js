window.addEventListener("DOMContentLoaded", () => {
    var inputs = document.querySelectorAll("input");
    inputs.forEach(input => {
        input.readOnly = true;
    });
});