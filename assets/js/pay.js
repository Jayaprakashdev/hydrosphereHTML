// payment QR generater 
function generateQR() {
    const amount = document.getElementById("amount").value;
    const qrImage = document.getElementById("qrImage");
    const payText = document.getElementById("payText");
    const whatsappBtn = document.getElementById("whatsappBtn");

    if (!amount || amount <= 0) {
        alert("Please enter a valid amount");
        return;
    }

    // 🔴 CHANGE YOUR UPI ID HERE
    const upiId = "8825939355@ybl";
    const name = "Hydrosphere";
    const note = "Payment";

    const upiUrl = `upi://pay?pa=${upiId}&pn=${name}&am=${amount}&cu=INR&tn=${note}`;

    // QR Code Image (Google API)
    qrImage.src = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(upiUrl)}`;
    qrImage.style.display = "block";

    payText.innerText = `Pay ₹${amount} using the QR code`;

    // WhatsApp Share
    // const message = `Please pay ₹${amount} using this QR:\n${upiUrl}`;
    // whatsappBtn.href = `https://wa.me/?text=${encodeURIComponent(message)}`;
    // whatsappBtn.classList.remove("d-none");
}