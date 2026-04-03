// Run after page loads
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("customerForm");
    const name = document.getElementById("name");
    const mobile = document.getElementById("mobile");
    const pincode = document.getElementById("pincode");

    // ✅ REAL-TIME VALIDATION (while typing)

    // NAME → only letters
    name.addEventListener("input", function () {
        this.value = this.value.replace(/[^A-Za-z\s]/g, '');
    });

    // MOBILE → only numbers, max 10
    mobile.addEventListener("input", function () {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });

    // PINCODE → only numbers, max 6
    pincode.addEventListener("input", function () {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
    });

    // ✅ SUBMIT VALIDATION
    form.addEventListener("submit", function (e) {

        let valid = true;

        let nameVal = name.value.trim();
        let mobileVal = mobile.value.trim();
        let pinVal = pincode.value.trim();

        // Clear errors
        document.getElementById("nameError").innerText = "";
        document.getElementById("mobileError").innerText = "";
        document.getElementById("pincodeError").innerText = "";

        // Name check
        if (!/^[A-Za-z\s]+$/.test(nameVal)) {
            document.getElementById("nameError").innerText = "Only letters allowed";
            valid = false;
        }

        // Mobile check
        if (!/^[0-9]{10}$/.test(mobileVal)) {
            document.getElementById("mobileError").innerText = "Enter 10-digit mobile number";
            valid = false;
        }

        // Pincode check
        if (!/^[0-9]{6}$/.test(pinVal)) {
            document.getElementById("pincodeError").innerText = "Enter valid 6-digit pincode";
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }

    });

});

// PINCODE → Auto fetch district & state
document.getElementById("pincode").addEventListener("input", function () {

    let pincode = this.value;
    let districtField = document.getElementById("district");
    let stateField = document.getElementById("state");
    let areaField = document.getElementById("area");

    // Only call API when 6 digits entered
    if (pincode.length === 6) {

        // ✅ Show loading BEFORE API call
        districtField.value = "Loading...";
        stateField.value = "";
        areaField.value = "";

        fetch("https://api.postalpincode.in/pincode/" + pincode)
            .then(response => response.json())
            .then(data => {

                if (data[0].Status === "Success") {

                    let postOffice = data[0].PostOffice[0];

                    districtField.value = postOffice.District;
                    stateField.value = postOffice.State;
                    areaField.value = postOffice.Name; // ✅ Area working

                } else {
                    districtField.value = "";
                    stateField.value = "";
                    areaField.value = "";

                    alert("Invalid Pincode");
                }

            })
            .catch(error => {
                console.error("Error:", error);

                districtField.value = "";
                stateField.value = "";
                areaField.value = "";
            });

    } else {
        // Clear fields if pincode not 6 digits
        districtField.value = "";
        stateField.value = "";
        areaField.value = "";
    }

});

// Mobile validation + duplicate check

const mobileInput = document.getElementById("mobile");
const mobileError = document.getElementById("mobileError");

mobileInput.addEventListener("keyup", function () {

    let mobile = this.value;

    // ✅ Allow only numbers
    this.value = mobile.replace(/\D/g, '');

    // ✅ Validate length
    if (mobile.length < 10) {
        mobileError.innerText = "Mobile must be 10 digits";
        return;
    } else if (mobile.length > 10) {
        mobileError.innerText = "Only 10 digits allowed";
        return;
    } else {
        mobileError.innerText = "";
    }

    // ✅ Call API only when 10 digits
    if (mobile.length === 10) {

        fetch("check_mobile.php?mobile=" + mobile)
            .then(response => response.text())
            .then(data => {

                if (data === "exists") {
                    mobileError.innerText = "❌ Mobile already exists";
                    mobileError.style.color = "red";
                } else {
                    mobileError.innerText = "✅ Mobile available";
                    mobileError.style.color = "green";
                }

            })
            .catch(error => {
                console.error("Error:", error);
            });
    }
});

const form = document.getElementById("customerForm");

form.addEventListener("submit", function (e) {
    if (mobileError.innerText.includes("exists") || mobileInput.value.length !== 10) {
        e.preventDefault();
        mobileError.innerText = "Fix mobile number before submitting";
        mobileError.style.color = "red";
    }
});