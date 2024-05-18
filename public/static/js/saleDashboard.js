let purchaseDetail = [];
document.addEventListener('DOMContentLoaded', () => {
    getCustomerList();
    document.getElementById('offcanvasRight').addEventListener('show.bs.offcanvas', handlePurchaseHistory)
    document.getElementById('billModal').addEventListener('show.bs.modal', handlePurchaseDetail)
})

function getCustomerList() {
    fetch('/final/src/customer/get-all-customers.php')
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Failed to fetch data');
            }
        })
        .then(data => {
            createCustomerTable(data);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching data. Please try again later.');
        });
}

function createCustomerTable(data) {
    let tbody = document.getElementById('customer-data');
    tbody.innerHTML = "";
    data.forEach((customer, index) => {
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="customerPhone" style="display: none;">${customer.phone}</td>
            <td>${index + 1}</td>
            <td>${customer.name}</td>
            <td>${customer.phone}</td>
            <td>${customer.address}</td>
            <td>
                <button class="btn paycheck" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fa-solid fa-clock-rotate-left"></i></button>
            </td>`;
        tbody.appendChild(tr);
    });
}

function handlePurchaseHistory(ev) {
    const button = ev.relatedTarget;
    const tr = button.closest("tr");
    const customer_phone = tr.querySelector('.customerPhone').textContent;
    fetch("/final/src/customer/get-history-purchase.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ customer_phone })
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            } 
        })
        .then(data => {
            purchaseDetail = data;
            createOffCanvasBody(data)
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function createOffCanvasBody(data) {
    const offcanvasRight = document.getElementById('offcanvasRight');
    let table = offcanvasRight.querySelector('table tbody')
    table.innerHTML = "";
    data.forEach((purchase, index) => {
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="purchaseID" style="display: none;">${purchase.id}</td>
            <td>${index + 1}</td>
            <td>${purchase.total_bill}</td>
            <td>${purchase.total_given_amount}</td>
            <td>${purchase.total_change_amount}</td>
            <td>${purchase.create_date}</td>
            <td><button id="showInvoice" class="btn paycheck" data-bs-target="#billModal" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i></button></td>
            `
        table.appendChild(tr);
    });
}

function handlePurchaseDetail(ev) {
    const button = ev.relatedTarget;
    const tr = button.closest("tr");
    const purchase_id = parseInt( tr.querySelector('.purchaseID').textContent.trim());
    fetch("/final/src/customer/get-purchase-item.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ purchase_id })
    })
        .then(response => response.json())
        .then(data => {
            createModalBody(data)
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function createModalBody(data) {
    const detailModal = document.getElementById('billModal');
    let table = detailModal.querySelector('table tbody')
    table.innerHTML = "";
    data.forEach((product, index) => {
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${index + 1}</td>
            <td>${product.product_name}</td>
            <td>${product.unit_price}</td>
            <td>${product.quantity}</td>
            <td>${product.total_amount}</td>
            `
        table.appendChild(tr);
    });
}