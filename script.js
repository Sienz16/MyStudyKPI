/*Javascript List For The Entire Website*/

/*Declare Variable*/
var loginRegisterSection = document.getElementById("loginRegisterSection");
var registerDiv = document.getElementById("registerDiv");
var newsDiv = document.getElementById("newsDiv");
let sideNav = document.querySelector('.side-nav');
let contentSection = document.querySelector('.content-section');
var listAndButton = document.getElementById("listAndButton");
var formDiv = document.getElementById("formDiv");

//JS to Toggle the visibility of the login/register section
function showLoginRegister() {
    loginRegisterSection.style.display = (loginRegisterSection.style.display === "none" || loginRegisterSection.style.display === "") ? "block" : "none";
}

//JS to show div Registration id=registerDiv
function showRegister(){
    registerDiv.style.display = 'block';
    newsDiv.style.display = 'none';
    var firstField = document.getElementById('matricNo');
    firstField.focus();
}

//JS to cancel registration by hiding div (display=none)
function cancelRegister(){
    registerDiv.style.display = 'none';
    newsDiv.style.display = 'block';
}

//JS for the toggle button
function toggleNav(){
    // Toggle 'active' class
    sideNav.classList.toggle('active');
    contentSection.classList.toggle('active');
}

function show_AddEntry() {  
    formDiv.style.display = 'block';
    var firstField = document.getElementById('sem');
    firstField.focus();
}

//JS to toggle form in my_challenge.php
function toggleFormVisibility() {
    const listDiv = document.getElementById('listAndButton');
    const formDiv = document.getElementById('formDiv');
    
    formDiv.style.display = 'block';
    listDiv.style.display = 'none';
}

//JS to hide form in my_challenge.php
function hideFormAfterSubmit() {
    listAndButton.style.display = 'block';
    formDiv.style.display = 'none';
}

//JS to reset form after modification to a php echo to fields
function resetForm() {
    document.getElementById("myForm").reset();
}

//JS to clear form to empty the form for new data
function clearForm() {
    var form = document.getElementById("myForm");
    if (form) {
        var inputs = form.getElementsByTagName("input");
        var textareas = form.getElementsByTagName("textarea");

        //clear select
        form.getElementsByTagName("select")[0].selectedIndex=0;        
        
        //clear all inputs
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type !== "button" && inputs[i].type !== "submit" && inputs[i].type !== "reset") {
                 inputs[i].value = "";
            }
        }

        //clear all textareas
        for (var i = 0; i < textareas.length; i++) {
            textareas[i].value = "";
        }
    } else {
        console.error("Form not found");
    }
}

//JS to confirm user logout the system
function confirmLogout(){
    var result = confirm("Are you sure you want to logout? :(");
    if(result){
        // If the user confirms, proceed with the logout
        window.location.href = "logout.php";
    } 
    else{
        // If the user cancels, display a message (you can customize this message)
        alert("Logout canceled. You are still logged in. :)");
    }
}

// Function to handle live search for list activity
function liveSearchActivity() {
    var searchValue = document.getElementById("searchInput").value;

    // Create XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Define the callback function
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Update the content with the response from the server
            document.getElementById("projectable").innerHTML = xhr.responseText;
        }
    };

    // Open a GET request to the server with the search value as a parameter
    xhr.open("GET", "my_activities_live_search.php?search=" + searchValue, true);

    // Send the request
    xhr.send();
}

// Function to handle live search for challenge
function liveSearchChallenge() {
    var searchValue = document.getElementById("searchInput").value;

    // Create XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Define the callback function
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Update the content with the response from the server
            document.getElementById("projectable").innerHTML = xhr.responseText;
        }
    };

    // Open a GET request to the server with the search value as a parameter
    xhr.open("GET", "my_challenge_live_search.php?search=" + searchValue, true);

    // Send the request
    xhr.send();
}

// Add these functions to your existing script.js
function showLogin() {
    document.getElementById('loginForm').style.display = 'block';
    document.getElementById('registerForm').style.display = 'none';
    document.querySelectorAll('.auth-tab')[0].classList.add('active');
    document.querySelectorAll('.auth-tab')[1].classList.remove('active');
}

function showRegister() {
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('registerForm').style.display = 'block';
    document.querySelectorAll('.auth-tab')[0].classList.remove('active');
    document.querySelectorAll('.auth-tab')[1].classList.add('active');
}

// Function to toggle password visibility
function togglePassword(button) {
    const input = button.previousElementSibling;
    const icon = button.querySelector('ion-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('name', 'eye-off-outline');
    } else {
        input.type = 'password';
        icon.setAttribute('name', 'eye-outline');
    }
}

// Add this new function for handling form visibility
function hideForm() {
    const listDiv = document.getElementById('listAndButton');
    const formDiv = document.getElementById('formDiv');
    
    formDiv.style.display = 'none';
    listDiv.style.display = 'block';
    
    // Reset form when hiding
    document.getElementById('myForm').reset();
}