$(document).ready(function() {
    $('.autoscroll').jscroll({
        loadingHtml: '<img src="' + baseUrl +'/public/user/images/loading.gif" alt="Loading" /> Loading...',
        padding: 20,
        nextSelector: 'a.jscroll-next:last',
        callback: function() {
            setTimeout(initPopupDetail, 500);
        }
    });
})