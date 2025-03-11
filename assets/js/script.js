document.addEventListener('DOMContentLoaded', function() {
    // Event listener for registration form submission
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            // Get form fields
            const username = registerForm.querySelector('input[name="username"]').value;
            const mobile_number = registerForm.querySelector('input[name="mobile_number"]').value;
            const password = registerForm.querySelector('input[name="password"]').value;
            const confirm_password = registerForm.querySelector('input[name="confirm_password"]').value;
            const address = registerForm.querySelector('input[name="address"]').value;
            const gender = registerForm.querySelector('select[name="gender"]').value;

            let errors = [];

            // Validate Username: Min length 3, Max length 15
            if (username === "") {
                errors.push("Username is required.");
            } else if (username.length < 3 || username.length > 15) {
                errors.push("Username should be between 3 and 15 characters.");
            }

            // Validate Mobile Number: Must be 10 digits
            if (mobile_number === "") {
                errors.push("Mobile number is required.");
            } else if (mobile_number.length !== 10) {
                errors.push("Mobile number should be exactly 10 digits.");
            }

            // Validate Password: Min length 6 characters
            if (password === "") {
                errors.push("Password is required.");
            } else if (password.length < 6) {
                errors.push("Password should be at least 6 characters.");
            }

            // Validate Confirm Password: Should match password and follow the same length rule
            if (confirm_password === "") {
                errors.push("Confirm password is required.");
            } else if (confirm_password !== password) {
                errors.push("Passwords do not match.");
            }

            // Validate Address: Min length 10 characters
            if (address === "") {
                errors.push("Address is required.");
            } else if (address.length < 10) {
                errors.push("Address should be at least 10 characters.");
            }

            // Validate Gender: Must select a gender
            if (gender === "") {
                errors.push("Gender is required.");
            }

            // If there are errors, prevent form submission and show the errors
            if (errors.length > 0) {
                event.preventDefault(); // Prevent form submission
                alert(errors.join("\n")); // This can be replaced with UI-based error display
            }
        });
    } else {
        console.error('Register form not found');
    }

    // Event listener for login form submission
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            // Get form fields
            const username = document.querySelector('input[name="loginUsername"]').value;
            const password = document.querySelector('input[name="loginPassword"]').value;

            let errors = [];

            // Validate Username: Min length 3, Max length 15
            if (username === "") {
                errors.push("Username is required.");
            } else if (username.length < 3 || username.length > 15) {
                errors.push("Username should be between 3 and 15 characters.");
            }

            // Validate Password: Min length 6 characters
            if (password === "") {
                errors.push("Password is required.");
            } else if (password.length < 6) {
                errors.push("Password should be at least 6 characters.");
            }

            // If there are errors, prevent form submission and show the errors
            if (errors.length > 0) {
                event.preventDefault(); // Prevent form submission
                alert(errors.join("\n")); // This can be replaced with UI-based error display
            }
        });
    } else {
        console.error('Login form not found');
    }

    // Toggle between signup and signin forms with smooth transition
    const signInBtn = document.querySelector("#signin-btn");
    const signUpBtn = document.querySelector("#signup-btn");
    const container = document.querySelector(".container");

    login.addEventListener("click", () => {
        container.classList.add("sign-up-mode");
    });

    register.addEventListener("click", () => {
        container.classList.remove("sign-up-mode");
    });

    // Add animated RGB shadow effect to buttons on hover
    const buttons = document.querySelectorAll("button");
    buttons.forEach((btn) => {
        btn.addEventListener("mouseenter", () => {
            btn.style.boxShadow = "0 0 15px rgba(0, 255, 255, 0.8)";
        });
        btn.addEventListener("mouseleave", () => {
            btn.style.boxShadow = "none";
        });
    });

    
    let chartInstance;

    function updateChart(salesData) {
        let ctx = document.getElementById('salesChart').getContext('2d');
        
        let labels = salesData.map(item => item.date);
        let sales = salesData.map(item => item.total_sales);

        // If chart instance exists, destroy it and create a new one
        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales ($)',
                    data: sales,
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderWidth: 2
                }]
            }
        });
    }

    // Auto-update every 5 seconds
    setInterval(updateDashboard, 5000);
    updateDashboard(); // Initial call to populate the dashboard when page loads

    // Handle sidebar link clicks
    document.querySelectorAll('.sidebar a.nav-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var url = this.getAttribute('href');
            loadContent(url);
        });
    });

    function loadContent(url) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                document.getElementById('dashboard-content').innerHTML = response;
            },
            error: function() {
                alert('Failed to load content.');
            }
        });
    }
});
