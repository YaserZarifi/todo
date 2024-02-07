// AJAX form submission for updating profile
document.addEventListener('DOMContentLoaded', function() {
    var updateProfileForm = document.getElementById('updateProfileForm');

    updateProfileForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        var formData = new FormData(updateProfileForm); // Serialize form data

        // Send AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_profile.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Handle successful response
                    console.log(xhr.responseText);
                    // Optionally, update the UI or perform other actions
                } else {
                    // Handle error response
                    console.error('Error: ' + xhr.status);
                }
            }
        };
        xhr.send(formData); // Send form data
    });
});
