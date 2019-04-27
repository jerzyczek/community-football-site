
var userActions = {
    ajaxCall : function (url, method, data, callback)
    {
        $.ajax({
            type: method || "GET",
            data: data || {},
            url: url,
            success: callback
        });
    },
    getUsers: function () {
        this.ajaxCall( '/user/online', 'GET', null, function (data) {
            $('#usersOnlineSidebar').html(data)
        });
    },
};

$(document).ready(function () {

    userActions.getUsers();
    setInterval(function() {
        userActions.getUsers();
    }, 120000);
});