document.getElementById("year").textContent = new Date().getFullYear();


document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("#priceTable tbody");
    const searchInput = document.getElementById("searchInput");

    function renderTable(data) {
        tableBody.innerHTML = "";

        if (data.length === 0) {
            tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No matching products found
                        </td>
                    </tr>
                `;
            return;
        }

        data.forEach((item, index) => {
            const row = document.createElement("tr");

            row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.model || "-"}</td>
                    <td>${item.brand || "HYDROSPHERE"}</td>
                    <td>₹${item.price?.toLocaleString() || "-"}</td>
                    <td>₹${item.offer?.toLocaleString() || "-"}</td>
                    <td>${item.color || "-"}</td>
                    <td>${item.kit || "-"}</td>
                    <td>${item.validity || "01-02-2026 to 28-02-2026"}</td>
                `;

            tableBody.appendChild(row);
        });
    }

    // Initial load
    renderTable(priceList);

    // Live search filter
    searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase();

        const filteredData = priceList.filter(item =>
            item.model?.toLowerCase().includes(query) ||
            item.brand?.toLowerCase().includes(query) ||
            item.category?.toLowerCase().includes(query)
        );

        renderTable(filteredData);
    });
});