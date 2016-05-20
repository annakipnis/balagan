/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function deletegroup(groupID) {
    var ans = confirm(document.getElementById('are_you_sure').value);
    if (ans == true) {
        window.location.href = document.getElementById('base_url').value + '/managegroups/deletegroup/g/' + groupID;
    } 
}

function deletestudent(studentID) {
    var ans = confirm(document.getElementById('are_you_sure').value);
    if (ans == true) {
        window.location.href = document.getElementById('base_url').value + '/managestudents/deletestudent/s/' + studentID;
    } 
}

function deletestudentingroup(studentID, groupID) {
    var ans = confirm(document.getElementById('are_you_sure').value);
    if (ans == true) {
        window.location.href = document.getElementById('base_url').value + '/managegroups/deletestudent/s/' + studentID + '/g/' + groupID;
    } 
}

function deleteuser(userID, ganID) {
    var ans = confirm(document.getElementById('are_you_sure').value);
    if (ans == true) {
        window.location.href = document.getElementById('base_url').value + '/admin/deleteuser/g/'+ ganID +'/u/' + userID;
    } 
}

function deletegan(ganID) {
    var ans = confirm(document.getElementById('are_you_sure').value);
    if (ans == true) {
        window.location.href = document.getElementById('base_url').value + '/admin/deletegan/g/'+ ganID;
    } 
}

function deletegoal(goalID) {
    var ans = confirm(document.getElementById('are_you_sure').value);
    if (ans == true) {
        window.location.href = document.getElementById('base_url').value + '/admin/deletegoal/g/'+ goalID;
    } 
}

function deletefield (fieldID) {
    var ans = confirm(document.getElementById('are_you_sure').value);
    if (ans == true) {
        window.location.href = document.getElementById('base_url').value + '/admin/deletefield/f/'+ fieldID;
    }
}

function deletesubgoal(goalID) {
    var ans = confirm(document.getElementById('are_you_sure').value);
    if (ans == true) {
        window.location.href = document.getElementById('base_url').value + '/admin/deletesubgoal/g/'+ goalID;
    } 
}

function deletegame(gameID) {
    var ans = confirm(document.getElementById('are_you_sure').value);
    if (ans == true) {
        window.location.href = document.getElementById('base_url').value + '/admin/deletegame/g/'+ gameID;
    } 
}

function copygroups (text) {
    var ans = confirm(text);
    if (ans == true) {
        window.location.href = document.getElementById('base_url').value + '/managegroups/copygroups/';
    } 
}