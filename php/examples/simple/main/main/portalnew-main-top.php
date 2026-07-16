<?php
#
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ###
#
if ($use_lightTheme === '1') {
    $footerLogo_filename = "portalnew-footerLogo-1.png";
} else {
    $footerLogo_filename = "portalnew-footerLogo-1-inverse.png";
}
?>

<!-- Подключаем конфигурацию сервисов -->
<?php include 'dinners-promo.config.php'; ?>


<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {

    var portalMain_syslog = $('#portalmain-syslog').DataTable({
        dom: "<'row'<'col-sm-12'tr >>",
        language: {
            url: "php/examples/simple/main/main/dt_russian-portal-syslog.json"
        },
        ajax: {
            url: "php/examples/simple/main/main/process/portalnew-main-syslog-process.php",
            type: "POST"
        },
        serverSide: true,
        columns: [{
                data: "portal_syslog.timestamp"
            },
            {
                data: "portal_syslog.service"
            },
            {
                data: "portal_syslog.user_id"
            },
            {
                data: "portal_syslog.message"
            },
        ],
        columnDefs: [{
                orderable: false,
                searchable: false,
                targets: 0
            },
            {
                orderable: false,
                searchable: false,
                targets: 1,
                render: function(data, type, row, meta) {
                    if (data !== null && typeof data !== 'undefined' && data !== "") {
                        switch (data) {
                            case 'Портал':
                                result = '<span class="badge badge-primary">' + data +
                                    '</span>';
                                break;
                            case 'Договор':
                                result = '<span class="badge badge-warning">' + data +
                                    '</span>';
                                break;
                            case 'Почта АТГС':
                                result = '<span class="badge badge-info">' + data + '</span>';
                                break;
                            default:
                                result = "-";
                        }
                    } else {
                        result = "-";
                    }
                    return result;
                },
            },
            {
                orderable: false,
                searchable: false,
                targets: 2,
                render: function(data, type, row, meta) {
                    if (data !== null && typeof data !== 'undefined' && data !== "") {
                        return row.portal_syslog.user_firstname + ' ' + row.portal_syslog
                            .user_lastname;
                    } else {
                        return "-";
                    }
                },
            },
            {
                orderable: false,
                searchable: false,
                targets: 3,
                render: function(data, type, row, meta) {
                    let res1 = '',
                        res2 = '',
                        res3 = '',
                        service = row.portal_syslog.service,
                        subgroup = row.portal_syslog.subgroup,
                        docid = row.portal_syslog.doc_id,
                        docnumber = row.portal_syslog.doc_number,
                        field_info1 = row.portal_syslog.field_info1,
                        field_info2 = row.portal_syslog.field_info2,
                        sex = row.users.sex;
                    if (service === "Портал") {
                        switch (data) {
                            case 'Вход в систему':
                                res1 = (sex == 0) ? 'Авторизовался' : 'Авторизовалась';
                                res2 = 'в системе';
                                break;
                            default:
                                res1 = "";
                                res2 = "";
                        }
                    }
                    if (service === "Почта АТГС") {
                        switch (data) {
                            case 'Новое письмо':
                                res1 = (sex == 0) ? 'Создал' : 'Создала';
                                res1 = res1 + ' новый документ';
                                break;
                            case 'Редактирование письма':
                                res1 = (sex == 0) ? 'Отредактировал' : 'Отредактировала';
                                res1 = res1 + ' документ';
                                break;
                            case 'Прикрепление файла':
                                res1 = (sex == 0) ? 'Прикрепил' : 'Прикрепила';
                                res1 = res1 + ' файл к документу';
                                break;
                            default:
                                res1 = "";
                        }
                        switch (subgroup) {
                            case 'Исходящие':
                                res2 = (docnumber !== "") ? '№ 1-1/' + docnumber : '';
                                res3 = 'в Исходящих';
                                break;
                            case 'Входящие':
                                res2 = (docnumber !== "") ? '№ 1-2/' + docnumber : '';
                                res3 = 'во Входящих';
                                break;
                            default:
                                res2 = "";
                                res3 = "";
                        }
                    }
                    if (service === "Договор") {
                        if (subgroup === "Текущие договора") {
                            switch (data) {
                                case 'Новый договор':
                                    res1 = (sex == 0) ? 'Создал' : 'Создала';
                                    res2 = 'новый договор';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Просмотр общего списка':
                                    res1 = (sex == 0) ? 'Открыл' : 'Открыла';
                                    res2 = 'общий список договоров';
                                    res3 = '';
                                    break;
                                default:
                                    res1 = "";
                                    res2 = "";
                                    res3 = "";
                            }
                        }
                        if (subgroup === "Карточка договора") {
                            res1 = (sex == 0) ? 'Открыл' : 'Открыла';
                            res2 = 'карточку договора';
                            res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                        }
                        if (subgroup === "Договор") {
                            switch (data) {
                                case 'Создан договор':
                                    res1 = (sex == 0) ? 'Создал' : 'Создала';
                                    res2 = 'новый договор';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Редактирование договора':
                                    res1 = (sex == 0) ? 'Отредактировал' : 'Отредактировала';
                                    res2 = 'договор';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Прикрепление документа':
                                    res1 = (sex == 0) ? 'Прикрепил' : 'Прикрепила';
                                    res2 = 'документ к договору';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Удаление документа':
                                    res1 = (sex == 0) ? 'Удалил' : 'Удалила';
                                    res2 = 'документ из договора';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                default:
                                    res1 = "";
                                    res2 = "";
                                    res3 = "";
                            }
                        }
                        if (subgroup === "Календарный план") {
                            switch (data) {
                                case 'Добавление этапа':
                                    res1 = (sex == 0) ? 'Создал' : 'Создала';
                                    res2 = 'новый этап в договоре';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Редактирование этапа':
                                    res1 = (sex == 0) ? 'Отредактировал' : 'Отредактировала';
                                    res2 = 'этап в договоре';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Удаление этапа':
                                    res1 = (sex == 0) ? 'Удалил' : 'Удалила';
                                    res2 = 'этап в договоре';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                default:
                                    res1 = "";
                                    res2 = "";
                                    res3 = "";
                            }
                        }
                        if (subgroup === "Счета-фактуры") {
                            switch (data) {
                                case 'Добавление счета-фактуры':
                                    res1 = (sex == 0) ? 'Добавил' : 'Добавила';
                                    res2 = 'новый счет-фактуру в этап договора';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Изменение счета-фактуры':
                                    res1 = (sex == 0) ? 'Изменил' : 'Изменила';
                                    res2 = 'счет-фактуру в этапе договора';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Удаление счета-фактуры':
                                    res1 = (sex == 0) ? 'Удалил' : 'Удалила';
                                    res2 = 'счет-фактуру в этапе договора';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                default:
                                    res1 = "";
                                    res2 = "";
                                    res3 = "";
                            }
                        }
                        if (subgroup === "Авансы") {
                            switch (data) {
                                case 'Добавление аванса':
                                    res1 = (sex == 0) ? 'Добавил' : 'Добавила';
                                    res2 = 'новый аванс по договору';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Изменение аванса':
                                    res1 = (sex == 0) ? 'Изменил' : 'Изменила';
                                    res2 = 'аванс по договору';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Удаление аванса':
                                    res1 = (sex == 0) ? 'Удалил' : 'Удалила';
                                    res2 = 'аванс по договору';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                default:
                                    res1 = "";
                                    res2 = "";
                                    res3 = "";
                            }
                        }
                        if (subgroup === "Зачет аванса") {
                            switch (data) {
                                case 'Зачет аванса':
                                    res1 = (sex == 0) ? 'Добавил' : 'Добавила';
                                    res2 = 'новый зачет аванса в этап договора';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Изменение зачтенного аванса':
                                    res1 = (sex == 0) ? 'Изменил' : 'Изменила';
                                    res2 = 'зачет аванса в этапе договора';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Удаление зачтенного аванса':
                                    res1 = (sex == 0) ? 'Удалил' : 'Удалила';
                                    res2 = 'зачет аванса в этапе договора';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                default:
                                    res1 = "";
                                    res2 = "";
                                    res3 = "";
                            }
                        }
                        if (subgroup === "Оплата") {
                            switch (data) {
                                case 'Зачет платежа':
                                    res1 = (sex == 0) ? 'Добавил' : 'Добавила';
                                    res2 =
                                        'новый платеж по счету-фактуре в этапе договора';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Изменение платежа':
                                    res1 = (sex == 0) ? 'Изменил' : 'Изменила';
                                    res2 = 'платеж по счету-фактуре в этапе договора';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                case 'Удаление платежа':
                                    res1 = (sex == 0) ? 'Удалил' : 'Удалила';
                                    res2 = 'платеж по счету-фактуре в этапе договора';
                                    res3 = (docnumber !== "") ? '№ 3-4/' + docnumber : '';
                                    break;
                                default:
                                    res1 = "";
                                    res2 = "";
                                    res3 = "";
                            }
                        }
                        if (subgroup === "Отчеты") {
                            switch (data) {
                                case 'Просмотр общего списка':
                                    res1 = (sex == 0) ? 'Открыл' : 'Открыла';
                                    res2 = 'список доступных отчетов';
                                    res3 = '';
                                    break;
                                case 'Просмотр онлайн-отчета':
                                    res1 = (sex == 0) ? 'Открыл' : 'Открыла';
                                    res2 = 'онлайн-отчет';
                                    res3 = '"' + field_info1 + '"';
                                    break;
                                case 'Экспорт отчета':
                                    res1 = (sex == 0) ? 'Выполнил' : 'Выполнила';
                                    res2 = 'экспорт отчета';
                                    res3 = '"' + field_info1 + '"';
                                    break;
                                default:
                                    res1 = "";
                                    res2 = "";
                                    res3 = "";
                            }

                        }
                        if (subgroup === "Реестр договоров") {
                            res1 = (sex == 0) ? 'Открыл' : 'Открыла';
                            res2 =
                                'реестр договоров для создания служебного задания на командировку';
                            res3 = '';
                        }
                    }
                    result = res1 + ' ' + res2 + ' ' + res3;
                    return (result !== "  ") ? result : '<span class="text-warning">' +
                        field_info1 + '</span>';
                }
            }
        ],
        order: [
            [0, 'desc']
        ],
        select: false,
        processing: false,
        paging: false,
        scrollCollapse: true,
        scrollY: '9.0rem',
        buttons: []
    });


    function callticker() {
        portalMain_syslog.ajax.reload(null, false);
        timer = setTimeout(callticker, 60 * 1000);
    }
    timer = setTimeout(callticker, 60 * 1000);
    $("#portalnew-main-top-block-1").mouseover(function() {
        clearTimeout(timer);
    }).mouseout(function() {
        timer = setTimeout(callticker, 60 * 1000);
    })


});

