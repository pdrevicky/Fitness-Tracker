// <!-- Author: Peter Drevicky 2019 -->
// <!-- License: MIT -->

// if enter is pressed send message
$(document).ready(function() {
    $("#messages_input_text").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#messages_input_button").click();
        }
    });
});

// suggest users based on searched input
$(document).ready(function() {
    $('#search_user_input').keyup(function() {
        var query = $(this).val(); //value of textbox from input:search_user_input
        if (query != '') { // if value is not empty
            $.ajax({
                url: "includes/handlers/search_friend_handler.php",
                method: "POST",
                data: { query: query },
                success: function(data) {
                    $('#username_list').fadeIn();
                    $('#username_list').html(data);
                }
            })
        }
    });
    // if user click on choosed user other users dissapear
    $(document).on('click', 'li', function() {
        $('#search_user_input').val($(this).text());
        $('#username_list').fadeOut();
    });
});

// scroll down to 
$(document).ready(function() {
    var objDiv = document.getElementById("message_box");
    objDiv.scrollTop = objDiv.scrollHeight;
});