/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
window.onload = function () {
    $('#largeModal').modal();

//    var button = document.getElementById('button');
//    if (button) {
//        button.onclick = function () {
//            document.getElementById('modal').style.display = "none";
//            window.location.href = document.getElementById('base_url').value + "/groups/groups";
//        };
//    }
};

function closeDialog () {
    window.location.href = document.getElementById('base_url').value + "/groups/groups";
}

function nextActivity(groupID, recommendation){
    var notes = $('#groupNotes').val();
    var base_url = $('#baseurl').val();
    var data = {g:groupID, recommend:recommendation, notes:notes};
    $.ajax({
        type: 'POST',
        url: base_url + '/documentation/students',
        async: false,
        data: data,
        success: function(result) {
            if (recommendation == '0') {
                window.location.href = base_url + "/groups/groups";
            } else {
               //return parameters to view, for popup
                var data = JSON.parse(result);
                $("#recommended_game").text(data.recommended_game);
                $("#recommended_target").text(data.recommended_target);
                $("#recommendation").text(data.recommendation);
                if (data.continue_childrenless == true) {
                    var ans = confirm(document.getElementById('contiue_childrenless').value);
                    if (ans == true) {
                        $('#modal').modal();
                    } 
                }
                else {
                        $('#modal').modal();
                }
            }
        }
    });
}