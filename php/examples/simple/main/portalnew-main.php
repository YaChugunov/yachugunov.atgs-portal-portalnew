<?php
#
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ###
#
; ?>
<script type="text/javascript" language="javascript" class="">
//
var reqField_getCurrentDinner = {
    currentDinner: function(response) {
        $('#currentDinner').empty();
        if (checkVal(response)) {
            $('#currentDinner').html(response);
            //
        }
    }
};

function ajaxRequest_getCurrentDinner(responseHandler) {
    request_getCurrentDinner = $.ajax({
        type: "post",
        url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/process/ajaxrequests/ajaxReq-getCurrentDinner.php',
        cache: false,
        data: {},
        success: reqField_getCurrentDinner[responseHandler]
    });
    // Callback handler that will be called on success
    request_getCurrentDinner.done(function(response, textStatus, jqXHR) {
        res = response.replace(new RegExp("\\r?\\n", "g"), "");
        $('#QR-currentDinner').popover('dispose');
        console.log('currentDinner loaded');
        setTimeout(function() {
            $('#QR-currentDinner').popover({
                container: 'body',
                placement: 'right',
                trigger: 'hover',
                html: true,
                content: '<div class="text-center">Наведите смартфон на QR-код, откройте ссылку и вы увидите ваш сегодняшний обед на экране смартфона в красивом оформлении. Без регистрации и СМС!</div>'
            });
            $('#currentDinner .digit').popover({
                container: 'body',
                placement: 'top',
                trigger: 'hover',
                html: true,
            });
        }, 200);

    });
    request_getCurrentDinner.fail(function(jqXHR, textStatus, errorThrown) {
        console.error(
            "The following error occurred: " +
            textStatus, errorThrown
        );
    });
    // Callback handler that will be called regardless
    // if the request_addItem failed or succeeded
    request_getCurrentDinner.always(function() {});
}
//
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
//
var reqField_getWorkStatus = {
    currentAbsence: function(response) {
        $('div.staffAbsence-out').empty();
        if (checkVal(response)) {
            workstatus = response.split("<<+>>");
            $('div.staffAbsence-out').html(workstatus[0]);
            setTimeout(function() {
                if (workstatus[1] > 3 && !$('#portalmain-workStatus .enlarge').hasClass("on")) {
                    $('#portalmain-workStatus .enlarge').click();
                }
                setTimeout(function() {
                    if ($('#portalmain-workStatus .enlarge').hasClass("on")) {
                        $('#portalmain-workStatus .enlarged-state-msg').html(
                            '<span class="" style=""><sup>*</sup>Найдено более 3-х записей о рабочем статусе, поэтому блок выведен сразу в развернутом виде. Чтобы свернуть его используйте соответствующую иконку в правом нижнем углу блока.</span>'
                        );
                    }
                }, 500);
            }, 1000);
        }
    }
};

