document.addEventListener('DOMContentLoaded', () => {
    // Call getReport with today's value when the page loads
    getReport('today');
    // Event listener for dropdown menu items
    document.querySelector('.dropdown-menu').addEventListener('click', function (event) {
        if (event.target.classList.contains('dropdown-item')) {
            const value = event.target.getAttribute('value');

            // Toggle custom date inputs visibility and fetch report accordingly
            if (value === 'custom') {
                document.getElementById('custom-date-inputs').style.display = 'block';
            } else {
                document.getElementById('custom-date-inputs').style.display = 'none';
                document.getElementById('purchaseItemList').innerHTML = "";
                getReport(value);
            }
        }
    });

    // Event listener for fetching report based on custom dates
    document.getElementById('fetch-report').addEventListener('click', () => {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        if (startDate && endDate) {
            getReport('custom', startDate, endDate);
            document.getElementById('purchaseItemList').innerHTML = "";
        } else {
            alert('Please select both start and end dates.');
        }
    });

    // Event listener for showing purchase details modal
    document.getElementById('billModal').addEventListener('show.bs.modal', handlePurchaseDetail);
});

// Function to fetch report data from server
function getReport(timeframe, startDate = '', endDate = '') {
    let url = `/final/src/report/get-report.php?timeframe=${encodeURIComponent(timeframe)}`;
    if (timeframe === 'custom') {
        url += `&start_date=${encodeURIComponent(startDate)}&end_date=${encodeURIComponent(endDate)}`;
    }
    fetch(url)
        .then(response => response.json())
        .then(data => {
            updateReport(data);
            createPurchaseItemTable(data);
        })
        .catch(error => console.error(error));
}

// Function to update the report on the webpage
async function updateReport(data) {
    let totalEarn = 0;
    let totalProduct = 0;
    let totalProfit = 0;
    if (data.length !== 0) {
        // Calculate total earnings
        data.forEach(async element => {
            totalEarn += element.total_given_amount;
            totalProduct += element.total_products;
            const items = await getPurchaseItems(element.id);
            items.forEach(item => {
                totalProfit += item.total_profit;
            });
        });
        // Wait for all promises to resolve before updating the webpage
        await Promise.all(data.map(element => getPurchaseItems(element.id)));
    }
    // Update total number of purchases and total earnings on the webpage
    document.getElementById('noPurchase').querySelector('.card-text').textContent = data.length + ' đơn';
    document.getElementById('totalEarn').querySelector('.card-text').textContent = totalEarn + '$';
    document.getElementById('noProduct').querySelector('.card-text').textContent = totalProduct + ' đơn vị';
    document.getElementById('totalProfit').querySelector('.card-text').textContent = totalProfit + '$';
}


// Function to handle purchase detail modal
function handlePurchaseDetail(ev) {
    const button = ev.relatedTarget;
    const tr = button.closest("tr");
    const purchase_id = parseInt(tr.querySelector('.purchaseID').textContent.trim());
    getPurchaseItems(purchase_id)
    .then(data => createModalBody(data));
}

function getPurchaseItems(purchase_id){
    return fetch("/final/src/customer/get-purchase-item.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ purchase_id })
    })
        .then(response => response.json())
        .catch(error => {
            console.error('Error:', error);
        });
}

// Function to create modal body for purchase details
function createModalBody(data) {
    const detailModal = document.getElementById('billModal');
    let table = detailModal.querySelector('table tbody');
    table.innerHTML = "";
    data.forEach((product, index) => {
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${index + 1}</td>
            <td>${product.product_name}</td>
            <td>${product.unit_price}$</td>
            <td>${product.quantity}</td>
            <td>${product.total_amount}$</td>
            `;
        table.appendChild(tr);
    });
}

// Function to create the purchase item table
function createPurchaseItemTable(purchaseItems) {
    let tbody = document.getElementById('purchaseItemList');
    tbody.innerHTML = ""; // Clear previous content
    purchaseItems.forEach((purchase, index) => {
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="purchaseID" style="display: none;">${purchase.id}</td>
            <td>${index + 1}</td>
            <td>${purchase.customer_name}</td>
            <td>${purchase.total_bill}$</td>
            <td>${purchase.total_given_amount}$</td>
            <td>${purchase.total_change_amount}$</td>
            <td>${purchase.create_date}</td>
            <td><button id="showInvoice" class="btn paycheck" data-bs-target="#billModal" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i></button></td>
            `;
        tbody.appendChild(tr);
    });
}