// Инициализация popover для элементов в блоках сервисов
$(function() {
    // Для темной темы
    $('#servicesBlock .feature-item').popover({
        trigger: 'hover',
        placement: 'top',
        html: true,
        container: 'body',
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-body" style="min-width:280px;max-width:320px;font-size:0.8rem;padding:10px 14px;"></div></div>'
    });

    // Для светлой темы
    $('#servicesBlock .feature-item-light').popover({
        trigger: 'hover',
        placement: 'top',
        html: true,
        container: 'body',
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-body" style="min-width:280px;max-width:320px;font-size:0.8rem;padding:10px 14px;"></div></div>'
    });
});
</script>


<style>
#portalmain-syslog>thead>tr>th,
#portalmain-syslog>tbody>tr>td {
    border-bottom: none;
    border-top: none;
}

#portalmain-syslog>tbody {
    font-size: 1.0rem;
    border-bottom: none;
    border-top: none
}

#portalmain-syslog>tbody>tr>td {
    font-size: 0.75rem;
    padding: 6px 2px;
    line-height: 1.42857143;
    vertical-align: middle
}

#portalmain-syslog>tbody>tr>td:first-child {
    width: 9%;
    text-align: left;
}

#portalmain-syslog>tbody>tr>td:nth-child(2) {
    width: 10%;
    text-align: left;
}

