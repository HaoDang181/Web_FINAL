let detailsButton = document.querySelector('.view');
// Get the image element and file input element
const avatar = document.getElementById("showImage");
const imageInput = document.getElementById("imageInput");

// Add click event listener to the image
avatar.addEventListener('click', () => {
    // Trigger click event on file input when image is clicked
    imageInput.click();
});

// Add change event listener to the file input
imageInput.addEventListener('change', () => {
    // Check if any file is selected
    if (imageInput.files && imageInput.files[0]) {
        const reader = new FileReader();
        
        // Read the selected file as Data URL
        reader.readAsDataURL(imageInput.files[0]);
        
        // When file reading is done
        reader.onload = function (e) {
            // Set the src attribute of the image to the Data URL
            avatar.src = e.target.result;
        };
    }
});

detailsButton.addEventListener('click', () => {
    let userId = document.querySelector('.userId').value;

    // Fetch data from the PHP script
    fetch(`http://localhost/final/src/user/view-staff-detail.php?id=${encodeURIComponent(userId)}`)
        .then(response => {
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
