$(document).ready(function() {
    $('#register-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        // Correct the path to the PHP file
        $.post('../Model/register_admin.php', formData, function(response) {
            alert(response); // Show success or error message

            // If registration is successful, redirect to login page or any other action
            if (response === "Нового адміністратора додано успішно!") {
                window.location.href = "login.html"; // Redirect to login page
            }
        });
    });
});
