
var liveChat = {

    lastUserId: null,

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
                message: $(element).val(),
                targetuserid: chatBox.data('targetuserid'),
            };
            self = this;
            this.ajaxCall("/chat/message/add", 'POST', data, function (data) {
                self.getHistory(data.targetuserid);
            });

        }
    },
    getHistory: function (event) {
        var data = {
            userId: this.lastUserId
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
    liveChat.lastUserId = $(this).data('userid');

    liveChat.getHistory(event);
    setInterval(function() {
        liveChat.getHistory();
    }, 1000);

});

$(document).on('keypress', '.chatMessage', function (event) {
    liveChat.addMessage(this, event);
});

