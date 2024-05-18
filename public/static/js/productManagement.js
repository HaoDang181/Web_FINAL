document.addEventListener("DOMContentLoaded", () => {
    const showImage = document.getElementById('showImage');
    const imageInput = document.getElementById('imageInput');
    const showImage2 = document.getElementById('showImage2');
    const imageInput2 = document.getElementById('imageInput2');
    const userRole = document.getElementById('user-role').value;

    initPage();

    if (userRole === 'admin') {
        document.getElementById("updateProduct").addEventListener('show.bs.modal', handleEditProduct);
        document.getElementById("deleteConfirmModal").addEventListener('show.bs.modal', handleDeleteProduct);
    }

    imageInput.addEventListener('change', () => handleImageChange(imageInput, showImage));
    imageInput2.addEventListener('change', () => handleImageChange(imageInput2, showImage2));
});

async function initPage() {
    try {
        const products = await fetchProducts();
        createProductTable(products);
    } catch (error) {
        console.error('Error:', error);
    }
}

async function fetchProducts() {
    const response = await fetch('/final/src/product/get-all-product.php');
    if (!response.ok) throw new Error('Failed to fetch data');
    return response.json();
}

function createProductTable(data) {
    const role = document.getElementById("user-role").value;
    const tbody = document.getElementById("tbody");
    const thead = document.getElementById("thead");

    thead.innerHTML = `
        <th>Barcode</th>
        <th>Hình ảnh</th>
        <th>Tên sản phẩm</th>
        ${role === 'admin' ? '<th>Giá gốc</th>' : ''}
        <th>Giá bán</th>
        <th>Loại</th>
        ${role === 'admin' ? '<th>Thao tác</th>' : ''}
    `;

    tbody.innerHTML = data.map(product => `
        <tr>
            <td style='display: none;'><input class='productId' type='hidden' value=${product.id}></td>
            <td class='barcode'>${product.barcode}</td>
            <td><img src="/final/src/uploadImage/${product.image}" alt="${product.image}" style="width: 100px;"></td>
            <td>${product.name}</td>
            ${role === 'admin' ? `<td class="original">${product.import_price}</td>` : ''}
            <td>${product.retail_price}</td>
            <td>${product.category}</td>
            ${role === 'admin' ? `
                <td class="action">
                    <button type="button" class="btn btn-info edit me-2" data-bs-toggle="modal" data-bs-target="#updateProduct">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button type="button" class="btn btn-danger delete" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>` : ''}
        </tr>`).join('');
}

function handleImageChange(input, imgElement) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => { imgElement.src = e.target.result; };
        reader.readAsDataURL(input.files[0]);
    }
}

function handleEditProduct(ev) {
    const button = ev.relatedTarget;
    const tr = button.closest("tr");
    const productId = tr.querySelector('.productId').value;
    populateUpdateForm(tr, productId);
}

function handleDeleteProduct(ev) {
    const button = ev.relatedTarget;
    const tr = button.closest("tr");
    const productId = tr.querySelector('.productId').value;
    populateDeleteConfirmation(productId);
}

function populateUpdateForm(tr, productId) {
    const updateForm = document.getElementById('updateProduct');
    updateForm.querySelector("#productId").value = productId;
    updateForm.querySelector("#barcode").value = tr.cells[1].textContent;
    updateForm.querySelector("#name").value = tr.cells[3].textContent;
    updateForm.querySelector("#import_price").value = tr.cells[4].textContent;
    updateForm.querySelector("#retail_price").value = tr.cells[5].textContent;
    updateForm.querySelector("#category").value = tr.cells[6].textContent;
}

function populateDeleteConfirmation(productId) {
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    deleteConfirmModal.querySelector("#productId").value = productId;
}
