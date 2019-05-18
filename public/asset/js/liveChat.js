
var liveChat = {

    lastUserId: null,
    chatMessagesHtmlId: 'chatView',
    chatBoxHtmlId: 'chatBox',
    refreshing: false,

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
            var data = {
                message: $(element).val(),
                chatid: chatBox.data('chatid'),
                targetuserid: chatBox.data('targetuserid'),
            };
            self = this;
            this.ajaxCall("/chat/message/add", 'POST', data, function (data) {
                self.refresh();
                $(element).val('');
            });
        }
    },
    getHistory: function (element, event) {
        var self = this;

        self.lastUserId = $(element).data('userid');
        var data = {
            userId: self.lastUserId,
            chatId: $(element).data('chatid')
        };

        this.ajaxCall( '/chat/history', 'GET', data, function (data) {
            var sideBar = $('#asideBarRight');
            if(sideBar.find('div[id="chatBox"]').length > 0)
            {
                $('#chatBox').remove();
            }
            sideBar.append(data);
            self.refresh(event);
            self.scrollToBottom(event);
            self.refreshing = true;
        });

    },
    refresh: function (event) {
        self = this;
        var chatid = $('#'+ self.chatMessagesHtmlId).data('chatid')
        var data = {
            chatid: chatid
        };

        this.ajaxCall( '/chat/refresh', 'GET', data, function (data) {
            $('#'+ self.chatMessagesHtmlId).html(data);
            self.scrollToBottom(event);
        });


    },
    scrollToBottom: function (event) {
        var self = this;
        var chatView = $('#'+ self.chatMessagesHtmlId);
        chatView.scrollTop(chatView.prop("scrollHeight"));
    },
    // loadingOnScroll: function () {} //futureva
    closeChatBox: function (element, event) {
        var self = this;
        this.lastUserId = null;
        this.refreshing = false;
        $('#'+ self.chatBoxHtmlId).remove();
    },
    addChat: function (form, event) {

        var data = $(form).serializeArray();

        this.ajaxCall("/chat/createNew", 'POST', data, function (data) {
            $('#chats').append(data);
        });
    }
};
$(document).on('click', '.sidebarUser', function (event) {

    liveChat.getHistory(this, event);
    setInterval(function() {
        if(liveChat.refreshing === true)
        {
            liveChat.refresh(event);
        }
    }, 10000);
});

$(document).on('keypress', '.chatMessage', function (event) {
    liveChat.addMessage(this, event);
});

$(document).on('click', '.closeChatbox', function (event) {
    liveChat.closeChatBox(this, event)
});

$(document).on('click', '#createChatModal button[type="submit"]', function (event) {

   event.preventDefault();
   liveChat.addChat($('form[name="chat"]'), event);
});