#portalmain-syslog>tbody>tr>td:nth-child(3) {
    width: 15%;
    text-align: left;
}

#portalmain-syslog>tbody>tr>td:last-child {
    text-align: left;
}





/* Marquee styles */
.marquee {
    --gap: 1rem;
    position: relative;
    display: flex;
    overflow: hidden;
    user-select: none;
    gap: var(--gap);
}

.marquee__content {
    flex-shrink: 0;
    display: flex;
    justify-content: start;
    gap: var(--gap);
    min-width: 100%;
}

@keyframes scroll {
    from {
        transform: translateX(0);
    }

    to {
        transform: translateX(calc(-100% - var(--gap)));
    }
}

/* Pause animation when reduced-motion is set */
@media (prefers-reduced-motion: reduce) {
    .marquee__content {
        animation-play-state: paused !important;
    }
}

/* Enable animation */
.enable-animation .marquee__content {
    animation: scroll 60s linear infinite;
}

/* Reverse animation */
.marquee--reverse .marquee__content {
    animation-direction: reverse;
}

/* Pause on hover */
.marquee--hover-pause:hover .marquee__content {
    animation-play-state: paused;
}

/* Attempt to size parent based on content. Keep in mind that the parent width is equal to both content containers that stretch to fill the parent. */
.marquee--fit-content {
    max-width: fit-content;
}

