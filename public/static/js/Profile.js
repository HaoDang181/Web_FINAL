// Ensure the DOM is fully loaded before attaching event listeners
document.addEventListener("DOMContentLoaded", function () {
    // Select the file input and the image element
    const imageInput = document.getElementById('imageInput');
    const showImage = document.getElementById('showImage');
    const userImage = document.getElementById('userImage');

    // Ensure the showImage src is updated when the modal is shown
    document.getElementById('updateAvatar').addEventListener('show.bs.modal', function () {
        showImage.src = userImage.src;
    });

    // Listen for changes on the file input
    imageInput.addEventListener('change', function () {
        // Check if a file was selected
        if (imageInput.files && imageInput.files[0]) {
            // Create a FileReader to read the selected file
            const reader = new FileReader();


            // When the file is read, set the src of the image to the result
            reader.onload = function (e) {
                showImage.src = e.target.result;
            };

            // Read the selected file as a data URL
            reader.readAsDataURL(imageInput.files[0]);
        }
    });

    // Get the userId value
    let userId = document.querySelector('.userId').value;

    // Fetch data from the PHP script
    fetch(`/final/src/user/view-staff-detail.php?id=${encodeURIComponent(userId)}`)
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
            userImage.src = "/final/src/uploadImage/" + user.avatar;
            document.getElementById('showName').value = user.fullname;
            document.getElementById('showEmail').value = user.email;
            document.getElementById('showRole').value = user.role;
            document.getElementById('showStatus').value = user.is_active === 0 ? "Chưa kích hoạt" : "Đã kích hoạt";
        })
        .catch(error => {
            // Handle errors
            console.error('There was a problem with the fetch operation:', error);
        });
});
