const params = new URLSearchParams(window.location.search);
const productSlug = params.get("product");

fetch("/assets/json/products.json")
    .then(res => res.json())
    .then(data => {
        data.forEach(categoryObj => {
            Object.values(categoryObj)[0].forEach(p => {

                const slug = p.title.toLowerCase().replace(/[^a-z0-9]+/g, "-");

                if (slug === productSlug) {

                    // BASIC INFO
                    document.getElementById("productTitle").innerText = p.title;
                    document.getElementById("productImage").src = p.image;

                    if (p.videoUrl) {
                        const videoId = getYouTubeId(p.videoUrl);

                        if (videoId) {
                            const playBtn = document.getElementById("playVideoBtn");
                            const iframe = document.getElementById("videoFrame");

                            playBtn.classList.remove("d-none");

                            playBtn.addEventListener("click", () => {
                                iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                            });

                            // Stop video on modal close
                            document.getElementById("videoModal")
                                .addEventListener("hidden.bs.modal", () => {
                                    iframe.src = "";
                                });
                        }
                    }

                    // PRICE
                    document.getElementById("mrpPrice").innerText =
                        p.mrp ? `₹${p.mrp.toLocaleString()}` : "";

                    document.getElementById("offerPrice").innerText =
                        p.offerPrice ? `₹${p.offerPrice.toLocaleString()}` : "Call for Price";

                    if (p.mrp && p.offerPrice) {
                        document.getElementById("saveAmount").innerText =
                            `You Save ₹${(p.mrp - p.offerPrice).toLocaleString()}`;
                    }

                    // BADGES
                    if (p.emi) document.getElementById("emiBadge").classList.remove("d-none");
                    if (p.gstIncluded) document.getElementById("gstBadge").classList.remove("d-none");
                    if (p.freeInstallation)
                        document.getElementById("installBadge").classList.remove("d-none");

                    // WHATSAPP BUTTON
                    document.getElementById("whatsappBtn").href =
                        `https://wa.me/919087667766?text=Hello%20Hydrosphere,%0A%0AI%20would%20like%20to%20BOOK:%0A👉%20${encodeURIComponent(p.title)}%0A💰%20Offer%20Price:%20₹${p.offerPrice}`;

                    document.getElementById("productDescription").innerText =
                        p.description || "";

                    document.getElementById("specList").innerHTML =
                        p.specifications
                            ? p.specifications.map(item => `<li>✔ ${item}</li>`).join("")
                            : "";

                    document.getElementById("benefitList").innerHTML =
                        p.benefits
                            ? p.benefits.map(item => `<li>✔ ${item}</li>`).join("")
                            : "";

                }
            });
        });
    });

function getYouTubeId(url) {
    const match = url.match(
        /(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/);
    return match ? match[1] : null;
}

