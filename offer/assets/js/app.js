document.getElementById("year").textContent = new Date().getFullYear();
// Refresh Event
const refresh = document.querySelector(".refresh-btn");

refresh.addEventListener('click', () => {
    window.location.reload();
})


// Form validation
const form = document.querySelector(".form-wrapper");
const userName = document.getElementById("userName");
const submitBtn = document.getElementById("sbmtBtn");
let itsClicked = false;

// Submit Click Event
submitBtn.addEventListener('click', () => {
    hasClicked();
})

// Validation Function
function validateInputs() {
    const userNameVal = userName.value.trim();
    let success = true;
    if (userNameVal === "") {
        setError(userName, "please enter your good name!");
        success = false;
    } else if (/\d/.test(userNameVal)) {
        setError(userName, "please enter your currect name!");
        success = false;
    } else {
        setSuccess(userName, "Thank you! Save water, secure the future.");
    }
    return success;
}

// Set Error Function
function setError(ele, msg) {
    const inputGroup = ele.parentElement;
    const errorElement = inputGroup.querySelector(".err");

    errorElement.innerText = msg;
    errorElement.classList.add("active");
    inputGroup.classList.add("itsError");
    inputGroup.classList.remove("itsSuccess");
}
// Set Success Function
function setSuccess(ele, msg) {
    const inputGroup = ele.parentElement;
    const errorElement = inputGroup.querySelector(".err");

    errorElement.innerText = msg;
    errorElement.classList.add("active");
    inputGroup.classList.remove("itsError");
    inputGroup.classList.add("itsSuccess");
}

// Number Count And Success Message Function
function numberCount() {
    const offerCount = document.getElementById("percentCount");
    const description = document.getElementById("description");
    const blast = document.querySelector(".blast-effect");
    let startCount = 1;
    let targetCount = 5;
    let randomTarget = Math.floor(Math.random() * (targetCount - startCount)) + startCount;
    let currentCount = 0;

    const counter = setInterval(() => {
        if (currentCount != randomTarget) {
            currentCount++;
            offerCount.innerText = `${currentCount}%`;
        } else {
            clearInterval(counter);
            setTimeout(() => {
                if (!blast.classList.contains("active")) {
                    blast.classList.add("active");
                    blast.stop();
                    blast.play();
                }
            }, 500);
        }
    }, 50)

    setTimeout(() => {
        let user = userName.value.trim();
        let properUser = user.charAt(0).toUpperCase() + user.slice(1).toLowerCase();
        description.innerText = `Congratulations, ${properUser}! 🎉 Enjoy your ${randomTarget}% offer!`;
    }, 500);
}

// Fucntion Clicked
function hasClicked() {
    if (itsClicked) return;

    if (validateInputs()) {
        numberCount();
        itsClicked = true;
    }
}