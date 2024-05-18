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
    fetch('/final/src/user/view-staff-list.php')
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
            handleResendEmail();
            viewStaffDetail();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching data. Please try again later.');
        });
}

function handleResendEmail() {
    document.querySelectorAll('.resend').forEach(btn => {
        btn.addEventListener('click', () => {
            const tr = btn.closest('tr');
            const id = tr.querySelector('.userId').textContent
            fetch("/final/src/account/resend-email.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            })
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        throw new Error('Failed to update lock status');
                    }
                })
                
                .catch(error => {
                    console.error('Error:', error);

                });
        })
    })
}

function viewStaffList(data) {
    let tbody = document.getElementById('data');
    tbody.innerHTML = "";
    data.forEach(user => {
        let tr = document.createElement('tr');
        let activeStatus = user.is_active === 0 ? `<span class="badge rounded-pill text-bg-danger p-2">Chưa kích hoạt</span>` : `<span class="badge rounded-pill text-bg-info p-2">Đã kích hoạt</span>`;
        tr.innerHTML = `
            <td class="userId">${user.id}</td>
            <td>
                <img src="/final/src/uploadImage/${user.avatar}" alt="Avatar" width="50" height="50">
            </td>
            <td>${user.fullname}</td>
            <td>${user.email}</td>
            <td>${activeStatus}</td>
            <td>
                <div>
                    <button type="button" class="btn btn-lg view" data-bs-toggle="modal" data-bs-target="#viewStaffInfo">
                        <i class="fa-solid fa-eye"></i> 
                    </button>
                    ${user.is_lock === 0 ?
                '<button type="button" class="btn btn-lg lock open"><i class="fa-solid fa-unlock"></i></button>' :
                '<button type="button" class="btn btn-lg lock closed"><i class="fa-solid fa-user-lock"></i></button>'}
                    ${user.is_active === 0 ? '<button type="button" class="btn btn-lg resend"><i class="fa-solid fa-paper-plane"></i></button>' : ''}  
                </div>
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
            let isLocked = button.classList.contains('closed');
            let newLockStatus = isLocked ? 0 : 1;
            changeLockStatus(userId, newLockStatus);
        });
    });
}

function changeLockStatus(userId, lockStatus) {
    let data = { id: userId, lock_status: lockStatus };
    fetch("/final/src/user/update-lock-status.php", {
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
            fetch(`/final/src/user/view-staff-detail.php?id=${encodeURIComponent(userId)}`)
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
