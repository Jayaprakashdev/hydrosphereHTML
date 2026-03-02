fetch("/assets/json/products.json")
  .then(res => res.json())
  .then(data => {
    const container = document.getElementById("productList");
    container.innerHTML = "";

    data.forEach(categoryObj => {
      const categoryName = Object.keys(categoryObj)[0];
      const products = categoryObj[categoryName];

      // Category Section
      container.innerHTML += `
            <div class="category-group">
              <h4 class="category-title">${categoryName}</h4>
              <div class="row g-4" id="${categoryName.replace(/\s/g, "")}">
              </div>
      `;

      const productContainer = document.getElementById(
        categoryName.replace(/\s/g, "")
      );

      products.forEach(p => {
        const saveAmount =
          p.mrp && p.offerPrice ? p.mrp - p.offerPrice : null;

        const productSlug = p.title
          .toLowerCase()
          .replace(/[^a-z0-9]+/g, "-");

        productContainer.innerHTML += `
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="product-card h-100">

              ${p.emi ? `<span class="emi-badge">EMI Available</span>` : ""}
              
              <!-- VIDEO (ONLY IF EXISTS) -->
                  ${p.videoUrl
                    ? `
                              <button class="btn btn-dark play-video-btn"
                                      data-video="${p.videoUrl}"
                                      data-bs-toggle="modal"
                                      data-bs-target="#videoModal">
                                <i class="fa-solid fa-play"></i>
                              </button>
                              `
                    : ""
                  }

              <div class="product-img">
                <img src="${p.image}" alt="${p.title}">
              </div>

              <div class="product-body">
                <h6 class="product-title">${p.title}</h6>

                <div class="price-box">
                  ${p.mrp && p.offerPrice
            ? `
                        <div class="original-price">₹${p.mrp.toLocaleString()}</div>
                        <div class="offer-price">₹${p.offerPrice.toLocaleString()}</div>
                        <div class="save-text">You Save ₹${saveAmount.toLocaleString()}</div>
                      `
            : `<div class="offer-price">Call for Price</div>`
          }
                </div>

                ${p.gstIncluded ? `<div class="gst-text">GST Included</div>` : ""}
                ${p.freeInstallation ? `<div class="install-text">Free Installation</div>` : ""}

                <div class="d-flex gap-2 mt-3">
                <a href="https://wa.me/919087667766?text=Hello%20Hydrosphere,%0A%0AI%20would%20like%20to%20BOOK%20the%20following%20product:%0A👉%20${encodeURIComponent(p.title)}"
                  class="btn btn-success w-50">
                  <i class="fa-brands fa-whatsapp me-1"></i> Book Now
                </a>
                  <a href="product-details.html?product=${productSlug}"
                     class="btn btn-outline-success w-50" target="_blank">
                     View Details
                  </a>
                  
                </div>

              </div>
            </div>
          </div>
          </div>
        `;
      });
    });
  });

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

// Stop video when modal closes
document.getElementById("videoModal")
  .addEventListener("hidden.bs.modal", () => {
    document.getElementById("videoFrame").src = "";
  });