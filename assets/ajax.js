<<<<<<< HEAD
// Task 1 - 23-53651-3
// JS validation for registration and login

document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("registerForm");
    const loginForm = document.getElementById("loginForm");

    function showClientError(message) {
        const errorBox = document.getElementById("clientError");

        if (errorBox) {
            errorBox.textContent = message;
            errorBox.style.display = "block";
        } else {
            alert(message);
        }
    }

    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            const name = registerForm.name.value.trim();
            const email = registerForm.email.value.trim();
            const password = registerForm.password.value.trim();
            const address = registerForm.address.value.trim();
            const phone = registerForm.phone.value.trim();
            const role = registerForm.role.value;

            if (name === "" || email === "" || password === "" || address === "" || phone === "" || role === "") {
                event.preventDefault();
                showClientError("All fields are required.");
                return;
            }

            if (!email.includes("@") || !email.includes(".")) {
                event.preventDefault();
                showClientError("Please enter a valid email address.");
                return;
            }

            if (password.length < 8) {
                event.preventDefault();
                showClientError("Password must be at least 8 characters.");
                return;
            }

            if (role !== "admin" && role !== "member") {
                event.preventDefault();
                showClientError("Please select a valid role.");
            }
        });
    }

    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            const email = loginForm.email.value.trim();
            const password = loginForm.password.value.trim();

            if (email === "" || password === "") {
                event.preventDefault();
                showClientError("Email and password are required.");
                return;
            }

            if (!email.includes("@") || !email.includes(".")) {
                event.preventDefault();
                showClientError("Please enter a valid email address.");
            }
        });
    }
});
=======
function deleteMemberAjax(id){
    if(confirm('Are you sure to delete this member?')){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                let data = JSON.parse(this.responseText);
                alert(data.message);
                if(data.status == 'success'){
                    document.getElementById('memberRow'+id).remove();
                }
            }
        };
        xhttp.open('POST', '../controllers/deleteMember.php', true);
        xhttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
        xhttp.send('id='+id);
    }   
}
>>>>>>> origin/main
