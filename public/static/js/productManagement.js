document.addEventListener("DOMContentLoaded", () => {
    getAllProducts();
});

async function getAllProducts() {
    try {
        const response = await fetch('http://localhost/final/src/product/get-all-product.php');
        if (!response.ok) {
            throw new Error('Failed to fetch data');
        }
        const data = await response.json();
        createProductTable(data);
        handleEditProduct();
        handleDeleteProduct();
    } catch (error) {
        console.error('Error:', error);
        // Handle error (e.g., display an error message to the user)
    }
}

function createProductTable(data) {
    const role = document.getElementById("user-role").value;
    const tbody = document.getElementById("tbody");
    const thead = document.getElementById("thead");

    tbody.innerHTML = '';
    thead.innerHTML = `
        <th>Barcode</th>
        <th>Tên sản phẩm</th>
        ${role === 'admin' ? '<th>Giá gốc</th>' : ''}
        <th>Giá bán</th>
        <th>Loại</th>
        ${role === 'admin' ? '<th>Thao tác</th>' : ''}
    `;

    data.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td style='display: none;'><input class='productId' type='hidden' value=${product.id}></td>
            <td>${product.barcode}</td>
            <td>${product.name}</td>
            ${role === 'admin' ? `<td class="original">${product.import_price}</td>` : ''}
            <td>${product.retail_price}</td>
            <td>${product.category}</td>
            ${role === 'admin' ? `<td class="action"><button class='edit'>Edit</button><button class='delete'>Delete</button></td>` : ''}
        `;
        tbody.appendChild(row);
    });
}

function handleEditProduct() {
    const editButtons = document.querySelectorAll('.edit');
    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tr = button.closest("tr");
            const productId = tr.querySelector('td .productId').value;
            populateUpdateForm(tr, productId);
        });
    });
}

function handleDeleteProduct() {
    const deleteButtons = document.querySelectorAll('.delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tr = button.closest("tr");
            const productId = tr.querySelector('td .productId').value;
            populateDeleteConfirmation(productId);
        });
    });
}

function populateUpdateForm(tr, productId) {
    const updateForm = document.getElementById('updateProduct');
    updateForm.querySelector("#productId").value = productId;
    updateForm.querySelector("#barcode").value = tr.querySelector("td:nth-child(2)").textContent;
    updateForm.querySelector("#name").value = tr.querySelector("td:nth-child(3)").textContent;
    updateForm.querySelector("#import_price").value = tr.querySelector("td:nth-child(4)").textContent;
    updateForm.querySelector("#retail_price").value = tr.querySelector("td:nth-child(5)").textContent;
    updateForm.querySelector("#category").value = tr.querySelector("td:nth-child(6)").textContent;
    const updateProductModal = new bootstrap.Modal(updateForm);
    updateProductModal.show();
}

function populateDeleteConfirmation(productId) {
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    deleteConfirmModal.querySelector("#productId").value = productId;
    const modal = new bootstrap.Modal(deleteConfirmModal);
    modal.show();
}
