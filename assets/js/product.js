fetch("/assets/json/products.json")
  .then(res => res.json())
  .then(products => {
    const container = document.getElementById("productList");

    products.forEach(p => {
      const saveAmount =
        p.mrp && p.offerPrice ? p.mrp - p.offerPrice : null;

      container.innerHTML += `
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="product-card h-100">
          
            ${p.emi ? `<span class="emi-badge">EMI Available</span>` : ""}

            <div class="product-img">
              <img src="${p.image}" alt="${p.title}">
            </div>

            <div class="product-body">
              <h6 class="product-title">${p.title}</h6>

              <div class="price-box">
                ${
                  p.mrp && p.offerPrice
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

              <a href="https://wa.me/919087667766?text=Hello%20Hydrosphere,%20I%20am%20interested%20in%20${encodeURIComponent(p.title)}"
                 class="btn btn-success w-100 mt-3">
                 WhatsApp Enquiry
              </a>
            </div>
          </div>
        </div>
      `;
    });
  });