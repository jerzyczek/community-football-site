
var liveChat = {

    ajaxCall : function (url, method, data, callback)
    {
        $.ajax({
            type: method || "GET",
            data: data || {},
            url: url,
            success: callback
        });
    },
    addMessage: function (element, event) {
        if(event.which == 13 && !event.shiftKey)
        {
            event.preventDefault();
            var value = jQuery.trim($(element).val());
            if(value === '')
            {
                return;
            }
            var chatBox = $(element).prev('.chatView');

            var chatid = chatBox.data('chatid');

            var data = {
                chatid: chatid,
                message: $(element).val()
            };
            self = this;
            this.ajaxCall("/chat/message/add", 'POST', data, function (data) {
                self.getHistory(data.userid);
            });

        }
    },
    getHistory: function (userid, event) {
        var data = {
            userId: userid
        };

        this.ajaxCall( '/chat/history', 'GET', data, function (data) {
            var sideBar = $('#asideBarRight');
            if(sideBar.find('div[id="chatBox"]').length > 0)
            {
                $('#chatBox').remove();
            }
            sideBar.append(data);
        });
    },
};
$(document).on('click', '.sidebarUser', function (event) {
    liveChat.getHistory($(this).data('userid'), event);
});

$(document).on('keypress', '.chatMessage', function (event) {
    liveChat.addMessage(this, event);
});