function ajaxRequest_getWorkStatus(responseHandler) {
    request_getWorkStatus = $.ajax({
        type: "post",
        url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/process/ajaxrequests/ajaxReq-getWorkStatus.php',
        cache: false,
        data: {},
        success: reqField_getWorkStatus[responseHandler]
    });
    // Callback handler that will be called on success
    request_getWorkStatus.done(function(response, textStatus, jqXHR) {
        res = response.replace(new RegExp("\\r?\\n", "g"), "");
        $('div.staffAbsence-out *[data-toggle="popover"]').popover('dispose');
        setTimeout(function() {
            var userid = "<?php echo $_SESSION['id']; ?>";
            $('div.staffAbsence-out *[data-toggle="popover"]').popover({
                container: 'body',
                placement: 'top',
                trigger: 'hover',
                html: true,
            });

            $("#portalmain-workStatus .reportStatus")
                .on('click', function(e) {
                    let reportid = $(this).attr("data-reportid");
                    $.ajax({
                        url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/process/ajaxrequests/ajaxReq-getWorkStatusByID.php',
                        method: 'post',
                        dataType: 'json',
                        data: {
                            reportid: reportid
                        },
                        success: function(data) {
                            if (userid == data.userID) {
                                console.log(data);
                                console.log(checkVal(data.dateFrom), checkVal(data
                                    .dateTo));
                                datefrom = checkVal(data.dateFrom) ? moment(data
                                        .dateFrom, "YYYY-MM-DD").format("YYYY-MM-DD") :
                                    '';
                                dateto = checkVal(data.dateTo) ? moment(data.dateTo,
                                    "YYYY-MM-DD").format("YYYY-MM-DD") : '';
                                $('#modal-workStatus *[name="reportID"]').val(data
                                    .reportID);
                                $('#modal-workStatus *[name="userID"]').val(userid);
                                $('#modal-workStatus *[name="action"]').val('edit');
                                $('#modal-workStatus *[name="reportType"]').val(data
                                    .reportType);
                                $('#modal-workStatus *[name="reportOption"]').val(data
                                    .reportOption);
                                $('#modal-workStatus *[name="dateFrom"]').val(datefrom);
                                $('#modal-workStatus *[name="dateTo"]').val(dateto);
                                $('#modal-workStatus *[name="comment"]').val(data
                                    .comment);

                                reportOption = data.reportOption;
                                switch (reportOption) {
                                    case "1":
                                        $('#modal-workStatus input[name="dateFrom"]')
                                            .prop(
                                                "disabled", true);
                                        $('#modal-workStatus input[name="dateTo"]')
                                            .prop(
                                                "disabled", true);
                                        break;
                                    case "2":
                                        $('#modal-workStatus input[name="dateFrom"]')
                                            .prop(
                                                "disabled", true);
                                        $('#modal-workStatus input[name="dateTo"]')
                                            .prop(
                                                "disabled", true);
                                        break;
                                    case "3":
                                        $('#modal-workStatus input[name="dateFrom"]')
                                            .prop(
                                                "disabled", false);
                                        $('#modal-workStatus input[name="dateTo"]')
                                            .prop(
                                                "disabled", true);
                                        break;
                                    case "4":
                                        $('#modal-workStatus input[name="dateFrom"]')
                                            .prop(
                                                "disabled", false);
                                        $('#modal-workStatus input[name="dateTo"]')
                                            .prop(
                                                "disabled", false);
                                        break;
                                    default:
                                }
                                $('#modal-workStatus').modal('show');
                            }
                        }
                    });

                });

            $("#portalmain-workStatus .workstatus-btn.btn2")
                .on('click', function(e) {
                    $('#modal-workStatus *[name="reportID"]').val('');
                    $('#modal-workStatus *[name="userID"]').val(userid);
                    $('#modal-workStatus *[name="action"]').val('new');
                    $('#modal-workStatus *[name="reportType"]').val('');
                    $('#modal-workStatus *[name="reportOption"]').val('');
                    $('#modal-workStatus *[name="dateFrom"]').val('');
                    $('#modal-workStatus *[name="dateTo"]').val('');
                    $('#modal-workStatus *[name="comment"]').val('');
                    $('#modal-workStatus').modal('show');
                });

            $('#modal-workStatus select[name="reportOption"]').on("change", function() {
                reportOption = $(this).val();
                switch (reportOption) {
                    case "1":
                        datefrom = moment().format("YYYY-MM-DD");
                        dateto = '';
                        $('#modal-workStatus input[name="dateFrom"]').val(datefrom);
                        $('#modal-workStatus input[name="dateTo"]').val(dateto);
                        $('#modal-workStatus input[name="dateFrom"]').prop("disabled", true);
                        $('#modal-workStatus input[name="dateTo"]').prop("disabled", true);
                        break;
                    case "2":
                        datefrom = moment().add(1, 'days').format("YYYY-MM-DD");
                        dateto = '';
                        $('#modal-workStatus input[name="dateFrom"]').val(datefrom);
                        $('#modal-workStatus input[name="dateTo"]').val(dateto);
                        $('#modal-workStatus input[name="dateFrom"]').prop("disabled", true);
                        $('#modal-workStatus input[name="dateTo"]').prop("disabled", true);
                        break;
                    case "3":
                        datefrom = moment().format("YYYY-MM-DD");
                        dateto = '';
                        $('#modal-workStatus input[name="dateFrom"]').val(datefrom);
                        $('#modal-workStatus input[name="dateTo"]').val(dateto);
                        $('#modal-workStatus input[name="dateFrom"]').prop("disabled", false);
                        $('#modal-workStatus input[name="dateTo"]').prop("disabled", true);
                        break;
                    case "4":
                        datefrom = moment().format("YYYY-MM-DD");
                        dateto = moment().format("YYYY-MM-DD");
                        $('#modal-workStatus input[name="dateFrom"]').val(datefrom);
                        $('#modal-workStatus input[name="dateTo"]').val(dateto);
                        $('#modal-workStatus input[name="dateFrom"]').prop("disabled", false);
                        $('#modal-workStatus input[name="dateTo"]').prop("disabled", false);
                        break;
                    default:
                }
            });


        }, 200);

    });
    request_getWorkStatus.fail(function(jqXHR, textStatus, errorThrown) {
        console.error(
            "The following error occurred: " +
            textStatus, errorThrown
        );
    });
    // Callback handler that will be called regardless
    // if the request_addItem failed or succeeded
    request_getWorkStatus.always(function() {});
}
//
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
//
var reqField_getBirthdays = {
    currentBirthdays: function(response) {
        $('#currentBirthdays').empty();
        if (checkVal(response)) {
            $('#currentBirthdays').html(response);
            //
        }
        $('#staffBirthdays-out').empty();
        if (checkVal(response)) {
            $('#staffBirthdays-out').html(response);
            //
        }
    }
};

