document.addEventListener("DOMContentLoaded", () => {
    const getPhoneButton = document.getElementById('getPhoneNumber');
    const createCustomerButton = document.getElementById('createNewCustomeAccount');
    const createOrderButton = document.getElementById('createOrder');
    const paycheckButton = document.getElementById('paycheck');
    const table = document.querySelector('.table-striped');

    let phone;

    if (table.rows.length > 0) {
        paycheckButton.setAttribute('disabled', 'disabled') // Enable the button if rows exist
    } else {
        paycheckButton.removeAttribute('disabled') // Disable the button if no rows exist
    }
    getPhoneButton.addEventListener('click', checkCustomerPhone);
    createCustomerButton.addEventListener('click', createNewCustomerAccount);
    createOrderButton.addEventListener('click', createOrder);
    getSuggestion();
});

const productDataMap = new Map();

function checkCustomerPhone() {
    phone = document.getElementById('customerPhoneInput').value;

    fetch('/final/src/customer/customer-info.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ phone })
    })
        .then(response => response.json())
        .then(data => {
            const modalBody = document.getElementById('billModal').querySelector('.modal-body');
            if (data.length > 0) {
                const customer = data[0];
                modalBody.querySelector('.fullname').value = customer.name;
                modalBody.querySelector('.address').value = customer.address;
                modalBody.querySelector('.phone').value = customer.phone;
                modalBody.querySelector('.totalBill').value = calculateTotalBill();
                document.getElementById('showInvoice').click();
            } else {
                document.getElementById('moveToNext').click();
            }
        })
        .catch(error => console.error('Error:', error));
}

function createNewCustomerAccount() {
    const customerInfoModal = document.getElementById('customerInfoModal2');
    const fullname = customerInfoModal.querySelector('.modal-body .fullname').value;
    const address = customerInfoModal.querySelector('.modal-body .address').value;
    const data = { name: fullname, phone, address };

    fetch('/final/src/customer/create-customer-account.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (response.ok) {
                const modalBody = document.getElementById('billModal').querySelector('.modal-body');
                modalBody.querySelector('.fullname').value = fullname;
                modalBody.querySelector('.address').value = address;
                modalBody.querySelector('.phone').value = phone;
                modalBody.querySelector('.totalBill').value = calculateTotalBill();
                document.getElementById('showInvoice').click();
            }
        })
        .catch(error => console.error('Error:', error));
}

function createOrder() {
    const billModal = document.getElementById('billModal');
    let name = billModal.querySelector('.modal-body .fullname').value;
    let address = billModal.querySelector('.modal-body .address').value;
    let totalBill = parseFloat(billModal.querySelector('.modal-body .totalBill').value);
    let totalGiven = parseFloat(billModal.querySelector('.modal-body .totalGiven').value);
    const totalChange = parseFloat(billModal.querySelector('.modal-body .totalChange').value);
    const tr = document.querySelectorAll('#checkoutList tr');
    const order = [];

    if (isNaN(totalGiven) || isNaN(totalBill) || totalGiven <= totalBill) {
        alert('Số tiền nhận phải hợp lệ và lớn hơn tổng tiền trong hoá đơn');
        return;
    }

    tr.forEach(item => {
        const product_barcode = item.querySelector('.barcode').textContent;
        const quantity = parseInt(item.querySelector('.quantity span').textContent);
        order.push({ product_barcode, quantity });
    });

    const data = {
        phone,
        name,
        address,
        total_given_amount: totalGiven,
        total_change_amount: totalChange,
        order
    };
    console.log(data);

    fetch('/final/src/transaction/create-new-purchase.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
}

function getSuggestion() {
    const searchInput = document.getElementById('search');

    searchInput.addEventListener('input', () => {
        const input = searchInput.value;

        if (input.length >= 2) {
            fetch('/final/src/transaction/search-product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ input })
            })
                .then(response => response.json())
                .then(data => {
                    const suggestions = document.getElementById('suggestions');
                    suggestions.innerHTML = '';

                    if (data && data.length > 0) {
                        data.forEach(product => {
                            const listItem = document.createElement('li');
                            listItem.className = 'list-group-item';
                            listItem.dataset.product = JSON.stringify(product);
                            listItem.textContent = product.name;
                            listItem.addEventListener('click', () => addProductIntoTable(product));
                            suggestions.appendChild(listItem);
                        });
                    } else {
                        const noProductItem = document.createElement('li');
                        noProductItem.className = 'list-group-item';
                        noProductItem.textContent = 'Không có sản phẩm như thế';
                        suggestions.appendChild(noProductItem);
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            document.getElementById('suggestions').innerHTML = '';
        }
    });
}

function calculateTotalBill() {
    const totalCells = document.querySelectorAll('#checkoutList .total');
    let totalSum = 0;

    totalCells.forEach(cell => {
        totalSum += parseFloat(cell.textContent);
    });

    return totalSum.toFixed(2);
}

function addProductIntoTable(product) {
    const tbody = document.getElementById('checkoutList');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    document.getElementById('paycheck').removeAttribute('disabled')

    let productExists = false;

    rows.forEach(row => {
        const barcodeCell = row.querySelector('.barcode');

        if (barcodeCell.textContent.trim() === product.barcode) {
            productExists = true;
            updateProductQuantity(row, product, 1);
        }
    });

    if (!productExists) {
        const newRow = createProductRow(product);
        tbody.appendChild(newRow);
        // Store product data in the Map
        productDataMap.set(product.barcode, product);
    }
}

function updateProductQuantity(row, product, quantityChange) {
    const quantityCell = row.querySelector('.quantity span');
    const totalPriceCell = row.querySelector('.total');
    let quantity = parseInt(quantityCell.textContent) + quantityChange;

    quantityCell.textContent = quantity;
    totalPriceCell.textContent = (quantity * parseFloat(product.retail_price)).toFixed(2);
}

function createProductRow(product) {
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td class='barcode'>${product.barcode}</td>
        <td><img src="/final/src/uploadImage/${product.image}" alt="${product.image}"></td>
        <td>${product.name}</td>
        <td>${product.category}</td>
        <td>${product.retail_price}</td>
        <td class='quantity'>
            <div class="btn-group" role="group" aria-label="Quantity group">
                <button type="button" class="btn subtract">-</button>
                <span class="btn">1</span>
                <button type="button" class="btn add">+</button>
            </div>
        </td>
        <td class='total'>${product.retail_price}$</td>`;

    const subtractButton = newRow.querySelector('.subtract');
    const addButton = newRow.querySelector('.add');
    const quantitySpan = newRow.querySelector('.quantity span');

    subtractButton.addEventListener('click', () => {
        if (parseInt(quantitySpan.textContent) > 1) {
            updateProductQuantity(newRow, product, -1);
        }
    });

    addButton.addEventListener('click', () => updateProductQuantity(newRow, product, 1));

    return newRow;
}

