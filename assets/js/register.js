$(document).ready(function() {

    //On click signup, hide login and show registration form
    $("#signup").click(function(){
        $("#login_form").slideUp("slow", function(){
            $("#register_form").slideDown("slow");
        });
    });

    //On click signin, hide registration and show login form
    $("#sign_in").click(function(){
        $("#register_form").slideUp("slow", function(){
            $("#login_form").slideDown("slow");
        });
    });

});

