
var post = {
    ajaxCall : function (url, method, data, callback)
    {
        $.ajax({
            type: method || "GET",
            data: data || {},
            url: url,
            success: callback
        });
    },
    toggle: function (element) {
        element.toggle('slow');
    },
    addComment: function (element, event) {
        if(event.which == 13 && !event.shiftKey)
        {
            event.preventDefault();
            var value = jQuery.trim($(element).val());
            if(value === '')
            {
                return;
            }
            var postid = $(element).closest('.postItem').data('postid');
            var groupid = $(element).closest('.groupItem').data('groupid');
            var url = '/group/' + groupid + '/post/' + postid + '/comment/newAjax';

            this.ajaxCall(url, 'POST', {content: $(element).val()}, function (data) {
                $(element).closest('.postItem').find(".comments-list").append(data);
            });
        }
    },
    generateGroupView: function (element) {
        var groupId = $(element).data('groupid');

        this.ajaxCall("group/"+groupId+"/groupView", false, false, function (data) {
            $('#mainView').html(data);
        });
    },
    likeComment: function (element) {

        var commentid = element.closest('.comment').data('commentid');
        var url = '/comment/' + commentid + '/like';
        this.ajaxCall(url, 'POST', {id: commentid}, function (data) {
            $('#commentid' + commentid).html(data);
        });

    },
    unlikeComment: function (element) {

        var commentid = element.closest('.comment').data('commentid');
        var url = '/comment/' + commentid + '/unlike';
        this.ajaxCall(url, 'POST', {id: commentid}, function (data) {
            $('#commentid' + commentid).html(data);
        });
    },
    deleteComment: function (element) {
        var formid = $(element).data('formid');
        if(!formid)
        {
            return;
        }
        var commentid = $("#"+ formid + ' input[name="commentid"]').val();
        var callData = $("#"+ formid).serializeArray();

        this.ajaxCall('/comment/' + commentid + "/ajax", "DELETE", callData, function (data) {
            $('#commentid' + commentid).remove();
        });



    },
    generateLastCommentGroupView: function (element) {
        var postId = $(element).data('postid');
        var groupId = $(element).data('groupid');

        this.ajaxCall("group/"+groupId+"/post/"+postId+"/postView", false, false, function (data) {
            //$('#mainView').html(data);
        });
    },
    addPost: function (element, event) {
        var data = $(element).serializeArray();
        console.log(data);
        data.push({name: "isAjax", value: true});

        var groupid = $(element).find('button[type="submit"]').data('groupid');
        this.ajaxCall("group/" + groupid + "/post/new", 'POST', data, function (data) {
            $('.groupItem').prepend(data);
            $("#addPostModal").modal('toggle');
        });
    },
    showMoreComments: function (element, event) {
        var element = $(element);
        element.closest('.postItem').find('.extraComment').toggle('slow');
        element.remove();
        // var action = element.data('action');
        // var value = 'show';
        // var text = 'Show more';
        // if(action == 'show')
        // {
        //     value = 'hide';
        //     text = 'Hide More';
        // }
        // element.text(text)
        // element.data('action', value);
    },
    toggleMorePostContent: function (element, event) {
        $(element).parents(':first').find('.morePostContent').toggle();
        $(element).parents(':first').find('.dots').remove();
        $(element).remove();
    },
    likePost: function (element) {

        var postid = element.closest('.postItem').data('postid');
        var url = '/post/' + postid + '/like';
        this.ajaxCall(url, 'POST', {id: postid}, function (data) {

            $(element).removeClass('likePost');
            $(element).addClass('unlikePost');
            $(element).children('i:first').removeClass('fa-thumbs-up');
            $(element).children('i:first').addClass('fa-thumbs-down');
            $(element).children('span:first').text('Unlike it');
            $(element).next('b').text(parseInt($(element).next('b').text()) + 1);
        });

    },
    unlikePost: function (element) {

        var postid = element.closest('.postItem').data('postid');
        var url = '/post/' + postid + '/unlike';
        this.ajaxCall(url, 'POST', {id: postid}, function (data) {

            $(element).removeClass('unlikePost');
            $(element).addClass('likePost');

            $(element).children('i:first').removeClass('fa-thumbs-down');
            $(element).children('i:first').addClass('fa-thumbs-up');

            $(element).children('span:first').text('Like it');
            $(element).next('b').text(parseInt($(element).next('b').text()) - 1);
        });
    },
};

$(document).on('click', 'a.groupViewLink', function (event) {
    post.generateGroupView(this)
});

$(document).on('keypress', '.commentField', function (event) {
    post.addComment(this, event);
});

$(document).on('click', 'a.likeComment', function (event) {
    event.preventDefault();
    post.likeComment($(this));
});

$(document).on('click', 'a.unlikeComment', function (event) {
    event.preventDefault();
    post.unlikeComment($(this));
});

$(document).on('click', 'a.responseLink', function (event) {
    event.preventDefault();
    var commentResponse = $(this).parents('.comment').children('.commentResponse');
    post.toggle(commentResponse);
});

$(document).on('click', 'a.commentDelete', function (event) {
    $('input[name="commentid"]').val($(this).data('commentid'));
});

$(document).on('click', 'a.commentDelete', function (event) {
    $('input[name="commentid"]').val($(this).data('commentid'));
    $('input[name="_token"]').val($(this).data('commenttoken'));
});

$(document).on('click', '.modal button[type="submit"]', function () {
    post.deleteComment(this);
});

$(document).on('click', '.showMoreComments', function (event) {
    event.preventDefault();
    post.showMoreComments(this, event);
});

$(document).on('click', 'a.lastCommentViewLink', function (event) {
    post.generateLastCommentGroupView(this)
});

$(document).on('submit', 'form[name="post"]', function (event) {
    event.preventDefault();
    post.addPost(this, event);
});

$(document).on('click', '.morePostContentToggle', function (event) {
    event.preventDefault();
    post.toggleMorePostContent(this, event);
});

$(document).on('click', 'a.likePost', function (event) {
    event.preventDefault();
    post.likePost($(this));
});

$(document).on('click', 'a.unlikePost', function (event) {
    event.preventDefault();
    post.unlikePost($(this));
});


