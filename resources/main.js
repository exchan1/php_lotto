
$(document).ready(function () {
    $('[name="kai"]').val($('#kai').val());

    $('.btnSlackTest').on('click', function () {
        //$.get('/welcome/slacktest');
        $.ajax({
            type: 'post',
            url: '/?mode=slacktest',
            dataType: 'json',
            data: { msg: $('#slackMsg').val() }
        }).done(function (data) {
            console.log(data);
        });
    });

    $('.btnRecommend, .btnLottoNo, .btnLottoBomb').on('click', function () {
        var url = $(this).data('url');
        $.get(url, function () {
            $('.btnRecommendList').trigger('click');
        });
    });

    $('.btnBombTest').on('click', function () {
        var url = $(this).data('url');
        $.get(url, function () {});
    });

    $('.btnRecommendas').on('click', function () {
        var url = $(this).data('url') + '&kai=' + $('#slackMsg').val();
        $.get(url, function (data) {
            $('.rec_list').remove();
            var html = '', tb = '<ul class="list-group">', re_str = '', liClass = '', re_arr=[];
            var rehtml = '<b>회차</b> : '+$('#slackMsg').val();
            re_str = $.map(data.result[0], function(i,v) { return i; }).join(',');
            re_arr = re_str.split(',');
            rehtml += '<br/><b>List</b> : '+data.keys.join(',');
            rehtml += '<br/><b>Result</b> : '+re_str;
            $.each(data.nums, function(k,v) {
                liClass = ($.inArray(k, re_arr)!=-1) ? 'list-group-item-success' : '';
                tb += '<li class="list-group-item '+liClass+'">'+k+' / '+v+'</li>';
            });
            tb += '</ul>';
            html += '<tr class="rec_list"><td colspan="7">'+rehtml+tb+'</td></tr>';
            $('.table').append(html);
            $('.rec_list').children('td, th').css('background-color', '#eee');
        });
    });

    $('.btnLottoDel').on('click', function () {
        setNextLno();
        var url = $(this).data('url') + '&kai=' + $('#slackMsg').val();
        $.get(url, function () {
            $('.btnRecommendList').trigger('click');
        });
    });

    $('.lottoNodel').on('click', function () {
        // setLastLno();
        var url = $(this).data('url') + '&kai=' + $('#slackMsg').val();
        $.get(url, function () {
            $('.btnRecommendList').trigger('click');
        });
    });

    $('.btnRecommendList').on('click', function () {
        var nextNo = $.trim($('#slackMsg').val());
        if (0 == nextNo.length) setNextLno();
        var url = $(this).data('url') + '&kai=' + $('#slackMsg').val();
        var html = '';
        $('.rec_list').remove();
        $.get(url, function (d) {
            if (0 == d.length) {
                html += '<tr class="rec_list"><td colspan="7">결과가 없습니다.</td></tr>';
            } else {
                for (var i = 0; i < d.length; i++) {
                    html += '<tr class="rec_list">';
                    html += '<td>' + d[i].n1 + '</td>';
                    html += '<td>' + d[i].n2 + '</td>';
                    html += '<td>' + d[i].n3 + '</td>';
                    html += '<td>' + d[i].n4 + '</td>';
                    html += '<td>' + d[i].n5 + '</td>';
                    html += '<td>' + d[i].n6 + '</td>';
                    html += '<td>' + d[i].lno + ' 회차 추천</td>';
                    html += '</tr>';
                }
            }
            $('.table').append(html);
            $('.rec_list').children('td, th').css('background-color', '#eee');
        });
    });

    $('.btnMessageSet').on('click', function () {
        $('#slackMsg').val(($('#kaiList option:selected').val() * 1));
    });

    $('.btnBigvoca').on('click', function () {
        var url = $(this).data('url') + '&kai=' + $('#slackMsg').val();
        $.get(url, function (d) {
            console.log(d);
        });
    });

    $('.btnHan').on('click', function () {
        var url = $(this).data('url') + '&kai=' + $('#slackMsg').val();
        $.get(url, function (d) {
            console.log(d);
        });
    });
});

function setNextLno() {
    $('#slackMsg').val(($('#kaiList option:first').val() * 1) + 1);
}

function setLastLno() {
    $('#slackMsg').val(($('#kaiList option:first').val() * 1));
}