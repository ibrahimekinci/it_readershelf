

$(document).ready(function () {
    // hide flash msgs
    setTimeout(function () {
        $('.alert:not(.alert-danger)').alert('close');
    }, 5000);
});
