import './bootstrap';

import * as bootstrap from 'bootstrap';

window.addEventListener('action-modal', event => {
    console.log(event);
    $('#'+event.detail.id).modal('toggle')
});

function showMessage(msg, type) {
    $("#snackbar").html(msg);
    var x = document.getElementById("snackbar");
    x.className = "show snackbar-"+type;
    setTimeout(function() {
        x.className = x.className.replace("show snackbar-"+type, "");
    }, 3000);
}

window.addEventListener('show-message', event => {
    showMessage(event.detail.message, event.detail.type)
});
