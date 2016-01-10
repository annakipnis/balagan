/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
window.onload = function () {
    var button = document.getElementById('button');
    if (button) {
        button.onclick = function () {
            document.getElementById('modal').style.display = "none";
            window.location.href = document.getElementById('base_url').value + "/groups";
        };
    }
};
