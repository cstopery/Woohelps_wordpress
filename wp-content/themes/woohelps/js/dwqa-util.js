/**
 * Created by mogita on 9/17/16.
 */
"use strict";

var $ = jQuery;
var page = 1;

$(function() {
    var offset = 250;
    var duration = 300;
    var elToTop = $('#toTop');

    $(window).scroll(function() {
        if ($(this).scrollTop() > offset) {
            elToTop.fadeIn(duration);
        }
        else {
            elToTop.fadeOut(duration);
        }
    });

    elToTop.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, duration);

        return false;
    });

    $('#askButton').on('click', function(e) {
        e.preventDefault();

        if (!user_logged_in) {
            $('#loginModal').modal('show');
        }
        else {
            $('#newQuestionModal').modal('show');
        }
    });

    $('#newQuestionModal').on('hidden.bs.modal', function(){
        $(this).find('iframe').html('');
        $(this).find('iframe').attr('src', '');
    });

    $('#answerButton').on('click', function(e) {
        e.preventDefault();

        if (!user_logged_in) {
            $('#loginModal').modal('show');
        }
        else {
            window.location.href = "/dwqa-questions/?filter=unanswered";
        }
    });

    $('#backButton').on('click', function(e) {
        e.preventDefault();
            window.location.href = "/dwqa-questions/";
    });



    $('.subscribe-button').on('click',function(e){
        e.preventDefault();
        var t = $(this);

        if (!user_logged_in) {
            $('#loginModal').modal('show');
            return false;
        }

        if (t.hasClass('processing')) {
            return false;
        }

        t.addClass('processing');

        var data = {
            action: 'dwqa-follow-question',
            nonce: t.data('nonce'),
            post: t.data('post')
        };

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function(data){
                t.removeClass('processing');
                if (data.success && data.success == true) {
                    if (data.data.code == 'unfollowed') {
                        t.html('关注问题');
                    }
                    else if (data.data.code == 'followed') {
                        t.html('已关注');
                    }
                }
            }
        });
    });

    $('#loadMore').on('click', function (e) {
        e.preventDefault();

        var _this = $(this);

        if (_this.hasClass('disabled')) {
            return false;
        }

        _this.addClass('disabled');
        _this.html('正在载入...');

        var newPage = parseInt(page) + 1;

        var param = '';
        if (queryString) {
            console.log(queryString);
            param = '/?' + queryString;
        }

        $.ajax({
            url: currentLocation + 'page/' + newPage + param,
            type: 'GET',
            dataType: 'html',
            success: function(data) {
                var list = $(data).find('.dwqa-questions-list').html();
                if (~list.indexOf('dwqa-question-item')) {
                    $(list).appendTo($('.dwqa-questions-list'));
                    page++;
                    _this.removeClass('disabled');
                    _this.html('载入更多问题');
                }
                else {
                    _this.html('没有更多内容了');
                }
            },
            fail: function() {
                _this.html('没有更多内容了');
            },
            complete: function() {

            }
        });
    })
});

var currentLocation = (window.location.href.split('?')[0] || window.location);
var queryString = (window.location.search.split('?')[1] || '');