function ajaxRequest_getBirthdays(responseHandler) {
    request_getBirthdays = $.ajax({
        type: "post",
        url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/process/ajaxrequests/ajaxReq-getBirthdays.php',
        cache: false,
        data: {},
        success: reqField_getBirthdays[responseHandler]
    });
    // Callback handler that will be called on success
    request_getBirthdays.done(function(response, textStatus, jqXHR) {
        res = response.replace(new RegExp("\\r?\\n", "g"), "");
    });
    request_getBirthdays.fail(function(jqXHR, textStatus, errorThrown) {
        console.error(
            "The following error occurred: " +
            textStatus, errorThrown
        );
    });
    // Callback handler that will be called regardless
    // if the request_addItem failed or succeeded
    request_getBirthdays.always(function() {});
}
//
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
//
function ajaxRequest_getSpTelDocListAsync() {
    var result = false;
    $.ajax({
        async: false,
        cache: false,
        type: "post",
        url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/process/ajaxrequests/ajaxReq-getSpTelDocList.php',
        data: {},
        success: function(response) {
            res = response.replace(new RegExp("\\r?\\n", "g"), "");
            if (response != '0' && response != 'error -1') {
                // resArr = JSON.parse(response);
                // result = response.replace(new RegExp("\\r?\\n", "g"), "");
                // result = JSON.parse(response);
                $('#spTel-linkToDownload-info').html(
                    'Актуальность формируемого списка телефонов зависит от того, насколько сами сотрудниками следят за актуальностью своих контактных данных в профиле Портала и Людмилы Алексеевны, если конечно она не забыла о просьбе админа присматривать за телефонным справочником.'
                );
                $('#spTel-linkToDownload').html(response);
                $('#modal-spTel').modal('show');
                result = "ok";
            } else {
                result = 'error';
                $('#spTel-linkToDownload-info').html('');
                $('#spTel-linkToDownload').html('Файл не сформирован из-за какой-то ошибки');
            }
            // console.log('ajaxRequest_getRelativeOutgoingDataAsync', result);
        }
    });
    return result;
}
//
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
//
var reqField_getStaffNews = {
    getStaffNews: function(response) {
        // $('#newStaffPersons').empty();
        console.log('ajaxRequest_getStaffNews', response);
    }
};

