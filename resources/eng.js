$(document).ready(function(){
    $('.btnUpdateBigvoca').hide();
    $(this).on('click', '.btnUpdateBigvoca, .btnInsertBigvoca', function(){
        var param = getData();
        $.post($(this).data('url'), param, function(data){
            if (200==data.code) {
                resetFrm();
            } else {
                alert('error');
            }
        });
    });
    $(this).on('click', '.btnDeleteBigvoca', function(){
        var param = { idx : $(this).parent().parent().find('.idx').html() };
        $.post($(this).data('url'), param, function(data){
            if (200==data.code) {
                resetFrm();
            } else {
                alert('error');
            }
        });
    });
    $(this).on('click', '.btnEditBigvoca', function(){
        $('#input_eng').val($(this).parent().parent().find('.eng').html());
        $('#input_ko').val($(this).parent().parent().find('.ko').html());
        $('#input_idx').val($(this).parent().parent().find('.idx').html());
        $('.btnInsertBigvoca').hide();
        $('.btnUpdateBigvoca').show();
    });
    $(this).on('click', '.btnGetQuiz', function(){
        $('.quizList>tr').remove();
        $.post($(this).data('url'), function(data){
            var html = '';
            var d = data.list;
            if (0==d.length) {
                html += '<tr><td colspan="4">결과가 없습니다.</td></tr>';
            } else {
                for (var i=0 ; i < d.length ; i++) {
                    html += '<tr>';
                    html += '<td class="idx">'+d[i].idx+'</td>';
                    html += '<td class="eng">'+d[i].eng+'</td>';
                    html += '<td class="ko">'+d[i].ko+'</td>';
                    html += '<td></td>';
                    html += '</tr>';
                }
            }
            $('.quizList')
                .append(html)
                .find('td, th')
                .css('background-color','#eee');
        });
    });
    $(this).on('click', '.btnListBigvoca', function(){
        $('.bigvocaList>tr').remove();
        $.post($(this).data('url'), function(d){
            var html = '';
            if (0==d.length) {
                html += '<tr><td colspan="4">결과가 없습니다.</td></tr>';
            } else {
                var btnHtml = '<button type="button" class="btn btn-primary btnEditBigvoca">Edit</button>';
                btnHtml += '<button type="button" class="btn btn-danger btnDeleteBigvoca" data-url="/?mode=deleteEng">Del</button>';
                for (var i=0 ; i < d.length ; i++) {
                    html += '<tr>';
                    html += '<td class="idx">'+d[i].idx+'</td>';
                    html += '<td class="eng">'+d[i].eng+'</td>';
                    html += '<td class="ko">'+d[i].ko+'</td>';
                    html += '<td class="form-group form-inline">'+btnHtml+'</td>';
                    html += '</tr>';
                }
            }
            $('.bigvocaList')
                .append(html)
                .find('td, th')
                .css('background-color','#eee');
        });
    });
});

function getData() {
    return {
        'eng' : $('#input_eng').val()
        ,'ko' : $('#input_ko').val()
        ,'idx' : $('#input_idx').val()
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