// <!-- Author: Peter Drevicky 2019 -->
// <!-- License: MIT -->

$(document).ready(function() {
    // on click signup, hide login and show registration form
    $("#signup").click(function() {
        $("#login_form").slideUp("slow", function() {
            $("#register_form").slideDown("slow");
        });
    });
    // on click signin, hide registration and show login form
    $("#sign_in").click(function() {
        $("#register_form").slideUp("slow", function() {
            $("#login_form").slideDown("slow");
        });
    });

});

// after user press register button show input error or registration success
function showRegisterForm() {
    $(document).ready(function() {
        $("#login_form").hide();
        $("#register_form").show();
    });
}