/* A fit-content sizing fix: Absolute position the duplicate container. This will set the size of the parent wrapper to a single child container. Shout out to Olavi's article that had this solution 👏 @link: https://olavihaapala.fi/2021/02/23/modern-marquee.html  */
.marquee--pos-absolute .marquee__content:last-child {
    position: absolute;
    top: 0;
    left: 0;
}

/* Enable position absolute animation on the duplicate content (last-child) */
.enable-animation .marquee--pos-absolute .marquee__content:last-child {
    animation-name: scroll-abs;
}

@keyframes scroll-abs {
    from {
        transform: translateX(calc(100% + var(--gap)));
    }

    to {
        transform: translateX(0);
    }
}

.marquee {
    font-family: "Stolzl", Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 0.75rem;
}

.marquee__content {
    height: 2.0rem;
    padding: 0.5rem 0;
}

/* Other page demo styles */
.marquee__content>.marquee__item {
    text-align: left;
    flex: 0 0 auto;
}

.marquee-logo {
    display: flex;
    align-items: center;
    background-color: transparent;
}

.marquee__item span.item__el4 {
    margin-left: 1rem;
}

.alert-admin-msg {
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 0.25rem 0.75rem;
    margin-bottom: 1rem;
}

.alert-admin-msg .body {}

.alert-admin-msg p {
    font-family: 'HeliosCond', sans-serif;
    line-height: 1.55rem;
    margin-bottom: 0;
}

.alert-admin-msg p span {
    background-color: #DC3545;
    padding: 0 0.15rem;
}

.alert-admin-msg p.text {
    color: #FFFFFF !important;
    font-size: 1.25rem;
}

.alert-admin-msg p.sign {
    color: #DC3545 !important;
    font-size: 1.0rem;
    text-align: right;
}
</style>

<div id="portalnew-main-top-blocks" class="d-flex flex-column">
    <div class="container enable-animation px-0 mb-3 d-flex">
        <div class="marquee-logo pr-3" data-toggle="popover"
             data-content="<div class='text-center w-100'>Последние обновления с корпоративного сайта и наиболее важные события на Портале в формате бегущей строки. Типы важных событий будут определяться по ходу работы.</div>">
            <i class="fa-solid fa-info fa-lg"></i>
        </div>
        <div class="marquee marquee--hover-pause">
            <div class="marquee__content"></div>
            <div aria-hidden="true" class="marquee__content"></div>
        </div>
    </div>
    <?php if ((checkIsItSuperadmin_defaultDB($_SESSION['id']) == 1 && __ADMSETTINGS_MSG_TEST) || __ADMSETTINGS_MSG_SHOW) {
    ?>
    <div class="d-flex flex-row justify-content-center alert-admin-msg" style="width:100%">
        <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/_assets/images/avatars/users/999-avatar-1.jpg"
             class="align-self-center mr-3 rounded-circle" width="64" alt=""
             title="Ярослав Чугунов, администратор Портала">
        <div class="body" style="flex-grow:1">
            <p class="text"><span>Коллеги!</span><br><span><?php echo __ADMSETTINGS_MSG_TEXT; ?></span></p>
        </div>

        <!-- <img src="<?php echo __ROOT . __SERVICENAME_PORTALNEW . '/_assets/images/banners/banner-crocus-22032024-1.jpg'; ?>" class="img-fluid mx-auto mb-3 border-dark rounded-lg"> -->
    </div>
    <?php
}
?>



    <!-- ===== БЛОК СЕРВИСОВ ЗАКАЗА ОБЕДОВ ===== -->
    <?php 