function ajaxRequest_getStaffNews(responseHandler) {
    request_getStaffNews = $.ajax({
        type: "post",
        url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/process/ajaxrequests/ajaxReq-getStaffNews.php',
        cache: false,
        data: {},
        dataType: 'json',
        success: reqField_getStaffNews[responseHandler]
    });
    // Callback handler that will be called on success
    request_getStaffNews.done(function(response, textStatus, jqXHR) {
        // res = response.replace(new RegExp("\\r?\\n", "g"), "");
        // const obj = JSON.parse(response);
        // response.forEach(function(entry) {
        //     console.log(entry);
        // });
        // $('#newStaffPersons').html(response);
        let arrIn = response.in;
        let arrOut = response.out;
        let htmlResult = "";
        //
        let htmlIn = "";
        if (arrIn !== undefined) {
            reportIcon = '<i class="fa-solid fa-user-plus fa-lg" style="color:green !important"></i>';
            for (i = 0; i < arrIn.length; ++i) {
                console.log(arrIn[i]);
                podrmain = checkVal(arrIn[i].podrmain) ? '&nbsp;&bull;&nbsp;' + arrIn[i].podrmain : '';
                dept = checkVal(arrIn[i].dept) ? ',&nbsp;' + arrIn[i].dept : '';
                dolj = checkVal(arrIn[i].dolj) ? '&nbsp;&bull;&nbsp;' + arrIn[i].dolj : '';
                htmlIn +=
                    '<div class="media p-2 mb-1 reportStatus">' +
                    '<div class="rounded-circle mr-3">' + reportIcon + '</div>' +
                    '<div class="media-body">' +
                    '<h5 class="mt-0 mb-0">' + arrIn[i].name + '</h5>' +
                    '<p class="mb-0">Принят приказом № ' + arrIn[i].order + ' от ' + arrIn[i].date + '</p>' +
                    '<p class="mb-0">' + arrIn[i].office + podrmain + dept + dolj +
                    '</p>' +
                    '</div></div>';
            }
        }
        // $('#portalmain-staffNews .staffPersons-in').html(htmlIn);
        //
        let htmlOut = "";
        if (arrOut !== undefined) {
            reportIcon = '<i class="fa-solid fa-user-minus fa-lg" style="color:red !important"></i>';
            for (i = 0; i < arrOut.length; ++i) {
                console.log(arrOut[i]);
                htmlOut +=
                    '<div class="media p-2 mb-1 reportStatus">' +
                    '<div class="rounded-circle mr-3">' + reportIcon + '</div>' +
                    '<div class="media-body">' +
                    '<h5 class="mt-0 mb-0">' + arrOut[i].name + '</h5>' +
                    '<p class="mb-0">Уволен приказом № ' + arrOut[i].order + ' от ' + arrOut[i].date + '</p>' +
                    '<p class="mb-0">' + arrOut[i].office + '</p>' +
                    '</div></div>';
            }
        }
        // $('#portalmain-staffNews .staffPersons-out').html(htmlOut);
        //
        let htmlNone = "";
        if (arrIn === undefined && arrOut === undefined) {
            htmlNone +=
                '<div class="my-auto" style="font-size:0.75rem"><p class="text-center">Никакой движухи на радарах не наблюдается...</p><p class="text-center"><i class="fa-solid fa-face-rolling-eyes fa-2xl"></i></p></div>';
        }
        htmlResult += htmlIn + htmlOut + htmlNone;
        $('#portalmain-staffNews .staffPersons-result').html(htmlResult);
    });
    request_getStaffNews.fail(function(jqXHR, textStatus, errorThrown) {
        console.error(
            "The following error occurred: " +
            textStatus, errorThrown
        );
    });
    // Callback handler that will be called regardless
    // if the request_addItem failed or succeeded
    request_getStaffNews.always(function() {});
}
//
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
//
var reqField_getMarqeeData = {
    getMarqeeData: function(response) {
        console.log('ajaxRequest_getMarqeeData', response);
    }
};

function ajaxRequest_getMarqeeData(responseHandler) {
    request_getMarqeeData = $.ajax({
        type: "post",
        url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/process/ajaxrequests/ajaxReq-getMarqeeData.php',
        cache: false,
        data: {},
        dataType: 'json',
        success: reqField_getMarqeeData[responseHandler]
    });
    // Callback handler that will be called on success
    request_getMarqeeData.done(function(response, textStatus, jqXHR) {
        let arrMarqee = response;
        let htmlMarqee = "";
        for (i = 0; i < arrMarqee.length; ++i) {
            console.log(arrMarqee[i]);
            htmlMarqee +=
                '<div class="marquee__item">' +
                '<span class="item__el1">' + arrMarqee[i].date + '</span>' +
                '<span class="item__el2">' + arrMarqee[i].postlink + '</span>' +
                '<i class="fa-solid fa-minus mx-2"></i>' +
                '<span class="item__el3">' + arrMarqee[i].servicename + '</span>' +
                // '<span class="item__el4"><i class="fa-solid fa-circle fa-2xs"></i></span>' +
                '</div>';
        }
        console.log(htmlMarqee);
        $('.marquee__content').html(htmlMarqee);

    });
    request_getMarqeeData.fail(function(jqXHR, textStatus, errorThrown) {
        console.error(
            "The following error occurred: " +
            textStatus, errorThrown
        );
    });
    // Callback handler that will be called regardless
    // if the request_addItem failed or succeeded
    request_getMarqeeData.always(function() {});
}


