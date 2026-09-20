document.getElementById("registrationForm").addEventListener("submit", function(event) 
{
    event.preventDefault();
    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let mobile = document.getElementById("mobileno").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm").value;
    let course = document.getElementById("course").value;
    let year = document.getElementById("year").value;
    let terms = document.getElementById("terms").checked;
    let gender = document.querySelector('input[name="gender"]:checked');
    document.querySelectorAll("span").forEach(function(span) 
    {
        span.textContent = "";
    });
    document.getElementById("successMessage").textContent = "";
    let valid = true;
    let namePattern = /^[A-Za-z ]{2,50}$/;
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let mobilePattern = /^[0-9]{10}$/;
    let passwordPattern = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;
    if (name === "") 
    {
        document.getElementById("name-error").textContent = "Name is required.";
        valid = false;
    }
    else if (!namePattern.test(name)) 
    {
        document.getElementById("name-error").textContent = "Name must contain only letters and spaces.";
        valid = false;
    }
     if (email === "") 
    {
        document.getElementById("email-error").textContent = "Email is required.";
        valid = false;
    }
    else if (!emailPattern.test(email)) 
    {
        document.getElementById("email-error").textContent = "Enter a valid email address.";
        valid = false;
    }
    if (mobile === "") 
    {
        document.getElementById("mobile-error").textContent = "Mobile number is required.";
        valid = false;
    }
    else if (!mobilePattern.test(mobile)) 
    {
        document.getElementById("mobile-error").textContent = "Enter a valid 10-digit mobile number.";
        valid = false;
    }
    if (password === "") 
    {
        document.getElementById("password-error").textContent = "Password is required.";
        valid = false;
    }
    else if (!passwordPattern.test(password)) 
    {
        document.getElementById("password-error").textContent = "Password must be at least 8 characters and contain a letter and number.";
        valid = false;
    }
    if (confirmPassword === "") 
    {
        document.getElementById("confirm-error").textContent = "Please confirm your password.";
        valid = false;
    }
    else if (password !== confirmPassword) 
    {
        document.getElementById("confirm-error").textContent = "Passwords do not match.";
        valid = false;
    }
    if (course === "") 
    {
        document.getElementById("course-error").textContent = "Please select a course.";
        valid = false;
    }
    if (!gender) 
    {
        document.getElementById("gender-error").textContent = "Please select your gender.";
        valid = false;
    }
    if (!terms) 
    {
        document.getElementById("terms-error").textContent = "You must accept the Terms and Conditions.";
        valid = false;
    }
    if (valid) 
    {
        document.getElementById("successMessage").textContent = "Registration successful!"; 
        document.getElementById("registrationForm").reset();
    }
});