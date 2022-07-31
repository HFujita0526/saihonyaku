const GET_LATEST_INTERVAL = 60000;
const GET_OLDER_INTERVAL = 200;

var enabledLoading = true;

$(function () {
    getLatest(-1);
    setInterval(getLatest, GET_LATEST_INTERVAL);

    $(window).scroll(function () {
        if (enabledLoading && parseInt($("#content").data('lastnum')) !== 0 && $(window).scrollTop() + $(window).height() >= $("#scroll-area").get(0).scrollHeight * 0.8) {
            enabledLoading = false;
            setTimeout(function () { enabledLoading = true; }, GET_OLDER_INTERVAL);
            if (parseInt($("#content").data('lastnum')) !== 0) getOlder();
        }
    });
});

function checkResult(result) {
    switch (result['status']) {
        case 200:
            break;

        case 422:
            throw new Error();
            break;

        case 500:
            throw new Error();
            break;
    }
}

function getLatest(latestnum = parseInt($("#content").data('latestnum'))) {
    $.ajax({
	    url: "http://saihonyaku.ga/api/getLatest",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'get',
        data: {
            latestnum: latestnum,
        },
        dataType: 'json',
    }).done(function (result) {
        console.log(result);
        try {
            checkResult(result);
            for (let i = 1; i <= result['content'][0]; i++) {
                if ($("#content").data('latestnum') === undefined) {
                    $("#content").data('latestnum', result['content'][i]['id'] - 1);
                    $("#content").data('lastnum', result['content'][i]['id']);
                }

                if (result['content'][i]['id'] > parseInt($("#content").data('latestnum'))) {
                    if (result['content'][i]['id'] !== 0) appendLatestText('【日本語】\n' + result['content'][i]['text_ja'], '【' + result['content'][i]['lang_name'] + '】\n' + result['content'][i]['text_fo'], result['content'][i]['id']);
                    else appendOldestText('【日本語】\n' + result['content'][i]['text_ja']);
                }
            }
        } catch (ex) {
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("ajax通信に失敗しました");
        console.log("jqXHR          : " + jqXHR.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    });
}

function appendLatestText(textJa, textFo, id) {
    $("#content").data('latestnum', id);
    $("#content").prepend('<div class="d-flex flex-column flex-sm-row mb-4"> <textarea class="form-control sentence" readonly>' + textJa + '</textarea> <div class="d-none d-sm-flex flex-grow-1"></div> <div class="d-flex d-sm-none justify-content-center my-4"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" /> </svg> </div> <textarea class="form-control sentence" readonly>' + textFo + '</textarea> </div> <div class="d-flex justify-content-center mb-4"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" /> </svg> </div>');
}

function getOlder() {
    $(".spinner-border").removeClass('d-none');
    $(".spinner-border").addClass('d-flex');

    $.ajax({
	    url: "http://saihonyaku.ga/api/getOlder",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'get',
        data: {
            lastnum: parseInt($("#content").data('lastnum')),
        },
        dataType: 'json',
    }).done(function (result) {
        console.log(result);
        try {
            checkResult(result);
            for (let i = 1; i <= result['content'][0]; i++) {
                appendOlderText('【日本語】\n' + result['content'][i]['text_ja'], '【' + result['content'][i]['lang_name'] + '】\n' + result['content'][i]['text_fo'], result['content'][i]['id']);
            }
        } catch (ex) {
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("ajax通信に失敗しました");
        console.log("jqXHR          : " + jqXHR.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
    }).always(function () {
        $(".spinner-border").removeClass('d-flex');
        $(".spinner-border").addClass('d-none');
    });
}

function appendOlderText(textJa, textFo, id) {
    $("#content").data('lastnum', id);
    if (id === 0) {
        appendOldestText(textJa);
    } else {
        $("#content").append('<div class="d-flex flex-column flex-sm-row mb-4"> <textarea class="form-control sentence" readonly>' + textJa + '</textarea> <div class="d-none d-sm-flex flex-grow-1"></div> <div class="d-flex d-sm-none justify-content-center my-4"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" /> </svg> </div> <textarea class="form-control sentence" readonly>' + textFo + '</textarea> </div> <div class="d-flex justify-content-center mb-4"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" /> </svg> </div>');
    }
}

function appendOldestText(textJa) {
    $("#content").data('lastnum', 0);
    $("#content").append('<div class="d-flex flex-column flex-sm-row mb-4"> <textarea class="form-control sentence" readonly>' + textJa + '</textarea> <div class="d-none d-sm-flex flex-grow-1"> </div> </div>');
}
