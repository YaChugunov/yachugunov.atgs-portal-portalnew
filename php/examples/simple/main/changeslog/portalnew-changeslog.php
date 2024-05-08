<?php

/**
 * -----  ----- ----- ----- ----- ----- ----- ----- ----- -----
 * * Основная страница раздела Changeslog
 * -----  ----- ----- ----- ----- ----- ----- ----- ----- -----
 */
?>

<?php
# Проверяем, является ли пользователь суперадмином, чтобы иметь возможность редактировать записи об изменениях
$userIsSU = checkIsItSuperadmin($_SESSION['id']);
?>
<script type="text/javascript" language="javascript" class="init">
function getMonthName(num) {
    const monthNames = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь",
        "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
    ];
    return monthNames[num - 1];
}

//
// ###### ## ##### ## ###### ## ##### ## ###### ## ##### ## ###### ## #####
// Функция проверки переменной на значение
//
function checkVal(val) {
    if (typeof val !== "undefined" && val !== "" && val !== null) {
        return 1;
    } else {
        return 0;
    }
}


var reqField_getChangesList = {
    getChangesList: function(response) {}
};

function ajaxRequest_getChangesList(responseHandler) {
    // Fire off the request_addItem to /form.php
    request_getChangesList = $.ajax({
        type: "post",
        url: '<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_WORKPATH; ?>/changeslog/process/ajaxrequests/ajaxRequest-getChangesList.php',
        cache: false,
        data: {},
        success: reqField_getChangesList[responseHandler]
    });
    // Callback handler that will be called on success
    request_getChangesList.done(function(response, textStatus, jqXHR) {
        let userIsSU = <?php echo $userIsSU; ?>;
        if (response !== '-1') {
            // console.log('getChangesList!', response);
            jsonData = JSON.parse(response);
            // console.log('getChangesList!', jsonData);
            let monthOldName = '';
            let outBlock = '';
            let outItem = '';
            let outArr = new Array();
            y = 0;
            for (var key1 in jsonData) {
                console.log('>', key1, jsonData[key1]);
                let output = '';
                let outTitle = '';
                let outItem = '';
                kodchangeOld = '';
                monthNumOld = '';
                n = 1;
                for (var key2 in jsonData[key1]) {
                    let outItem = '';
                    monthObj = jsonData[key1][key2].change;
                    console.log('>>', key2, monthObj);
                    //
                    monthNum = monthObj.month;
                    monthName = getMonthName(monthObj.month);
                    kodchange = monthObj.kodchange;
                    service = monthObj.service;
                    refusers = monthObj.refusers;
                    comment = checkVal(monthObj.comment) ? ' / ' + monthObj.comment : ''
                    textChange = checkVal(monthObj.kodchange) ?
                        '<span class="textLabel mr-2">ID</span>' +
                        monthObj.kodchange :
                        '';
                    textType = checkVal(monthObj.type) ?
                        '<span class="textLabel mx-2">Тип</span>' +
                        monthObj.type :
                        '';
                    textService =
                        '<span class="textLabel mx-2">Где / Что</span>' +
                        service + comment;
                    textFlag = checkVal(monthObj.flag) ?
                        '<span class="mx-2">&bull;</span><span class="textLabel mr-1">Важность</span>' +
                        monthObj.flag :
                        '';
                    textRestr = checkVal(monthObj.restriction) ?
                        '<span class="textLabel mx-2">Кто</span>' + monthObj
                        .restriction : '';
                    textRefusers = checkVal(monthObj.refusers) ?
                        '<span class="textLabel mx-2">Ref</span>' + monthObj.refusers : '';
                    link1 = checkVal(monthObj.link1) ?
                        '<i class="fa-solid fa-link mx-2"></i><a href="' + monthObj.link1 +
                        '" class="" target="_blank">Ссылка на пост в Телеграм</a>' : '';
                    link2 = checkVal(monthObj.link2) ?
                        '<i class="fa-solid fa-link mx-2"></i><a href="' + monthObj.link2 +
                        '" class="" target="_blank">Ссылка на пост в ВК</a>' : '';
                    timestamp = moment(monthObj.timestamp).format("DD.MM");
                    timestampClass = checkVal(monthObj.status) ? (monthObj.status === 'Показать' ?
                        'timestampOn' :
                        'timestampOff') : '';

                    if (monthNumOld !== monthNum) {
                        outTitle = '<h4 class="title mb-3 mt-5">' + key1 + ', ' + getMonthName(monthObj.month) +
                            '</h4>';
                    } else {
                        outTitle = '';
                    }
                    //
                    if (kodchange !== kodchangeOld) {
                        outItem += '<div id="changeid-' + kodchange +
                            '" class="item mx-3 my-2 p-2">';
                        outItem += '<div class="maintext">' +
                            '<span class="' + timestampClass + '">' + timestamp + '</span>' +
                            '<span class="mx-2">&bull;</span>' +
                            monthObj.shortname +
                            '</div>';
                        outItem += '<div class="specialtext">' +
                            '<span class="">' + textChange + '</span>' +
                            '<span class="">' + textType + '</span>' +
                            '<span class="">' + textService + '</span>' +
                            '<span class="">' + textRestr + '</span>' +
                            '<span class="">' + textRefusers + '</span>' +
                            '<span class="">' + link1 + '</span>' +
                            '<span class="">' + link2 + '</span>' +
                            '</div>';
                        outItem += '</div>';
                    } else {
                        outItem = '';
                    }

                    output += outTitle + outItem;
                    n++;
                    monthNumOld = monthNum;
                    kodchangeOld = kodchange;
                };
                y++;
                outArr[y] = output;
            };
            for (i = 0; i < outArr.length; i++) {
                $('#changeslogList div.title').after(outArr[i]);
            }
        }
    });
    // Callback handler that will be called on failure
    request_getChangesList.fail(function(jqXHR, textStatus, errorThrown) {
        console.error(
            "The following error occurred: " +
            textStatus, errorThrown
        );
    });
    // Callback handler that will be called regardless
    // if the request_addItem failed or succeeded
    request_getChangesList.always(function() {});
}
</script>

<link href="<?php echo __ROOT . __SERVICENAME_PORTALNEW . __PORTAL_MAIN_WORKPATH; ?>/changeslog/changeslog.css"
      rel="stylesheet">
<div id="changeslogList" class="container">
    <div class="title">
        <h3 class="">История изменений и обновлений</h3>
        <div class="small">До середины 2023 года системно лог
            изменений и обновлений не велся,
            поэтому в настоящий момент он постепенно пополняется теми записями, что удается вспомнить и восстановить из
            разных источников, поэтому раздел пока в стадии наполнения...</div>
    </div>
    <div class="items">
        <div class="item"></div>
    </div>
</div>

<script type="text/javascript" language="javascript" class="init">
$(window).on("load", function() {

    ajaxRequest_getChangesList('getChangesList');

});
</script>