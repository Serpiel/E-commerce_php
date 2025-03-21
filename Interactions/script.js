function validateForm() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;
    var errorMsg = document.getElementById("error_msg");

    if (password !== confirmPassword) {
        errorMsg.textContent = "Passwords don't match";
        errorMsg.style.color = "red";
        return false;
    }
    return true;
}