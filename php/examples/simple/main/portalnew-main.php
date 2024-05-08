<?php
# 
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### 
# 
?>
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
    var reqField_getBirthdays = {
        currenBirthdays: function(response) {
            $('#currentBirthdays').empty();
            if (checkVal(response)) {
                $('#currentBirthdays').html(response);
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
            let htmlIn = "";
            if (arrIn !== undefined) {
                for (i = 0; i < arrIn.length; ++i) {
                    console.log(arrIn[i]);
                    htmlIn +=
                        '<div class="mb-1 d-flex flex-column">' +
                        '<div class="nameIn text-left" style="font-size:0.75rem"><i class="fa-solid fa-user-plus mr-2" style="color:green !important"></i>' +
                        arrIn[i].name + '</div>' +
                        '<div class="text-left" style="font-size:0.70rem; color:#999999 !important; margin-left:1.45rem">Принят приказом № ' +
                        arrIn[i].order + ' от ' + arrIn[i].date + '</div>' +
                        '<div class="text-left" style="font-size:0.70rem; color:#999999 !important; margin-left:1.45rem">' +
                        arrIn[i].office + ', ' + arrIn[i].dept + ', ' + arrIn[i].dolj + '</div>' +
                        '</div>';
                }
            }
            $('#portalmain-staffNews .staffPersons-in').html(htmlIn);
            //
            let htmlOut = "";
            if (arrOut !== undefined) {
                for (i = 0; i < arrOut.length; ++i) {
                    console.log(arrOut[i]);
                    htmlOut +=
                        '<div class="mb-1 d-flex flex-column">' +
                        '<div class="nameOut text-left" style="font-size:0.75rem"><i class="fa-solid fa-user-minus mr-2" style="color:red !important"></i>' +
                        arrOut[i].name + '</div>' +
                        '<div class="text-left" style="font-size:0.70rem; color:#999999 !important; margin-left:1.45rem">Уволен приказом № ' +
                        arrOut[i].order + ' от ' + arrOut[i].date + '</div>' +
                        '<div class="text-left" style="font-size:0.70rem; color:#999999 !important; margin-left:1.45rem">' +
                        arrOut[i].office +
                        '</div>';
                }
            }
            $('#portalmain-staffNews .staffPersons-out').html(htmlOut);
            if (arrIn === undefined && arrOut === undefined) {
                $('#portalmain-staffNews .staffPersons-noresults').html(
                    '<span style="font-size:0.8rem">Никакой движухи на радарах не наблюдается...</span>');
            }
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
</script>

<style>
</style>

<?php if (isset($_SESSION['password']) && isset($_SESSION['login'])) {
    if (checkUserAuthorization_defaultDB($_SESSION['login'], $_SESSION['password']) == -1) {
        // Редирект на главную страницу
?>
        <meta http-equiv="refresh" content="0; url=<?php echo __ROOT; ?>">
    <?php
    } else {
        // При удачном входе пользователю выдается все, что расположено НИЖЕ звездочек
        // ************************************************************************************
        if (!isset($_GET['type']) && empty($_GET['type'])) {
            if (__UI_PERSONAL_PORTALNEW_SHOWTOPBLOCK == '1') {
                echo '<div id="portalnew-main-top" class="container-xl mb-3">';
                include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH  . "/portalnew-main-top.php";
                echo '</div>';
            }
            echo '<div id="portalnew-main-center" class="d-flex justify-content-center align-items-center">';
            include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH  . "/portalnew-main-center.php";
            echo '</div>';
            echo '<div id="portalnew-main-bottom" class="container-xl mb-5">';
            include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_MAIN_WORKPATH  . "/portalnew-main-bottom.php";
            echo '</div>';
        } elseif (isset($_GET['type']) && !empty($_GET['type']) && $_GET['type'] == "telegram-feed") {
            include __DIR_ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_WORKPATH  . "/telegram-feed/portalnew-telegram-feed.php";
        } else {
            include __DIR_ROOT . __SERVICENAME_PORTALNEW . "/php/examples/simple/main/main/common-includes/errpage-wrongurls.php";
        }
        // ************************************************************************************
        // При удачном входе пользователю выдается все, что расположено ВЫШЕ звездочек
    }
} else {
    # Редирект на главную страницу 
    ?>
    <meta http-equiv="refresh" content="0; url=<?php echo __ROOT; ?>">
<?php
}
?>