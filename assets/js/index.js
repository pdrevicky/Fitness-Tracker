function editProfileUserInfo(){
    var hiddeAddButtonAge = document.getElementById('profile_user_info');
    hiddeAddButtonAge.classList.add('hidden');
    var showFormDateOfBirth = document.getElementById('profile_user_info_edit');
    showFormDateOfBirth.classList.remove('hidden');
}

$(document).ready(function() {
    $("#messagesInputText").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#messageInputbutton").click();
        }
    });
});


$(document).ready(function() {
    $('#search_user_input').keyup(function(){
        var query = $(this).val();   //value of textbox from input:search_user_input
        if(query != ''){     // if value is not empty
            $.ajax({
                url:"includes/handlers/search_friend_handler.php",
                method:"POST",
                data:{query:query},
                success:function(data){
                    $('#usernameList').fadeIn();
                    $('#usernameList').html(data);
                }
            })
        } 
    });
    //if user click on choosed user other users dissapear
    $(document).on('click', 'li' ,function(){
        $('#search_user_input').val($(this).text());
        $('#usernameList').fadeOut();
    });
});

$(document).ready(function() {
    var objDiv = document.getElementById("messageBox");
    objDiv.scrollTop = objDiv.scrollHeight;
});


   

