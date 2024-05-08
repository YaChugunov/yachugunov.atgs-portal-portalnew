<?php if (checkIsItSuperadmin_defaultDB($_SESSION['id']) == 1) { ?>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {

    var portalMain_syslog = $('#portalProfile-syslog').DataTable({
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
        timer = setTimeout(callticker, 20 * 1000);
    }
    timer = setTimeout(callticker, 20 * 1000);
    $("#portalnew-main-top-block-1").mouseover(function() {
        clearTimeout(timer);
    }).mouseout(function() {
        timer = setTimeout(callticker, 20 * 1000);
    })


});
</script>

<h5 class="<?php echo $cardTitle_H5_class; ?>" data-toggle="popover"
    data-content="<div class='text-center w-100'>Активность пользователей в Портале с 8:00 до конца текущего дня. На текущий момент отображаются НЕ ВСЕ операции пользователей, но большая их часть.</div>">
    Портал.Live<sup><i class="fa-regular fa-circle-dot fa-beat-fade fa-xs ml-1" style="color:red"></i></sup>
</h5>

<div id="portalProfile-syslog-block" class="card-text text-center align-self-center mb-0 w-100"
     style="overflow:hidden !important">
    <table id="portalProfile-syslog" class="table table-condensed display compact" cellspacing="0" width="100%">
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


<?php } else {
} ?>