if (checkIsItSuperadmin_defaultDB($_SESSION['id']) == 1 || 1 === 1) {
    if ($use_lightTheme !== '1') {
        // Подключаем темную тему
        include 'dinners-promo.dark.html';
    } else {
        // Подключаем светлую тему
        include 'dinners-promo.light.html';
    }
}
?>


    <div class="d-flex flex-row justify-content-center">
        <div id="portalnew-main-top-block-1" class="card border-transparent mb-3 mx-2 corner-box corner-box-topC"
             style="min-width:100%; height:15rem">
            <div
                 class="card-body text-secondary corner-textbox d-flex flex-column align-items-center justify-content-top h-100">

                <h5 class="<?php echo $cardTitle_H5_class; ?>" data-toggle="popover"
                    data-content="<div class='text-center w-100'>Активность пользователей в Портале с 8:00 предыдущего до конца текущего дня. На текущий момент отображаются НЕ ВСЕ операции пользователей, но большая их часть.</div>">
                    Портал.Live<sup><i class="fa-regular fa-circle-dot fa-beat-fade fa-xs ml-1"
                           style="color:red"></i></sup>
                </h5>

                <div id="portalmain-syslog-block" class="card-text text-center align-self-center mb-0 w-100"
                     style="overflow:hidden !important">
                    <table id="portalmain-syslog" class="table table-condensed display compact" cellspacing="0"
                           width="100%">
                        <thead style="display:none">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="portalmain-blocks" class="d-flex flex-row justify-content-center">
        <div class="card border-transparent mb-3 corner-box corner-box-topC box-1" style="min-width:33%; height:auto">
            <div
                 class="card-body text-secondary corner-textbox d-flex flex-column align-items-center justify-content-top h-100">
                <h5 class="<?php echo $cardTitle_H5_class; ?>" data-toggle="popover"
                    data-content="<div class='text-center w-100'>Принятые и уволенные за последние 7 дней по данным сервиса Кадры.</div>">
                    Кадровый вопрос</h5>
                <div id="portalmain-staffNews" class="card-text mb-0 w-100" style="overflow-y:auto !important">
                    <div class="staffPersons-result"></div>
                </div>
            </div>
        </div>
        <div class="card border-transparent mb-3 mx-2 corner-box corner-box-topC box-2"
             style="min-width:34%; height:auto">
            <div
                 class="card-body text-secondary corner-textbox d-flex flex-column align-items-center justify-content-top h-100">
                <h5 class="<?php echo $cardTitle_H5_class; ?>" data-toggle="popover"
                    data-content="<div class='text-center w-100'>Не забудьте поздравить коллег, у которых в ближайшие 3 дня ожидается День Рождения.</div>">
                    Дни рождения</h5>
                <div id="portalmain-staffBirthdays" class="card-text mb-0 w-100" style="overflow-y:auto !important">
                    <div id="staffBirthdays-out"></div>
                </div>
            </div>
        </div>
        <div class="card border-transparent mb-3 corner-box corner-box-topC box-3" style="min-width:33%; height:auto">
            <div id="portalmain-workStatus"
                 class="card-body text-secondary corner-textbox d-flex flex-column align-items-center justify-content-top h-100">
                <div class="d-flex flex-column">
                    <h5 class="<?php echo $cardTitle_H5_class; ?>"><span data-toggle="popover"
                              data-content="<div class='text-center w-100'>Коллеги, не работающие по причине отпуска, больничного или иных объективных причин, а также находящиеся в командировке или на дистанционном формате работы. Данные вводятся самими сотрудниками через телеграм-бот Портала или через форму в самом Портале.</div>">Рабочий
                            статус</span></h5>
                    <div class="enlarged-state-msg <?php echo ($use_lightTheme === '1') ? 'text-dark' : 'text-light'; ?>"
                         style="position:relative; top:-1.5rem; font-size:0.7rem; line-height:1rem;"></div>
                </div>
                <div id="portalmain-staffAbsence" class="card-text mb-0 w-100 d-flex flex-column"
                     style="overflow-y:auto !important">
                    <div class="staffAbsence-out d-flex flex-column"></div>
                </div>
                <div class="mt-auto d-flex flex-row align-items-end" style="position:absolute;bottom:10px;right:30px;">
                    <a href="https://t.me/atgsportal_bot" target="_blank">
                        <div class="workstatus-btn mx-1 p-2" style="display:none" data-toggle="popover"
                             data-content="<div class='text-center w-100'>Сообщить о своем рабочем статусе через телеграм-бот Портала</div>">
                            <i class="fa-brands fa-telegram fa-lg"></i>
                        </div>
                    </a>
                    <div class="workstatus-btn btn2 mx-1 p-2" style="display:none" data-toggle="popover"
                         data-content="<div class='text-center w-100'>Сообщить о своем рабочем статусе через форму Портала</div>">
                        <i class="fa-solid fa-plus fa-lg"></i>
                    </div>
                    <div class="workstatus-btn btn3 mx-1 p-2 enlarge" style="display:none" data-toggle="popover"
                         data-content="<div class='text-center w-100'>Увеличить/уменьшить размер блока</div>">
                        <i class="fa-solid fa-expand fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* hide scrollbar but allow scrolling */
