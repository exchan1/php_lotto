$(document).ready(function () {
    $('.btnUpdateBigvoca').hide();
    $(this).on('click', '.btnUpdateBigvoca, .btnInsertBigvoca', function () {
        var param = getData();
        $.post($(this).data('url'), param, function (data) {
            if (200 == data.code) {
                resetFrm();
            } else {
                alert('error');
            }
        });
    });
    $(this).on('click', '.btnDeleteBigvoca', function () {
        var param = { idx: $(this).parent().parent().find('.idx').html() };
        $.post($(this).data('url'), param, function (data) {
            if (200 == data.code) {
                resetFrm();
            } else {
                alert('error');
            }
        });
    });
    $(this).on('click', '.btnEditBigvoca', function () {
        $('#input_eng').val($(this).parent().parent().find('.eng').html());
        $('#input_ko').val($(this).parent().parent().find('.ko').html());
        $('#input_idx').val($(this).parent().parent().find('.idx').html());
        $('.btnInsertBigvoca').hide();
        $('.btnUpdateBigvoca').show();
    });
    $(this).on('click', '.btnGetQuiz', function () {
        $('.quizList>tr').remove();
        $.post($(this).data('url'), function (data) {
            var html = '';
            var d = data.list;
            if (0 == d.length) {
                html += '<tr><td colspan="4">결과가 없습니다.</td></tr>';
            } else {
                for (var i = 0; i < d.length; i++) {
                    html += '<tr>';
                    html += '<td class="idx">' + d[i].idx + '</td>';
                    html += '<td class="eng">' + d[i].eng + '</td>';
                    html += '<td class="ko">' + getKoStr(d[i].ko) + '</td>';
                    html += '<td></td>';
                    html += '</tr>';
                }
            }
            $('.quizList')
                .append(html)
                .find('td, th')
                .css('background-color', '#eee');
        });
    });
    $(this).on('click', '.btnListBigvoca', function () {
        $('.bigvocaList>tr').remove();
        $.post($(this).data('url'), function (d) {
            var html = '';
            if (0 == d.length) {
                html += '<tr><td colspan="4">결과가 없습니다.</td></tr>';
            } else {
                var btnHtml = '<button type="button" class="btn btn-primary btnEditBigvoca">Edit</button>';
                btnHtml += '<button type="button" class="btn btn-danger btnDeleteBigvoca" data-url="/?mode=deleteEng">Del</button>';
                for (var i = 0; i < d.length; i++) {
                    html += '<tr>';
                    html += '<td class="idx">' + d[i].idx + '</td>';
                    html += '<td class="eng">' + d[i].eng + '</td>';
                    html += '<td class="ko">' + getKoStr(d[i].ko) + '</td>';
                    html += '<td class="form-group form-inline">' + btnHtml + '</td>';
                    html += '</tr>';
                }
            }
            $('.bigvocaList')
                .append(html)
                .find('td, th')
                .css('background-color', '#eee');
        });
    });
});

function getData() {
    return {
        'eng': $('#input_eng').val()
        , 'ko': $('#input_ko').val()
        , 'idx': $('#input_idx').val()
    };
}

function resetFrm() {
    $('#input_eng').val('');
    $('#input_ko').val('');
    $('#input_idx').val('');
    $('.btnListBigvoca').trigger('click');
    $('.btnInsertBigvoca').show();
    $('.btnUpdateBigvoca').hide();
}

function getKoStr(str) {
    str = str.split('(').join('<i>(').split(')').join(')</i>');
    return str;
}

/* javascript: ! function() {
    var t, e = document.getElementsByTagName("head")[0];
    try {
        t = document.standardCreateElement("script")
    } catch (t) {}
    "object" != typeof t && (t = document.createElement("script")), t.type = "text/javascript", t.src = "https://code.jquery.com/jquery-3.3.1.min.js", t.id = "jquery_js", e.appendChild(t)
}(), $.ajax({
    url: "https://www.googleapis.com/urlshortener/v1/url?shortUrl=http://goo.gl/fbsS&key=AIzaSyA5xZ3voM6Qg3Fk-c3nD2xYtgIB9DQksas",
    type: "POST",
    contentType: "application/json; charset=utf-8",
    data: '{ longUrl: "' + encodeURI(location.href) + '"}',
    dataType: "json",
    success: function(t) {
        var e = JSON.parse(JSON.stringify(t));
        jQuery.ajax({
            data: JSON.stringify({
                attachments: [{
                    fallback: "Required plain-text summary of the attachment.",
                    color: "#2eb886",
                    pretext: "크롬 북마크에서 전송된 내용",
                    author_name: "linkbot",
                    title: document.title,
                    title_link: location.href,
                    text: e.id,
                    ts: Math.floor((new Date).getTime() / 1e3)
                }]
            }),
            dataType: "json",
            processData: !1,
            type: "POST",
            url: "//hooks.slack.com/services/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"
        })
    }
});



fetch('https://www.googleapis.com/urlshortener/v1/url?shortUrl=http://goo.gl/fbsS&key=AIzaSyA5xZ3voM6Qg3Fk-c3nD2xYtgIB9DQksas', {
    method: 'POST',
    body: '{longUrl:"' + urls + '"}',
    headers: {
        "Content-Type": "application/json"
    }
}).then(function(response) {
    return response.json();
}).then(function(body) {
    console.log(JSON.parse(JSON.stringify(body)).id);
}); */