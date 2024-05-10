document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("search").addEventListener('click', () => {
        const data = document.getElementById('searchField').value;
        getProductInfo(data)
    })
})
function getProductInfo(data) {
    fetch(`/final/src/product/get-product.php?name=${encodeURIComponent(data)}&barcode=${encodeURIComponent(data)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })

        .then(data => {
            addProductIntoTable(data)
        })
        .catch(error => {
            // Handle errors
            console.error('There was a problem with the fetch operation:', error);
        });
}

function addProductIntoTable(product) {
    const tbody = document.getElementById('checkoutList');
    tbody.innerHTML += `
    <tr>
        <td>${product.barcode}</td>
        <td>${product.name}</td>
        <td>${product.retail_price}</td>
        <td>${product.category}</td>
    </tr>`
}