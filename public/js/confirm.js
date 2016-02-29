/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function deletegroup(groupID, text) {
    var ans = confirm(document.getElementById('are_you_sure').value);
    if (ans == true) {
        window.location.href = document.getElementById('base_url').value + '/managegroups/deletegroup/g/' + groupID;
    } 
}