//feature/task2-23-54253-3
//Task2-23-54353-3(car validation and delete member ajax)
function carValidation(){
    let name = document.getElementById('name').value;
    let model = document.getElementById('model').value;
    let price = document.getElementById('price_per_day').value;
    let desc = document.getElementById('description').value;
    if(name=='' || model=='' || price=='' || desc==''){
        alert('All fields are required');
        return false;
    }
    if(price <= 0){
        alert('Price must be positive');
        return false;
    }
    return true;
}
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




// task1-23-53651-3 AJAX category browsing
document.addEventListener("DOMContentLoaded", function () {
    const categoryButtons = document.querySelectorAll(".category-btn");
    const resultBox = document.getElementById("categoryResult");

    if (categoryButtons.length === 0 || !resultBox) {
        return;
    }

    function safeText(value) {
        return String(value ?? "")
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");
    }

    categoryButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const type = button.getAttribute("data-type");
            resultBox.innerHTML = "<p>Loading cars...</p>";

            fetch("../controllers/categoryCars.php?type=" + encodeURIComponent(type))
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (!data.success || data.cars.length === 0) {
                        resultBox.innerHTML = "<p>No cars found in this category.</p>";
                        return;
                    }

                    let html = "";

                    data.cars.forEach(function (car) {
                        const imagePath = car.image_path ? "../" + car.image_path : "../assets/no-car.png";

                        html += `
                            <div class="car-card">
                                <img src="${safeText(imagePath)}" alt="Car Image">
                                <h3>${safeText(car.name)}</h3>
                                <p>Model: ${safeText(car.model)}</p>
                                <p>Type: ${safeText(car.type)}</p>
                                <p>Price/Day: ${safeText(car.price_per_day)} BDT</p>
                                <p>Status: ${safeText(car.availability_status)}</p>
                                <a class="btn" href="car_details.php?id=${encodeURIComponent(car.id)}">View Details</a>
                            </div>
                        `;
                    });

                    resultBox.innerHTML = html;
                })
                .catch(function () {
                    resultBox.innerHTML = "<p>Something went wrong while loading cars.</p>";
                });
        });
    });
});