#startPopupMsg-modal .modal-body {
    -ms-overflow-style: none;
    /* for Internet Explorer, Edge */
    scrollbar-width: none;
    /* for Firefox */
    overflow-y: scroll;
}

#startPopupMsg-modal .modal-body::-webkit-scrollbar {
    display: none;
    /* for Chrome, Safari, and Opera */
}

#startPopupMsg-modal .modal-body {}
</style>

<?php
if ($_SESSION['id'] != '999' && $_SESSION['login'] != 'yachugunov') {
    include $_SERVER['DOCUMENT_ROOT'] . "/portalnew/php/examples/simple/updates/updates.php";
} else {
    include $_SERVER['DOCUMENT_ROOT'] . "/portalnew/php/examples/simple/updates/updates-test.php";
}
?>



<!-- <div id="startPopupMsg-modal" class="modal fade" tabindex="-1" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body" style="min-height:5rem; height:35rem">
                <div class="msg-content"></div>
            </div>
            <div class="modal-footer border-top-0 justify-content-center">
                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div> -->

<script type="text/javascript" language="javascript" class="init">
$(window).on("load", function() {

    // $('#startPopupMsg-modal .modal-body .msg-content').empty();
    // $('#startPopupMsg-modal .modal-body .msg-content').fadeIn("slow", function() {
    // // Animation complete
    //     $(this).load(
    //         '<?php echo __SERVICENAME_PORTALNEW; ?>/php/examples/simple/main/main/data/startmodalmsg/202412-01.startmodalmsg.html'
    //     );
    // })

    // setTimeout(function() {
    //     $.ajax({
    //         async: false,
    //         cache: false,
    //         type: "post",
    //         url: "<?php echo __ROOT . __SERVICENAME_PORTALNEW; ?>/php/examples/simple/main/main/process/ajaxrequests/ajaxReq-showStartupModal.php",
    //         data: {
    //             msgid: '<?php echo !empty(__STARTUPMODAL_MSGID) ? __STARTUPMODAL_MSGID : ""; ?>'
    //         },
    //         success: function(response) {
    //             result = JSON.parse(response);
    //             console.log("ajaxReq-showStartupModal", result);
    //         }
    //     });
    // }, 2000);

    // setTimeout(function() {
    //     $('#startPopupMsg-modal').fadeIn("slow", function() {
    //         $('#startPopupMsg-modal').modal('show');
    //     })
    // }, 2000);
    // $('#startPopupMsg-modal').on('show.bs.modal', event => {
    //     var button = $(event.relatedTarget);
    //     var modal = $(this);
    // });
});
</script>