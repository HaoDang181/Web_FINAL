document.addEventListener("DOMContentLoaded", function () {
    fetchData();
    document.querySelector("#addEmployeeModal .submit").addEventListener("click", function () {
        let modal = new bootstrap.Modal(document.getElementById('addEmployeeModal'));
        modal.hide();
    });
});

function readInfo(avatar, name, email, phone) {
    document.querySelector('.img').src = avatar;
    document.querySelector('#showName').value = name;
    document.querySelector("#showEmail").value = email;
    document.querySelector("#showPhone").value = phone;
}

function fetchData() {
    fetch('http://localhost/final/src/user/view-staff-list.php')
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Failed to fetch data');
            }
        })
        .then(data => {
            viewStaffList(data);
            handleLock();
            viewStaffDetail();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching data. Please try again later.');
        });
}

function viewStaffList(data) {
    let tbody = document.getElementById('data');
    tbody.innerHTML = ""; 
    data.forEach(user => {
        let tr = document.createElement('tr');
        let activeStatus = user.is_active === 0 ? "Chưa kích hoạt" : "Đã kích hoạt";
        let lockStatus = user.is_lock === 0 ? '<button class="lock open"><ion-icon class="open" name="lock-open-outline"></ion-icon></button>' : '<button class="lock close"><ion-icon class ="close" name="lock-closed-outline"></ion-icon></button>';
        tr.innerHTML = `
            <td class="userId">${user.id}</td>
            <td>
                <img src="/final/src/uploadImage/${user.avatar}" alt="Avatar" width="50" height="50">
            </td>
            <td>${user.fullname}</td>
            <td>${user.email}</td>
            <td>${activeStatus}</td>
            <td>
                <button class="view" data-bs-toggle="modal" data-bs-target="#viewStaffInfo">
                    <ion-icon name="eye-outline"></ion-icon>
                </button>
                ${lockStatus}
            </td>`;
        tbody.appendChild(tr);
    });
}

function handleLock() {
    let lockButtons = document.querySelectorAll('.lock');
    lockButtons.forEach(button => {
        button.addEventListener('click', () => {
            let tr = button.closest("tr");
            let userId = tr.querySelector('.userId').textContent;
            let isLocked = button.classList.contains('close');
            let newLockStatus = isLocked ? 0 : 1;
            changeLockStatus(userId, newLockStatus);
        });
    });
}

function changeLockStatus(userId, lockStatus) {
    let data = { id: userId, lock_status: lockStatus };
    fetch("http://localhost/final/src/user/update-lock-status.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Failed to update lock status');
            }
        })
        .then(data => {
            window.location.reload()
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating lock status. Please try again later.');
        });
}

function viewStaffDetail() {
    let detailsButton = document.querySelectorAll('.view');
    detailsButton.forEach(btn => {
        btn.addEventListener('click', () => {
            let tr = btn.closest("tr");
            let userId = tr.querySelector('.userId').textContent;

            // Fetch data from the PHP script
            fetch(`http://localhost/final/src/user/view-staff-detail.php?id=${encodeURIComponent(userId)}`)
                .then(response => {
                    console.log(response);
                    // Check if the response is successful
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    // Parse JSON data from the response
                    return response.json();
                })
                .then(data => {
                    const user = data[0];

                    // Populate modal body with user data
                    document.getElementById('showImage').src = "/final/src/uploadImage/" + user.avatar
                    document.getElementById('showName').value = user.fullname;
                    document.getElementById('showEmail').value = user.email;
                    document.getElementById('showRole').value = user.role;
                    document.getElementById('showStatus').value = user.is_active === 0 ? "Chưa kích hoạt" : "Đã kích hoạt";
                })
                .catch(error => {
                    // Handle errors
                    console.error('There was a problem with the fetch operation:', error);
                });

        })
    })
}
