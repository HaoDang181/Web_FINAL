var form = document.getElementById("myForm");
    imgInput = document.getElementById(".img");
    file = document.getElementById("imgInput");
    fullName = document.getElementById("name");
    email = document.getElementById("email");
    phone = document.getElementById("phone");
    submitBtn = document.querySelector(".submit"),
    userInfo = document.getElementById("data"),
    modalTitle = document.querySelector("#addEmployeeModal .modal-title");
    newUserBtn = document.querySelector(".add")

let getData = localStorage.getItem('userProfile')? JSON.parse(localStorage.getItem('userProfile')) : []
showInfo()

newUserBtn.addEventListener('click', ()=> {
    submitBtn.innerText = 'Submit',
    modalTitle.innerText = "Fill the Form"
    isEdit = false
    imgInput.src = "./image/Profile Icon.webp"
    form.reset()
})

function showInfo(){
    document.querySelectorAll('.employeeDetails').forEach(info => info.remove())
    getData.forEach((element, index) => {
        let createElement = `<tr class="employeeDetails">
            <td><img src="${element.picture}" alt="" width="50" height="50"></td>
            <td>${element.employeeName}</td>
            <td>${element.employeeEmail}</td>
            <td>${element.employeePhone}</td>


            <td>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#viewStaffInfo">
                <ion-icon name="eye-outline"></ion-icon>
                </button>                            
            </td>
        </tr>`

        userInfo.innerHTML += createElement
    })
}
showInfo()

function readInfo(pic, name,email, phone){
    document.querySelector('.showImg').src = pic,
    document.querySelector('#showName').value = name,
    document.querySelector("#showEmail").value = email,
    document.querySelector("#showPhone").value = phone
}
form.addEventListener('submit', (e)=> {
    e.preventDefault()

    const information = {
        picture: imgInput.src == undefined ? "https://i.pinimg.com/564x/ef/5a/77/ef5a778b6fa98a57169609e8244a8bd8.jpg" : imgInput.src,
        employeeName: fullName.value,
        employeeEmail: email.value,
        employeePhone: phone.value
    }

    localStorage.setItem('userProfile', JSON.stringify(getData))

    submitBtn.innerText = "Submit"
    modalTitle.innerHTML = "Fill The Form"

    showInfo()

    form.reset()

    imgInput.src = "https://i.pinimg.com/564x/ef/5a/77/ef5a778b6fa98a57169609e8244a8bd8.jpg"  

    // modal.style.display = "none"
    // document.querySelector(".modal-backdrop").remove()
})