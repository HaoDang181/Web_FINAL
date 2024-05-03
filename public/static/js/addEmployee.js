document.addEventListener("DOMContentLoaded", function () {
    let lock = 0; 
    fetchData()
    document.querySelector("#addEmployeeModal .submit").addEventListener("click", function (event) {

        
        var modal = new bootstrap.Modal(document.getElementById('addEmployeeModal'));
        modal.hide();
    });

    function addEmployeeToTable(avatarUrl, name, email) {
        var tableBody = document.getElementById("data");
        var newRow = document.createElement("tr");

        // Avatar cell
        var avatarCell = document.createElement("td");
        var avatarButton = document.createElement("button");
        avatarButton.className = "view";
        avatarButton.setAttribute("data-bs-toggle", "modal");
        avatarButton.setAttribute("data-bs-target", "#viewStaffInfo");
        avatarButton.innerHTML = '<ion-icon name="eye-outline"></ion-icon>';
        avatarCell.appendChild(avatarButton);

        var avatarImage = document.createElement("img");
        avatarImage.src = avatarUrl;
        avatarImage.alt = "Avatar";
        avatarImage.width = 50;
        avatarImage.height = 50;
        avatarCell.appendChild(avatarImage);

        newRow.appendChild(avatarCell);

        // Name cell
        var nameCell = document.createElement("td");
        nameCell.textContent = name;
        newRow.appendChild(nameCell);

        // Email cell
        var emailCell = document.createElement("td");
        emailCell.textContent = email;
        newRow.appendChild(emailCell);

        // Status cell
        var statusCell = document.createElement("td");
        var lockButton = document.createElement("button");
        lockButton.className = "lock";
        lockButton.innerHTML = '<ion-icon name="lock-closed-outline"></ion-icon>';
        var unlockButton = document.createElement("button");
        unlockButton.className = "unlock";
        unlockButton.innerHTML = '<ion-icon name="lock-open-outline"></ion-icon>';
        statusCell.appendChild(lockButton);
        statusCell.appendChild(unlockButton);
        newRow.appendChild(statusCell);

        // Add the new row to the table
        tableBody.appendChild(newRow);
    }
});

function readInfo(avatar, name, email, phone) {
    document.querySelector('.img').src = avatar,
        document.querySelector('#showName').value = name,
        document.querySelector("#showEmail").value = email,
        document.querySelector("#showPhone").value = phone
}

function fetchData() {
    // Gửi yêu cầu POST đến endpoint /legacy/prop/add
    fetch('/WEB-FINAL/src/user/view-staff-list.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            console.log(response);
            if (response.ok) {
                return response.json(); // Parse the response body as JSON
            } else {
                // Xử lý lỗi
                alert('Failed to create property. Please try again.');
            }
        })
        .then(data => {
            // Process the data returned from the server
            viewStaffList(data);
            handleLock();
            // Do whatever you want with the data
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating property. Please try again later.');
        });

}
function viewStaffList(data) {
    console.log(data)
    let tbody = document.getElementById('data')
    data.forEach(user => {
        let tr = document.createElement('tr')
        let activeStatus = user.is_active === 0? "Chưa kích hoạt" : "Đã kích hoạt"
        let lockStatus = user.is_lock ===0 ? `<button class="lock"><ion-icon class="open" name="lock-open-outline"></ion-icon></button>` : `<button class="lock"><ion-icon class ="close" name="lock-closed-outline"></ion-icon></button>`
        lock = user.is_lock;
        console.log(lockStatus);
        tr.innerHTML = `
        <td class="userId">${user.id}</td>
        <td>

                <img src="https://i.pinimg.com/564x/ef/5a/77/ef5a778b6fa98a57169609e8244a8bd8.jpg" alt="" width="50" height="50"></td>
            
            <td>${user.fullname}</td>
            <td>${user.email}</td>
            <td>
               ${activeStatus}
            
            </td>
                
            <td>
            <button class="view" data-bs-toggle="modal" data-bs-target="#viewStaffInfo">
            <ion-icon name="eye-outline"></ion-icon>
        </button>
        ${lockStatus}

            </td>`

        tbody.appendChild(tr)
    })
}

function handleLock() {
    let lockButton = document.querySelectorAll('.lock')
    lockButton.forEach(button => {
        button.addEventListener('click' , ()=> {
            let tr = button.closest("tr")
            let lockStatus = lock === 1 ? 0 : 1
        
            changeLockStatus(tr.querySelector('.userId').textContent, lockStatus)
        })
    })
}

function changeLockStatus(userId, lockStatus) {
    let data = {id:userId, lock_status : lockStatus}
    fetch('/WEB-FINAL/src/user/update-lock-status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }, 
        body: JSON.stringify(data)
    })
        .then(response => {
            console.log(response);
            if (response.ok) {
                return response.json(); // Parse the response body as JSON
            } else {
                // Xử lý lỗi
                alert('Failed to create property. Please try again.');
            }
        })
        .then(data => {
            // Process the data returned from the server
            window.location.reload()
            // Do whatever you want with the data
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating property. Please try again later.');
        });
}
