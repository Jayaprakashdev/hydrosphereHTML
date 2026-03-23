// ============================================================
// Load products from JSON - used for homepage + header dropdown
// ============================================================

const PRODUCTS_URL = "/assets/json/products.json";
// ✅ Fetch once, use everywhere
fetch(PRODUCTS_URL)
    .then(res => res.json())
    .then(data => {
        renderHomepageProducts(data);
        buildDropdownMenu(data);
    });

// ============================================================
// 1. HOMEPAGE - Render all product categories and cards
// ============================================================
// Category to dropdown nav mapping
// const categoryMap = {
//     "Softeners": { label: "Water Softeners", icon: "fa-filter", color: "text-info" },
//     "Domestic RO System 12 LPH": { label: "Domestic RO System 12 LPH", icon: "fa-droplet", color: "text-primary" },
//     "Domestic RO System 25 LPH": { label: "Domestic RO System 25 LPH", icon: "fa-droplet", color: "text-primary" },
//     "Domestic RO System 40 LPH": { label: "Domestic RO System 40 LPH", icon: "fa-droplet", color: "text-primary" },
//     "Ultra Filter": { label: "Ultra Filter", icon: "fa-droplet", color: "text-primary" },
//     "Alkaline Ioniser": { label: "Alkaline Ionisers", icon: "fa-bolt", color: "text-warning" }
// };

function renderHomepageProducts(data) {
    const container = document.getElementById("productList");
    if (!container) return;

    // ✅ Support multiple categories e.g. "Domestic RO System 12 LPH,Ultra Filter"
    const filterAttr = container.getAttribute("data-category");
    const filterCategories = filterAttr
        ? filterAttr.split(",").map(s => s.trim())
        : null;

    container.innerHTML = "";

    data.forEach(categoryObj => {
        const categoryName = Object.keys(categoryObj)[0];

        // ✅ Skip if filter is set and this category isn't in the list
        if (filterCategories && !filterCategories.includes(categoryName)) return;

        const products = categoryObj[categoryName];

        container.innerHTML += `
      <div class="category-group">
        <h4 class="category-title">${categoryName}</h4>
        <div class="row g-4" id="cat-${categoryName.replace(/\s/g, "")}"></div>
      </div>
    `;

        const productContainer = document.getElementById(`cat-${categoryName.replace(/\s/g, "")}`);

        products.forEach(p => {
            const saveAmount = p.mrp && p.offerPrice ? p.mrp - p.offerPrice : null;
            const productSlug = p.title.toLowerCase().replace(/[^a-z0-9]+/g, "-");

            productContainer.innerHTML += `
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="product-card h-100">
            ${p.emi ? `<span class="emi-badge">EMI Available</span>` : ""}
            ${p.videoUrl ? `
              <button class="btn btn-dark play-video-btn"
                      data-video="${p.videoUrl}"
                      data-bs-toggle="modal"
                      data-bs-target="#videoModal">
                <i class="fa-solid fa-play"></i>
              </button>` : ""}
            <div class="product-img">
              <img src="${p.image}" alt="${p.title}">
            </div>
            <div class="product-body">
              <h6 class="product-title">${p.title}</h6>
              <div class="price-box">
                ${p.mrp && p.offerPrice ? `
                  <div class="original-price">₹${p.mrp.toLocaleString()}</div>
                  <div class="offer-price">₹${p.offerPrice.toLocaleString()}</div>
                  <div class="save-text">You Save ₹${saveAmount.toLocaleString()}</div>
                ` : `<div class="offer-price">Call for Price</div>`}
              </div>
              ${p.gstIncluded ? `<div class="gst-text">GST Included</div>` : ""}
              ${p.freeInstallation ? `<div class="install-text">Free Installation</div>` : ""}
              <div class="d-flex gap-2 mt-3">
                <a href="https://wa.me/919087667766?text=Hello%20Hydrosphere,%0A%0AI%20would%20like%20to%20BOOK%20the%20following%20product:%0A👉%20${encodeURIComponent(p.title)}"
                  class="btn btn-success w-50">
                  <i class="fa-brands fa-whatsapp me-1"></i> Book Now
                </a>
                <a href="/product-details.html?product=${productSlug}"
                   class="btn btn-outline-success w-50" target="_blank">
                   View Details
                </a>
              </div>
            </div>
          </div>
        </div>
      `;
        });
    });
}

// ============================================================
// 2. HEADER DROPDOWN - Build menu items from categories
// ============================================================
function buildDropdownMenu(data) {
    const dropdownMenu = document.querySelector("#productsDropdown + .dropdown-menu");
    if (!dropdownMenu) return;

    dropdownMenu.innerHTML = "";
    const added = new Set(); // ✅ Track already-added labels

    data.forEach(categoryObj => {
        const categoryName = Object.keys(categoryObj)[0];
        const map = categoryMap[categoryName];

        const label = map ? map.label : categoryName;
        const icon = map ? map.icon : "fa-box";
        const color = map ? map.color : "text-secondary";
        const slug = label.toLowerCase().replace(/[^a-z0-9]+/g, "-");

        if (added.has(label)) return; // ✅ Skip duplicates
        added.add(label);

        dropdownMenu.innerHTML += `
      <li>
        <a class="dropdown-item d-flex align-items-center gap-2"
           href="/products/${slug}">
          <i class="fa-solid ${icon} ${color}"></i> ${label}
        </a>
      </li>
    `;
    });
}

// ============================================================
// 3. VIDEO MODAL - Play & stop
// ============================================================
document.addEventListener("click", function (e) {
    if (e.target.closest(".play-video-btn")) {
        const btn = e.target.closest(".play-video-btn");
        const url = btn.getAttribute("data-video");
        const videoId = url.match(/(?:v=|\.be\/)([^&]+)/)?.[1];
        if (!videoId) return;
        document.getElementById("videoFrame").src =
            `https://www.youtube.com/embed/${videoId}?autoplay=1`;
    }
});

const videoModal = document.getElementById("videoModal");
if (videoModal) {
    videoModal.addEventListener("hidden.bs.modal", () => {
        document.getElementById("videoFrame").src = "";
    });
}
