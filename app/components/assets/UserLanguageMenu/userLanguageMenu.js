$(document).ready(function() {
    $('body').on('click', '#user-languages li a', function() {
        var userLang = $(this).attr('data-user-lang');
        var date = new Date();
        date.setDate(date.getDate() + 365);
        document.cookie = userLanguages.cookieName + '=' + userLang + '; path=/; domain=' + userLanguages.cookieDomain + '; expires=' + date.toUTCString();
        window.location.reload();
        return false;
    });
});