//
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
//
function ajaxRequest_getWorkStatusDelete(reportid) {
    var result = false;
    $.ajax({
        async: false,
        cache: false,
        type: "post",
        url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/process/ajaxrequests/ajaxReq-getWorkStatusDelete.php',
        data: {
            reportid: reportid
        },
        success: function(response) {}
    });
    return result;
}

//
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
//
function ajaxRequest_getWorkStatusSave(userid, action, reportid, reporttype, reportoption, datefrom, dateto, comment) {
    var result = false;
    console.log("ajaxRequest_getWorkStatusSave #1", userid, action, reportid, reporttype, reportoption, datefrom,
        dateto, comment);
    $.ajax({
        async: false,
        cache: false,
        type: "post",
        url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH; ?>/process/ajaxrequests/ajaxReq-getWorkStatusSave.php',
        data: {
            userid: userid,
            action: action,
            reportid: reportid,
            reporttype: reporttype,
            reportoption: reportoption,
            datefrom: datefrom,
            dateto: dateto,
            comment: comment,
        },
        success: function(response) {
            console.log("ajaxRequest_getWorkStatusSave #2", response);
        }
    });
    return result;
}
</script>

<style>
</style>

<?php if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization_defaultDB($_SESSION['login'], $_SESSION['password']) == -1) {; // Редирект на главную страницу
        ?>
<meta http-equiv="refresh" content="0; url=<?php echo __ROOT; ?>">
<?php
} else {
        // При удачном входе пользователю выдается все, что расположено НИЖЕ звездочек
        // ************************************************************************************
        if (!isset($_GET['type']) && empty($_GET['type'])) {
            if (__UI_PERSONAL_PORTALNEW_SHOWTOPBLOCK == '1') {
                if (!checkIsItSuperadmin($_SESSION['id']) == 1) {
                    echo '<div id="portalnew-main-top" class="container-xl mb-3">';
                    include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH . "/portalnew-main-top.php";
                    echo '</div>';
                } else {
                    echo '<div id="portalnew-main-top" class="container-xl mb-3">';
                    include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH . "/portalnew-main-top_test.php";
                    echo '</div>';
                }
            }
            if (!checkIsItSuperadmin($_SESSION['id']) == 1) {
                echo '<div id="portalnew-main-center" class="d-flex justify-content-center align-items-center">';
                include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH . "/portalnew-main-center.php";
                echo '</div>';
            } else {
                echo '<div id="portalnew-main-center" class="d-flex justify-content-center align-items-center">';
                include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH . "/portalnew-main-center_test.php";
                echo '</div>';
            }
            if (!checkIsItSuperadmin($_SESSION['id']) == 1) {
                echo '<div id="portalnew-main-bottom" class="container-xl mb-5">';
                include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH . "/portalnew-main-bottom.php";
                echo '</div>';
            } else {
                echo '<div id="portalnew-main-bottom" class="container-xl mb-3">';
                include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH . "/portalnew-main-bottom_test.php";
                echo '</div>';
            }
        } elseif (isset($_GET['type']) && !empty($_GET['type']) && $_GET['type'] == "telegram-feed") {
            include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_WORKPATH . "/telegram-feed/portalnew-telegram-feed.php";
        } else {
            include __DIR_ROOT . __SERVICENAME_PORTALNEW . "/php/examples/simple/main/main/common-includes/errpage-wrongurls.php";
        }
        // ************************************************************************************
        // При удачном входе пользователю выдается все, что расположено ВЫШЕ звездочек
    }
} else {; # Редирект на главную страницу
    ?>
<meta http-equiv="refresh" content="0; url=<?php echo __ROOT; ?>">
<?php
}
?>