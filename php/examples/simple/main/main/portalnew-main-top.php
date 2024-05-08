<?php
#
# ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### 
#
?>
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
    width: 12%;
    text-align: left;
}

#portalmain-syslog>tbody>tr>td:nth-child(2) {
    width: 10%;
    text-align: left;
}

#portalmain-syslog>tbody>tr>td:nth-child(3) {
    width: 18%;
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
    <!-- <div class="d-flex flex-row justify-content-center" style="width:100%">
        <img src="<?php echo __ROOT . __SERVICENAME_PORTALNEW . '/_assets/images/banners/banner-crocus-22032024-1.jpg'; ?>" class="img-fluid mx-auto mb-3 border-dark rounded-lg">
    </div> -->
    <div class="d-flex flex-row justify-content-center">
        <div id="portalnew-main-top-block-1" class="card border-transparent mb-3 mx-2 corner-box corner-box-topC"
             style="min-width:75%; height:15rem">
            <div
                 class="card-body text-secondary corner-textbox d-flex flex-column align-items-center justify-content-top h-100">

                <h5 class="<?php echo $cardTitle_H5_class; ?>" data-toggle="popover"
                    data-content="<div class='text-center w-100'>Активность пользователей в Портале с 8:00 до конца текущего дня. На текущий момент отображаются НЕ ВСЕ операции пользователей, но большая их часть.</div>">
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
        <div class="card border-transparent mb-3 mx-2 corner-box corner-box-topC" style="min-width:25%; height:15rem">
            <div
                 class="card-body text-secondary corner-textbox d-flex flex-column align-items-center justify-content-top h-100">
                <h5 class="<?php echo $cardTitle_H5_class; ?>" data-toggle="popover"
                    data-content="<div class='text-center w-100'>Принятые и уволенные за последние 14 дней по данным сервиса Кадры.</div>">
                    Кадровый вопрос</h5>
                <div id="portalmain-staffNews" class="card-text text-center align-self-center mb-0 pr-1"
                     style="overflow-y:auto !important">
                    <div class="staffPersons-in mb-3"></div>
                    <div class="staffPersons-out"></div>
                    <div class="staffPersons-noresults"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$('#exampleModal').on('show.bs.modal', event => {
    var button = $(event.relatedTarget);
    var modal = $(this);
    // Use above variables to manipulate the DOM

});
</script>

<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {});
</script>