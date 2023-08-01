$(document).ready(function() {
    $('#login-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.post('../Model/login.php', formData, function(response) {
            if (response === "success") {
                window.location.href = "main_admin.html";
            } else {
                alert(response); // Show error message
            }
        });
    });
});

